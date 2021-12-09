<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'api'], function () {
    
    # All Products
    Route::get('products/all', [ProductController::class, 'all']);
    Route::get('product/search', [ProductController::class, 'search']);

    Route::group(['middleware' => 'auth:api'], function () {
        # Auth Routes
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('profile', [AuthController::class, 'profile']);

        # Product Routes
        Route::get('product/search-my-store', [ProductController::class, 'searchMyStore']);
        Route::resource('products', ProductController::class);
    });
});
