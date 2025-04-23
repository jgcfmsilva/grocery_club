<?php

use Illuminate\Support\Facades\Route;

// Sem sessão iniciada
Route::middleware('guest')->group(function () {

    // Carrinho
    Route::get('/cart', function () {
        return view('pages.cart.index');
    })->name('cart');
});
