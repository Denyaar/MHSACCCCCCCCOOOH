<?php

use App\Http\Controllers\API\ApiAuthController;
use App\Http\Controllers\API\ApiUserDetails;
use App\Http\Controllers\API\BankingDetailsController;
use App\Http\Controllers\API\DeductionsController;
use App\Http\Controllers\API\EmploymentDetailsController;
use App\Http\Controllers\API\LoansController;
use App\Http\Controllers\API\NextOfKinController;
use App\Http\Controllers\API\RequirementsController;
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
        Route::post('/forget_password', [ApiAuthController::class, 'forgetPassword']);
        Route::post('/reset', [ApiAuthController::class, 'reset'])->name('password.reset');


        Route::group(['middleware' => ['auth:api','json.response'] ], function () {

            Route::get('/nextofkin', [NextOfKinController::class, 'index']);
           // Route::post('/nextofkin', [NextOfKinController::class, 'store']);
            Route::put('/nextofkin/{id}', [NextOfKinController::class, 'update']);
            Route::get('/nextofkin/{id}', [NextOfKinController::class, 'show']);
            Route::delete('/nextofkin/{id}', [NextOfKinController::class, 'destroy']);


            Route::get('/userdetails', [ApiUserDetails::class, 'index']);
            Route::post('/userdetails', [ApiUserDetails::class, 'store']);
            Route::put('/userdetails/{id}', [ApiUserDetails::class, 'update']);
            Route::get('/userdetails/{id}', [ApiUserDetails::class, 'show']);
            Route::get('/user_approve/{id}', [ApiUserDetails::class, 'Approve']);
            Route::delete('/userdetails/{id}', [ApiUserDetails::class, 'destroy']);
            Route::delete('/forcedeleteuserdetails/{id}', [ApiUserDetails::class, 'ForceDelete']);

            Route::get('/employmentdetails', [EmploymentDetailsController::class, 'index']);
            //Route::post('/employmentdetails', [EmploymentDetailsController::class, 'store']);
            Route::put('/employmentdetails/{id}', [EmploymentDetailsController::class, 'update']);
            Route::get('/approve_emplomentdetails/{id}', [EmploymentDetailsController::class, 'show']);
            Route::get('/appempdetails/{id}', [EmploymentDetailsController::class, 'Approve']);
            Route::delete('/employmentdetails/{id}', [EmploymentDetailsController::class, 'destroy']);


            Route::get('/loan', [LoansController::class, 'index']);
            Route::post('/loan', [LoansController::class, 'store']);
            Route::put('/loan/{id}', [LoansController::class, 'update']);
            Route::get('/loan/{id}', [LoansController::class, 'show']);
            Route::get('/loan_approve/{id}', [LoansController::class, 'Approve']);
            Route::delete('/loan/{id}', [LoansController::class, 'destroy']);
            Route::delete('/forcedeleteloan/{id}', [LoansController::class, 'ForceDelete']);

            Route::get('/bankingdetails', [BankingDetailsController::class, 'index']);
           // Route::post('/bankingdetails', [BankingDetailsController::class, 'store']);
            Route::put('/bankingdetails/{id}', [BankingDetailsController::class, 'update']);
            Route::get('/bankingdetails/{id}', [BankingDetailsController::class, 'show']);
            Route::get('/approve_bankingdetails/{id}', [BankingDetailsController::class, 'Approve']);
            Route::delete('/bankingdetails/{id}', [BankingDetailsController::class, 'destroy']);


            Route::get('/deductions', [DeductionsController::class, 'index']);
            Route::post('/deductions', [DeductionsController::class, 'store']);
            Route::get('/deductions/{id}', [DeductionsController::class, 'show']);
            Route::put('/deductions/{id}', [DeductionsController::class, 'update']);
            Route::delete('/deductions/{id}', [DeductionsController::class, 'destroy']);

            Route::get('/requirements', [RequirementsController::class, 'index']);
           // Route::post('/requirements_nat_id', [RequirementsController::class, 'store']);
            Route::post('/requirements_payslip', [RequirementsController::class, 'storePaySlip']);
            Route::post('/requirements_bank_statement', [RequirementsController::class, 'storeBankStatement']);
            Route::delete('/requirements/{id}', [RequirementsController::class, 'destroy']);


        });

    });
});
