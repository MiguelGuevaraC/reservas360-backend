<?php

use App\Http\Controllers\PersonaController;
use Illuminate\Support\Facades\Route;

Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::get('company', [PersonaController::class, 'index']);
    Route::post('company', [PersonaController::class, 'store']);
    Route::get('company/{id}', [PersonaController::class, 'show']);
    Route::put('company/{id}', [PersonaController::class, 'update']);
    Route::delete('company/{id}', [PersonaController::class, 'destroy']);
});
