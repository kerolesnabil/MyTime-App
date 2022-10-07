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
        Route::get('/show_new_users/{report_type}', 'UserController@showNewUsers')->name('user.show_new_users');

    });

    Route::prefix('vendors')->group(function (){
        Route::get('/', 'VendorController@index')->name('vendor.index');
        Route::post('update_vendor_activation', 'VendorController@updateActivateVendor')->name('vendor.update_activation');
        Route::get('/show_vendor/{id}', 'VendorController@showVendorById')->name('vendor.show_vendor');
        Route::post('report_vendors', 'VendorController@reportVendors')->name('vendor.report_vendors');
        Route::get('/show_new_vendors/{report_type}', 'VendorController@showNewVendors')->name('vendor.show_new_vendors');

    });

    Route::prefix('admins')->group(function (){
        Route::get('/', 'AdminController@index')->name('admin.index');
        Route::post('update_admin_activation', 'UserController@updateActivateUser')->name('admin.update_activation');
        Route::get('get_admin/{id?}', 'AdminController@getAdmin')->name('admin.get_admin');
        Route::delete('destroy/{id}', 'AdminController@destroy')->name('admin.destroy');
        Route::post('save/{id?}', 'AdminController@saveAdmin')->name('admin.save_admin');
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
        Route::get('/show_new_orders/{report_type}', 'OrderController@showNewOrders')->name('order.show_new_orders');
    });

    Route::prefix('ads')->group(function (){
        Route::get('/', 'AdController@index')->name('ad.index');
        Route::get('/show_ad/{id}', 'AdController@showAdById')->name('ad.show_ad');
        Route::get('/show_available_ads', 'AdController@showAvailableAds')->name('ad.show_available_ads');
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

        Route::prefix('diameter_search')->group(function (){
            Route::get('get_diameter_search', 'SettingController@getDiameterSearch')->name('setting.get_diameter_search');
            Route::post('save', 'SettingController@saveDiameterSearch')->name('setting.save_diameter_search');
        });

        Route::prefix('bank_account_details')->group(function (){
            Route::get('get_bank_account_details', 'SettingController@getBankAccountDetails')->name('setting.get_bank_account_details');
            Route::post('save', 'SettingController@saveBankAccountDetails')->name('setting.save_bank_account_details');
        });
    });

    Route::prefix('services')->group(function (){
        Route::get('/', 'ServiceController@index')->name('service.index');
        Route::post('save/{id?}', 'ServiceController@saveService')->name('service.save_service');
        Route::get('get_service/{id?}', 'ServiceController@getService')->name('service.get_service');
        Route::delete('destroy/{id}', 'ServiceController@destroy')->name('service.destroy');
        Route::get('show_suggested_service', 'ServiceController@showSuggestedService')->name('service.show_suggested_services');
    });


    Route::prefix('financial_requests')->group(function (){
        Route::get('/show_deposit_requests', 'FinancialRequestsController@showDepositRequests')->name('financial_request.show_deposit_requests');
        Route::get('/show_withdrawal_requests', 'FinancialRequestsController@showWithdrawalRequests')->name('financial_request.show_withdrawal_requests');
        Route::get('/get_financial_request/{id}', 'FinancialRequestsController@getFinancialRequest')->name('financial_request.get_financial_request');
        Route::post('/update_financial_request/{id}', 'FinancialRequestsController@updateFinancialRequest')->name('financial_request.update_financial_request');

    });


    Route::prefix('transactions_logs')->group(function (){
        Route::get('/', 'TransactionLogController@index')->name('transaction_log.index');
    });

});

Route::prefix('dashboard')->group(function () {

    Route::get('/login',[\App\Http\Controllers\Dashboard\Auth\LoginController::class,'getViewLogin'] )->name("login");

    Route::post('/login',[\App\Http\Controllers\Dashboard\Auth\LoginController::class,'login']);

});
