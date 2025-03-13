<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::get('logout', [UserController::class, 'logout']);
    Route::get('authenticate', [UserController::class, 'authenticate']);
    Route::post('send-token', [UserController::class, 'send_token_sign_up']);
    Route::post('sign-up', [UserController::class, 'validate_mail']);
});
