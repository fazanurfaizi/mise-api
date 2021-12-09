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

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\VariantController;

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

Route::group(['as' => 'admin', 'prefix' => 'admin', 'middleware' => 'jwt.verify'], function() {
    Route::apiResource('users', UserController::class);

    Route::apiResource('roles', RoleController::class);
    Route::post('assign-roles', [RoleController::class, 'assign']);

    Route::apiResource('permissions', PermissionController::class);
    Route::post('/generate-permissions', [PermissionController::class, 'generate'])->name('generate');

    Route::apiResource('product-categories', ProductCategoryController::class)->parameters([
        'product-categories' => 'id',
    ]);
    Route::group(['as' => 'product-categories'], function() {
        Route::get('product-categories-bin', [ProductCategoryController::class, 'browseBin'])->name('browse-bin');
        Route::delete('product-categories-forceDestroy/{id}', [ProductCategoryController::class, 'forceDestroy'])->name('force-destroy');
        Route::post('product-categories-multipleDestroy', [ProductCategoryController::class, 'multipleDestroy'])->name('multiple-destroy');
        Route::post('product-categories-multipleForceDestroy', [ProductCategoryController::class, 'multipleForceDestroy'])->name('multiple-force-destroy');
        Route::put('product-categories-restore/{id}', [ProductCategoryController::class, 'restore'])->name('restore');
        Route::put('product-categories-multipleRestore', [ProductCategoryController::class, 'multipleRestore'])->name('multiple-restore');
    });

    Route::apiResource('variants', VariantController::class)->parameters([
        'variants' => 'id',
    ]);
    Route::group(['as' => 'variants'], function() {
        Route::get('variants-bin', [VariantController::class, 'browseBin'])->name('browse-bin');
        Route::delete('variants-forceDestroy/{id}', [VariantController::class, 'forceDestroy'])->name('force-destroy');
        Route::post('variants-multipleDestroy', [VariantController::class, 'multipleDestroy'])->name('multiple-destroy');
        Route::post('variants-multipleForceDestroy', [VariantController::class, 'multipleForceDestroy'])->name('multiple-force-destroy');
        Route::put('variants-restore/{id}', [VariantController::class, 'restore'])->name('restore');
        Route::put('variants-multipleRestore', [VariantController::class, 'multipleRestore'])->name('multiple-restore');
    });
});
