<?php

namespace App\Models;

use App\Helpers\ImgHelper;
use App\Helpers\ResponsesHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;


class Order extends Model
{
    use HasFactory;
    use AbstractionModelTrait;
    use SoftDeletes;

    protected $table = "orders";
    protected $primaryKey = "order_id";
    protected $fillable = [
        'user_id',
        'vendor_id',
        'payment_method_id',
        'order_address',
        'order_phone',
        'order_type',
        'order_notes',
        'order_status',
        'is_paid',
        'order_custom_date',
        'order_custom_time',
        'order_total_items_price_before_discount',
        'order_total_discount',
        'order_total_items_price_after_discount',
        'order_taxes_rate',
        'order_taxes_cost',
        'order_total_price',
        'order_app_profit',
        'order_lat',
        'order_long'
    ];
    protected $dates = ['deleted_at'];

    public static function createOrder($order)
    {
        $appProfit = '';
        return self::create([
            "user_id"                                 => $order['user_id'],
            "vendor_id"                               => $order['vendor_id'],
            "payment_method_id"                       => $order['payment_method_id'],
            "order_address"                           => $order['order_address'],
            "order_phone"                             => $order['order_phone'],
            "order_type"                              => $order['order_type'],
            "order_notes"                             => $order['order_notes'],
            "order_custom_date"                       => $order['order_custom_date'],
            "order_custom_time"                       => $order['order_custom_time'],
            "order_lat"                               => $order['order_lat'],
            "order_long"                              => $order['order_long'],
            "order_total_items_price_before_discount" => $order['order_total_items_price_before_discount'],
            "order_total_discount"                    => $order['order_total_discount'],
            "order_total_items_price_after_discount"  => $order['order_total_items_price_after_discount'],
            "order_taxes_rate"                        => $order['order_taxes_rate'],
            "order_taxes_cost"                        => $order['order_taxes_cost'],
            "order_total_price"                       => $order['order_total_price'],
            "order_app_profit"                        => 0,
            "order_status"                            => 'pending'
        ]);
    }

    public static function getLastOrdersOfVendor($id)
    {
        $lastOrders = self::query()
            ->select(
                'order_id',
                'order_custom_date',
                'order_custom_time',
                'order_status',
                'users.user_name',
                'users.user_img',
                DB::raw('DATE_FORMAT(orders.updated_at, "%Y-%m-%d %H:%i") as order_created_at')
            )->join('users', 'orders.user_id', '=', 'users.user_id')
            ->where('vendor_id','=',$id)
            ->where('order_status','=','pending')
            ->orderBy('order_created_at','desc')
            ->limit(5)
            ->get();

        if (!empty($lastOrders)){

            foreach (collect($lastOrders)->toArray() as $key => $order) {
                $lastOrders[$key]["user_img"]  = ImgHelper::returnImageLink($order["user_img"]);
            }
        }

        return $lastOrders;

    }

    public static function countAllOrdersOfVendor($id)
    {
        return self::query()
            ->select(
                DB::raw('count(order_id) as all_count_orders')
            )
            ->where('vendor_id',$id)->first();
    }

    public static function getOrdersListOrDetailsOfUser($userId, $orderId=null)
    {
        //chain
        $listOfOrders=
            self::query()
                ->select(
                    'orders.order_id',
                    'orders.order_custom_date as order_date',
                    'orders.order_custom_time as order_time',
                    'orders.order_type',
                    'orders.order_status',
                    'orders.is_paid',
                    'orders.vendor_id',
                    'users.user_name as vendor_name',
                    'users.user_img as vendor_logo',
                    'payment_methods.payment_method_id',
                    self::getValueWithSpecificLang('payment_methods.payment_method_name', app()->getLocale(), 'payment_method'),
                    DB::raw('DATE_FORMAT(orders.created_at, "%Y-%m-%d %H:%i") as order_created_at'),
                    'orders.is_rated'
                )
                ->join('users', 'orders.vendor_id', '=', 'users.user_id')
                ->join('payment_methods','orders.payment_method_id', '=','payment_methods.payment_method_id')
                ->where('orders.user_id', '=', $userId);

        if(!is_null($orderId)){
            $listOfOrders =
                $listOfOrders
                    ->addSelect
                    (
                        'order_total_items_price_before_discount',
                        'order_total_discount',
                        'order_total_items_price_after_discount',
                        'order_taxes_rate',
                        'order_taxes_cost',
                        'order_total_price'
                    )
                    ->where('orders.order_id', '=', $orderId);
        }

        return $listOfOrders->orderBy('orders.created_at','desc')->get()->toArray();
    }

    public static function changePaymentMethodOfOrder($data, $userId)
    {
        if (!self::checkIfUserHaveOrder($data->order_id, $userId)){
            return ['data' => [], 'code' =>400, 'msg' => __('api.can_not_change_order_payment_method')];
        }

        self::where('order_id', '=', $data->order_id)
            ->where('user_id', '=', $userId)
            ->update(array(
                'payment_method_id'    => $data->payment_method_id
        ));

        return ['data' => [], 'code' =>200, 'msg' => __('api.order_payment_method_updated')];
    }

    public static function changeStatusOfOrder($status, $orderId)
    {
        $orderStatusArray = array('pending', 'canceled', 'rejected', 'reschedule', 'done', 'accepted');

        if (in_array($status, $orderStatusArray)){
            self::where('order_id','=', $orderId)->update(['order_status' => $status]);
            return true;
        }
        return false;
    }

    public static function getOrderStatus($orderId){
        return  self::query()
            ->select(
                'order_status'
            )
            ->where('order_id',$orderId)->first();
    }

    public static function checkIfUserHaveOrder($orderId, $userId)
    {
        $order =
            self::query()
                ->where('user_id', '=', $userId)
                ->where('order_id', '=', $orderId)
                ->first();

        if (!count((array)$order)){
            return false;
        }
        return true;
    }

    public static function getSuggestedDatesOfOrderOfUser($orderId)
    {
        $orderSuggestedDates =
            self::query()
                ->select(
                    'suggested_date_by_vendor',
                    'reschedule_reason_msg'
                )
                ->where('order_id',$orderId)->first();
        return $orderSuggestedDates;
    }

    public static function changeOrderDate($data, $userId)
    {

        self::where('order_id', '=', $data->order_id)
            ->where('user_id', '=', $userId)
            ->update(array(
                'order_custom_date'    => $data->date,
                'order_custom_time'    => $data->time
            ));
    }

    public static function listOfOrdersOfVendor($vendorId)
    {
        $listOfOrders =
            self::query()
                ->select(
                    'orders.order_id',
                    'orders.order_custom_date as order_date',
                    'orders.order_custom_time as order_time',
                    'orders.order_type',
                    'orders.order_status',
                    'orders.is_paid',
                    'users.user_name',
                    'users.user_img',
                    DB::raw('DATE_FORMAT(orders.created_at, "%Y-%m-%d") as order_created_at')
                )
                ->join('users', 'orders.user_id', '=', 'users.user_id')
                ->where('orders.vendor_id', '=', $vendorId)
                ->get()->toArray();

        foreach ($listOfOrders as $key => $order) {
            $listOfOrders[$key]["user_img"]  = ImgHelper::returnImageLink($order["user_img"]);
        }

        return $listOfOrders;

    }

    public static function getOrderDetailsOfVendor($vendorId, $orderId = null)
    {

        $orderDetails['main_order_details'] =
            self::query()
                ->select(
                    'orders.order_id',
                    'orders.order_custom_date',
                    'orders.order_custom_time',
                    'orders.order_status',
                    'users.user_id',
                    'users.user_name',
                    'users.user_img',
                    DB::raw('DATE_FORMAT(orders.created_at, "%Y-%m-%d") as order_created_at'),
                    'orders.order_total_items_price_before_discount',
                    'orders.order_total_discount',
                    'orders.order_total_items_price_after_discount',
                    'orders.order_taxes_rate',
                    'orders.order_taxes_cost',
                    'orders.order_total_price',
                    'orders.order_type',
                    self::getValueWithSpecificLang('payment_methods.payment_method_name', app()->getLocale(), 'payment_method')

                )
                ->join('users', 'orders.user_id', '=', 'users.user_id')
                ->join('payment_methods','orders.payment_method_id', '=','payment_methods.payment_method_id')
                ->where('orders.vendor_id', '=', $vendorId)
                ->where('orders.order_id','=', $orderId)
                ->first();

        if (empty($orderDetails)){

            foreach (collect($orderDetails['main_order_details'])->toArray() as $key => $order) {
                $orderDetails['main_order_details'][$key]["user_img"]  = ImgHelper::returnImageLink($order["user_img"]);
            }
            $orderDetails['order_services'] = OrderItem::getItemsOfOrderByOrderId($orderId);
        }

        return $orderDetails;
    }

    public static function checkIfVendorHaveOrder($orderId, $vendorId)
    {
        $order =
            self::query()
                ->where('vendor_id', '=', $vendorId)
                ->where('order_id', '=', $orderId)
                ->first();

        if (!count((array)$order)){
            return false;
        }
        return true;
    }

    public static function createSuggestedDates($data)
    {
        self::where('order_id', '=', $data['order_id'])
            ->update(array(
                'reschedule_reason_msg'    => $data['reschedule_reason_msg'],
                'suggested_date_by_vendor'    => $data['suggested_dates'],
            ));

    }

    public static function getNewOrders($paginate, $reportType)
    {
        // $reportType => daily, weekly, monthly, yearly, all

        $time = Carbon::now();

        if ($reportType == 'daily'){
            $time =  Carbon::now()->startOfDay();
        }
        elseif ($reportType == 'weekly'){
            $time =  Carbon::now()->subWeek()->startOfDay();
        }
        elseif ($reportType == 'monthly'){
            $time =  Carbon::now()->subMonth()->startOfDay();
        }
        elseif ($reportType == 'yearly')
        {
            $time =  Carbon::now()->subYear()->startOfDay();
        }

        $time = $time->format('Y-m-d H:i:s');

        $orders = self::query()
            ->select(
                'orders.order_id',
                'orders.order_custom_date as order_date',
                'orders.order_custom_time as order_time',
                'orders.order_type',
                'orders.order_status',
                'orders.is_paid',
                'vendors.user_name as vendor_name',
                'users.user_name',
                self::getValueWithSpecificLang('payment_methods.payment_method_name', app()->getLocale(), 'payment_method'),
                DB::raw('DATE_FORMAT(orders.created_at, "%Y-%m-%d %H:%i") as order_created_at'),
                'order_taxes_cost',
                'order_total_price',
                'order_app_profit',
                'order_phone'
            )
            ->join('users as vendors', 'orders.vendor_id', '=', 'vendors.user_id')
            ->join('users', 'orders.user_id', '=', 'users.user_id')
            ->join('payment_methods','orders.payment_method_id', '=','payment_methods.payment_method_id');

        if($reportType != 'all'){
            $orders = $orders->where('orders.created_at', '>=', $time);
        }

        $orders = $orders->orderBy('orders.created_at','desc')->paginate($paginate);

        return $orders;

    }

    public static function updateRatedStatusOfOrder($orderId)
    {
        self::where('order_id', '=', $orderId)
            ->update(array(
                'is_rated'  => 1,
            ));

    }

    public static function getAllOrders($attrs)
    {
        $orders = self::query()
                ->select(
                    'orders.order_id',
                    'orders.vendor_id',
                    'orders.order_custom_date as order_date',
                    'orders.order_custom_time as order_time',
                    'orders.order_type',
                    'orders.order_status',
                    'orders.is_paid',
                    'vendors.user_name as vendor_name',
                    'users.user_name',
                    self::getValueWithSpecificLang('payment_methods.payment_method_name', app()->getLocale(), 'payment_method'),
                    DB::raw('DATE_FORMAT(orders.created_at, "%Y-%m-%d %H:%i") as order_created_at'),
                    'order_taxes_cost',
                    'order_total_price',
                    'order_app_profit',
                    'order_phone'
                )
                ->join('users as vendors', 'orders.vendor_id', '=', 'vendors.user_id')
                ->join('users', 'orders.user_id', '=', 'users.user_id')
                ->join('payment_methods','orders.payment_method_id', '=','payment_methods.payment_method_id')
                ->orderBy('orders.created_at','desc');

        if(isset($attrs['date_from']) && $attrs['date_from'] != ''){
            $attrs['date_from'] = date('Y-m-d H:i:s', strtotime($attrs['date_from']));
            $orders = $orders->where('orders.created_at','>=', $attrs['date_from']);
        }

        if(isset($attrs['date_to']) && $attrs['date_to'] != ''){
            $attrs['date_to'] = date('Y-m-d H:i:s', strtotime('+23 hour +59 minutes +59 seconds',strtotime($attrs['date_to'])));
            $orders = $orders->where('orders.created_at','<=', $attrs['date_to']);
        }

        if(isset($attrs['order_type']) && $attrs['order_type'] != '' &&  $attrs['order_type'] != 'all'){
            $orders = $orders->where('orders.order_type','=', $attrs['order_type']);
        }

        if(isset($attrs['order_status']) && $attrs['order_status'] != '' &&  $attrs['order_status'] != 'all'){
            $orders = $orders->where('orders.order_status','=', $attrs['order_status']);
        }

        if(isset($attrs['order_id']) && $attrs['order_id'] != ''){
            $orders = $orders->where('orders.order_id','=', $attrs['order_id']);
        }

        if(isset($attrs['user_id']) && $attrs['user_id'] != '' &&  $attrs['user_id'] != 'all'){
            $orders = $orders->where('orders.user_id','=', $attrs['user_id']);
        }

        if(isset($attrs['vendor_id']) && $attrs['vendor_id'] != '' &&  $attrs['vendor_id'] != 'all'){
            $orders = $orders->where('orders.vendor_id','=', $attrs['vendor_id']);
        }

        if(isset($attrs['order_phone']) && $attrs['order_phone'] != ''){
            $orders = $orders->where('orders.order_phone','like', $attrs['order_phone']);
        }


        if(isset($attrs['paginate']) && $attrs['paginate'] != ''){
            $orders = $orders->paginate($attrs['paginate']);
        }
        else{
            $orders = $orders->get();
        }

        return $orders;

    }

    public static function getOrderById($orderId)
    {
        return self::query()
            ->select(
                'orders.order_id',
                'orders.order_custom_date as order_date',
                'orders.order_custom_time as order_time',
                'orders.order_type',
                'orders.order_status',
                'orders.is_paid',
                'vendors.user_name as vendor_name',
                'users.user_name',
                'payment_methods.payment_method_type',
                self::getValueWithSpecificLang('payment_methods.payment_method_name', app()->getLocale(), 'payment_method'),
                DB::raw('DATE_FORMAT(orders.created_at, "%Y-%m-%d _ %H:%i") as order_created_at'),
                'orders.order_total_items_price_before_discount',
                'orders.order_total_discount',
                'orders.order_total_items_price_after_discount',
                'orders.order_taxes_rate',
                'order_taxes_cost',
                'order_total_price',
                'order_app_profit',
                'order_phone',
                'is_rated'
            )
            ->join('users as vendors', 'orders.vendor_id', '=', 'vendors.user_id')
            ->join('users', 'orders.user_id', '=', 'users.user_id')
            ->join('payment_methods','orders.payment_method_id', '=','payment_methods.payment_method_id')
            ->where('orders.order_id','=', $orderId)->first();

    }

    public static function getOrdersOfVendorByKeyWord($vendorId, $orderId = null, $username =null)
    {
        // get order by => order id or username
        $orders =
            self::query()
                ->select(
                    'orders.order_id',
                    'orders.order_custom_date as order_date',
                    'orders.order_custom_time as order_time',
                    'orders.order_type',
                    'orders.order_status',
                    'orders.is_paid',
                    'users.user_name',
                    'users.user_img',
                    DB::raw('DATE_FORMAT(orders.created_at, "%Y-%m-%d") as order_created_at')
                )
                ->join('users', 'orders.user_id', '=', 'users.user_id')
                ->where('orders.vendor_id', '=', $vendorId);


                if(!is_null($orderId)){

                    $order = $orders->where('orders.order_id','=', $orderId);
                }

                if (!is_null($username)){

                    $order = $orders->where('users.user_name','like', "%$username%");
                }

                $orders = $orders->get();

        if (!empty($order)){

            foreach ($orders as $order){
                $order['user_img'] = ImgHelper::returnImageLink($order['user_img']);
            }
        }

        return $orders;
    }


    public static function getOrderChartsDaily($attr)
    {
        $dbDateFormat = '%Y-%m-%d';
        $dateFrom     = Carbon::now()->firstOfMonth()->format('Y-m-d');
        $dateTo       = Carbon::now()->lastOfMonth()->format('Y-m-d');

        if (isset($attr['order_count_daily_form_month']) && $attr['order_count_daily_form_month'] != ''){

            $dateFrom     = Carbon::createFromFormat('Y-m', $attr['order_count_daily_form_month'])->
                            firstOfMonth()->format('Y-m-d');
            $dateTo       = Carbon::createFromFormat('Y-m', $attr['order_count_daily_form_month'])->
                            lastOfMonth()->format('Y-m-d');
        }

        $data      = self::getOrderChartsBasedOnType($dateFrom, $dateTo, $dbDateFormat);
        $loopStart = date('Y-m-d', strtotime($dateFrom));
        $loopEnd   = date('Y-m-d', strtotime($dateTo));


        for ($i = $loopStart; $i <= $loopEnd; $i++){
            if (!isset($data[$i])){
                $data[$i] = 0;
            }


        }

        return $data;
    }

    public static function getOrderChartsMonthly($attr)
    {
        $dbDateFormat = '%Y-%m';
        $dateFrom     = Carbon::now()->firstOfYear()->format('Y-m');
        $dateTo       = Carbon::now()->lastOfYear()->format('Y-m');

        if (isset($attr['order_count_monthly_form_year']) && $attr['order_count_monthly_form_year'] != ''){

            $dateFrom     = Carbon::createFromFormat('Y', $attr['order_count_monthly_form_year'])->
                            firstOfYear()->format('Y-m');
            $dateTo       = Carbon::createFromFormat('Y', $attr['order_count_monthly_form_year'])->
                            lastOfYear()->format('Y-m');
        }

        $data  = self::getOrderChartsBasedOnType($dateFrom, $dateTo, $dbDateFormat);

        $loopStart = date('Y-m', strtotime($dateFrom));
        $loopEnd   = date('Y-m', strtotime($dateTo));

        for ($i = $loopStart; $i <= $loopEnd; $i++){
            if (!isset($data[$i])){
                $data[$i] = 0;
            }
        }

        return $data;
    }

    public static function getOrderChartsYearly()
    {
        $dateFrom     = Carbon::now()->subYears(5)->format('Y');
        $dateTo       = Carbon::now()->format('Y');
        $dbDateFormat = '%Y';
        return self::getOrderChartsBasedOnType($dateFrom, $dateTo, $dbDateFormat);
    }








    private static function getOrderChartsBasedOnType($dateFrom, $dateTo, $dbDateFormat)
    {
        $data =  self::query()->
            select(
                    DB::raw('COUNT(*) as `count`, DATE_FORMAT(created_at, "'. $dbDateFormat .'") as date')
            )->
            where(DB::raw('DATE_FORMAT(created_at, "'. $dbDateFormat .'")'), '>=', $dateFrom)->
            where(DB::raw('DATE_FORMAT(created_at, "'. $dbDateFormat .'")'), '<=', $dateTo)->
            groupBy('date')->
            orderBy('date', 'ASC')->
            get();


        $result = [];
        foreach ($data as $key => $item){
            $key          = $item->date;
            $result[$key] = $item->count;
        }
        return $result;
    }

}
