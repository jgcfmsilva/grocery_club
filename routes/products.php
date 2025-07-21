<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Products\ProductController;
use App\Http\Livewire\ProductPage;
use App\Models\Product;

// Produtos - Acesso a todos os utilizadores
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/category/{category_id}', [ProductController::class, 'categoryProducts'])->name('category');
    Route::get('/{product}', [ProductController::class, 'show'])->name('show');});
