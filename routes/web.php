<?php

use Illuminate\Support\Facades\Route;

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


Route::get('/login', function () {
    return [
        "message" => "please login"
    ];
})->name("login");


Route::namespace('Dashboard')->prefix('dashboard')->group(function () {
    Route::get('/', 'DashboardController@index')->name('admin.homepage');

    Route::prefix('users')->group(function (){
        Route::get('/', 'UserController@index')->name('user.index');
        Route::get('save', 'UserController@save')->name('user.save');
        Route::post('update_user_activation', 'UserController@updateActivateUser')->name('user.update_activation');

    });

    Route::prefix('vendors')->group(function (){
        Route::get('/', 'VendorController@index')->name('vendor.index');
        Route::get('save', 'VendorController@save')->name('vendor.save');
        Route::post('update_vendor_activation', 'VendorController@updateActivateVendor')->name('vendor.update_activation');

    });

    Route::prefix('categories')->group(function (){
        Route::get('/', 'CategoryController@index')->name('category.index');
        Route::get('destroy', 'CategoryController@destroy')->name('category.destroy');
        Route::get('save/{id?}', 'CategoryController@save')->name('category.save');
        Route::post('update_category_activation', 'CategoryController@updateActivateCategory')->name('category.update_activation');

    });


});