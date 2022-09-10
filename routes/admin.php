<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\Product\ProductCategoryController;
use App\Http\Controllers\Admin\Product\ProductController;

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

Route::apiResource('products', ProductController::class);
Route::controller(ProductController::class)
    ->as('products')
    ->group(function() {
        Route::get('products-bin', 'browseBin')->name('browse-bin');
        // Route::delete('products-forceDestroy/{id}', 'forceDestroy')->name('force-destroy');
        // Route::post('products-multipleDestroy', 'multipleDestroy')->name('multiple-destroy');
        // Route::post('products-multipleForceDestroy', 'multipleForceDestroy')->name('multiple-force-destroy');
        // Route::put('products-restore/{id}', 'restore')->name('restore');
        // Route::put('products-multipleRestore', 'multipleRestore')->name('multiple-restore');
    });
