<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginUserController;
use App\Http\Controllers\Auth\ResetPasswordController;

// No active session
Route::middleware('guest')->group(function () {

    // Create Account
    Route::get('/register', [RegisterUserController::class, 'show'])->name('register');
    Route::post('/register', [RegisterUserController::class, 'register'])->name('register');

    // Login
    Route::get('/login', [LoginUserController::class, 'show']) -> name('login');
    Route::post('/login', [LoginUserController::class, 'login'])->name('login.submit');

    // Forgot Password
    Route::post('/forgot-password', [ForgotPasswordController::class, 'show'])->name('forgot-password.post');
    Route::get('/forgot-password', [ForgotPasswordController::class, 'show'])->name('forgot-password.show');
    Route::post('/sendEmailVerification', [ForgotPasswordController::class, 'sendEmailVerification'])->name('forgot-password.send-email');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'show'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

});

// Logged in but email not verified
Route::middleware('auth')->group(function () {
    
    // Logout
    Route::post('/logout', [LoginUserController::class, 'logout']) -> name('logout');
    Route::get('/logout', [LoginUserController::class, 'logout']) -> name('logout');

    // Email Verification
    Route::prefix('email')->group(function () {
        Route::get('verify', [EmailVerificationController::class, 'show'])->name('verification.notice');

        Route::get('validated', [EmailVerificationController::class, 'finish'])
            ->name('email.validated')
            ->middleware('email.validated');

        Route::get('verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
            ->name('verification.verify');

        Route::post('resend', [EmailVerificationController::class, 'resend'])
            ->name('verification.resend');
    });
});
