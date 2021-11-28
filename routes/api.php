<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\EmailVerificationController;

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
    });

    Route::group(['middleware' => 'jwt.verify'], function() {
        Route::get('/me', [LoginController::class, 'me'])->name('me');
        Route::post('/refresh', [LoginController::class, 'refresh'])->name('refresh');
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    });
});
