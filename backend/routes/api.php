<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/get_user', [AuthController::class, 'get_user']);
Route::get('/verify-email/{id}/{hash}', [AuthController::class, 'verifyEmail'])->name('verify.email');

Route::get('auth/google', [AuthController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

Route::get('/paypal/success', [OrderController::class, 'handlePayPalSuccess'])->name('paypal.success');
Route::get('/paypal/cancel', [OrderController::class, 'handlePayPalCancel'])->name('paypal.cancel');


Route::get('/brands', [ProductController::class, 'getBrandList']);
Route::get('/categories', [ProductController::class, 'getCategoryList']);


Route::get('/products', [ProductController::class, 'getProductList']);
Route::get('/products/{id}', [ProductController::class, 'getProduct']);



Route::middleware(['auth:api','verified'])->group(function () {
    Route::get('/cart', [CartController::class, 'getCart']);
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::post('/cart/remove', [CartController::class, 'removeFromCart']);
    Route::patch('/cart/update', [CartController::class, 'updateQuantity']);


    Route::get('/orders', [OrderController::class, 'getOrderList']);
    Route::post('/order/create', [OrderController::class, 'createOrder']);
});




