<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\NextOfKin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use OpenApi\Annotations as OA;

class NextOfKinController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/nextofkin",
     *      operationId="getnextofkinList",
     *      tags={"NextOfKin"},
     *      summary="Get list of Next of kins",
     *      description="Returns list of Next of kins",
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
        $nextofkin = NextOfKin::all();

        $response = ['status' => true, 'message' => '', 'data' => $nextofkin];
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
     * path="/api/nextofkin",
     * operationId="NextOfKinCreate",
     * tags={"NextOfKin"},
     * summary="Save Next of Kin Details",
     * description="Saving next of kin details",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"next_of_kin_name","next_of_kin_surname","next_of_kin_mobile_num","next_of_kin_nat_id","next_of_kin_date_of_birth", "next_of_kin_gender", "relationship","next_of_kin_address"},
     *               @OA\Property(property="next_of_kin_name", type="text"),
     *               @OA\Property(property="next_of_kin_surname", type="text"),
     *               @OA\Property(property="next_of_kin_mobile_num", type="text"),
     *               @OA\Property(property="next_of_kin_nat_id", type="text"),
     *               @OA\Property(property="next_of_kin_date_of_birth", type="date"),
     *               @OA\Property(property="next_of_kin_gender", type="text"),
     *               @OA\Property(property="relationship", type="text"),
     *               @OA\Property(property="next_of_kin_address", type="text"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Next Of Kin Saved Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Next Of Kin Saved Successfully",
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

            $request['user_id'] = Auth::user()->id;
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

            $response = ['status' => true, 'message' => 'Data Saved Successfully', 'data' => $nextOfkin];
            return response($response, 200);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/nextofkin/{id}",
     *      operationId="getEmployeenextofkinList",
     *      tags={"NextOfKin"},
     *      summary="Get Employee Next of kin Details",
     *      description="Returns Details of Next of kins",
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
        $nextOfKin = NextOfKin::findorfail($id);

        $response=['status'=>true,'message'=>'Employee Next of Kin Details','data'=>$nextOfKin];

        return response($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\NextOfKin $nextOfKin
     * @return \Illuminate\Http\Response
     */
    public function edit(NextOfKin $nextOfKin)
    {
        //
    }


    /**
     * @OA\Put(
     * path="/api/nextofkin/{id}",
     * operationId="NextOfKinUpdate",
     * tags={"NextOfKin"},
     * summary="Update Next of Kin Details",
     * description="Updating next of kin details",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"next_of_kin_name","next_of_kin_surname","next_of_kin_mobile_num","next_of_kin_nat_id","next_of_kin_date_of_birth", "next_of_kin_gender", "relationship","next_of_kin_address"},
     *               @OA\Property(property="next_of_kin_name", type="text"),
     *               @OA\Property(property="next_of_kin_surname", type="text"),
     *               @OA\Property(property="next_of_kin_mobile_num", type="text"),
     *               @OA\Property(property="next_of_kin_nat_id", type="text"),
     *               @OA\Property(property="next_of_kin_date_of_birth", type="date"),
     *               @OA\Property(property="next_of_kin_gender", type="text"),
     *               @OA\Property(property="relationship", type="text"),
     *               @OA\Property(property="next_of_kin_address", type="text"),
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Next Of Kin Updated Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Next Of Kin Updated Successfully",
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
        $nextOfKin = NextOfKin::findorfail($id);
        {
            $validator = Validator::make($request->all(), [
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

            $nextOfKin->user_id = Auth::user()->id;
            $nextOfKin->next_of_kin_name = $request->input('next_of_kin_name');
            $nextOfKin->next_of_kin_surname = $request->input('next_of_kin_surname');
            $nextOfKin->next_of_kin_date_of_birth = $request->input('next_of_kin_date_of_birth');
            $nextOfKin->next_of_kin_mobile_num = $request->input('next_of_kin_mobile_num');
            $nextOfKin->next_of_kin_gender = $request->input('next_of_kin_gender');
            $nextOfKin->relationship = $request->input('relationship');
            $nextOfKin->next_of_kin_nat_id = $request->input('next_of_kin_nat_id');
            $nextOfKin->next_of_kin_address = $request->input('next_of_kin_address');
            $nextOfKin->save();

            if ($nextOfKin->save()) {
                $response = ['status' => true, 'message' => 'Next of Kin Details Updated Successfully', 'data' => $nextOfKin];
                return response($response, 200);
            }

        }
    }

    /**
     * @OA\Delete(
     * path="/api/nextofkin/{id}",
     * operationId="NextOfKinDelete",
     * tags={"NextOfKin"},
     * summary="Delete Next of Kin Details",
     * description="Deleting next of kin details",
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
        $nextofkin = NextOfKin::findOrFail($id);
        $nextofkin->delete();

        return response(['status' => true, 'message' => 'Next of Kin Details Deleted Successfully', 'data' => []]);
    }
}
