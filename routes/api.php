<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

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
        Route::post('/register', RegisterController::class)->name('register');
        Route::post('/login', [AuthenticationController::class, 'login'])->name('login');
        Route::get('/verify/{token}', EmailVerificationController::class)->name('verify');
        Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forgot-password');
        Route::post('/reset-password/{token}', [ResetPasswordController::class, 'reset'])->name('reset-password');
    });

    Route::group(['middleware' => 'jwt.verify'], function() {
        Route::get('/me', [AuthenticationController::class, 'me'])->name('me');
        Route::get('/refresh', [AuthenticationController::class, 'refresh'])->name('refresh');
        Route::delete('/logout', [AuthenticationController::class, 'logout'])->name('logout');
    });
});

// Route::group(['as' => 'devices', 'prefix' => 'devices'], function() {
//     Route::group(['middleware' => 'jwt.verify'], function() {
//         Route::get('/', [AuthorizeDeviceController::class, 'index'])->name('index');
//         Route::delete('/{id}', [AuthorizeDeviceController::class, 'logoutDevice'])->name('logoutDevice');
//     });
// });
