<?php

namespace App\Http\Controllers\api\v1\common;

use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\Page;
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

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                'page_type.required' => __('api.page_type_required'),
                'page_type.string' => __('api.page_type_string'),
            ]
        );

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

       // dd($request->type);

        if(!$request->type == 'vendor' || !$request->type == 'user')
        {
             return ResponsesHelper::returnError(400,__('api.page_type_entered_is_wrong'));
        }


        $data['first_pages']     = Page::getPageByPosition($request->type, 'first');
        $data['last_pages']      = Page::getPageByPosition($request->type, 'last');
        $data['social_media']    = SettingsController::getSocialMedia();
        $data['whatsapp_number'] = SettingsController::getWhatsAppNumber();

        return ResponsesHelper::returnData($data, '200', '');
    }

}
