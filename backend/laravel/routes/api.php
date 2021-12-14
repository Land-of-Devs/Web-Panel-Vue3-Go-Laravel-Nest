<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Products\ProductController;
use App\Http\Api\Tickets\TicketsController;

Route::group(['middleware' => 'goapi', 'prefix' => 'internal'], function () {
  Route::get('twostep', [TwoStepCodeController::class, 'generate']);
  Route::get('twostep', [TwoStepCodeController::class, 'verify']);
});

Route::group(['prefix' => 'staff'], function () {
  
    Route::group(['prefix' => 'products'], function () {
        Route::delete('delete', [ProductController::class, 'delete']);
    });

    Route::resource('products', ProductController::class, ['only' => ['index', 'show', 'store','update']]);
});
