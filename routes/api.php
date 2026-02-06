<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ProductController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rutas de Productos
    Route::apiResource('products', ProductController::class);
    Route::get('products/{product}/prices', [ProductController::class, 'prices']);
    Route::post('products/{product}/prices', [ProductController::class, 'storePrice']);
});
