<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\OrderController;
use App\Http\Controllers\Dashboard\MembershipController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Dashboard\VirtualCardController;
use App\Http\Controllers\Dashboard\InventoryController;
use App\Http\Controllers\Dashboard\ProductController;
use App\Http\Controllers\Dashboard\CategoryController;
use App\Http\Controllers\Dashboard\SettingController;
use App\Http\Controllers\Dashboard\SupplyOrderController;

// Board and Employee Dashboard Routes
Route::middleware(['auth','role:board,employee'])->group(function () {
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');

        Route::prefix('orders')->name('orders.')->group(function () {
            Route::middleware(['role:board'])->group(function () {
                Route::delete('/{order}', [OrderController::class, 'destroy'])->name('destroy');
                Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
            });

            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/{order}', [OrderController::class, 'show'])->name('show');
            Route::get('/{order}/invoice', [OrderController::class, 'invoice'])->name('invoice');
            Route::get('/{order}/confirm', [OrderController::class, 'confirm'])->name('confirm');
            Route::post('/{order}/complete', [OrderController::class, 'complete'])->name('complete');
        });

        Route::middleware(['role:board'])->group(function () {
            Route::prefix('users')->name('users.')->group(function () {
                Route::get('/', [UserController::class, 'index'])->name('index');
                Route::post('/{user}/promote', [UserController::class, 'promote'])->name('promote');
                Route::post('/{user}/demote', [UserController::class, 'demote'])->name('demote');
                Route::post('/{user}/block', [UserController::class, 'block'])->name('block');
                Route::post('/{user}/unblock', [UserController::class, 'unblock'])->name('unblock');
                Route::post('/{user}/cancel-membership', [UserController::class, 'cancelMembership'])->name('cancel-membership');
                Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
                Route::put('/{user}', [UserController::class, 'update'])->name('update');
                Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
            });
        });

        Route::middleware(['role:board'])->group(function () {
            Route::prefix('memberships')->name('memberships.')->group(function () {
                Route::get('/', [MembershipController::class, 'index'])->name('index');
            });
        });

        Route::prefix('inventory')->name('inventory.')->group(function () {
            Route::get('/', [InventoryController::class, 'index'])->name('index');
            Route::post('/{product}/adjust-stock', [InventoryController::class, 'adjustStock'])->name('adjust-stock');

            Route::prefix('supply-orders')->name('supply-orders.')->group(function () {
                Route::get('/', [SupplyOrderController::class, 'index'])->name('index');
                Route::get('/create', [SupplyOrderController::class, 'create'])->name('create');
                Route::post('/', [SupplyOrderController::class, 'store'])->name('store');
                Route::get('/{supplyOrder}', [SupplyOrderController::class, 'show'])->name('show');
                Route::get('/{supplyOrder}/edit', [SupplyOrderController::class, 'edit'])->name('edit');
                Route::put('/{supplyOrder}', [SupplyOrderController::class, 'update'])->name('update');
                Route::delete('/{supplyOrder}', [SupplyOrderController::class, 'destroy'])->name('destroy');
                Route::post('/{supplyOrder}/complete', [SupplyOrderController::class, 'complete'])->name('complete');
            });
        });

        Route::middleware(['role:board'])->group(function () {
            Route::prefix('virtual-cards')->name('virtual-cards.')->group(function () {
                Route::get('/', [VirtualCardController::class, 'index'])->name('index');
                Route::get('/virtual-cards/create', [VirtualCardController::class, 'create'])->name('create');
                Route::get('/{card}', [VirtualCardController::class, 'show'])->name('show');
                Route::get('/{card}/edit', [VirtualCardController::class, 'edit'])->name('edit');
            });
        });

        Route::prefix('products')->name('products.')->group(function () {
            Route::middleware(['role:board'])->group(function () {
                Route::get('/create', [ProductController::class, 'create'])->name('create');
                Route::post('/', [ProductController::class, 'store'])->name('store');
                Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
                Route::put('/{product}', [ProductController::class, 'update'])->name('update');
                Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
            });

            Route::get('/', [ProductController::class, 'index'])->name('index');
            Route::get('/{product}', [ProductController::class, 'show'])->name('show');
        });

        Route::middleware(['role:board'])->group(function () {
            Route::prefix('settings')->name('settings.')->group(function () {
                Route::get('/', [SettingController::class, 'index'])->name('index');
                Route::post('/update', [SettingController::class, 'update'])->name('update');
                Route::put('/update/shipping-cost/{id}', [SettingController::class, 'update'])->name('shipping-cost.update');
                Route::post('/add/shipping-cost', [SettingController::class, 'addShippingCost'])->name('shipping-cost.add');
                Route::delete('/shipping-cost/{id}', [SettingController::class, 'deleteShippingCost'])->name('shipping-cost.delete');
            });
        });

        Route::middleware(['role:board'])->group(function () {
            Route::prefix('categories')->name('categories.')->group(function () {
                Route::get('/', [CategoryController::class, 'index'])->name('index');
                Route::get('/create', [CategoryController::class, 'create'])->name('create');
                Route::post('/', [CategoryController::class, 'store'])->name('store');
                Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
                Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
                Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
            });
        });
    });
});
