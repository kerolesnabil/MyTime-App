<?php

namespace App\Http\Controllers\api\v1\notification;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\NotificationToken;
use Illuminate\Http\Request;
use App\Helpers\ResponsesHelper;
use Illuminate\Support\Facades\Auth;
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

        $checkIfTokenUnique =  NotificationToken::getNotificationByToken($request->get('token'));

        if (!is_object($checkIfTokenUnique)){
            NotificationToken::createNotificationToken($request);
        }
        
        return ResponsesHelper::returnSuccessMessage('','200');
    }



    public function showNotifications()
    {

        Notification::updateNotificationsIsSeenColByUserId(Auth::user()->user_id);

        $notifications = Notification::showNotificationsByUserId(Auth::user()->user_id);
        return ResponsesHelper::returnData($notifications,'200','');

    }
}
