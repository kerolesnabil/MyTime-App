<?php


namespace App\Http\Controllers\Dashboard;


use App\Models\Ad;
use App\Models\Order;
use App\Models\User;
use App\Models\VendorDetail;

class DashboardController
{
    public function index()
    {
        $data['new_users']              = User::countNewUsers(30);
        $data['vendor_type_salon']      = VendorDetail::countVendorsByType('salon');
        $data['vendor_type_specialist'] = VendorDetail::countVendorsByType('specialist');
        $data['new_orders']             = Order::countNewOrders(20);
        $data['available_ads']          = Ad::countAvailableAds();

        return view('dashboard.index', $data);
    }

}