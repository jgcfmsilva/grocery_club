<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\User\CardController;
use App\Http\Controllers\Membership\MembershipController;
use App\Http\Controllers\Orders\OrderController;
use App\Http\Controllers\User\TransactionController;
use App\Http\Controllers\User\StatisticsController;
use App\Http\Controllers\User\ChangePasswordController;

Route::middleware(['auth','role:pending_member,member,board'])->group(function () {

    // Rotas para o My Account
    Route::prefix('my-account')->name('my-account.')->group(function () {

        // Dados Pessoais
        Route::get('/', [AccountController::class, 'show'])->name('index');
        Route::put('/update', [AccountController::class, 'update'])->name('personal-data.update');

        // Membership
        Route::prefix('membership')->name('membership.')->group(function () {
            Route::get('/', [MembershipController::class, 'index'])->name('index');
            Route::post('/pay', [MembershipController::class, 'pay'])->name('pay');
        });

        // Encomendas
        Route::prefix('orders')->middleware(['role:member,board'])->name('orders.')->group(function () {
            // Lista de pedidos
            Route::get('/', [OrderController::class, 'index'])->name('index');

            // Detalhes do pedido
            Route::get('/{order}', [OrderController::class, 'show'])->name('show');

            // Download do recibo
            Route::get('/{order}/download', [OrderController::class, 'downloadReceipt'])->name('download');

            // Cancelar pedido
            Route::post('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');

            // Encomendar pedido anterior
            Route::post('/{order}/reorder', [OrderController::class, 'reorder'])->name('reorder');
        });

        // Cartão Virtual
        Route::prefix('virtual-card')->name('virtual-card.')->group(function () {
            Route::get('/', [CardController::class, 'index'])->name('index');
            Route::post('/topup', [CardController::class, 'topUpCard'])->name('topup');
        });

        Route::middleware(['role:member,board'])->group(function () {
            // Transações
            Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

            // Estatísticas
            Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
        });

        // Alterar Password
        Route::get('/change-password', [ChangePasswordController::class, 'index'])->name('change-password.index');

        // Change Password
        Route::post('/change-password', [ChangePasswordController::class, 'update'])->name('change-password.update');
    });
});
