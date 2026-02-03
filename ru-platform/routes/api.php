<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/menus', [MenuController::class, 'index']);
Route::get('/menus/{menu}', [MenuController::class, 'show']);
Route::get('/dishes', [DishController::class, 'index']);
Route::get('/dishes/{dish}', [DishController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);

Route::middleware('api.auth')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    Route::apiResource('users', UserController::class);
    Route::apiResource('categories', CategoryController::class)->except(['index']);
    Route::apiResource('dishes', DishController::class)->except(['index', 'show']);
    Route::apiResource('ingredients', IngredientController::class);
    Route::apiResource('menus', MenuController::class)->except(['index', 'show']);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel']);
    Route::post('/orders/{order}/pay', [PaymentController::class, 'pay']);

    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::get('/reviews', [ReviewController::class, 'index']);

    Route::get('/stock/movements', [StockController::class, 'index']);
    Route::post('/stock/movements', [StockController::class, 'store']);

    Route::get('/stats/attendance', [StatsController::class, 'attendance']);
    Route::get('/stats/top-dishes', [StatsController::class, 'topDishes']);
    Route::get('/stats/stock-alerts', [StatsController::class, 'stockAlerts']);
});
