<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Products\ProductController;
use App\Http\Api\Tickets\TicketsController;

Route::group(['prefix' => 'staff'], function () {

    Route::group(['prefix' => 'products'], function () {
        Route::delete('delete', [ProductController::class, 'delete']);
    });
    Route::resource('products', ProductController::class, ['only' => ['index', 'show', 'store','update']]);
    
    Route::group(['middleware' => 'auth:api'], function () {
        # Auth Routes
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('profile', [AuthController::class, 'profile']);
    });
});
