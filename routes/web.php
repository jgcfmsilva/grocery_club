<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\HomeController;

// Página Principal
Route::get('/', [HomeController::class, 'index'])->name('home');

require __DIR__.'/auth.php';
require __DIR__.'/my-account.php';
require __DIR__.'/products.php';
require __DIR__.'/cart.php';
require __DIR__.'/order.php';
