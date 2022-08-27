<?php

namespace App\Http\Controllers\api\v1\notification;

use App\Http\Controllers\Controller;
use App\Models\NotificationToken;
use Illuminate\Http\Request;
use App\Helpers\ResponsesHelper;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function saveNotificationToken(Request $request)
    {
        $rules = [
            "user_id"   => "required|exists:users,user_id",
            "token"     =>  "required|string"
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        $notification=NotificationToken::createNotificationToken($request);

        return ResponsesHelper::returnSuccessMessage('','200');

    }
}
