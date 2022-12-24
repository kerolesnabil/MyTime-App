<?php

namespace App\Http\Controllers\api\v1\common;

use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\SupportMessage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;



class SupportMessagesController extends Controller
{

    public static function addSupportMessage(Request $request)
    {
        $user['user']=Auth::user();

        $rules = [
            "name" => "required|string",
            "phone" => "required|digits:9",
            "message" => "required|string",
        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                "name_required" => __("api.name_required"),
                "name_should_string" => __("api.name_should_string"),

                'phone.required' => __('api.phone_is_required'),
                'phone.digits'   => __('api.phone_is_9_digits'),

                "message_required" => __("api.message_required"),
                "message_string" => __("api.message_should_string"),


            ]
        );

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }


        $supportMessage = SupportMessage::createSupportMessage($user['user']->user_id, $request);

        return ResponsesHelper::returnData(['order_id' => $supportMessage->support_message_id], '200', __('api.support_message_sent_successfully'));
    }

}
