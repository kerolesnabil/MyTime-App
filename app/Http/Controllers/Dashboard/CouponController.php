<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ImgHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveCouponRequest;
use App\Models\Coupon;
use App\Models\CouponUsed;
use App\Models\Page;
use Illuminate\Http\Request;

class CouponController extends Controller
{

    public function index()
    {
        $attr['paginate'] = 20;
        $coupons = Coupon::getAllCoupons($attr);
        return view('dashboard.coupons.index')->with(['coupons' => $coupons]);
    }



    public function saveCoupon(SaveCouponRequest $request, $couponId= null)
    {

        $data['coupon_code']        = $request->coupon_code;
        $data['coupon_value']       = $request->coupon_value;
        $data['coupon_type']        = $request->coupon_type;
        $data['coupon_limited_num'] = $request->coupon_limited_num;
        $data['coupon_start_at']    = $request->coupon_start_at;
        $data['coupon_end_at']      = $request->coupon_end_at;
        $data['is_active']          = $request->is_active;



        if (!is_null($couponId)){
            /**************  edit ***************/

            Coupon::saveCoupon($data, $couponId);
            session()->flash('success', __('site.updated_successfully'));
        }
        else{
            /**************  create ***************/

            Coupon::saveCoupon($data);
            session()->flash('success', __('site.created_successfully'));
        }

        return redirect(route('coupon.index'));
    }

    public function getCoupon($couponId = null)
    {

        if (!is_null($couponId)){
            //edit
            $coupon = Coupon::getCouponById($couponId);
            return view('dashboard.coupons.save')->with(['coupon' => $coupon]);
        }
        //create
        return view('dashboard.coupons.save');
    }

    public function updateActivationCoupon(Request $request)
    {

        if (isset($request->active_status) && isset($request->coupon_id)){

            if ($request->active_status == 'true'){
                Coupon::updateCouponActivationStatus($request->coupon_id, 1);
                return response()->json(['coupon_id' =>$request->coupon_id, 'status' => 'activate']);
            }

            Coupon::updateCouponActivationStatus($request->coupon_id, 0);
            return response()->json(['coupon_id' =>$request->coupon_id, 'status' => 'deactivate']);
        }
        return response()->json(false);

    }


    public function destroy($id)
    {
        Coupon::findOrFail($id)->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return back();
    }

    public function getUsersUsedCoupon(Request $request, $couponId)
    {
        $usersUsedCoupon = collect(CouponUsed::getUsersUsedCoupon($couponId))->toArray();


        return view('dashboard.coupons.users_used_coupon')->with(['data' => $usersUsedCoupon]);
    }

}
