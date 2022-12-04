<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class ApiUserDetails  extends  Controller
{
    /**
     * @OA\Get(
     *      path="/api/userdetails",
     *      operationId="getuserdetailsList",
     *      tags={"UserDetails"},
     *      summary="Get list of User  Details",
     *      description="Returns list Users details",
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function index()
    {
        $nextofkin = UserDetails::withoutTrashed()
            ->whereNull('deleted_at')->get();

        $response = ['status'=>true,'message'=>'','data' => $nextofkin];
        return response($response, 200);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * @OA\Post(
     * path="/api/userdetails",
     * operationId="userdetailsCreate",
     * tags={"UserDetails"},
     * summary="Save User Details",
     * description="Saving User  details",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"tittle","mobile","source_of_income","nat_id","date_of_birth", "gender","address"},
     *               @OA\Property(property="tittle", type="text"),
     *               @OA\Property(property="mobile", type="text"),
     *               @OA\Property(property="nat_id", type="text"),
     *               @OA\Property(property="date_of_birth", type="date"),
     *               @OA\Property(property="gender", type="text"),
     *               @OA\Property(property="address", type="text"),
     *               @OA\Property(property="source_of_income", type="text"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="User Details Saved Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="User Details Saved Successfully",
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
    public function store(Request $request)
    {
        {
            $validator = Validator::make($request->all(), [
                'source_of_income' => 'required',
                'tittle' => 'required',
                'date_of_birth' => 'required|date',
                'mobile' => 'required|unique:user_details',
                'gender' => 'required',
                'nat_id' => 'required|unique:user_details',
                'address' => 'required|max:255'
            ]);
            if ($validator->fails()) {
                return response(['status' => false, 'message' => 'There were some problems with your input',
                    'data' => $validator->errors()]);
            }

            $user_d = Auth::user()->id;

            $request['user_id']=$user_d;
            $request['date_of_birth']=  $request->input('date_of_birth');
            $request['mobile']=  $request->input('mobile');
            $request['gender']=  $request->input('gender');
            $request['address']=  $request->input('address');
            $request['source_of_income']=  $request->input('source_of_income');
            $request['tittle']=  $request->input('tittle');
            $request['nat_id']=  $request->input('nat_id');
            $userDetails = UserDetails::create($request->toArray());
            $userDetails->save();

            $response = ['status'=>true,'message'=>'Data Saved Successfully','data' => $userDetails];
            return response($response, 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserDetails  $userDetails
     * @return \Illuminate\Http\Response
     */
    public function show(UserDetails $userDetails)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserDetails  $userDetails
     * @return \Illuminate\Http\Response
     */
    public function edit(UserDetails $userDetails)
    {
        //
    }


    /**
     * @OA\Put(
     * path="/api/userdetails/{id}",
     * operationId="userdetailsUpdate",
     * tags={"UserDetails"},
     * summary="Update User Details",
     * description="Updating User  details",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"tittle","source_of_income","mobile","nat_id","date_of_birth", "gender","address"},
     *               @OA\Property(property="tittle", type="text"),
     *               @OA\Property(property="mobile", type="text"),
     *               @OA\Property(property="nat_id", type="text"),
     *               @OA\Property(property="date_of_birth", type="date"),
     *               @OA\Property(property="gender", type="text"),
     *               @OA\Property(property="address", type="text"),
     *               @OA\Property(property="source_of_income", type="text"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="User Details Updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="User Details Updated Successfully",
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
    public function update(Request $request,  $id)
    {
        $userDetails = UserDetails::findorfail($id);
        {
            $validator = Validator::make($request->all(), [
                'tittle' => 'required',
                'date_of_birth' => 'required|date',
                'mobile' => 'required|unique:user_details',
                'gender' => 'required',
                'source_of_income' => 'required',
                'nat_id' => 'required|unique:user_details',
                'address' => 'required|max:255'
            ]);
            if ($validator->fails()) {
                return response(['status' => false, 'message' => 'There were some problems with your input',
                    'data' => $validator->errors()]);
            }

            $userDetails-> user_id =Auth::user()->id;
            $userDetails->tittle =  $request->input('tittle');
            $userDetails->date_of_birth =  $request->input('date_of_birth');
            $userDetails->mobile =  $request->input('mobile');
            $userDetails->gender =  $request->input('gender');
            $userDetails->source_of_income =  $request->input('source_of_income');
            $userDetails->nat_id =  $request->input('nat_id');
            $userDetails->address =  $request->input('address');
            $userDetails->save();

            if($userDetails->save()){
                $response = ['status'=>true,'message'=>'User Details Details Updated Successfully','data' =>$userDetails];
                return response($response, 200);
            }

        }
    }

    /**
     * @OA\Delete(
     * path="/api/userdetails/{id}",
     * operationId="UserDetailsDelete",
     * tags={"UserDetails"},
     * summary="Delete User  Details",
     * description="Deleting User  details",
     *      @OA\Parameter(
     *          name="id",
     *          description="Project id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=204,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function destroy($id)
    {
        $userDetails = UserDetails::findOrFail($id);
        $userDetails->delete();

        return  response(['status'=>true,'message'=>'User Details Deleted Successfully', 'data'=>'']);
    }

}
