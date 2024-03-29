<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BankingDetails;
use App\Models\EmploymentDetails;
use App\Models\NextOfKin;
use App\Models\UserDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
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
        $userDetails = UserDetails::withoutTrashed()
            ->whereNull('deleted_at')->get();

        $response = ['status'=>true,'message'=>'','data' => $userDetails];
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
     *               required={"tittle","mobile","source_of_income","copy_of_nat_id","nat_id","date_of_birth","gender","address","bank","bank_branch",
     *               "branch_code","acc_name","acc_number","bank_status","acc_type","employer","employer_phone","department","grade",
     *                "approved_status","position_at_work","date_of_employment","employer_address","next_of_kin_name","next_of_kin_surname",
     *                "next_of_kin_mobile_num","next_of_kin_nat_id","next_of_kin_date_of_birth", "next_of_kin_gender", "relationship","next_of_kin_address"},
     *               @OA\Property(property="tittle", type="text"),
     *               @OA\Property(property="mobile", type="text"),
     *               @OA\Property(property="nat_id", type="text"),
     *               @OA\Property(property="date_of_birth", type="date"),
     *               @OA\Property(property="gender", type="text"),
     *               @OA\Property(property="address", type="text"),
     *               @OA\Property(property="source_of_income", type="text"),
     *               @OA\Property(property="copy_of_nat_id", type="text"),
     *
     *               @OA\Property(property="bank", type="text"),
     *               @OA\Property(property="bank_branch", type="text"),
     *               @OA\Property(property="branch_code", type="text"),
     *               @OA\Property(property="acc_name", type="text"),
     *               @OA\Property(property="acc_number", type="text"),
     *               @OA\Property(property="acc_type", type="text"),
     *
     *               @OA\Property(property="employer", type="text"),
     *               @OA\Property(property="employer_phone", type="text"),
     *               @OA\Property(property="position_at_work", type="text"),
     *               @OA\Property(property="grade", type="text"),
     *               @OA\Property(property="approved_status", type="text"),
     *               @OA\Property(property="date_of_employment", type="date"),
     *               @OA\Property(property="employer_address", type="text"),
     *               @OA\Property(property="department", type="text"),
     *
     *               @OA\Property(property="next_of_kin_name", type="text"),
     *               @OA\Property(property="next_of_kin_surname", type="text"),
     *               @OA\Property(property="next_of_kin_mobile_num", type="text"),
     *               @OA\Property(property="next_of_kin_nat_id", type="text"),
     *               @OA\Property(property="next_of_kin_date_of_birth", type="date"),
     *               @OA\Property(property="next_of_kin_gender", type="text"),
     *               @OA\Property(property="relationship", type="text"),
     *               @OA\Property(property="next_of_kin_address", type="text"),
     *
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
                'address' => 'required|max:255',
                'copy_of_nat_id'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096|unique:requirements',


                'bank' => 'required',
                'bank_branch' => 'required',
                'branch_code' => 'required',
                'acc_name' => 'required|unique:banking_details',
                'acc_number' => 'required|unique:banking_details',
                'acc_type' => 'required',

                'department' => 'required',
                'employer' => 'required',
                'date_of_employment' => 'required|date',
                'employer_phone' => 'required|unique:employment_details',
                'grade' => 'required',
                'position_at_work' => 'required',
                'employer_address' => 'required|max:255',

                'next_of_kin_name' => 'required|string|max:255',
                'next_of_kin_surname' => 'required|string|max:255',
                'next_of_kin_date_of_birth' => 'required|date',
                'next_of_kin_mobile_num' => 'required|unique:next_of_kin',
                'next_of_kin_gender' => 'required',
                'relationship' => 'required',
                'next_of_kin_nat_id' => 'required|unique:next_of_kin',
                'next_of_kin_address' => 'required|max:255'
            ]);
            if ($validator->fails()) {
                $response = (['status' => false, 'message' => 'There were some problems with your input',
                    'data' => $validator->errors()]);
                return  response($response,422);
            }


            $id_name = $request->file('copy_of_nat_id')->getClientOriginalName();

            if ($request->file('copy_of_nat_id')->isValid()) {
                $idPhoto = $request->file('copy_of_nat_id');
                Storage::disk('public')->put('user-ids/' . $id_name, File::get($idPhoto));
            } else {
                $response=['status' => false, 'message' => 'Invalid  photo',
                    'data' => $validator->errors()];
                return  response($response,415);
            }


            $user_id = Auth::user()->id;

            $request['user_id']=$user_id;
            $request['date_of_birth']=  $request->input('date_of_birth');
            $request['mobile']=  $request->input('mobile');
            $request['gender']=  $request->input('gender');
            $request['user_status']=0;
            $request['address']=  $request->input('address');
            $request['source_of_income']=  $request->input('source_of_income');
            $request['tittle']=  $request->input('tittle');
            $request['nat_id']=  $request->input('nat_id');
            $request['copy_of_nat_id']= $id_name;

            $userDetails = UserDetails::create($request->toArray());
            $userDetails->save();

            if ($userDetails->save()){
                $request['user_id']= $user_id;
                $request['bank']=  $request->input('bank');
                $request['bank_branch']=  $request->input('bank_branch');
                $request['branch_code']=  $request->input('branch_code');
                $request['acc_name']=  $request->input('acc_name');
                $request['bank_status']=0;
                $request['acc_number']=  $request->input('acc_number');
                $request['acc_type']=  $request->input('acc_type');

                $bankingdetails = BankingDetails::create($request->toArray());
                $bankingdetails->save();
            }

            if($bankingdetails->save()){

                $request['user_id'] = $user_id;

                $request['date_of_employment'] = $request->input('date_of_employment');
                $request['employer_phone'] = $request->input('employer_phone');
                $request['grade'] = $request->input('grade');
                $request['approved_status'] = 0;
                $request['employer_address'] = $request->input('employer_address');
                $request['department'] = $request->input('department');
                $request['employer'] = $request->input('employer');
                $request['position_at_work'] = $request->input('position_at_work');
                $employmentDetails = EmploymentDetails::create($request->toArray());
                $employmentDetails->save();
            }

            if ($employmentDetails->save()){
                $request['user_id'] =  $user_id;
                $request['next_of_kin_name'] = $request->input('next_of_kin_name');
                $request['next_of_kin_surname'] = $request->input('next_of_kin_surname');
                $request['next_of_kin_date_of_birth'] = $request->input('next_of_kin_date_of_birth');
                $request['next_of_kin_mobile_num'] = $request->input('next_of_kin_mobile_num');
                $request['next_of_kin_gender'] = $request->input('next_of_kin_gender');
                $request['relationship'] = $request->input('relationship');
                $request['next_of_kin_nat_id'] = $request->input('next_of_kin_nat_id');
                $request['next_of_kin_address'] = $request->input('next_of_kin_address');
                $nextOfkin = NextOfKin::create($request->toArray());
                $nextOfkin->save();
            }



            $response = ['status'=>true,'message'=>'Data Saved Successfully','data' => $userDetails,$bankingdetails,$employmentDetails,$nextOfkin];
            return response($response, 200);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/userdetails/{id}",
     *      operationId="getspecificuserdetails",
     *      tags={"UserDetails"},
     *      summary="Specific User  Details",
     *      description="Returns Specific Users details",
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
    public function show($id)
    {
        $userDetails = UserDetails::findorfail($id);

        $response = ['status'=>true,'message'=>'','data' => $userDetails];
        return response($response, 200);


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
     *               required={"tittle","source_of_income","mobile","nat_id","date_of_birth","user_status","gender","address"},
     *               @OA\Property(property="tittle", type="text"),
     *               @OA\Property(property="mobile", type="text"),
     *               @OA\Property(property="nat_id", type="text"),
     *               @OA\Property(property="date_of_birth", type="date"),
     *               @OA\Property(property="gender", type="text"),
     *               @OA\Property(property="user_status", type="text"),
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
                $response = (['status' => false, 'message' => 'There were some problems with your input',
                    'data' => $validator->errors()]);
                return  response($response,422);
            }

            $userDetails-> user_id =Auth::user()->id;
            $userDetails->tittle =  $request->input('tittle');
            $userDetails->date_of_birth =  $request->input('date_of_birth');
            $userDetails->mobile =  $request->input('mobile');
            $userDetails->gender =  $request->input('gender');
            $userDetails->user_status =  $request->input('user_status');
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
     * @OA\Get(
     *      path="/api/user_approve/{id}",
     *      operationId="approveuserdetails",
     *      tags={"UserDetails"},
     *      summary="Approve User Details",
     *      description="Returns Approved User Details",
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

    public function Approve($id)
    {
        $approve_userDetails = UserDetails::findorfail($id)->WithoutTrashed();

        if($approve_userDetails->user_status == 1){
            $response = (['status' => false, 'message' => 'Loan has been Approved Already!',
                'data' => $approve_userDetails]);
            return  response($response,422);
        }
        else{
            $approve_userDetails->user_status =1;
            $approve_userDetails->save();
            $response=(['status' => true, 'message' => 'Loan Approval Successfully ', 'data' => $approve_userDetails]);
            return  response($response,200);
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

    /**
     * @OA\Delete(
     * path="/api/forcedeleteuserdetails/{id}",
     * operationId="userdetailspermdelete",
     * tags={"UserDetails"},
     * summary="Permanantly Delete User  Details",
     * description="Deleting User  details Permanantly",
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

    public function ForceDelete($id)
    {
        $loan = UserDetails::findOrFail($id);
        $loan->forceDelete();

        return  response(['status'=>true,'message'=>'Loan Permanently Deleted Successfully', 'data'=>'']);
    }

}
