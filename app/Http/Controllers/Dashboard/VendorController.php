<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VendorDetail;
use Illuminate\Http\Request;

class VendorController extends Controller
{

    public function index()
    {
        $vendors = User::getUsersByType('vendor');
        return view('dashboard.vendors.index')->with(['vendors'=>$vendors]);
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


    public function reportVendors(Request $request)
    {
        if (!empty($request->date_from) && !empty($request->date_to)){

            $dateFrom = date('Y-m-d H:i:s', strtotime($request->date_from));
            $dateTo   = date('Y-m-d H:i:s', strtotime('+23 hour +59 minutes +59 seconds',strtotime($request->date_to)));

            $allStatusOfOrder = ['pending', 'accepted', 'done', 'reschedule', 'canceled', 'rejected'];


            if ($request->order_status != 'no_status' && in_array($request->order_status, $allStatusOfOrder)){
                $vendorHaveFilteredOrders = User::getUsersHaveOrdersWithFilters('vendor',$dateFrom, $dateTo, $request->order_status);
            }
            else {
                $vendorHaveFilteredOrders = User::getUsersHaveOrdersWithFilters('vendor',$dateFrom, $dateTo);
            }

            return view('dashboard.vendors.filtered_vendors')->with(['vendors' => $vendorHaveFilteredOrders]);
        }

        return response()->json(false);


    }
}
