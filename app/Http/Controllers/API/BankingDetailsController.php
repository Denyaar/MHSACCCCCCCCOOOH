<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BankingDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BankingDetailsController extends  Controller
{
    /**
     * @OA\Get(
     *      path="/api/bankingdetails",
     *      operationId="getBankingdetaillist",
     *      tags={"BankingDetails"},
     *      summary="Get list of Banking Details",
     *      description="Returns list of Banking Details",
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
        $bankingdetails = BankingDetails::all();

        $response = ['status'=>true,'message'=>'','data' => $bankingdetails];
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
     * path="/api/bankingdetails",
     * operationId="BankingdetailsCreate",
     * tags={"BankingDetails"},
     * summary="Save bank Details",
     * description="Saving Banking details",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"bank","bank_branch","branch_code","acc_name","acc_number","status","acc_type"},
     *               @OA\Property(property="bank", type="text"),
     *               @OA\Property(property="bank_branch", type="text"),
     *               @OA\Property(property="branch_code", type="text"),
     *               @OA\Property(property="acc_name", type="text"),
     *               @OA\Property(property="status", type="text"),
     *               @OA\Property(property="acc_number", type="text"),
     *               @OA\Property(property="acc_type", type="text"),
     *
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Banking Details Saved Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Banking Details Saved Successfully",
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
                'bank' => 'required',
                'bank_branch' => 'required',
                'branch_code' => 'required',
                'acc_name' => 'required|unique:banking_details',
                'acc_number' => 'required|unique:banking_details',
                'acc_type' => 'required',
            ]);
            if ($validator->fails()) {
                return response(['status' => false, 'message' => 'There were some problems with your input',
                    'data' => $validator->errors()]);
            }

            $request['user_id']= Auth::user()->id;
            $request['bank']=  $request->input('bank');
            $request['bank_branch']=  $request->input('bank_branch');
            $request['branch_code']=  $request->input('branch_code');
            $request['acc_name']=  $request->input('acc_name');
            $request['status']=0;
            $request['acc_number']=  $request->input('acc_number');
            $request['acc_type']=  $request->input('acc_type');

            $bankingdetails = BankingDetails::create($request->toArray());
            $bankingdetails->save();

            $response = ['status'=>true,'message'=>'Banking Details Saved Successfully','data' => $bankingdetails];
            return response($response, 200);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/bankingdetail/{id}",
     *      operationId="getUserBankingDetails",
     *      tags={"BankingDetails"},
     *      summary="Employee Banking Details",
     *      description="Returns Employee Banking Details",
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
        $bankingdetails = BankingDetails::findorfail($id);

        $response = ['status'=>true,'message'=>'','data' => $bankingdetails];
        return response($response, 200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BankingDetails  $bankingdetails
     * @return \Illuminate\Http\Response
     */
    public function edit(BankingDetails $bankingdetails)
    {
        //
    }


    /**
     * @OA\Put(
     * path="/api/bankingdetails/{id}",
     * operationId="BankBranchUpdate",
     * tags={"BankingDetails"},
     * summary="Update Banking Details",
     * description="Updating Banking details",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"bank","bank_branch","branch_code","acc_name","acc_number","acc_type","status"},
     *               @OA\Property(property="bank", type="text"),
     *               @OA\Property(property="bank_branch", type="text"),
     *               @OA\Property(property="branch_code", type="text"),
     *               @OA\Property(property="acc_name", type="text"),
     *               @OA\Property(property="status", type="text"),
     *               @OA\Property(property="acc_number", type="text"),
     *               @OA\Property(property="acc_type", type="text"),
     *
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Banking Details Updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Banking Details Updated Successfully",
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
    public function update(Request $request, $id)
    {
        $bankingdetails = BankingDetails::findOrFail($id);
        {
            $validator = Validator::make($request->all(), [
                'bank' => 'required',
                'bank_branch' => 'required',
                'branch_code' => 'required',
                'acc_name' => 'required|unique:banking_details',
                'acc_number' => 'required|unique:banking_details',
                'acc_type' => 'required',
            ]);
            if ($validator->fails()) {
                return response(['status' => false, 'message' => 'There were some problems with your input',
                    'data' => $validator->errors()]);
            }

            $bankingdetails->user_id =  Auth::user()->id;
            $bankingdetails->bank =  $request->input('bank');
            $bankingdetails->bank_branch =  $request->input('bank_branch');
            $bankingdetails->branch_code =  $request->input('branch_code');
            $bankingdetails->acc_name =  $request->input('acc_name');
            $bankingdetails->status =  $request->input('status');
            $bankingdetails->acc_number =  $request->input('acc_number');
            $bankingdetails->acc_type =  $request->input('acc_type');

            $bankingdetails->save();

            if($bankingdetails->save()){
                $response = ['status'=>true,'message'=>'Banking Details  Updated Successfully','data' => $bankingdetails];
                return response($response, 200);
            }

        }
    }

    /**
     * @OA\Get(
     *      path="/api/approve_bankingdetails/{id}",
     *      operationId="approvebankingdetails",
     *      tags={"BankingDetails"},
     *      summary="Approve banking details",
     *      description="Return Approved banking Details",
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
        $banking_details = BankingDetails::findorfail($id)->WithoutTrashed();

        if($banking_details->status == 1){
            return response(['status' => false, 'message' => 'Banking Details have been Approved Already!  ',
                'data' =>$banking_details ]);
        }
        else{
            $banking_details->status =1;
            $banking_details->save();
            return response(['status' => true, 'message' => 'Banking Details Approval Successfully', 'data' => $banking_details]);
        }

    }

    /**
     * @OA\Delete(
     * path="/api/bankingdetails/{id}",
     * operationId="BankBranchDelete",
     * tags={"BankingDetails"},
     * summary="Delete Banking  Details",
     * description="Deleting Banking details",
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
        $bankingdetails = BankingDetails::findOrFail($id);
        $bankingdetails->delete();

        return  response(['status'=>true,'message'=>'Banking  Details Deleted Successfully', 'data'=>'']);
    }

}
