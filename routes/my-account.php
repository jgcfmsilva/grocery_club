<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\AccountController;
use App\Http\Controllers\User\CardController;
use App\Http\Controllers\Orders\OrderController;
use App\Http\Controllers\User\TransactionController;
use App\Http\Controllers\User\StatisticsController;
use App\Http\Controllers\User\ChangePasswordController;

// Autenticado e com email validado
Route::middleware(['auth', 'verified'])->group(function () {

    // Rotas para o My Account
    Route::prefix('my-account')->middleware(['member'])->name('my-account.')->group(function () {

        // Dados Pessoais
        Route::get('/', [AccountController::class, 'show'])->name('index');
        Route::put('/update', [AccountController::class, 'update'])->name('personal-data.update');

        // Encomendas
        Route::prefix('orders')->name('orders.')->group(function () {
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
        Route::get('/virtual-card', [CardController::class, 'index'])->name('virtual-card.index');
        Route::post('/virtual-card/topup', [CardController::class, 'topUpCard'])->name('virtual-card.topup');

        // Transações
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');

        // Estatísticas
        Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');

        // Alterar Password
        Route::get('/change-password', [ChangePasswordController::class, 'index'])->name('change-password.index');
    });
});
