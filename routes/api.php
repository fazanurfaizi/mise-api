<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
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
        Route::post('/login', [AuthenticationController::class, 'login'])->name('login');
        Route::post('/verify/{token}', [EmailVerificationController::class, 'verify'])->name('verify');
        Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forgot-password');
        Route::post('/reset-password/{token}', [ResetPasswordController::class, 'reset'])->name('reset-password');
    });

    Route::group(['middleware' => 'jwt.verify'], function() {
        Route::get('/me', [AuthenticationController::class, 'me'])->name('me');
        Route::post('/refresh', [AuthenticationController::class, 'refresh'])->name('refresh');
        Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');
    });
});

Route::group(['as' => 'devices', 'prefix' => 'devices'], function() {
    Route::group(['middleware' => 'jwt.verify'], function() {
        Route::get('/', [AuthorizeDeviceController::class, 'index'])->name('index');
        Route::delete('/{id}', [AuthorizeDeviceController::class, 'logoutDevice'])->name('logoutDevice');
    });
});

Route::group(['as' => 'admin.', 'prefix' => 'admin', 'middleware' => 'jwt.verify'], function() {
    Route::apiResource('users', UserController::class);

    Route::apiResource('roles', RoleController::class);
    Route::post('assign-roles', [RoleController::class, 'assign']);

    Route::apiResource('permissions', PermissionController::class);
    Route::post('/generate-permissions', [PermissionController::class, 'generate'])->name('generate');

    Route::apiResource('product-categories', ProductCategoryController::class)->parameters([
        'product-categories' => 'id',
    ]);
    Route::controller(ProductCategoryController::class)
        ->as('product-categories')
        ->group(function() {
            Route::get('product-categories-bin', 'browseBin')->name('browse-bin');
            Route::delete('product-categories-forceDestroy/{id}', 'forceDestroy')->name('force-destroy');
            Route::post('product-categories-multipleDestroy', 'multipleDestroy')->name('multiple-destroy');
            Route::post('product-categories-multipleForceDestroy', 'multipleForceDestroy')->name('multiple-force-destroy');
            Route::put('product-categories-restore/{id}', 'restore')->name('restore');
            Route::put('product-categories-multipleRestore', 'multipleRestore')->name('multiple-restore');
        });

    Route::apiResource('variants', VariantController::class)->parameters([
        'variants' => 'id',
    ]);
    Route::controller(VariantController::class)
        ->as('variants')
        ->group(function() {
            Route::get('variants-bin', 'browseBin')->name('browse-bin');
            Route::delete('variants-forceDestroy/{id}', 'forceDestroy')->name('force-destroy');
            Route::post('variants-multipleDestroy', 'multipleDestroy')->name('multiple-destroy');
            Route::post('variants-multipleForceDestroy', 'multipleForceDestroy')->name('multiple-force-destroy');
            Route::put('variants-restore/{id}', 'restore')->name('restore');
            Route::put('variants-multipleRestore', 'multipleRestore')->name('multiple-restore');
        });
});
