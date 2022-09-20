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
        $data['daily_orders']   = Order::getNewOrders(20, 'daily')->total();
        $data['weekly_orders']  = Order::getNewOrders(20, 'weekly')->total();
        $data['monthly_orders'] = Order::getNewOrders(20, 'monthly')->total();
        $data['yearly_orders']  = Order::getNewOrders(20, 'yearly')->total();
        $data['daily_users']    = User::getNewUsers(20, 'daily')->total();
        $data['weekly_users']   = User::getNewUsers(20, 'weekly')->total();
        $data['monthly_users']  = User::getNewUsers(20, 'monthly')->total();
        $data['yearly_users']   = User::getNewUsers(20, 'yearly')->total();






        $data['vendor_type_salon']      = VendorDetail::countVendorsByType('salon');
        $data['vendor_type_specialist'] = VendorDetail::countVendorsByType('specialist');
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