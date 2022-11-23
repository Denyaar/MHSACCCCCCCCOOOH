<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\EmploymentDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class EmploymentDetailsController extends  Controller
{
    /**
     * @OA\Get(
     *      path="/api/employmentdetails",
     *      operationId="getEmploymentDetailsList",
     *      tags={"EmploymentDetails"},
     *      summary="Get list Employment Details",
     *      description="Returns list of Employment Details",
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
        $employmentDetails = EmploymentDetails::all();

        $response = ['status' => true, 'message' => '', 'user' => [$employmentDetails]];
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
     * path="/api/employmentdetails",
     * operationId="EmploymentDetailsCreate",
     * tags={"EmploymentDetails"},
     * summary="Save Employment  Details",
     * description="Saving Employment  details",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"employer","employer_phone","department","grade","position_at_work","date_of_employment","employer_address"},
     *               @OA\Property(property="employer", type="text"),
     *               @OA\Property(property="employer_phone", type="text"),
     *               @OA\Property(property="position_at_work", type="text"),
     *               @OA\Property(property="grade", type="text"),
     *               @OA\Property(property="date_of_employment", type="date"),
     *               @OA\Property(property="employer_address", type="text"),
     *               @OA\Property(property="department", type="text"),
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
                'department' => 'required',
                'employer' => 'required',
                'date_of_employment' => 'required|date',
                'employer_phone' => 'required|unique:employment_details',
                'grade' => 'required',
                'position_at_work' => 'required',
                'employer_address' => 'required|max:255'
            ]);
            if ($validator->fails()) {
                return response(['status' => false, 'message' => 'There were some problems with your input',
                    'data' => $validator->errors()]);
            }

            $request['user_id'] = Auth::user()->id;

            $request['date_of_employment'] = $request->input('date_of_employment');
            $request['employer_phone'] = $request->input('employer_phone');
            $request['grade'] = $request->input('grade');
            $request['employer_address'] = $request->input('employer_address');
            $request['department'] = $request->input('department');
            $request['employer'] = $request->input('employer');
            $request['position_at_work'] = $request->input('position_at_work');
            $employmentDetails = EmploymentDetails::create($request->toArray());
            $employmentDetails->save();

            $response = ['status' => true, 'message' => 'Data Saved Successfully', 'data' => [$employmentDetails]];
            return response($response, 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\EmploymentDetails $employmentDetails
     * @return \Illuminate\Http\Response
     */
    public function show(EmploymentDetails $employmentDetails)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\EmploymentDetails $employmentDetails
     * @return \Illuminate\Http\Response
     */
    public function edit(EmploymentDetails $employmentDetails)
    {
        //
    }


    /**
     * @OA\Put(
     * path="/api/employmentdetails/{id}",
     * operationId="EmploymentDetailsUpdate",
     * tags={"EmploymentDetails"},
     * summary="Update Employment  Details",
     * description="Updating Employment  details",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"employer","department","employer_phone","grade","position_at_work","date_of_employment","employer_address"},
     *               @OA\Property(property="employer", type="text"),
     *               @OA\Property(property="employer_phone", type="text"),
     *               @OA\Property(property="position_at_work", type="text"),
     *               @OA\Property(property="grade", type="text"),
     *               @OA\Property(property="date_of_employment", type="date"),
     *               @OA\Property(property="employer_address", type="text"),
     *               @OA\Property(property="department", type="text"),
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
    public function update(Request $request,$id)
    {
        $employmentDetails = EmploymentDetails::findOrFail($id);
        {
            $validator = Validator::make($request->all(), [
                'employer' => 'required',
                'date_of_employment' => 'required|date',
                'employer_phone' => 'required|unique:employment_details',
                'grade' => 'required',
                'department' => 'required',
                'position_at_work' => 'required',
                'employer_address' => 'required|max:255'
            ]);
            if ($validator->fails()) {
                return response(['status' => false, 'message' => 'There were some problems with your input',
                    'data' => $validator->errors()]);
            }

            $employmentDetails->user_id = Auth::user()->id;
            $employmentDetails->employer = $request->input('employer');
            $employmentDetails->date_of_employment = $request->input('date_of_employment');
            $employmentDetails->employer_phone = $request->input('employer_phone');
            $employmentDetails->department = $request->input('department');
            $employmentDetails->grade = $request->input('grade');
            $employmentDetails->position_at_work = $request->input('position_at_work');
            $employmentDetails->employer_address = $request->input('employer_address');
            $employmentDetails->save();

            if ($employmentDetails->save()) {
                $response = ['status' => true, 'message' => 'User Details Details Updated Successfully', 'data' => [$employmentDetails]];
                return response($response, 200);
            }

        }
    }

    /**
     * @OA\Delete(
     * path="/api/employmentdetails/{id}",
     * operationId="EmploymentDetailsDelete",
     * tags={"EmploymentDetails"},
     * summary="Delete Employment Details",
     * description="Deleting Employment  details",
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
        $employmentdetails = EmploymentDetails::findOrFail($id);
        $employmentdetails->delete();


        return response(['status' => true, 'message' => 'User Details Deleted Successfully', 'data' => []]);
    }
}
