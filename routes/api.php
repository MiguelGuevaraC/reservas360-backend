<?php

use App\Http\Controllers\BranchInfoController;
use App\Http\Controllers\CompanyController;
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

    
    require __DIR__ . '/Api/AuthApi.php';//AUTHENTICATE
    require __DIR__ . '/Api/SearchApi.php';// SEARCH
    require __DIR__ . '/Api/ClientApi.php'; //CLIENTS
    require __DIR__ . '/Api/CompanyApi.php'; //CLIENTS
    require __DIR__ . '/Api/BranchOfficeApi.php'; //BRANCHOFFICE
    require __DIR__ . '/Api/CategoryApi.php'; //CATEGORY
    require __DIR__ . '/Api/ProductApi.php'; //CATEGORY
    require __DIR__ . '/Api/PersonApi.php'; //CATEGORY
});
