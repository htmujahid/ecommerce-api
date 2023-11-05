<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Customer\CategoryController;
use App\Http\Controllers\Api\V1\Customer\CategoryProductController;
use App\Http\Controllers\Api\V1\Customer\MarqueeController;
use App\Http\Controllers\Api\V1\Customer\OrderController;
use App\Http\Controllers\Api\V1\Customer\ProductController;
use App\Http\Controllers\Api\V1\Customer\SellerController;
use App\Http\Controllers\Api\V1\Customer\SellerProductController;
use App\Http\Controllers\Api\V1\Customer\SocialPostController;
use App\Http\Controllers\Api\V1\Customer\TestimonialController;
use App\Http\Controllers\Api\V1\Customer\BecomeSellerController;
use App\Http\Controllers\Api\V1\Customer\ContactMessageController;
use App\Http\Controllers\Api\V1\Customer\ProductCommentController;
use App\Http\Controllers\Api\V1\Customer\ShipmentPriceController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// group of customer routes with prefix api/v1/customer
Route::get('test', function(){
    return ['message' => 'Hello World!'];
});

Route::group(['prefix' => 'v1/customers'], function () {
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product:slug}', [ProductController::class, 'show']);
    Route::get('/products/{product:slug}/comments', [ProductCommentController::class, 'index']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category:slug}/products', [CategoryProductController::class, 'index']);
    Route::get('/sellers', [SellerController::class, 'index']);
    Route::get('/sellers/{seller}', [SellerController::class, 'show']);
    Route::get('/sellers/{seller}/products', [SellerProductController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/shipment-price', [ShipmentPriceController::class, 'index']);
    
    Route::get('/marquees', [MarqueeController::class, 'index']);
    Route::get('/testimonials', [TestimonialController::class, 'index']);
    Route::get('/social-posts', [SocialPostController::class, 'index']);

    Route::post('/become-sellers', [BecomeSellerController::class, 'store']);
    Route::post('/contact-messages', [ContactMessageController::class, 'store']);
});