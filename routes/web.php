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
        Route::post('update_user_activation', 'UserController@updateActivateUser')->name('user.update_activation');

    });

    Route::prefix('vendors')->group(function (){
        Route::get('/', 'VendorController@index')->name('vendor.index');
        Route::post('update_vendor_activation', 'VendorController@updateActivateVendor')->name('vendor.update_activation');
        Route::get('/show_vendor/{id}', 'VendorController@showVendorById')->name('vendor.show_vendor');

    });

    Route::prefix('categories')->group(function (){
        Route::get('/', 'CategoryController@index')->name('category.index');
        Route::delete('destroy/{id}', 'CategoryController@destroy')->name('category.destroy');
        Route::post('save/{id?}', 'CategoryController@saveCategory')->name('category.save_category');
        Route::get('get_category/{id?}', 'CategoryController@getCategory')->name('category.get_category');
        Route::post('update_category_activation', 'CategoryController@updateActivationCategory')->name('category.update_activation');
    });

    Route::prefix('pages')->group(function (){
        Route::get('/', 'PageController@index')->name('page.index');
        Route::delete('destroy/{id}', 'PageController@destroy')->name('page.destroy');
        Route::post('save/{id?}', 'PageController@savePage')->name('page.save_page');
        Route::get('get_page/{id?}', 'PageController@getPage')->name('page.get_page');
        Route::post('update_page_activation', 'PageController@updateActivationPage')->name('page.update_activation');
    });

    Route::prefix('orders')->group(function (){
        Route::get('/', 'OrderController@index')->name('order.index');
        Route::get('/show_order/{id}', 'OrderController@showOrderById')->name('order.show_order');


    });

    Route::prefix('langs')->group(function (){
        Route::get('/', 'LangController@index')->name('lang.index');
        Route::delete('destroy/{id}', 'LangController@destroy')->name('lang.destroy');
        Route::post('save/{id?}', 'LangController@saveLang')->name('lang.save_lang');
        Route::get('get_lang/{id?}', 'LangController@getLang')->name('lang.get_lang');
        Route::post('update_lang_activation', 'LangController@updateActivationLang')->name('lang.update_activation');
    });

    Route::prefix('order_rejection_reason')->group(function (){
        Route::get('/', 'OrderRejectionReasonController@index')->name('order_rejection_reason.index');
        Route::delete('destroy/{id}', 'OrderRejectionReasonController@destroy')->name('order_rejection_reason.destroy');
        Route::post('save/{id?}', 'OrderRejectionReasonController@saveOrderRejectionReason')->name('order_rejection_reason.save_order_rejection_reason');
        Route::get('get_order_rejection_reason/{id?}', 'OrderRejectionReasonController@getOrderRejectionReason')->name('order_rejection_reason.get_order_rejection_reason');
    });



});