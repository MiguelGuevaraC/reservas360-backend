<?php

use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;

Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::get('company', [CompanyController::class, 'index']);
    Route::post('company', [CompanyController::class, 'post']);
    Route::get('company/{id}', [CompanyController::class, 'show']);
    Route::put('company/{id}', [CompanyController::class, 'put']);
    Route::delete('company/{id}', [CompanyController::class, 'destroy']);

    Route::post('getdata-company', [CompanyController::class, 'getCompanyData']);
});
