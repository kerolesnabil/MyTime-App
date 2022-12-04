<?php

namespace App\Http\Controllers\api\v1\user;

use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;



class CouponController extends Controller
{

    public static function couponVerification($couponCode, $userId)
    {

        if($couponCode == ''){
            $data['data'] = [];
            $data['msg']  = __('api.no_coupon_entered');
            return $data;
        }

        $couponData = Coupon::couponVerification($userId, $couponCode);
        $data['data'] = $couponData['data'];
        $data['msg']  = $couponData['msg'];
        return $data;
    }

}
