<?php

use App\Http\Controllers\Api\V1\Auth\TokenLoginController;
use App\Http\Controllers\Api\V1\Auth\TokenLogoutController;
use App\Http\Controllers\Api\V1\Auth\TokenRegisterController;
use App\Http\Controllers\Api\V1\Category\CategoryController;
use App\Http\Controllers\Api\V1\Category\CategoryProductController;
use App\Http\Controllers\Api\V1\Category\CategoryStatusController;
use App\Http\Controllers\Api\V1\Product\ProductController;
use App\Http\Controllers\Api\V1\Product\ProductImageController;
use App\Http\Controllers\Api\V1\Product\ProductStatusController;
use App\Http\Controllers\Api\V1\Product\ProductVariationController;
use App\Http\Controllers\Api\V1\Product\ProductVariationStockController;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/register', TokenRegisterController::class)->middleware('guest');
Route::post('/login', TokenLoginController::class)->middleware('guest');

// products endpoints
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

Route::get('/categories/{category}', [CategoryController::class, 'show']);
Route::get('/categories/{category:slug}/products', CategoryProductController::class);
// authenticated routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/categories', [CategoryController::class, 'index']);

    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
    Route::post('/products/{product}/image', [ProductImageController::class, 'store']);
    Route::put('/products/{product}/status', ProductStatusController::class);

    Route::post('/products/{product}/variations', [ProductVariationController::class, 'store']);
    Route::put('/products/{product}/variations/{variation}', [ProductVariationController::class, 'update']);
    Route::delete('/products/{product}/variations/{variation}', [ProductVariationController::class, 'destroy']);
    Route::put('/products/{product}/variations/{variation}/stock', [ProductVariationStockController::class, 'update']);
    
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
    Route::put('/categories/{category}/status', CategoryStatusController::class);

    Route::post('/logout', TokenLogoutController::class)->middleware('auth:sanctum');
});
