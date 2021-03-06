<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'orders'], function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/{id}', [OrderController::class, 'show']);
    Route::put('/{id}/change-status', [OrderController::class, 'changeStatus']);
    Route::post('/{id}/finish', [OrderController::class, 'finish']);

    Route::post('/products/{productId}', [OrderController::class, 'addProductWithoutOrder']);
    Route::post('/{orderId}/products/{productId}', [OrderController::class, 'addProductWithOrder']);
    Route::delete('/{orderId}/products/{productId}', [OrderController::class, 'removeProduct']);
});

Route::group(['prefix' => 'products'], function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/search', [ProductController::class, 'search']);
});
