<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Products\ProductController;
use App\Http\Controllers\Api\Tickets\TicketController;
use App\Http\Controllers\Api\Internal\TwoStepCodeGenerateController;
use App\Http\Controllers\Api\Internal\TwoStepCodeValidateController;
use App\Http\Controllers\Api\Auth\AuthController;

Route::group(['middleware' => 'goapi', 'prefix' => 'internal'], function () {
  Route::get('twostep/generate', TwoStepCodeGenerateController::class);
  Route::post('twostep/validate', TwoStepCodeValidateController::class);
});

Route::group(['prefix' => 'staff', 'middleware' => ['auth:api','staff.only:api']], function () {
  
    Route::group(['prefix' => 'products'], function () {
        Route::group(['middleware' => 'admin.only:api'], function(){
            Route::delete('delete', [ProductController::class, 'delete']);
            Route::put('status', [ProductController::class, 'status']);
        });
        Route::get('all', [ProductController::class, 'all']);
    });
    Route::resource('products', ProductController::class, ['only' => ['index', 'show', 'store','update']]);

    Route::group(['prefix' => 'tickets'], function () {
        Route::delete('delete', [TicketController::class, 'delete']);
        Route::put('status', [TicketController::class, 'status']);
        Route::get('all', [TicketController::class, 'all']);
    });

    // Route::group([
    //     'prefix' => 'auth',
    //     'middleware' => ['auth:api', 'staff.only:api']
    // ], function () {
    //     Route::get('info', [AuthController::class, 'info']);
    // });

});
