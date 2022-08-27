<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Authenticate;

Route::group([
    'prefix'     => 'common',
], function () {

    Route::get('/get-menu-pages', [\App\Http\Controllers\api\v1\common\PagesController::class, 'getMenuPages']);

    Route::group(['middleware' => ['auth:api'],],function () {

        Route::post('/support-message', [\App\Http\Controllers\api\v1\common\SupportMessagesController::class, 'addSupportMessage']);

    });


});
