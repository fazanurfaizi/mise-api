<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\TwoFactorAuthenticationController;
use App\Http\Controllers\Auth\AuthorizeDeviceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['as' => 'auth.', 'prefix' => 'auth'], function() {
    Route::group(['middleware' => 'guest'], function() {
        Route::post('/register', [RegisterController::class, 'register'])->name('register');
        Route::post('/login', [LoginController::class, 'login'])->name('login');
        Route::post('/verify/{token}', [EmailVerificationController::class, 'verify'])->name('verify');
        Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forgot-password');
        Route::post('/reset-password/{token}', [ResetPasswordController::class, 'reset'])->name('reset-password');
    });

    Route::group(['middleware' => 'jwt.verify'], function() {
        Route::get('/me', [LoginController::class, 'me'])->name('me');
        Route::post('/refresh', [LoginController::class, 'refresh'])->name('refresh');
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

        Route::group(['as' => '2fa.'], function() {
            Route::post('/generate-2fa', [TwoFactorAuthenticationController::class, 'generate'])->name('generate');
            Route::post('/enable-2fa', [TwoFactorAuthenticationController::class, 'enable'])->name('enable');

            Route::group(['middleware' => '2fa.enabled'], function() {
                Route::post('/disable-2fa', [TwoFactorAuthenticationController::class, 'disable'])->name('disable');
            });

        });

    });
});

Route::group(['as' => 'devices', 'prefix' => 'devices'], function() {
    Route::group(['middleware' => 'jwt.verify'], function() {
        Route::get('/', [AuthorizeDeviceController::class, 'index'])->name('index');
        Route::get('/{id}', [AuthorizeDeviceController::class, 'logoutDevice'])->name('logoutDevice');
    });
});
