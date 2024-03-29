<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class LoansController extends  Controller
{
    /**
     * @OA\Get(
     *      path="/api/loan",
     *      operationId="getloansList",
     *      tags={"Loans"},
     *      summary="Get list of Loan ",
     *      description="Returns list Loans ",
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
        $nextofkin = Loan::withoutTrashed()
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
     * path="/api/loan",
     * operationId="loanCreate",
     * tags={"Loans"},
     * summary="Save Loan ",
     * description="Saving Loan  ",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"user_id","loan_name","loan_purpose","basic_salary","net_salary","other_income","approved_installments","repayment_period","type","applied_date", "amount","approved_date","status"},
     *               @OA\Property(property="user_id", type="text"),
     *               @OA\Property(property="loan_name", type="text"),
     *               @OA\Property(property="loan_purpose", type="text"),
     *               @OA\Property(property="type", type="text"),
     *               @OA\Property(property="applied_date", type="date"),
     *               @OA\Property(property="approved_date", type="date"),
     *               @OA\Property(property="amount", type="text"),
     *               @OA\Property(property="basic_salary", type="text"),
     *               @OA\Property(property="net_salary", type="text"),
     *               @OA\Property(property="other_income", type="text"),
     *               @OA\Property(property="status", type="text"),
     *               @OA\Property(property="approved_installments", type="text"),
     *               @OA\Property(property="repayment_period", type="text"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Loan Details Saved Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Loan Details Saved Successfully",
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
                'user_id' => 'required',
                'approved_installments' => 'required',
                'loan_name' => 'required',
                'applied_date' => 'required|date',
                'approved_date' => 'required|date',
                'loan_purpose' => 'required',
                'basic_salary' => 'required',
                'net_salary' => 'required',
                'other_income' => 'required',
                'amount' => 'required',
                'type' => 'required',
                'repayment_period' => 'required'
            ]);
            if ($validator->fails()) {
                $response = (['status' => false, 'message' => 'There were some problems with your input',
                    'data' => $validator->errors()]);
                return  response($response,422);
            }

            $request['user_id']=  $request->input('user_id');
            $request['applied_date']=  $request->input('applied_date');
            $request['approved_date']=  $request->input('approved_date');
            $request['repayment_period']=  $request->input('repayment_period');
            $request['loan_purpose']=  $request->input('loan_purpose');
            $request['amount']=  $request->input('amount');
            $request['basic_salary']=  $request->input('basic_salary');
            $request['net_salary']=  $request->input('net_salary');
            $request['other_income']=  $request->input('other_income');
            $request['status']= 0;
            $request['approved_installments']=  $request->input('approved_installments');
            $request['loan_name']=  $request->input('loan_name');
            $request['type']=  $request->input('type');
            $loans = Loan::create($request->toArray());
            $loans->save();

            $response = ['status'=>true,'message'=>'Data Saved Successfully','data' => $loans];
            return response($response, 200);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/loan/{id}",
     *      operationId="getSpecificloansList",
     *      tags={"Loans"},
     *      summary="Loan Details ",
     *      description="Returns Loan Details ",
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
        $loan = Loan::findorfail($id);

        $response= ['status'=>true,'message'=>'Loan Details','data'=>$loan];
        return response($response,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Loan  $loans
     * @return \Illuminate\Http\Response
     */
    public function edit(Loan $loans)
    {
        //
    }


    /**
     * @OA\Put(
     * path="/api/loan/{id}",
     * operationId="loanUpdate",
     * tags={"Loans"},
     * summary="Update Loan Details",
     * description="Updating Loan  details",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *              required={"user_id","loan_name","loan_purpose","basic_salary","net_salary","other_income","approved_installments","repayment_period","type","applied_date", "amount","approved_date","status"},
     *               @OA\Property(property="loan_name", type="text"),
     *               @OA\Property(property="user_id", type="text"),
     *               @OA\Property(property="loan_purpose", type="text"),
     *               @OA\Property(property="type", type="text"),
     *               @OA\Property(property="applied_date", type="date"),
     *               @OA\Property(property="approved_date", type="date"),
     *               @OA\Property(property="amount", type="text"),
     *               @OA\Property(property="basic_salary", type="text"),
     *               @OA\Property(property="net_salary", type="text"),
     *               @OA\Property(property="other_income", type="text"),
     *               @OA\Property(property="status", type="text"),
     *               @OA\Property(property="approved_installments", type="text"),
     *               @OA\Property(property="repayment_period", type="text"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Loan Details Updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Loan Details Updated Successfully",
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
    public function update(Request $request,$id )
    {
        $loans = Loan::findOrFail($id);
        {
            $validator = Validator::make($request->all(), [
                'approved_installments' => 'required',
                'user_id' => 'required',
                'loan_name' => 'required',
                'applied_date' => 'required|date',
                'approved_date' => 'required|date',
                'loan_purpose' => 'required',
                'basic_salary' => 'required',
                'net_salary' => 'required',
                'other_income' => 'required',
                'amount' => 'required',
                'type' => 'required',
                'repayment_period' => 'required'
            ]);
            if ($validator->fails()) {
                $response = (['status' => false, 'message' => 'There were some problems with your input',
                    'data' => $validator->errors()]);
                return  response($response,422);
            }

            $loans->user_id =  $request->input('user_id');
            $loans->loan_name =  $request->input('loan_name');
            $loans->applied_date =  $request->input('applied_date');
            $loans->approved_date =  $request->input('approved_date');
            $loans->loan_purpose =  $request->input('loan_purpose');
            $loans->amount =  $request->input('amount');
            $loans->basic_salary =  $request->input('basic_salary');
            $loans->net_salary =  $request->input('net_salary');
            $loans->other_income =  $request->input('other_income');
            $loans->approved_installments =  $request->input('approved_installments');
            $loans->repayment_period =  $request->input('repayment_period');
            $loans->type =  $request->input('type');
            $loans->status =  $request->input('status');
            $loans->save();

            if($loans->save()){
                $response = ['status'=>true,'message'=>'Loan  Updated Successfully','data' => $loans];
                return response($response, 200);
            }

        }
    }


    /**
     * @OA\Get(
     *      path="/api/loan_approve/{id}",
     *      operationId="approveloan",
     *      tags={"Loans"},
     *      summary="Approve Loan",
     *      description="Returns Approved loan Details",
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
        $loan_approve = Loan::findorfail($id)->WithoutTrashed();

        if($loan_approve->status == 1){
            $response = (['status' => false, 'message' => 'Loan has been Approved Already!',
                'data' => $loan_approve]);
            return  response($response,422);
        }
        else{
            $loan_approve->status =1;
            $loan_approve->save();
            return response(['status' => true, 'message' => 'Loan Approval Successfully ', 'data' => $loan_approve]);
        }

    }
    /**
     * @OA\Delete(
     * path="/api/loan/{id}",
     * operationId="loansDelete",
     * tags={"Loans"},
     * summary="Delete Loan  Details",
     * description="Deleting Loan  details",
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
        $loan = Loan::findOrFail($id);
        $loan->delete();

        return  response(['status'=>true,'message'=>'Loan Deleted Successfully', 'data'=>'']);
    }


    /**
     * @OA\Delete(
     * path="/api/forcedeleteloan/{id}",
     * operationId="loanspermDelete",
     * tags={"Loans"},
     * summary="Delete Loan  Details Permanantly",
     * description="Permanantly Deleting Loan  details",
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
        $loan = Loan::findOrFail($id);
        $loan->forceDelete();

        return  response(['status'=>true,'message'=>'Loan Permanently Deleted Successfully', 'data'=>'']);
    }

}
