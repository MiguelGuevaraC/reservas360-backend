<?php

use App\Http\Controllers\BranchInfoController;
use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;


Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::get('branchoffice', [BranchInfoController::class, 'index']);
    Route::post('branchoffice', [BranchInfoController::class, 'store']);
    Route::get('branchoffice/{id}', [BranchInfoController::class, 'show']);
    Route::put('branchoffice/{id}', [BranchInfoController::class, 'update']);
    Route::delete('branchoffice/{id}', [BranchInfoController::class, 'destroy']);

    Route::get('getdata-branchoffice', [BranchInfoController::class, 'getBranchInfo']);
});
