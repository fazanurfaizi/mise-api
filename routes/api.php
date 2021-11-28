<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
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

Route::group(['middleware' => 'guest'], function() {
    Route::group(['as' => 'auth.', 'prefix' => 'auth'], function() {
        Route::post('/register', [RegisterController::class, 'register'])->name('register');
    });

    Route::group(['as' => 'email.', 'prefix' => 'email'], function() {
        Route::post('/verify/{token}', [EmailVerificationController::class, 'verify'])->name('verify');
    });
});

