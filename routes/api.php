<?php

use App\Http\Controllers\ApiExternaController;
use App\Http\Controllers\BranchInfoController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\UserController;
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

Route::post('login', [UserController::class, 'login']);
Route::post('user', [UserController::class, 'store']);


Route::group(["middleware" => ["auth:sanctum"]], function () {

    //AUTHENTICATE
    Route::get('logout', [UserController::class, 'logout']);
    Route::get('authenticate', [UserController::class, 'authenticate']);

// SEARCH
    Route::get('searchByDni/{dni}', [ApiExternaController::class, 'searchByDni']);
    Route::get('searchByRuc/{ruc}', [ApiExternaController::class, 'searchByRuc']);

//CLIENTS
Route::get('person', [PersonaController::class, 'index']);
//COMPANY & BRANCH

Route::get('branch-office', [BranchInfoController::class, 'getBranchInfo']);

});
