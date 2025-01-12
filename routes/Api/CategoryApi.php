<?php

use App\Http\Controllers\BranchInfoController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;


Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::get('category', [CategoryController::class, 'index']);
    Route::post('category', [CategoryController::class, 'store']);
    Route::get('category/{id}', [CategoryController::class, 'show']);
    Route::put('category/{id}', [CategoryController::class, 'update']);
    Route::delete('category/{id}', [CategoryController::class, 'destroy']);

    Route::get('getdata-category', [CategoryController::class, 'getCategories']);
});