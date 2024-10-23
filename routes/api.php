<?php

use App\Http\Controllers\Accounts\GetAccountsController;
use App\Http\Controllers\Accounts\RegisterAccountsController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\GetUserController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\EmailVerification\SendVerificationController;
use App\Http\Controllers\EmailVerification\VerifyController;
use App\Http\Controllers\Transaction\RegisterTransactionController;
use Illuminate\Support\Facades\Route;

Route::post('/register-user', RegisterUserController::class)->name('register.user');
Route::get('/email/verify/{token}', VerifyController::class)->name('email.verify');
Route::post('email/send-verification/{userId}', SendVerificationController::class)->name('email.send-verification');

Route::post('password/email', ForgotPasswordController::class)->name('password.email');
Route::post('password/reset', ResetPasswordController::class)->name('password.reset');

Route::middleware('auth')->group(function () {

    Route::post('/logout', LogoutController::class)->name('auth.logout');
    Route::get('/user', GetUserController::class)->name('auth.get.user');

    Route::post('/account', RegisterAccountsController::class)->name('account.register');
    Route::get('/account/{accountId}', GetAccountsController::class)->name('account.get.user');

    Route::post('/transaction', RegisterTransactionController::class)->name('transaction.register');

    Route::middleware(['permission:ADMINISTRATOR'])->group(function () {
        //
    });
});