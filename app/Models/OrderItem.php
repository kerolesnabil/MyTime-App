<?php

namespace App\Models;

use App\Helpers\ImgHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class OrderItem extends Model
{
    use HasFactory;
    use AbstractionModelTrait;


    protected $table = "orders_items";
    protected $primaryKey = "order_item_id";
    protected $guarded = ['order_item_id'];
    protected $fillable = [
        'order_id',
        'item_id',
        'item_type',
        'item_price_before_discount',
        'item_price_after_discount',
        'item_count',
        'item_total_price_before_discount',
        'item_total_price_after_discount',
    ];
    protected $dates = ['deleted_at'];

    public $timestamps = true;


    public static function createOrderItems($orderItems)
    {

        return self::insert($orderItems);
    }


    public static function getItemsOfOrderByOrderId($orderId)
    {
        return self::query()
            ->select(
                'orders_items.item_id',
                'orders_items.item_count',
                self::getValueWithSpecificLang('services.service_name', app()->getLocale(), 'service_name')
            )
            ->join('vendor_services','vendor_services.vendor_service_id','=','orders_items.item_id')
            ->join('services','services.service_id', '=', 'vendor_services.service_id')
            ->where('orders_items.order_id','=', $orderId)
            ->get()
            ->toArray();
    }



}
