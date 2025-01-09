<?php

use App\Http\Controllers\PersonaController;
use Illuminate\Support\Facades\Route;

Route::group(["middleware" => ["auth:sanctum"]], function () {
    Route::get('person', [PersonaController::class, 'index']);
    Route::post('person', [PersonaController::class, 'post']);
    Route::get('person/{id}', [PersonaController::class, 'show']);
    Route::put('person/{id}', [PersonaController::class, 'put']);
    Route::delete('person/{id}', [PersonaController::class, 'destroy']);

    Route::get('getdata-company', [PersonaController::class, 'getCompanyData']);
});
