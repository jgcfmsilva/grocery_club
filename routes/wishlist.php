<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\WishlistController;


// Com sessão iniciada
Route::middleware(['auth', 'not_employee'])->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
});
