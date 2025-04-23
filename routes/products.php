<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Products\ProductController;


// Sem sessão iniciada
Route::prefix('products')->name('products.')->group(function () {

    // Ver Produtos
    Route::get('/products', [ProductController::class, 'index'])->name('index');
    Route::get('/products/category/{category_id}', [ProductController::class, 'categoryProducts'])->name('category');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('show');
});
