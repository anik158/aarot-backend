<?php

use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SizeController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/featured', [ProductController::class, 'featured']);
Route::get('/categories',[CategoryController::class, 'index']);
Route::get('/sizes',[SizeController::class, 'index']);
Route::get('/colors',[ColorController::class, 'index']);

Route::get('/products/{id}', [ProductController::class, 'show']);

Route::post('/login', [UserController::class, 'login']);
Route::post('/users', [UserController::class, 'store']);
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth:api');
Route::post('/cart/merge', [CartController::class, 'merge']);

Route::middleware('auth:api')->group(function () {
    Route::post('/checkout', [CheckoutController::class, 'store']);
    Route::get('/my-orders', [OrderController::class, 'index']);
    Route::get('/orders/{orderNumber}', [OrderController::class, 'showByOrderNumber']);
    Route::post('/favorites/{productId}', [FavoriteController::class, 'toggle']);
    Route::get('/favorites/{productId}/status', [FavoriteController::class, 'checkStatus']);
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::get('/my-reviews', [ReviewController::class, 'myReviews']);
    Route::get('/profile', [UserController::class, 'profile']);
    Route::post('/profile/update', [UserController::class, 'updateProfile']);
    Route::post('/payment/stripe/session', [PaymentController::class, 'createStripeSession']);});

Route::prefix('cart')->group(function () {
    Route::post('/add', [CartController::class, 'add']);
    Route::post('/update', [CartController::class, 'updateQuantity']);
    Route::post('/remove', [CartController::class, 'remove']);   // or DELETE
    Route::get('/', [CartController::class, 'index']);
    Route::delete('/clear', [CartController::class, 'clear']);
});
