<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:api'])->group(function () {
    Route::apiResource('travel-orders', \App\Http\Controllers\API\TravelOrderController::class)
        ->except(['update', 'destroy']);

    Route::patch('travel-orders/{travel_order}/status',
        [\App\Http\Controllers\API\TravelOrderController::class, 'updateStatus']);

    Route::post('travel-orders/{travel_order}/cancel',
        [\App\Http\Controllers\API\TravelOrderController::class, 'cancel']);
});

// Rota de teste
Route::get('/test', function () {
    return response()->json(['message' => 'API está funcionando']);
});
