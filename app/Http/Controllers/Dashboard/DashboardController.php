<?php


namespace App\Http\Controllers\Dashboard;


use App\Adpaters\IPayment;
use App\Models\Ad;
use App\Models\Order;
use App\Models\User;
use App\Models\VendorDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController
{
    public function index(Request $request)
    {

 /*       $paymenyObj = app(IPayment::class);
//        dd($paymenyObj->createPayment([
//            "order_id" => 10,
//            "user_id"  => 1,
//        ]));

        dd($paymenyObj->getPaymentInfo("dc27f4dd-db43-446a-a88d-d2fa3a401e18"));*/



        $data['daily_orders']   = Order::getNewOrders(20, 'daily')->total();
        $data['weekly_orders']  = Order::getNewOrders(20, 'weekly')->total();
        $data['monthly_orders'] = Order::getNewOrders(20, 'monthly')->total();
        $data['yearly_orders']  = Order::getNewOrders(20, 'yearly')->total();
        $data['all_orders']     = Order::getNewOrders(20, 'all')->total();


        $data['daily_users']   = User::getNewUsers(20, 'daily', 'user')->total();
        $data['weekly_users']  = User::getNewUsers(20, 'weekly', 'user')->total();
        $data['monthly_users'] = User::getNewUsers(20, 'monthly', 'user')->total();
        $data['yearly_users']  = User::getNewUsers(20, 'yearly', 'user')->total();
        $data['all_users']     = User::getNewUsers(20, 'all', 'user')->total();


        $data['daily_vendors']   = User::getNewUsers(20, 'daily', 'vendor')->total();
        $data['weekly_vendors']  = User::getNewUsers(20, 'weekly', 'vendor')->total();
        $data['monthly_vendors'] = User::getNewUsers(20, 'monthly', 'vendor')->total();
        $data['yearly_vendors']  = User::getNewUsers(20, 'yearly', 'vendor')->total();
        $data['all_vendors']     = User::getNewUsers(20, 'all', 'vendor')->total();


        $allVendors = User::getNewUsers(20, 'all', 'vendor');

        $walletsValue= 0;
        foreach ($allVendors as $vendor){

            if ($vendor->user_wallet >= 0){

                $walletsValue = $walletsValue + $vendor->user_wallet;
            }
        }

        $data['vendors_wallets_value'] = $walletsValue;

        $data['vendor_type_salon']      = VendorDetail::countVendorsByType('salon');
        $data['vendor_type_specialist'] = VendorDetail::countVendorsByType('specialist');
        if (!empty($data['new_orders'])){
            $data['new_orders'] = collect($data['new_orders'])->toArray()['total'];
        }

        $data['available_ads'] = Ad::getAllAvailableAds(20);

        if (!empty($data['available_ads'])){
            $data['available_ads'] = collect($data['available_ads'])->toArray()['total'];
        }


        $attrs                        = $request->all();
        $data['chart_orders_daily']   = collect(Order::getOrderChartsDaily($attrs))->toArray();
        $data['chart_orders_monthly'] = collect(Order::getOrderChartsMonthly($attrs))->toArray();
        $data['chart_orders_yearly']  = collect(Order::getOrderChartsYearly())->toArray();




        return view('dashboard.index', $data);
    }

}
