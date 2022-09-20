<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ImgHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SavePageRequest;
use App\Models\Ad;
use App\Models\CouponUsed;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Page;
use App\Models\Lang;
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
}
