<?php

namespace App\Http\Controllers\Dashboard;

use App\Events\CreateAd;
use App\Helpers\ImgHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SavePageRequest;
use App\Models\Ad;
use App\Models\CouponUsed;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Page;
use App\Models\Lang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdController extends Controller
{

    public function index()
    {
        $ads = Ad::getAllAds(20);

        return view('dashboard.ads.index')->with(['ads' => $ads]);
    }


    public function showAdById($adId)
    {
        $adData = Ad::getAd($adId);

        if (!is_null($adData)){
            return view('dashboard.ads.show_ad')->with(['ad'=> $adData]);
        }

        session()->flash('warning', __('site_order.order_id_not_valid'));
        return redirect(route('order.index'));

    }


    public function showAvailableAds()
    {

        $ads = Ad::getAllAvailableAds(20);
        return view('dashboard.ads.index')->with(['ads' => $ads]);
    }

    public function rejectAd(Request $request, $adId)
    {
        //  reject
        Ad::updateAdStatus(0, $adId);

        // @TODO Mass3ood Send Notification to vendor

        session()->flash('success', __('site.saved_successfully'));
        return redirect(route('ad.index'));

    }

    public function acceptAd(Request $request, $adId)
    {
        // accept
        $adObj     = Ad::getAd($adId);
        $vendorObj = User::getUserTypeVendor($adObj->vendor_id);

        Ad::updateAdStatus(1, $adId);

        event(new CreateAd(
            $vendorObj->user_id,
            $vendorObj->user_wallet,
            $adObj->ad_cost
        ));

        // @TODO Mass3ood Send Notification to vendor

        session()->flash('success', __('site.saved_successfully'));
        return redirect(route('ad.index'));
    }
}
