<?php

namespace App\Http\Controllers\api\v1\auth;


use App\Adpaters\ISMSGateway;
use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Http\Services\VerificationServices;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{

    public $verificationServices;

    public function __construct(VerificationServices $verificationServices)
    {
        $this->verificationServices = $verificationServices;
    }

    public function sendOTP(Request $request)
    {
        $rules = [
            "user_phone" => "required|exists:users,user_phone|digits:9",
        ];

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                "user_phone.required" => __("api.phone_is_required"),
                "user_phone.exists"   => __("api.you_should_register"),
            ]
        );

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        $user                    = User::getUserByPhone($request->user_phone);
        $verification['user_id'] = $user->user_id;
        $verification_data       = $this->verificationServices->setVerificationCode($verification);
        $message                 = $this->verificationServices->getSMSVerifyMessageByAppName($verification_data->code);

        app(ISMSGateway::class)->sendSms($user->user_phone, $message);

        //return token

        return ResponsesHelper::returnData(
            ['user_id'=>$user->user_id,
             'verification_code'=>$verification_data->code
            ],
            '200'
        );

    }

    public function login(Request $request)
    {
        $rules     = [
            "user_phone" => "required|exists:users,user_phone|digits:9",
            'code'       => 'required|exists:verification_codes,code',
        ];
        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                'user_phone.required' => __('api.phone_is_required'),
                'user_phone.exists'   => __('api.phone_exists'),
                'user_phone.digits'   => __('api.phone_is_9_digits'),
                'code.required'       => __('api.code_is_required'),
                'code.exists'         => __('api.code_exists'),
            ]
        );

        if ($validator->fails()) {
            return ResponsesHelper::returnValidationError('400', $validator);
        }

        $check = $this->verificationServices->checkOTPCode($request->code, $request->user_id);

        if (!$check) {
            return ResponsesHelper::returnError('400', __('api.code_incorrect'));
        }

        $this->verificationServices->removeOTPCode($request->code);
        $user = User::getUserByPhone($request->user_phone);
        Auth::login($user);

        $user->token = $user->createToken('mobile')->accessToken;

        return ResponsesHelper::returnData($user, '200', __('api.login'));
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return ResponsesHelper::returnSuccessMessage(__('api.logged_out_successfully'), '200');
    }

}
