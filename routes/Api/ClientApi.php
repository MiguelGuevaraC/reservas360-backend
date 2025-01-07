<?php

use App\Http\Controllers\PersonaController;
use Illuminate\Support\Facades\Route;

Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::get('company', [PersonaController::class, 'index']);
    Route::post('company', [PersonaController::class, 'post']);
    Route::get('company/{id}', [PersonaController::class, 'show']);
    Route::put('company/{id}', [PersonaController::class, 'put']);
    Route::delete('company/{id}', [PersonaController::class, 'destroy']);
});
