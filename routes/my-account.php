<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\User\CardController;
use App\Http\Controllers\Membership\MembershipController;
use App\Http\Controllers\Orders\OrderController;
use App\Http\Controllers\User\TransactionController;
use App\Http\Controllers\User\StatisticsController;
use App\Http\Controllers\User\ChangePasswordController;

Route::middleware(['auth','role:pending_member,member,board,employee'])->group(function () {

    // Routes for My Account
    Route::prefix('my-account')->name('my-account.')->group(function () {

        // Personal Data - todos podem ver
        Route::get('/', [AccountController::class, 'show'])->name('index');

        // Apenas board e member podem atualizar dados pessoais
        Route::put('/update', [AccountController::class, 'update'])
            ->middleware('role:pending_member,member,board')
            ->name('personal-data.update');

        // Membership - apenas pending_member, member, board
        Route::prefix('membership')->name('membership.')->middleware('role:pending_member,member,board')->group(function () {
            Route::get('/', [MembershipController::class, 'index'])->name('index');
            Route::post('/pay', [MembershipController::class, 'pay'])->name('pay');
        });

        // Orders - apenas member, board
        Route::prefix('orders')->middleware(['role:member,board'])->name('orders.')->group(function () {
            // List of orders
            Route::get('/', [OrderController::class, 'index'])->name('index');

            // Order details
            Route::get('/{order}', [OrderController::class, 'show'])->name('show');

            // Download receipt
            Route::get('/{order}/download', [OrderController::class, 'downloadReceipt'])->name('download');

            // Cancel order
            Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');

            // Reorder previous order
            Route::post('/{order}/reorder', [OrderController::class, 'reorder'])->name('reorder');
        });

        // Virtual Card - apenas pending_member, member, board
        Route::prefix('virtual-card')->middleware('role:pending_member,member,board')->name('virtual-card.')->group(function () {
            Route::get('/', [CardController::class, 'index'])->name('index');
            Route::post('/topup', [CardController::class, 'topUpCard'])->name('topup');
        });

        // Transactions e Statistics - apenas member, board
        Route::middleware(['role:member,board'])->group(function () {
            // Transactions
            Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

            // Statistics
            Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
            Route::get('/statistics/export', [StatisticsController::class, 'export'])->name('statistics.export');
        });

        // Change Password - todos podem aceder
        Route::get('/change-password', [ChangePasswordController::class, 'index'])->name('change-password.index');
        Route::post('/change-password', [ChangePasswordController::class, 'update'])->name('change-password.update');
    });
});
