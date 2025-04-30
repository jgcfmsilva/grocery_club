<?php

use App\Http\Controllers\Cart\CartController;
use Illuminate\Support\Facades\Route;


// Carrinho
Route::get('/cart', [CartController::class, 'show'])->name('cart');


