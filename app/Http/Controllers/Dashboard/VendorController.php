<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VendorController extends Controller
{

    public function index()
    {
        $vendors = User::getUsersByType('vendor');
        return view('dashboard.vendors.index')->with(['vendors'=>$vendors]);
    }


    public function save()
    {

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
}
