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

        $response = ['status' => true, 'message' => '', 'user' => [$nextofkin]];
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
     *               required={"name","surname","mobile_num","nat_id","date_of_birth", "gender", "relationship","address"},
     *               @OA\Property(property="name", type="text"),
     *               @OA\Property(property="surname", type="text"),
     *               @OA\Property(property="mobile_num", type="text"),
     *               @OA\Property(property="nat_id", type="text"),
     *               @OA\Property(property="date_of_birth", type="date"),
     *               @OA\Property(property="gender", type="text"),
     *               @OA\Property(property="relationship", type="text"),
     *               @OA\Property(property="address", type="text"),
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
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'date_of_birth' => 'required|date',
                'mobile_num' => 'required|unique:next_of_kin',
                'gender' => 'required',
                'relationship' => 'required',
                'nat_id' => 'required|unique:next_of_kin',
                'address' => 'required|max:255'
            ]);
            if ($validator->fails()) {
                return response(['status' => false, 'message' => 'There were some problems with your input',
                    'data' => $validator->errors()]);
            }

            $request['user_id'] = Auth::user()->id;
            $request['name'] = $request->input('name');
            $request['surname'] = $request->input('surname');
            $request['date_of_birth'] = $request->input('date_of_birth');
            $request['mobile_num'] = $request->input('mobile_num');
            $request['gender'] = $request->input('gender');
            $request['relationship'] = $request->input('relationship');
            $request['nat_id'] = $request->input('nat_id');
            $request['address'] = $request->input('address');
            $nextOfkin = NextOfKin::create($request->toArray());
            $nextOfkin->save();

            $response = ['status' => true, 'message' => 'Data Saved Successfully', 'data' => [$nextOfkin]];
            return response($response, 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\NextOfKin $nextOfKin
     * @return \Illuminate\Http\Response
     */
    public function show(NextOfKin $nextOfKin)
    {
        //
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
     *               required={"name","surname","mobile_num","nat_id","date_of_birth", "gender", "relationship","address"},
     *               @OA\Property(property="name", type="text"),
     *               @OA\Property(property="surname", type="text"),
     *               @OA\Property(property="mobile_num", type="text"),
     *               @OA\Property(property="nat_id", type="text"),
     *               @OA\Property(property="date_of_birth", type="date"),
     *               @OA\Property(property="gender", type="text"),
     *               @OA\Property(property="relationship", type="text"),
     *               @OA\Property(property="address", type="text"),
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
                'name' => 'required|string|max:255',
                'surname' => 'required|string|max:255',
                'date_of_birth' => 'required|date',
                'mobile_num' => 'required|unique:next_of_kin',
                'gender' => 'required',
                'relationship' => 'required',
                'nat_id' => 'required|unique:next_of_kin',
                'address' => 'required|max:255'
            ]);
            if ($validator->fails()) {
                return response(['status' => false, 'message' => 'There were some problems with your input',
                    'data' => $validator->errors()]);
            }

            $nextOfKin->user_id = Auth::user()->id;
            $nextOfKin->name = $request->input('name');
            $nextOfKin->surname = $request->input('surname');
            $nextOfKin->date_of_birth = $request->input('date_of_birth');
            $nextOfKin->mobile_num = $request->input('mobile_num');
            $nextOfKin->gender = $request->input('gender');
            $nextOfKin->relationship = $request->input('relationship');
            $nextOfKin->nat_id = $request->input('nat_id');
            $nextOfKin->address = $request->input('address');
            $nextOfKin->save();

            if ($nextOfKin->save()) {
                $response = ['status' => true, 'message' => 'Next of Kin Details Updated Successfully', 'data' => [$nextOfKin]];
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
