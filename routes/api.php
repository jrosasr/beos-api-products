<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rutas de Productos con Autorización vía Middleware
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->middleware('can:viewAny,App\Models\Product');
        Route::post('/', [ProductController::class, 'store'])->middleware('can:create,App\Models\Product');
        Route::get('/{product}', [ProductController::class, 'show'])->middleware('can:view,product');
        Route::put('/{product}', [ProductController::class, 'update'])->middleware('can:update,product');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->middleware('can:delete,product');
        
        // Precios por producto
        Route::get('/{product}/prices', [ProductController::class, 'prices'])->middleware('can:viewPrices,product');
        Route::post('/{product}/prices', [ProductController::class, 'storePrice'])->middleware('can:addPrice,product');
    });
});
