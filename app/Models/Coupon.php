<?php

namespace App\Models;

use App\Helpers\ImgHelper;
use App\Helpers\ResponsesHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class Coupon extends Model
{
    use HasFactory;

    protected $table = "coupons";
    protected $primaryKey = "coupon_id";
    protected $guarded = ['coupon_id'];
    protected $fillable = [
        'coupon_id',
        'coupon_code',
        'coupon_value',
        'coupon_start_at',
        'coupon_end_at',
        'coupon_type',
        'coupon_limited_num',
        'coupon_used_times',
        'is_active'
    ];

    public static function couponVerification($userId, $couponCode)
    {
        $current_day = Carbon::today();

        $couponData =
            self::query()
                ->select
                ('coupon_id','coupon_value', 'coupon_type')
                ->where('coupon_code','=',$couponCode)
                ->whereColumn('coupon_limited_num','>','coupon_used_times')
                ->where('is_active','=', 1)
                ->whereDate('coupon_start_at','<=', $current_day)
                ->whereDate('coupon_end_at','>=', $current_day)
                ->first();


        if(!is_object($couponData)){
            $data['status'] = false;
            $data['coupon_value'] = '0';
            $data['coupon_type'] = 'value';
            return $data;
        }

        if(CouponUsed::checkIfUserUseCoupon($couponData['coupon_id'], $userId) == true){
            $data['status'] = false;
            $data['coupon_value'] = '0';
            $data['coupon_type'] = 'value';
            return $data;
        }

        $couponData['status'] = true;
        $couponData = collect(self::couponValueFormat($couponData))->toArray();
        return $couponData;
    }


    private static function couponValueFormat($couponData)
    {

        if($couponData['coupon_type'] == 'value'){
            $couponData['coupon_value'] = intval($couponData['coupon_value']);
        }
        else{
            $couponData['coupon_value'] = (intval($couponData['coupon_value']) / 100);
        }

        return $couponData;


    }


    public static function increaseCouponUsedTimes($couponId)
    {
        $coupon =
            self::query()
                ->select('coupon_used_times')
                ->where('coupon_id','=', $couponId)
                ->first();

        if (is_object($coupon)){
            $couponUsedTimes = $coupon->coupon_used_times;
            $newCouponUsedTimes = $couponUsedTimes + 1;
            self::where('coupon_id', '=', $couponId)
                ->update(array('coupon_used_times'    => $newCouponUsedTimes,));
        }

    }


    public static function getCouponValueByCouponId($couponId)
    {
        $couponData =
            self::query()
                ->select
                ('coupon_value', 'coupon_type')
                ->where('coupon_id','=',$couponId)
                ->first();

        return $couponData;
    }

}
