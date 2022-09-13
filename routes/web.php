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


Route::group([
    'middleware' => ['auth'],
    'prefix'    =>'dashboard',
    'namespace' =>'Dashboard'
], function () {
    Route::get('/', 'DashboardController@index')->name('admin.homepage');

    Route::prefix('users')->group(function (){
        Route::get('/', 'UserController@index')->name('user.index');
        Route::post('update_user_activation', 'UserController@updateActivateUser')->name('user.update_activation');

    });

    Route::prefix('vendors')->group(function (){
        Route::get('/', 'VendorController@index')->name('vendor.index');
        Route::post('update_vendor_activation', 'VendorController@updateActivateVendor')->name('vendor.update_activation');
        Route::get('/show_vendor/{id}', 'VendorController@showVendorById')->name('vendor.show_vendor');
        Route::post('report_vendors', 'VendorController@reportVendors')->name('vendor.report_vendors');
    });

    Route::prefix('categories')->group(function (){
        Route::get('/', 'CategoryController@index')->name('category.index');
        Route::delete('destroy/{id}', 'CategoryController@destroy')->name('category.destroy');
        Route::post('save/{id?}', 'CategoryController@saveCategory')->name('category.save_category');
        Route::get('get_category/{id?}', 'CategoryController@getCategory')->name('category.get_category');
        Route::post('update_category_activation', 'CategoryController@updateActivationCategory')->name('category.update_activation');
        Route::get('get_sub_categories/{id?}', 'CategoryController@showSubCategoriesOfCategory')->name('category.get_sub_category');
        Route::get('get_category_services/{id?}', 'CategoryController@showServicesOfCategory')->name('category.get_category_services');

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
        Route::post('report_orders', 'OrderController@reportOrders')->name('order.report_order');

    });

    Route::prefix('langs')->group(function (){
        Route::get('/', 'LangController@index')->name('lang.index');
        Route::delete('destroy/{id}', 'LangController@destroy')->name('lang.destroy');
        Route::post('save/{id?}', 'LangController@saveLang')->name('lang.save_lang');
        Route::get('get_lang/{id?}', 'LangController@getLang')->name('lang.get_lang');
        Route::post('update_lang_activation', 'LangController@updateActivationLang')->name('lang.update_activation');
    });

    Route::prefix('order_rejection_reasons')->group(function (){
        Route::get('/', 'OrderRejectionReasonController@index')->name('order_rejection_reason.index');
        Route::delete('destroy/{id}', 'OrderRejectionReasonController@destroy')->name('order_rejection_reason.destroy');
        Route::post('save/{id?}', 'OrderRejectionReasonController@saveOrderRejectionReason')->name('order_rejection_reason.save_order_rejection_reason');
        Route::get('get_order_rejection_reason/{id?}', 'OrderRejectionReasonController@getOrderRejectionReason')->name('order_rejection_reason.get_order_rejection_reason');
    });

    Route::prefix('coupons')->group(function (){
        Route::get('/', 'CouponController@index')->name('coupon.index');
        Route::delete('destroy/{id}', 'CouponController@destroy')->name('coupon.destroy');
        Route::post('save/{id?}', 'CouponController@saveCoupon')->name('coupon.save_coupon');
        Route::get('get_coupon/{id?}', 'CouponController@getCoupon')->name('coupon.get_coupon');
        Route::post('update_coupon_activation', 'CouponController@updateActivationCoupon')->name('coupon.update_activation');
    });

    Route::prefix('payment_methods')->group(function (){
        Route::get('/', 'PaymentMethodController@index')->name('payment_method.index');
        Route::delete('destroy/{id}', 'PaymentMethodController@destroy')->name('payment_method.destroy');
        Route::post('save/{id?}', 'PaymentMethodController@savePaymentMethod')->name('payment_method.save_payment_method');
        Route::get('get_payment_method/{id?}', 'PaymentMethodController@getPaymentMethod')->name('payment_method.get_payment_method');
        Route::post('update_payment_method_activation', 'PaymentMethodController@updateActivationPaymentMethod')->name('payment_method.update_activation');
    });

    Route::prefix('settings')->group(function (){

        Route::prefix('social_media')->group(function (){
            Route::get('/', 'SettingController@showSocialMedia')->name('setting.social_media');
            Route::get('get_social_media/{id?}', 'SettingController@getSocialMedia')->name('setting.get_social_media');
            Route::post('save', 'SettingController@saveSocialMedia')->name('setting.save_social_media');
            Route::post('destroy_social_media', 'SettingController@destroySocialMedia')->name('setting.destroy_social_media');
        });

        Route::prefix('app_images')->group(function (){
            Route::get('get_app_images', 'SettingController@getAppImages')->name('setting.get_app_images');
            Route::post('save', 'SettingController@saveAppImages')->name('setting.save_app_images');
        });

        Route::prefix('ads_price')->group(function (){
            Route::get('get_ads_price', 'SettingController@getAdsPrice')->name('setting.get_ad_price');
            Route::post('save', 'SettingController@saveAdsPrice')->name('setting.save_ad_price');
        });

    });


});

Route::prefix('dashboard')->group(function () {

    Route::get('/login',[\App\Http\Controllers\Dashboard\Auth\LoginController::class,'getViewLogin'] )->name("login");


    Route::post('/sendSMS',[\App\Http\Controllers\Dashboard\Auth\LoginController::class,'sendSMS'] )->name("sendSMS");

    Route::post('/login',[\App\Http\Controllers\Dashboard\Auth\LoginController::class,'login'] )->name("checkUser");

});
