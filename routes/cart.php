<?php

use App\Http\Controllers\Cart\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Orders\OrderController;

// Cart
Route::middleware(['auth', 'verified', 'not_employee'])->group(function () {
    Route::get('/cart', [CartController::class, 'show'])->name('cart');
    Route::post('/cart/purchase', [OrderController::class, 'createOrder'])->name('order.create');
});