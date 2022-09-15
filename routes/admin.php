<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\Product\BrandController;
use App\Http\Controllers\Admin\Product\ProductAttributeController;
use App\Http\Controllers\Admin\Product\ProductCategoryController;
use App\Http\Controllers\Admin\Product\ProductController;
use App\Http\Controllers\Admin\Product\ProductUnitController;

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

Route::apiResource('product-categories', ProductCategoryController::class);
Route::controller(ProductCategoryController::class)
    ->as('product-categories')
    ->group(function() {
        Route::get('product-categories-bin', 'browseBin')->name('browse-bin');
        Route::delete('product-categories-forceDestroy/{id}', 'forceDestroy')->name('force-destroy');
        Route::post('product-categories-multipleDestroy', 'multipleDestroy')->name('multiple-destroy');
        Route::post('product-categories-multipleForceDestroy', 'multipleForceDestroy')->name('multiple-force-destroy');
        Route::get('product-categories-restore/{id}', 'restore')->name('restore');
        Route::put('product-categories-multipleRestore', 'multipleRestore')->name('multiple-restore');
    });

Route::apiResource('brands', BrandController::class);
Route::controller(BrandController::class)
    ->as('brands')
    ->group(function() {
        Route::get('brands-bin', 'browseBin')->name('browse-bin');
        Route::delete('brands-forceDestroy/{id}', 'forceDestroy')->name('force-destroy');
        Route::post('brands-multipleDestroy', 'multipleDestroy')->name('multiple-destroy');
        Route::post('brands-multipleForceDestroy', 'multipleForceDestroy')->name('multiple-force-destroy');
        Route::get('brands-restore/{id}', 'restore')->name('restore');
        Route::put('brands-multipleRestore', 'multipleRestore')->name('multiple-restore');
    });

Route::apiResource('product-units', ProductUnitController::class);
Route::controller(ProductUnitController::class)
    ->as('product-units')
    ->group(function() {
        Route::get('product-units-bin', 'browseBin')->name('browse-bin');
        Route::delete('product-units-forceDestroy/{id}', 'forceDestroy')->name('force-destroy');
        Route::post('product-units-multipleDestroy', 'multipleDestroy')->name('multiple-destroy');
        Route::post('product-units-multipleForceDestroy', 'multipleForceDestroy')->name('multiple-force-destroy');
        Route::get('product-units-restore/{id}', 'restore')->name('restore');
        Route::put('product-units-multipleRestore', 'multipleRestore')->name('multiple-restore');
    });

Route::apiResource('products', ProductController::class);
Route::controller(ProductController::class)
    ->as('products')
    ->group(function() {
        Route::get('products-bin', 'browseBin')->name('browse-bin');
        Route::delete('products-forceDestroy/{id}', 'forceDestroy')->name('force-destroy');
        Route::post('products-multipleDestroy', 'multipleDestroy')->name('multiple-destroy');
        Route::post('products-multipleForceDestroy', 'multipleForceDestroy')->name('multiple-force-destroy');
        Route::get('products-restore/{id}', 'restore')->name('restore');
        Route::put('products-multipleRestore', 'multipleRestore')->name('multiple-restore');
    });

Route::apiResource('product-attributes', ProductAttributeController::class);
Route::controller(ProductAttributeController::class)
    ->as('product-attributes')
    ->group(function() {
        Route::get('product-attributes-bin', 'browseBin')->name('browse-bin');
        Route::delete('product-attributes-forceDestroy/{id}', 'forceDestroy')->name('force-destroy');
        Route::post('product-attributes-multipleDestroy', 'multipleDestroy')->name('multiple-destroy');
        Route::post('product-attributes-multipleForceDestroy', 'multipleForceDestroy')->name('multiple-force-destroy');
        Route::get('product-attributes-restore/{id}', 'restore')->name('restore');
        Route::put('product-attributes-multipleRestore', 'multipleRestore')->name('multiple-restore');
    });
