<?php

use Illuminate\Support\Facades\Route;

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
