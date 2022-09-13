<?php

namespace App\Http\Controllers\Dashboard\Auth;

use App\Adpaters\ISMSGateway;
use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Http\Services\VerificationServices;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    public $verificationServices;

    public function __construct(VerificationServices $verificationServices)
    {
        $this->verificationServices = $verificationServices;
    }

    public function getViewLogin()
    {
        return view('dashboard.auth.login');
    }

    public function sendSMS(Request $request)
    {
        $rules = [
            "user_phone" => "required|exists:users,user_phone|digits:9",
        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                "user_phone.required" => __("phone_is_required"),
                "user_phone.exists"   => __("you_should_register"),
            ]
        );

        if ($validator->fails()) {
//            return  $validator;
        }

        $user                    = User::getUserByPhone($request->user_phone);
        $verification['user_id'] = $user->user_id;
        $verification_data       = $this->verificationServices->setVerificationCode($verification);
        $message                 = $this->verificationServices->getSMSVerifyMessageByAppName($verification_data->code);

        app(ISMSGateway::class)->sendSms($user->user_phone, $message);

        return view('dashboard.auth.verify')->with(['user_phone'=>$request->phone,'verification_code'=>$verification_data->code]);
    }

    public function login(Request $request)
    {
        $rules     = [
            "user_phone" => "required|exists:users,user_phone|digits:9",
            'code'       => 'required|exists:verification_codes,code',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
//
        }

        $check = $this->verificationServices->checkOTPCode($request->code, $request->user_id);

        if (!$check) {
//
        }

        $this->verificationServices->removeOTPCode($request->code);
        $user = User::getUserByPhone($request->user_phone);
        Auth::login($user);
    }


}
