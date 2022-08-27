<?php

namespace App\Models;

use App\Helpers\ImgHelper;
use App\Helpers\ResponsesHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class CouponUsed extends Model
{
    use HasFactory;

    protected $table = "coupons_used";
    protected $primaryKey = "coupon_used_id";
    protected $guarded = ['coupon_used_id'];
    protected $fillable = [
        'user_id',
        'coupon_id',
        'order_id',
        'coupon_value',
    ];



    public static function checkIfUserUseCoupon($couponId, $userId)
    {
        $usedCoupon =
            self::query()
                ->select
                ('coupon_used_id')
                ->where('user_id','=',$userId)
                ->where('coupon_id','=',$couponId)
                ->first();

        if(count((array)$usedCoupon) !=0){
            // Coupon has been used by this user previously
            return true;
        }

        // Coupon has not been used by this user previously
        return false;

    }

    public static function createUsedCoupon($userId, $orderId, $couponId)
    {
        return self::create([
            "user_id"      => $userId,
            "coupon_id"    => $couponId,
            "order_id"     => $orderId,
        ]);

    }

    public static function getUsedCoupon($userId, $orderId)
    {
        $usedCoupon =
            self::query()
                ->select
                ('coupons.coupon_id', 'coupons.coupon_value', 'coupons.coupon_type')
                ->join('coupons','coupons.coupon_id', '=', 'coupons_used.coupon_id')
                ->where('coupons_used.user_id','=', $userId)
                ->where('coupons_used.order_id','=', $orderId)
                ->first();


        if (is_null($usedCoupon)){
            return ResponsesHelper::returnData([],'400', 'There is no coupon used for this order');
        }

        return ResponsesHelper::returnData($usedCoupon,'200', '');
    }

}
