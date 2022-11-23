<?php

use App\Http\Controllers\API\ApiAuthController;
use App\Http\Controllers\API\ApiUserDetails;
use App\Http\Controllers\API\BankingDetailsController;
use App\Http\Controllers\API\BanksBranchesController;
use App\Http\Controllers\API\BanksController;
use App\Http\Controllers\API\DeductionsController;
use App\Http\Controllers\API\EmploymentDetailsController;
use App\Http\Controllers\API\LoansController;
use App\Http\Controllers\API\NextOfKinController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['api','middleware' => ['json.response']], function () {

    Route::group(['middleware' => ['cors']], function () {

        Route::post('/logAsadmin', [ApiAuthController::class, 'Adminlogin'])->name('login.admin');
        Route::post('/login', [ApiAuthController::class, 'login'])->name('login.api');
        Route::post('/register', [ApiAuthController::class, 'register'])->name('register.api');





    Route::group(['middleware' => ['auth:api','json.response'] ], function () {

        Route::get('/nextofkin', [NextOfKinController::class, 'index']);
        Route::post('/nextofkin', [NextOfKinController::class, 'store']);
        Route::put('/nextofkin/{id}', [NextOfKinController::class, 'update']);
        Route::delete('/nextofkin/{id}', [NextOfKinController::class, 'destroy']);


        Route::get('/userdetails', [ApiUserDetails::class, 'index']);
        Route::post('/userdetails', [ApiUserDetails::class, 'store']);
        Route::put('/userdetails/{id}', [ApiUserDetails::class, 'update']);
        Route::delete('/userdetails/{id}', [ApiUserDetails::class, 'destroy']);

        Route::get('/employmentdetails', [EmploymentDetailsController::class, 'index']);
        Route::post('/employmentdetails', [EmploymentDetailsController::class, 'store']);
        Route::put('/employmentdetails/{id}', [EmploymentDetailsController::class, 'update']);
        Route::delete('/employmentdetails/{id}', [EmploymentDetailsController::class, 'destroy']);


        Route::get('/loan', [LoansController::class, 'index']);
        Route::post('/loan', [LoansController::class, 'store']);
        Route::put('/loan/{id}', [LoansController::class, 'update']);
        Route::delete('/loan/{id}', [LoansController::class, 'destroy']);
        Route::delete('/loan/{id}', [LoansController::class, 'ForceDelete']);

        Route::get('/bankingdetails', [BankingDetailsController::class, 'index']);
        Route::post('/bankingdetails', [BankingDetailsController::class, 'store']);
        Route::put('/bankingdetails/{id}', [BankingDetailsController::class, 'update']);
        Route::delete('/bankingdetails/{id}', [BankingDetailsController::class, 'destroy']);


        Route::get('/deductions', [DeductionsController::class, 'index']);
        Route::post('/deductions', [DeductionsController::class, 'store']);
        Route::put('/deductions/{id}', [DeductionsController::class, 'update']);
        Route::delete('/deductions/{id}', [DeductionsController::class, 'destroy']);



//
//    Route::resource('nextofkin', \App\Http\Controllers\API\NextOfKinController::class);
//
//    Route::resource('userdetails', \App\Http\Controllers\API\ApiUserDetails::class);
//
//    Route::resource('employmentdetails', \App\Http\Controllers\API\EmploymentDetailsController::class);
//
//    Route::resource('loan', \App\Http\Controllers\API\LoansController::class);
//
//    Route::resource('bankingdetails', \App\Http\Controllers\API\BankingDetailsController::class);
//
//    Route::resource('deductions', \App\Http\Controllers\API\DeductionsController::class);


    });

    });
});
