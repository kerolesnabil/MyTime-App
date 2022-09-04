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
}
