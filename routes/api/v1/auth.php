<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Authenticate;

Route::group([
    'prefix' => 'auth',
], function () {

    Route::post('/sendOTP',[\App\Http\Controllers\api\v1\auth\AuthController::class,'sendOTP']);
    Route::post('/login',[\App\Http\Controllers\api\v1\auth\AuthController::class,'login']);

});

Route::group([
    'middleware' => ['auth:api'],
], function () {

    Route::post('notification/save-notification-token', [\App\Http\Controllers\api\v1\notification\NotificationController::class,'saveNotificationToken']);
    Route::get('notification/show-notification', [\App\Http\Controllers\api\v1\notification\NotificationController::class,'showNotifications']);
    Route::post('auth/logout', [\App\Http\Controllers\api\v1\auth\AuthController::class, 'logout']);
    Route::get('auth/show-transaction-log',[\App\Http\Controllers\Financial_Transaction\FinancialTransactionController::class, 'showTransactionLogOfVendor']);

});
