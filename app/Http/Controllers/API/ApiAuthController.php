<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use jeremykenedy\LaravelRoles\Models\Role;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Nette\Schema\ValidationException;
use OpenApi\Annotations as OA;
use PHPUnit\Framework\Constraint\Count;

class ApiAuthController extends Controller
{
    use HasRoleAndPermission;
    /**
     * @OA\Post(
     * path="/api/register",
     * operationId="Register",
     * tags={"Register"},
     * summary="User Registration",
     * description="User Registration here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"first_name","last_name","email","id_number","pay_number", "password", "password_confirmation"},
     *               @OA\Property(property="first_name", type="text"),
     *               @OA\Property(property="last_name", type="text"),
     *               @OA\Property(property="email", type="text"),
     *               @OA\Property(property="pay_number", type="text"),
     *               @OA\Property(property="password", type="password"),
     *               @OA\Property(property="password_confirmation", type="password")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function register (Request $request) {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'pay_number' => 'required|unique:users',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|min:6|same:password',
        ]);

        if ($validator->fails()) {
            $response=['status' => false, 'message' => 'There were some problems with your input',
                'data' => $validator->errors()];
            return  response($response,422);


        }

        $name = generateUsername($request['first_name'], $request['last_name']);
        $role = Role::where('slug', '=', 'user')->first();

        $request['name']=$name;
        $request['pay_number']=  $request->input('pay_number');
        $request['first_name']=  $request->input('first_name');
        $request['last_name']=  $request->input('last_name');
        $request['email']=  $request->input('email');
        $request['password']=Hash::make($request['password']);
        $request['token'] = str_random(64);
        $request['activated'] = 1;
        $request['remember_token'] = Str::random(10);
        $user = User::create($request->toArray());

        $user->attachRole($role);
        $user->save();

        $token = $user->createToken('MHSACCO Grant Client')->accessToken;

        $response = ['status'=>true,'message'=>'Welcome '.$user->first_name,'token' => $token,'data' => $user];
        return response($response, 200);
    }

    /**
     * @OA\Post(
     * path="/api/logAsadmin",
     * operationId="AdminLogin",
     * tags={"Login"},
     * summary="Admin Login",
     * description="Login User Here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"email", "password"},
     *               @OA\Property(property="email", type="email"),
     *               @OA\Property(property="password", type="password")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Login Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Login Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function Adminlogin (Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if($user->hasRole('admin')){
            if ($user) {
                if (Hash::check($request->password, $user->password)) {
                    $token = $user->createToken('MHSACCO Password Grant Client')->accessToken;
                    $response = ["status"=>true,'user' => $user, 'token' => $token];
                    return response($response, 200);
                } else {
                    $response = ["status"=>false,"message" => "Admin Password mismatch"];
                    return response($response, 422);
                }
            } else {
                $response = ["status"=>false,"message" =>'User does not exist','data'=>''];
                return response($response, 422);
            }
        }else {
            $response = ["status"=>false,"message" =>'You are not Admin','data'=>''];
            return response($response, 403);
        }

    }

    /**
     * @OA\Post(
     * path="/api/login",
     * operationId="authLogin",
     * tags={"Login"},
     * summary="User Login",
     * description="Login User Here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"pay_number", "password"},
     *               @OA\Property(property="pay_number", type="text"),
     *               @OA\Property(property="password", type="password")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Login Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Login Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function login (Request $request) {
        $validator = Validator::make($request->all(), [
            'pay_number' => 'required',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $user = User::where('pay_number', $request->pay_number)->first();






        if ($user) {
            if (Hash::check($request->password, $user->password)) {


                $userDetails = DB::table('user_details')->where('user_id','==',$user->id)->get();
                $countUserDetails = $userDetails->count();

                $token = $user->createToken('MHSACCO Password Grant Client')->accessToken;
                $response = ["status"=>true,'data' => $user, 'user-info'=>$countUserDetails, 'token' => $token];

                return response($response, 200);
            } else {
                $response = ["status"=>false,"message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["status"=>false,"message" =>'User does not exist','data'=>''];
            return response($response, 422);
        }
    }

    public function updateMyProfile(Request $request){
        $user = User::where('id',auth()->user()->id)->first();
        $validator = Validator::make(
            $request->all(),
            [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,'.$user->id,
                'pay_number' => 'required',

            ]
        );

        if ($validator->fails()) {
            return response(['status' => false, 'message' => 'There were some problems with your input',
                'data' => $validator->errors()]);
        }

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->pay_number = $request->input('pay_number');
        $user->save();

        $response = ['status'=>true,'message'=>'','user' => $user];
        return response($response, 200);
    }

    public function updateProfilePic(Request $request){
        $user = User::where('id',auth()->user()->id)->first();
        $validator = Validator::make(
            $request->all(),
            [
                'profile_pic'  => 'required|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ]
        );

        if ($validator->fails()) {
            return response(['status' => false, 'message' => 'There were some problems with your input',
                'data' => $validator->errors()]);
        }

        if($request->hasFile('profile_pic')) {
            if ($request->file('profile_pic')->isValid()) {
                $profileDoc = $request->file('profile_pic');
                $filename = $user->name . '.' . $profileDoc->getClientOriginalExtension();
                Storage::disk('public')->put('images/profile_pics/' . $filename, File::get($profileDoc));
                $user->profile_pic = $filename;

            } else {
                return response([ 'status' => false,'message' => 'Invalid image supplied.','data'=>'']);
            }
        } else {
            return response([ 'status' => false,'message' => 'No Image detected here.','data'=>'']);
        }

        $user->save();

        $response = ['status'=>true,'message'=>'','user' => $user];
        return response($response, 200);
    }

    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ["status"=>true,'message' => 'You have been successfully logged out!', 'data'=>[]];
        return response($response, 200);
    }

    /**
     * @OA\Post (
     * path="/api/forget_password",
     * operationId="forget_password",
     * tags={"Reset-Password"},
     * summary="User Forgot Password",
     * description="User Forgot Password",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"email"},
     *               @OA\Property(property="email", type="email"),
     *            ),
     *        ),
     *    ),
     *
     *      @OA\Response(
     *          response=201,
     *          description="Reset Link Successfully Sent",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Reset Link Successfully Sent",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public  function forgetPassword(Request $request){
        $request->validate([
            'email'=>'required|email'
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

//        return $status == Password::RESET_LINK_SENT
//            ? back()->with(['status' => __($status)])
//            : back()->withErrors(['email' => __($status)]);

        if($status == Password::RESET_LINK_SENT){
            return[
                'status'=> __($status)
            ];
        }

        throw ValidationException::withErrors([
            'email'=>[trans($status)]
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/reset",
     * operationId="reset_password",
     * tags={"Reset-Password"},
     * summary="User Reset Password",
     * description="User Reset Password",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"email","password","token","password_confirmation"},
     *               @OA\Property(property="token", type="text"),
     *               @OA\Property(property="email", type="email"),
     *               @OA\Property(property="password", type="password"),
     *               @OA\Property(property="password_confirmation", type="password"),
     *            ),
     *        ),
     *    ),
     *
     *      @OA\Response(
     *          response=201,
     *          description="Reset Link Successfully Sent",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Reset Link Successfully Sent",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public  function reset(Request $request){
        $request->validate([
            'token'=>'required',
            'email'=>'required|email',
            'password'=>'required','confirmed'
        ]);

        $status = Password::reset(
          $request->only('email','password','password_confirmation','token'),
            function ($user) use ($request){
              $user->forcefill([
                  'password'=>Hash::make($request->password),
                  'token'=>str_random(64),
              ])->save();

                $user->tokens()->delete();

              event(new PasswordReset($user));
            }
        );

        if($status == Password::PASSWORD_RESET){

            $response = ['status'=>true,'message'=>'Password Reset Successfully', 'data'=>''];
            return response($response,200);
        }

        return  response([
            'message'=>__($status)
        ],500);
    }

    public function deleteMyAccount (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        auth()->user()->forceDelete();
        $response = ["status"=>true,'message' => 'Account deleted!', 'data'=>[]];
        return response($response, 200);
    }

}
