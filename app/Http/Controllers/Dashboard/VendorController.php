<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ImgHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditVendorAppProfitRequest;
use App\Models\Category;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use App\Models\VendorDetail;
use App\Models\VendorServices;
use Illuminate\Http\Request;

class VendorController extends Controller
{

    public function index(Request $request)
    {

        $attrs['paginate'] = 10;
        $vendors = User::getUsersByType('vendor', array_merge($request->all(), $attrs));

        $orders = Order::getAllOrders([]);


        foreach ($vendors->items() as $vendor){
            $vendor['total_orders'] = $orders->where('vendor_id','=',$vendor->user_id)->count();
            $vendor['done_orders']  = $orders->where('vendor_id','=',$vendor->user_id)
                                     ->where('order_status','=','done')->count();

        }


        return view('dashboard.vendors.index')->with(['vendors'=> $vendors]);
    }

    public function updateActivateVendor(Request $request)
    {

        if (isset($request->active_status) && isset($request->user_id)){

            if ($request->active_status == 'true'){
                User::updateActivationStatus($request->user_id, 1);
                return response()->json(['user_id' =>$request->user_id, 'status' => 'activate']);
            }

            User::updateActivationStatus($request->user_id, 0);
            return response()->json(['user_id' =>$request->user_id, 'status' => 'deactivate']);
        }
        return response()->json(false);

    }

    public function showVendorById($vendorId)
    {
        $vendorData = VendorDetail::vendorDetails($vendorId);

        if (!is_null($vendorData)){
            return view('dashboard.vendors.show_vendor')->with(['vendor'=> $vendorData]);
        }

        session()->flash('warning', __('site_vendor.vendor_id_not_valid'));
        return redirect(route('vendor.index'));

    }


    public function showNewVendors($reportType)
    {
        $reportTypes= ['daily', 'weekly', 'monthly', 'yearly', 'all'];

        if (in_array($reportType, $reportTypes)){
            $users = User::getNewUsers(20, $reportType,'vendor');
            return view('dashboard.vendors.show_new_vendors')->with(['users' => $users]);
        }

        session()->flash('warning', __('site.report_type_wrong'));
        return redirect()->back();
    }


    public function saveVendorServices(Request $request , $vendorId)
    {

        $vendorData = User::getUserTypeVendor($vendorId);
        $allServices = Service::gelAllServices();
        $vendorServices = VendorServices::getAllServicesOfVendor($vendorId);

        if ($request->method() == 'POST'){
            $vendorServicesIds      = collect($vendorServices)->pluck('main_service_id')->toArray();
            $servicesIdsWillCreate  = array_diff($request->get('service_id'), $vendorServicesIds);
            $servicesIdsWillUpdate  = array_intersect($request->get('service_id'), $vendorServicesIds);

            $servicesDataWillUpdate = $this->handelVendorServicesWillUpdate($request, $vendorServices, $servicesIdsWillUpdate);
            $servicesDataWillCreate = array_values($this->handelVendorServicesWillCreate($request, $vendorId, $servicesIdsWillCreate));

            VendorServices::updateVendorServices($servicesDataWillUpdate);
            VendorServices::createVendorServices($servicesDataWillCreate);

            session()->flash('success', __('site.saved_successfully'));
            return redirect(route('vendor.save_vendor_services', $vendorId));
        }



        return view('dashboard.vendors.save_vendor_services')->with([
            'all_services'    => $allServices,
            'vendor_services' => $vendorServices,
            'vendor'          => $vendorData,
        ]);
    }

    private function handelVendorServicesWillCreate(Request $request, $vendorId, $servicesIdsWillCreate)
    {
        $data = [];
        foreach ($servicesIdsWillCreate as $key => $id){

            $data[$key]['vendor_id']                       = $vendorId;
            $data[$key]['service_id']                      = $id;
            $data[$key]['service_title']                   = $request->get('service_title')[$id];
            $data[$key]['service_price_at_salon']          = $request->get('service_price_at_salon')[$id];
            $data[$key]['service_discount_price_at_salon'] = $request->get('service_discount_price_at_salon')[$id];
            $data[$key]['service_price_at_home']           = $request->get('service_price_at_home')[$id];
            $data[$key]['service_discount_price_at_home']  = $request->get('service_discount_price_at_home')[$id];
            $data[$key]['updated_at']                      = now();
            $data[$key]['created_at']                      = now();
        }
        return $data;
    }


    private function handelVendorServicesWillUpdate(Request $request, $oldVendorServicesData, $servicesIdsWillUpdate)
    {
        $data = [];
        foreach ($servicesIdsWillUpdate as $key => $id){

            $oldVendorServiceObj                     = collect($oldVendorServicesData)->where('main_service_id', '=', $id)->first();
            $data[$key]['vendor_service_id']               = $oldVendorServiceObj->service_id;
            $data[$key]['service_title']                   = $request->get('service_title')[$id];
            $data[$key]['service_price_at_salon']          = $request->get('service_price_at_salon')[$id];
            $data[$key]['service_discount_price_at_salon'] = $request->get('service_discount_price_at_salon')[$id];
            $data[$key]['service_price_at_home']           = $request->get('service_price_at_home')[$id];
            $data[$key]['service_discount_price_at_home']  = $request->get('service_discount_price_at_home')[$id];
        }

        return $data;
    }


    public function editVendorAppProfit(Request $request , $vendorId)
    {
        $appProfit = $request->get('vendor_app_profit_percentage');
        VendorDetail::updateVendorAppProfit($vendorId, $appProfit);
        session()->flash('success', __('site.saved_successfully'));
        return redirect(route('vendor.index'));
    }


    public function getVendorAppProfit(Request $request, $vendorId)
    {
        $vendorData = User::getUsersByType('vendor');
        $vendorData  = collect($vendorData)->where('user_id','=', $vendorId)->first();

        return view('dashboard.vendors.edit_vendor_app_profit')->with([
            'vendor'          => $vendorData,
        ]);
    }
}
