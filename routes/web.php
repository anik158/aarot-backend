<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\StripeWebhookController;
use App\Http\Controllers\Admin\AttributeController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('login', [AdminController::class, 'login'])->name('login');
Route::post('login', [AdminController::class,'auth'])->name('auth');

Route::group(['prefix' => 'admin', 'as' => 'admin.','middleware' => ['admin']],function () {
    Route::get('dashboard', [AdminController::class, 'index'])->name('index');
    Route::post('logout', [AdminController::class, 'logout'])->name('logout');

    Route::resource('attributes', AttributeController::class);
    Route::resource('coupons', CouponController::class);
    Route::resource('products', ProductController::class);
    Route::resource('categories',CategoryController::class);
    Route::get('categories/{category}/attributes', [CategoryController::class, 'getAttributes'])->name('categories.attributes');
    Route::patch('reviews/{id}/status', [ReviewController::class, 'updateStatus'])->name('reviews.status');
    Route::resource('reviews', ReviewController::class);
});

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);


Route::get('/test-webhook', function () {
    \Log::info('Test webhook endpoint reached!');
    return response()->json(['message' => 'Webhook endpoint is reachable']);
});
