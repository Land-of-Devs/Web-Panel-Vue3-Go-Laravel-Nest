<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Products\ProductController;
use App\Http\Api\Tickets\TicketsController;
use App\Http\Controllers\Api\Internal\TwoStepCodeGenerateController;
use App\Http\Controllers\Api\Internal\TwoStepCodeValidateController;
use App\Http\Controllers\Api\Auth\AuthController;

Route::group(['middleware' => 'goapi', 'prefix' => 'internal'], function () {
  Route::get('twostep/generate', TwoStepCodeGenerateController::class);
  Route::post('twostep/validate', TwoStepCodeValidateController::class);
});

Route::group(['prefix' => 'staff'], function () {

  Route::group([
    'prefix' => 'auth',
    'middleware' => ['auth:api', 'staff.only:api']
  ], function () {
    Route::get('info', [AuthController::class, 'info']);
  });

  Route::group(['prefix' => 'products'], function () {
    Route::delete('delete', [ProductController::class, 'delete']);
  });

  Route::resource('products', ProductController::class, ['only' => ['index', 'show', 'store', 'update']]);
});
