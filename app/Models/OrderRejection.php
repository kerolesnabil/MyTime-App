<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;


class OrderRejection extends Model
{
    use HasFactory;

    protected $table = "order_rejections";
    protected $primaryKey = "rejection_id";
    protected $guarded = ['rejection_id'];
    protected $fillable = [
        'order_id', 'rejection_reason'
    ];


    public static function createOrderRejectionReasons($data)
    {
       self::insert($data);
    }


    public static function getRejectionReasonByOrderId($orderId)
    {
       return self::query()
            ->select(
                'rejection_reason'
            )
            ->where('order_id', $orderId)->first();
    }



}
