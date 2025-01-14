<?php

use App\Http\Controllers\BranchInfoController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ServiceC;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;


Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::get('service', [ServiceController::class, 'index']);
    Route::post('service', [ServiceController::class, 'store']);
    Route::get('service/{id}', [ServiceController::class, 'show']);
    Route::put('service/{id}', [ServiceController::class, 'update']);
    Route::delete('service/{id}', [ServiceController::class, 'destroy']);

    Route::get('getdata-service', [ServiceController::class, 'getServiceData']);
});