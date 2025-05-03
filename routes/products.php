<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Products\ProductController;


// Produtos - Acesso a todos os utilizadores
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/products/category/{category_id}', [ProductController::class, 'categoryProducts'])->name('category');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('show');
});
