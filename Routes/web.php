<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Modules\ShopDiscounts\Http\Controllers\ShopDiscountsController;

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], static function () {
    
    /* Shop */
    Route::group(['prefix' => 'shop/discounts'], static function () {
        Route::get('/', [ShopDiscountsController::class, 'index'])->name('discounts.index');

        /* Discount type */
        Route::group(['prefix' => '{type}'], static function () {
            Route::get('create', [ShopDiscountsController::class, 'create'])->name('discounts.create');
            Route::post('store', [ShopDiscountsController::class, 'store'])->name('discounts.store');
        });

        /* Discount id */
        Route::group(['prefix' => '{id}'], static function () {
            Route::get('edit', [ShopDiscountsController::class, 'edit'])->name('discounts.edit');
            Route::post('update', [ShopDiscountsController::class, 'update'])->name('discounts.update');
            Route::get('delete', [ShopDiscountsController::class, 'destroy'])->name('discounts.delete');
        });
    });
});
