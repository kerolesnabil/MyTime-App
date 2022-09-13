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
        $data['new_orders']             = Order::getNewOrders(20,20);
        if (!empty($data['new_orders'])){
            $data['new_orders'] = collect($data['new_orders'])->toArray()['total'];
        }

        $data['available_ads'] = Ad::getAllAvailableAds(20);

        if (!empty($data['available_ads'])){
            $data['available_ads'] = collect($data['available_ads'])->toArray()['total'];
        }
        return view('dashboard.index', $data);
    }

}