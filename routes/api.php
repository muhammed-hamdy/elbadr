<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\BalanceController;
use App\Http\Controllers\Api\AuthController;

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
// Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function() {

    Route::group(['prefix' => 'products', 'as' => 'products.'], function() {
        Route::get('/me', [ProductController::class, 'myProducts']);
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/purchase', [ProductController::class, 'purchase']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::patch('/{id}', [ProductController::class, 'update']);
    });

    Route::group(['prefix' => 'orders', 'as' => 'orders.'], function() {
        Route::get('/me', [OrderController::class, 'myOrders']);
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/{id}', [OrderController::class, 'report']);
    });

    Route::group(['prefix' => 'balance', 'as' => 'balance.'], function() {
        Route::get('/', [BalanceController::class, 'index']);
        Route::get('/me', [BalanceController::class, 'myBalance']);
        Route::post('/confirm-balance/{order_id}', [BalanceController::class, 'confirmBalance']);
        Route::post('/transfer-balance', [BalanceController::class, 'transferBalance']);
        Route::post('/', [BalanceController::class, 'RequestBalance']);
    });
});
