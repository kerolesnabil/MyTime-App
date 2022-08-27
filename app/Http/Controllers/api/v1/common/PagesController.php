<?php

namespace App\Http\Controllers\api\v1\common;

use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\Pages;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


class PagesController extends Controller
{

    public static function getMenuPages(Request $request)
    {
        $rules = [
            "type" => "required|string",
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

       // dd($request->type);

        if(!$request->type == 'vendor' || !$request->type == 'user')
        {
             return ResponsesHelper::returnError(400,'The type you entered is wrong');
        }


        $data['first_pages']     = Pages::getPageByPosition($request->type, 'first');
        $data['last_pages']      = Pages::getPageByPosition($request->type, 'last');
        $data['social_media']    = SettingsController::getSocialMedia();
        $data['whatsapp_number'] = SettingsController::getWhatsAppNumber();

        return ResponsesHelper::returnData($data, '200', '');
    }

}
