<?php

use App\Http\Controllers\BranchInfoController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::get('product', [ProductController::class, 'index']);
    Route::post('product', [ProductController::class, 'post']);
    Route::get('product/{id}', [ProductController::class, 'show']);
    Route::put('product/{id}', [ProductController::class, 'put']);
    Route::delete('product/{id}', [ProductController::class, 'destroy']);

    Route::get('getdata-product', [ProductController::class, 'getProducts']);
});