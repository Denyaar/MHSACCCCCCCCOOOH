<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Requirements;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RequirementsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requirements = DB::table('requirements')
            ->join('users','users.id','=','requirements.user_id')
            ->select('requirements.*','users.first_name','users.last_name','users.name')
            ->get();
        $response = ['status'=>true,'message'=>'','data' => $requirements];
        return response($response, 200);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'user_id'                  => 'required|unique:requirements',
                'copy_of_nat_id'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096|unique:requirements',
                'payslip' => 'required|mimes:csv,txt,xlx,xls,pdf|max:2048|unique:requirements',
                'bank_statement' => 'required|mimes:csv,txt,xlx,xls,pdf|max:2048|unique:requirements',

            ]
        );

        if ($validator->fails()) {
            $response=['status' => false, 'message' => 'Invalid  file',
                'data' => $validator->errors()];
            return  response($response,415);
        }

        $idname = $request->file('copy_of_nat_id')->getClientOriginalName();
        $payslip_name = $request->file('payslip')->getClientOriginalName();
        $bank_statement_name = $request->file('bank_statement')->getClientOriginalName();

       // $input_files=[$request->file('copy_of_nat_id'),$request->file('payslip'),$request->file('bank_statement')];

        if ($request->file('copy_of_nat_id')->isValid()) {
            $idPhoto = $request->file('copy_of_nat_id');
            Storage::disk('public')->put('user-ids/' . $idname, File::get($idPhoto));
        } else {
            $response=['status' => false, 'message' => 'Invalid  photo',
                'data' => $validator->errors()];
            return  response($response,415);
        }


        if ($request->file('payslip')->isValid()) {
            $payslip_pdf = $request->file('payslip');
            Storage::disk('public')->put('payslip/' . $payslip_name, File::get($payslip_pdf));
        } else {
            $response=['status' => false, 'message' => 'Invalid payslip document',
                'data' => $validator->errors()];
            return  response($response,415);
        }


        if ($request->file('bank_statement')->isValid()) {
            $bank_statement = $request->file('bank_statement');
            Storage::disk('public')->put('bank-statements/' . $bank_statement_name, File::get($bank_statement));
        } else {
            $response=['status' => false, 'message' => 'Invalid  bank statement',
                'data' => $validator->errors()];
            return  response($response,415);
        }


        $requirements = Requirements::create([
            'user_id'              => $request->input('user_id'),
            'payslip'              => $idname,
            'copy_of_nat_id'       => $payslip_name,
            'bank_statement'       => $bank_statement_name,
        ]);
        $requirements->save();

        if($requirements->save()){
            $response = ['status'=>true,'message'=>'User Documents updated Successfully','data' =>$requirements];
            return response($response, 200);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Requirements  $requirements
     * @return \Illuminate\Http\Response
     */
    public function show(Requirements $requirements)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Requirements  $requirements
     * @return \Illuminate\Http\Response
     */
    public function edit(Requirements $requirements)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Requirements  $requirements
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Requirements $requirements)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Requirements  $requirements
     * @return \Illuminate\Http\Response
     */
    public function destroy(Requirements $requirements)
    {
        //
    }
}
