<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ImgHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SavePageRequest;
use App\Models\CouponUsed;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Page;
use App\Models\Lang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    public function index(Request $request)
    {

        if (request()->ajax()){
            dd($request);
        }
        $orders = Order::getAllOrders(15);
        return view('dashboard.orders.index')->with(['orders' => $orders]);
    }


    public function showOrderById($orderId)
    {
        $orderData = Order::getOrderById($orderId);
        $orderItemsData = OrderItem::getItemsOfOrderByOrderId($orderId);
        $orderCoupon = CouponUsed::getUsedCouponByOrderId($orderId);

        if (!is_null($orderData)){
            return view('dashboard.orders.show_order')->with(['order'=> $orderData, 'items' => $orderItemsData, 'coupon' => $orderCoupon]);
        }
        session()->flash('warning', __('site_order.order_id_not_valid'));
        return redirect(route('order.index'));

    }

    public function reportOrders(Request $request)
    {
        if (!empty($request->date_from) && !empty($request->date_to)){

            $dateFrom = date('Y-m-d H:i:s', strtotime($request->date_from));
            $dateTo   = date('Y-m-d H:i:s', strtotime('+23 hour +59 minutes +59 seconds',strtotime($request->date_to)));
            $filteredOrders = Order::getOrdersWithFilters( $dateFrom, $dateTo);
            return view('dashboard.orders.filtered_orders')->with(['orders' => $filteredOrders]);
        }

        return response()->json(false);


    }
}
