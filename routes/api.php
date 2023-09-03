<?php

use App\Http\Controllers\Api\Category\CategoryController;
use App\Http\Controllers\Api\Category\CategoryProductController;
use App\Http\Controllers\Api\Category\CategoryStatusController;
use App\Http\Controllers\Api\Product\ProductController;
use App\Http\Controllers\Api\Product\ProductImageController;
use App\Http\Controllers\Api\Product\ProductStatusController;
use App\Http\Controllers\Auth\TokenLoginController;
use App\Http\Controllers\Auth\TokenLogoutController;
use App\Http\Controllers\Auth\TokenRegisterController;
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
Route::post('/logout', TokenLogoutController::class)->middleware('auth:sanctum');

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
    
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
    Route::put('/categories/{category}/status', CategoryStatusController::class);
});
