<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Deductions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class DeductionsController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/deductions",
     *      operationId="getDeductionsList",
     *      tags={"Deductions"},
     *      summary="Get list of Deductions",
     *      description="Returns list of Deductions",
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
        $deductions = Deductions::all();

        $response = ['status'=>true,'message'=>'','data' => $deductions];
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
     * path="/api/deductions",
     * operationId="DeductionsCreate",
     * tags={"Deductions"},
     * summary="Save Deductions Details",
     * description="Saving Deductions details",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"joining_fee","annual_sub","shares","monthly_saving"},
     *               @OA\Property(property="joining_fee", type="text"),
     *               @OA\Property(property="annual_sub", type="text"),
     *               @OA\Property(property="shares", type="text"),
     *               @OA\Property(property="monthly_saving", type="text"),
     *
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Deductions Saved Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Deductions Saved Successfully",
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
                'joining_fee' => 'required5',
                'annual_sub' => 'required',
                'shares' => 'required',
                'monthly_saving' => 'required',
            ]);
            if ($validator->fails()) {
                return response(['status' => false, 'message' => 'There were some problems with your input',
                    'data' => $validator->errors()]);
            }


            $request['joining_fee']=  $request->input('joining_fee');
            $request['monthly_saving']=  $request->input('monthly_saving');
            $request['annual_sub']=  $request->input('annual_sub');
            $request['shares']=  $request->input('shares');

            $deductions = Deductions::create($request->toArray());
            $deductions->save();

            $response = ['status'=>true,'message'=>'Data Saved Successfully','data' => $deductions];
            return response($response, 200);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/deductions/{id}",
     *      operationId="getOneDeductionsList",
     *      tags={"Deductions"},
     *      summary="Get Deductions Details",
     *      description="Returns  Deduction",
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
        $deductions = Deductions::findorfail($id);

        $r= ['status' => true,'message'=>'Deduction Details','data'=>$deductions];
        return response($r,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Deductions  $deductions
     * @return \Illuminate\Http\Response
     */
    public function edit(Deductions $deductions)
    {
        //
    }


    /**
     * @OA\Put(
     * path="/api/deductions/{id}",
     * operationId="DeductionsByUpdate",
     * tags={"Deductions"},
     * summary="Update Deductions Details",
     * description="Updating Deductions details",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"joining_fee","annual_sub","shares","monthly_saving"},
     *               @OA\Property(property="joining_fee", type="text"),
     *               @OA\Property(property="annual_sub", type="text"),
     *               @OA\Property(property="shares", type="text"),
     *               @OA\Property(property="monthly_saving", type="text"),
     *
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Deductions Updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Deductions Updated Successfully",
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
        $deductions = Deductions::findOrFail($id);
        {
            $validator = Validator::make($request->all(), [
                'joining_fee' => 'required',
                'annual_sub' => 'required',
                'shares' => 'required',
                'monthly_saving' => 'required',
            ]);
            if ($validator->fails()) {
                return response(['status' => false, 'message' => 'There were some problems with your input',
                    'data' => $validator->errors()]);
            }

            $deductions->joining_fee =  $request->input('joining_fee');
            $deductions->monthly_saving =  $request->input('monthly_saving');
            $deductions->annual_sub =  $request->input('annual_sub');
            $deductions->shares =  $request->input('shares');

            $deductions->save();

            if($deductions->save()){
                $response = ['status'=>true,'message'=>'Deductions Details Updated Successfully','data' => $deductions];
                return response($response, 200);
            }

        }
    }

    /**
     * @OA\Delete(
     * path="/api/deductions/{id}",
     * operationId="DeductionsDelete",
     * tags={"Deductions"},
     * summary="Delete Deductions Details",
     * description="Deleting Deductions details",
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
        $deductions = Deductions::findOrFail($id);
        $deductions->delete();

        return  response(['status'=>true,'message'=>'Deductions Details Deleted Successfully', 'data'=>'']);
    }

}
