<?php

namespace App\Http\Services;

use App\Models\User;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class VerificationServices
{
    /** set OTP code for mobile
     * @param $data
     *
     * @return VerificationCode
     */
    public function setVerificationCode($data)
    {
        $code = mt_rand(1000, 9999);
        $data['code'] = $code;
        $startTime = date("Y-m-d H:i:s");
        $data['verification_code_expire_time'] = date('Y-m-d H:i:s', strtotime('+15 minutes', strtotime($startTime)));
        VerificationCode::whereNotNull('user_id')->where(['user_id' => $data['user_id']])->delete();
        return VerificationCode::create($data);
    }

    public function getSMSVerifyMessageByAppName($code)
    {
        $message =__('mobile.verification code message');
        return $code . $message;
    }


    public function checkOTPCode($code,$user_id)
    {

        $verificationData = VerificationCode::where('code',$code)->first();
        //not test data Carbon
        $now = Carbon::now();

        if ($verificationData->code != $code || $verificationData->verification_code_expire_time<=$now ) {
            return false;
        }

        User::where('user_id',$user_id)->update(['user_phone_verified_at' => now()]);

        return true;
    }

    public function removeOTPCode($code)
    {
        VerificationCode::where('code', $code)->delete();
    }

}
