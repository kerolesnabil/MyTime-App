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
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $attrs             = $request->all();
        $attrs['paginate'] = 20;

        $orders  = Order::getAllOrders($attrs);
        $users   = User::getUsersByType('user', []);
        $vendors = User::getUsersByType('vendor', []);

        return view('dashboard.orders.index')->with([
            'orders'  => $orders,
            'users'   => $users,
            'vendors' => $vendors
        ]);
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

        $filteredOrders = Order::getOrdersWithFilters($request->all());
        return view('dashboard.orders.filtered_orders')->with(['orders' => $filteredOrders]);

    }

    public function showNewOrders($reportType)
    {
        $reportTypes= ['daily', 'weekly', 'monthly', 'yearly', 'all'];

        if (in_array($reportType, $reportTypes)){
            $orders = Order::getNewOrders(20, $reportType);

            $users   = User::getUsersByType('user', []);
            $vendors = User::getUsersByType('vendor', []);
            return view('dashboard.orders.index')->with([
                'orders' => $orders,
                'report' => 'report',
                'users'  => $users,
                'vendors'  => $vendors,
            ]);
        }


        session()->flash('warning', __('site.report_type_wrong'));
        return redirect()->back();

    }
}
