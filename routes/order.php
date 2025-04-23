<?php

use Illuminate\Support\Facades\Route;

// Com sessão iniciada e com email validado
Route::middleware(['auth', 'verified'])->group(function () {

    // Checkout da encomenda
    Route::get('/checkout', function () {
        return view('pages.checkout.index');
    })->name('checkout');
});
