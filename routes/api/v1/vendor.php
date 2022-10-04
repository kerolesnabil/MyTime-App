<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Authenticate;

Route::post('vendor/register', [\App\Http\Controllers\api\v1\vendor\VendorController::class, 'register']);


Route::group([
    'middleware' => ['APIGate','auth:api'],
    'prefix' => 'vendor',
], function () {


    Route::get('/get-vendor-homepage',[\App\Http\Controllers\api\v1\vendor\VendorController::class,'getVendorHomepage']);
    Route::get('/get-vendor-reviews',[\App\Http\Controllers\api\v1\vendor\VendorController::class,'getVendorReviews']);


    Route::get('get-main-categories-of-services',[\App\Http\Controllers\api\v1\vendor\ServiceController::class,'getMainCategoriesOfServices']);
    Route::get('get-sub-categories-of-services/{id}',[\App\Http\Controllers\api\v1\vendor\ServiceController::class,'getSubCategoriesOfServices']);
    Route::get('get-services-by-category/{id}',[\App\Http\Controllers\api\v1\vendor\ServiceController::class,'getServicesByCatId']);



    Route::post('create-service',[\App\Http\Controllers\api\v1\vendor\ServiceController::class,'saveService']);
    Route::post('update-service/{id?}',[\App\Http\Controllers\api\v1\vendor\ServiceController::class,'saveService']);
    Route::get('show-service/{id}',[\App\Http\Controllers\api\v1\vendor\ServiceController::class,'showService']);
    Route::post('delete-service/{id}',[\App\Http\Controllers\api\v1\vendor\ServiceController::class,'deleteService']);

    Route::post('create-suggested-service',[\App\Http\Controllers\api\v1\vendor\ServiceController::class,'addSuggestedService']);


    Route::get('get-orders-list',[\App\Http\Controllers\api\v1\vendor\OrderController::class,'getListOrdersOfVendor']);
    Route::get('get-order-details/{order_id}',[\App\Http\Controllers\api\v1\vendor\OrderController::class,'getOrderDetailsOfVendor']);
    Route::post('get-order-by-keyword',[\App\Http\Controllers\api\v1\vendor\OrderController::class,'getOrderByKeyword']);


    Route::get('get-all-services-vendor',[\App\Http\Controllers\api\v1\vendor\ServiceController::class,'getServicesOfVendor']);

    Route::post('create-package',[\App\Http\Controllers\api\v1\vendor\ServiceController::class,'savePackage']);
    Route::post('edit-package/{id}',[\App\Http\Controllers\api\v1\vendor\ServiceController::class,'savePackage']);

    Route::get('get-package/{id}',[\App\Http\Controllers\api\v1\vendor\ServiceController::class,'getPackage']);

    Route::get('get-all-package-vendor',[\App\Http\Controllers\api\v1\vendor\ServiceController::class,'getAllPackageOfVendor']);

    Route::post('delete-package/{id}',[\App\Http\Controllers\api\v1\vendor\ServiceController::class,'deletePackage']);

    Route::get('create-Ad',[\App\Http\Controllers\api\v1\vendor\AdController::class,'getCreateAds']);

    Route::post('create-Ad',[\App\Http\Controllers\api\v1\vendor\AdController::class,'saveAd']);


    Route::post('update-Ad/{ad_id?}',[\App\Http\Controllers\api\v1\vendor\AdController::class,'saveAd']);

    Route::post('delete-Ad/{ad_id}',[\App\Http\Controllers\api\v1\vendor\AdController::class,'deleteAd']);

    Route::get('get-Ad/{ad_id}',[\App\Http\Controllers\api\v1\vendor\AdController::class,'getAd']);

    Route::get('/get-vendor-profile', [\App\Http\Controllers\api\v1\vendor\VendorController::class, 'getVendorProfile']);
    Route::post('/update-vendor-profile', [\App\Http\Controllers\api\v1\vendor\VendorController::class, 'updateVendorProfile']);
    Route::post('/get-vendor-report', [\App\Http\Controllers\api\v1\vendor\VendorController::class, 'getVendorReport']);


    Route::post('/reschedule-order-date', [\App\Http\Controllers\api\v1\vendor\OrderActionController::class, 'rescheduleOrderDate']);
    Route::post('/reject-order', [\App\Http\Controllers\api\v1\vendor\OrderActionController::class, 'rejectOrder']);
    Route::get('/accept-order/{order_id}', [\App\Http\Controllers\api\v1\vendor\OrderActionController::class, 'acceptOrder']);
    Route::get('/get-all-order-rejection-reasons', [\App\Http\Controllers\api\v1\vendor\OrderActionController::class, 'getAllOrderRejectionReasons']);


    Route::post('deposit',[\App\Http\Controllers\Financial_Transaction\FinancialTransactionController::class, 'vendorCreateDepositRequest']);
    Route::post('withdrawal',[\App\Http\Controllers\Financial_Transaction\FinancialTransactionController::class, 'vendorCreateWithdrawalRequest']);
    Route::get('show-financial-requests',[\App\Http\Controllers\Financial_Transaction\FinancialTransactionController::class, 'showFinancialRequests']);


});




