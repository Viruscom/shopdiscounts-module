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

Route::prefix('shopdiscounts')->group(function() {
    Route::get('/', 'ShopDiscountsController@index')->name('discounts.index');
    Route::get('/create/{type}', 'ShopDiscountsController@create')->name('discounts.create');
    Route::post('/store/{type}', 'ShopDiscountsController@store')->name('discounts.store');

    Route::get('/edit/{id}', 'ShopDiscountsController@edit')->name('discounts.edit');
    Route::post('/update/{id}', 'ShopDiscountsController@update')->name('discounts.update');

    Route::get('/delete/{id}', 'ShopDiscountsController@destroy')->name('discounts.delete');
});
