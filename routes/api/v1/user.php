<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Authenticate;


Route::group([
    'prefix'     => 'user',
], function () {

    Route::post('/register', [\App\Http\Controllers\api\v1\user\UserController::class, 'register']);
    Route::get('/get-user-homepage', [\App\Http\Controllers\api\v1\user\UserController::class, 'getUserHomepageData']);
    Route::get('/get-vendor-details-by-id/{vendor_id}', [\App\Http\Controllers\api\v1\user\VendorController::class, 'getVendorDetail']);
    Route::get('/get-vendor-reviews/{vendor_id}', [\App\Http\Controllers\api\v1\user\VendorController::class, 'getVendorReviews']);
    Route::get('/get-vendor-packages/{vendor_id}', [\App\Http\Controllers\api\v1\user\VendorController::class, 'getVendorPackages']);
    Route::get('/get-vendor-sub-categories-of-category/{vendor_id}/{cat_id}', [\App\Http\Controllers\api\v1\user\VendorController::class, 'getVendorSubCategoriesOfCategory']);
    Route::get('/get-vendor-category-services/{vendor_id}/{cat_id}', [\App\Http\Controllers\api\v1\user\VendorController::class, 'getVendorCategoryServices']);

    Route::get('/get-user-recent-data', [\App\Http\Controllers\api\v1\user\RecentDataController::class, 'getRecentData']);


    Route::group(['middleware' => ['auth:api']] ,function () {

        Route::get('/get-payment-methods', [\App\Http\Controllers\api\v1\payment\PaymentController::class, 'getPaymentMethods']);
        Route::post('/create-order-payment', [\App\Http\Controllers\api\v1\payment\PaymentController::class, 'createOrderPayment']);


        Route::get('/wish-list', [\App\Http\Controllers\api\v1\user\WishListController::class, 'showWishListOfUser']);
        Route::post('/wish-list', [\App\Http\Controllers\api\v1\user\WishListController::class, 'addServiceToWishListOfUser']);
        Route::delete('/wish-list/{wish_list_item_id}', [\App\Http\Controllers\api\v1\user\WishListController::class, 'deleteServiceFromWishListOfUser']);


        Route::post('/get-items-price', [\App\Http\Controllers\api\v1\user\OrderController::class, 'getTotalPrice']);


        Route::post('/create-order', [\App\Http\Controllers\api\v1\user\OrderController::class, 'makeOrder']);

        Route::get('/get-list-of-orders-of-user', [\App\Http\Controllers\api\v1\user\OrderController::class, 'getListOrdersOfUser']);
        Route::get('/get-details-of-order-of-user/{order_id}', [\App\Http\Controllers\api\v1\user\OrderController::class, 'getOrderDetailsOfUser']);


        Route::post('/change-payment-method', [\App\Http\Controllers\api\v1\user\OrderActionController::class, 'changePaymentMethodOfOrder']);
        Route::post('/cancel-order/{order_id}', [\App\Http\Controllers\api\v1\user\OrderActionController::class, 'cancelOrderOfUser']);
        Route::post('/add-order-review', [\App\Http\Controllers\api\v1\user\OrderActionController::class, 'addOrderReview']);
        Route::get('/get-suggested-dates-by-vendor/{order_id}', [\App\Http\Controllers\api\v1\user\OrderActionController::class, 'getSuggestedDatesOfOrder']);
        Route::post('/change-order-date', [\App\Http\Controllers\api\v1\user\OrderActionController::class, 'changeOrderDate']);


        Route::get('/get-user-profile', [\App\Http\Controllers\api\v1\user\UserController::class, 'getUserProfile']);
        Route::post('/update-user-profile', [\App\Http\Controllers\api\v1\user\UserController::class, 'updateUserProfile']);

        Route::get('/show-order-cart', [\App\Http\Controllers\api\v1\user\OrderCartController::class, 'showOrderCart']);
        Route::post('/add-item-to-cart', [\App\Http\Controllers\api\v1\user\OrderCartController::class, 'addItemToOrderCart']);
        Route::delete('/delete-item-from-cart/{order_cart_item_id}', [\App\Http\Controllers\api\v1\user\OrderCartController::class, 'deleteItemFromCart']);
        Route::delete('/delete-cart', [\App\Http\Controllers\api\v1\user\OrderCartController::class, 'deleteOrderCart']);
        Route::post('/update-item-quantity', [\App\Http\Controllers\api\v1\user\OrderCartController::class, 'updateItemQtyInCart']);


    });


});
