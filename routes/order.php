<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Orders\OrderController;

// Com sessão iniciada e com email validado
Route::middleware(['auth', 'verified'])->group(function () {

    // Checkout da encomenda
    Route::post('/cart/purchase', [OrderController::class, 'createOrder'])->name('order.create');

});
