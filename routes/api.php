<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderProductController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'orders'], function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/{id}', [OrderController::class, 'show']);
    Route::put('/{id}/change-status', [OrderController::class, 'changeStatus']);
    Route::post('/{id}/finish', [OrderController::class, 'finish']);
});

Route::group(['prefix' => 'products'], function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/search', [ProductController::class, 'search']);
    Route::post('/{id}/add', [OrderProductController::class, 'addProduct']);
    Route::post('/{id}/remove', [OrderProductController::class, 'removeProduct']);
});
