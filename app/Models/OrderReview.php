<?php

namespace App\Models;

use App\Helpers\ImgHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class OrderReview extends Model
{
    use HasFactory;
    use AbstractionModelTrait;

    protected $table = "order_reviews";
    protected $primaryKey = "order_review_id";
    protected $guarded = ['order_review_id'];
    protected $fillable = [
        'user_id',
        'order_id',
        'vendor_id',
        'rate',
        'review_comment',
    ];

    public static function addOrderReview($data, $userId)
    {

        $data = self::create([
            'user_id'        => $userId,
            'order_id'       => $data->order_id,
            'vendor_id'      => $data->vendor_id,
            'rate'           => $data->rate,
            'review_comment' => $data->review_comment,
        ]);

        return $data->order_review_id;

    }
    public static function checkIfUserReviewOrder($orderId, $userId)
    {
        $oldReview =
            self::query()
                ->select('order_review_id')
                ->where('user_id', '=', $userId)
                ->where('order_id', '=', $orderId)
                ->first();

        if (count((array) $oldReview)){
            return false;
        }

        return true;

    }


    public static function getVendorReviews($vendorId)
    {
        $vendorReviews=
            self::query()
                ->select(
                    'users.user_id',
                    'users.user_name',
                    'users.user_img',
                    'order_reviews.order_review_id',
                    'order_reviews.rate',
                    'order_reviews.review_comment',
                    'order_reviews.created_at'
                )
                ->leftJoin('users', 'users.user_id', '=', 'order_reviews.user_id')
                ->where('order_reviews.vendor_id', '=', $vendorId)
                ->get()
                ->toArray();

        for($i=0; $i<count($vendorReviews);$i++){
            $vendorReviews[$i]['created_at'] = date( "Y-m-d H:i", strtotime($vendorReviews[$i]['created_at']));
            $vendorReviews[$i]['user_img'] = ImgHelper::returnImageLink($vendorReviews[$i]['user_img']);
        }

        return $vendorReviews;

    }

}
