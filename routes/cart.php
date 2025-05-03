<?php

use App\Http\Controllers\Cart\CartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Orders\OrderController;

// Carrinho
Route::get('/cart', [CartController::class, 'show'])->name('cart');

Route::middleware(['auth', 'verified', 'role:member,board'])->group(function () {
    Route::post('/cart/purchase', [OrderController::class, 'createOrder'])->name('order.create');
});