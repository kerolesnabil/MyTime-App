<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ImgHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveAdsPricesRequest;
use App\Http\Requests\SaveAppImagesRequest;
use App\Http\Requests\SaveAppProfitPercentageRequest;
use App\Http\Requests\SaveBankAccountDetailsRequest;
use App\Http\Requests\SaveDiameterSearchRequest;
use App\Http\Requests\SaveSocialMediaRequest;
use App\Http\Requests\SaveTaxRateRequest;
use App\Models\Lang;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{


    public function showSocialMedia()
    {
        $socialMedia = Setting::getSettingByKey('social_media','api');
        $socialMedia['setting_value'] = json_decode($socialMedia['setting_value'], true);
        return view('dashboard.settings.show_social_media')->with(['social_media' => $socialMedia]);
    }

    public function getSocialMedia($settingId = null)
    {
        $langs = Lang::getAllLangs();

        if (!is_null($settingId)){
            //edit
            $socialMedia                  = Setting::getSettingByKey('social_media', 'web');
            $socialMedia['setting_name']  = json_decode($socialMedia['setting_name'], true);
            $socialMedia['setting_value'] = json_decode($socialMedia['setting_value'], true);
            $operation = 'edit';
            return view('dashboard.settings.save_social_media')->with(['social_media' => $socialMedia, 'langs' => $langs, 'operation' =>$operation]);
        }
        //create
        $operation = 'add';
        return view('dashboard.settings.save_social_media')->with(['langs' => $langs, 'operation'=> $operation]);

    }

    public function saveSocialMedia(SaveSocialMediaRequest $request){

        if ($request->operation == 'edit'){
            /**************  edit ***************/
            $settingName = json_encode($request->setting_name);
            $settingValue = json_encode($request->setting_value);
            Setting::saveSettingByKey('social_media', $settingValue, $settingName);
            session()->flash('success', __('site.saved_successfully'));
        }
        else{
            /**************  create ***************/

            $socialMedia    = Setting::getSettingByKey('social_media', 'web');
            $settingValue   = json_decode($socialMedia['setting_value'], true);
            $settingValue[] = $request->setting_value;
            $settingValue   = json_encode($settingValue);

            Setting::saveSettingByKey('social_media', $settingValue);
            session()->flash('success', __('site.created_successfully'));
        }

        return redirect(route('setting.social_media'));
    }

    public function destroySocialMedia(Request $request)
    {
        if (isset($request->data) && isset($request->deleted_data_key)){
            $data = json_decode($request->data, true);
            unset($data[$request->deleted_data_key]);
            $data = json_encode($data);
            Setting::saveSettingByKey('social_media', $data);
        }
    }

    public function getAppImages()
    {
        $userSplash                    = Setting::getSettingByKey('user_splash_screen', 'web');
        $userSplash['setting_name']   = json_decode($userSplash['setting_name'], true);

        $userLogo                      = Setting::getSettingByKey('user_logo', 'web');
        $userLogo['setting_name']     = json_decode($userLogo['setting_name'], true);

        $vendorSplash                  = Setting::getSettingByKey('vendor_splash_screen', 'web');
        $vendorSplash['setting_name'] = json_decode($vendorSplash['setting_name'], true);

        $vendorLogo                    = Setting::getSettingByKey('vendor_logo', 'web');
        $vendorLogo['setting_name']   = json_decode($vendorLogo['setting_name'], true);


        $langs = Lang::getAllLangs();

        return view('dashboard.settings.save_app_images')->with(
        [
            'user_splash'   => $userSplash,
            'user_logo'     => $userLogo,
            'vendor_splash' => $vendorSplash,
            'vendor_logo'   => $vendorLogo,
            'langs'         => $langs,
        ]);

    }

    public function saveAppImages(SaveAppImagesRequest $request)
    {

        $userSplashSettingName = json_encode($request->user_splash['setting_name']);
        if (isset($request->user_splash['setting_value'])){

            $img = ImgHelper::uploadImage('images', $request->user_splash['setting_value']);
            Setting::saveSettingByKey('user_splash_screen', $img, $userSplashSettingName);
        }
        else {
            Setting::saveSettingByKey('user_splash_screen', null, $userSplashSettingName);
        }


        $userLogoSettingName = json_encode($request->user_logo['setting_name']);
        if (isset($request->user_logo['setting_value'])){

            $img = ImgHelper::uploadImage('images', $request->user_logo['setting_value']);
            Setting::saveSettingByKey('user_logo', $img, $userLogoSettingName);
        }
        else {
            Setting::saveSettingByKey('user_logo', null, $userLogoSettingName);
        }



        $vendorSplashSettingName = json_encode($request->vendor_splash['setting_name']);
        if (isset($request->vendor_splash['setting_value'])){

            $img = ImgHelper::uploadImage('images', $request->vendor_splash['setting_value']);
            Setting::saveSettingByKey('vendor_splash_screen', $img, $vendorSplashSettingName);
        }
        else {
            Setting::saveSettingByKey('vendor_splash_screen', null, $vendorSplashSettingName);
        }


        $vendorLogoSettingName = json_encode($request->vendor_logo['setting_name']);
        if (isset($request->vendor_logo['setting_value'])){

            $img = ImgHelper::uploadImage('images', $request->vendor_logo['setting_value']);
            Setting::saveSettingByKey('vendor_logo', $img, $vendorLogoSettingName);
        }
        else {
            Setting::saveSettingByKey('vendor_logo', null, $vendorLogoSettingName);
        }


        session()->flash('success', __('site.saved_successfully'));
        return redirect(route('admin.homepage'));
    }


    public function getAdsPrice()
    {
        // to do

        $adPriceInHomepage                     = Setting::getSettingByKey('price_ad_in_homepage', 'web');
        $adPriceInHomepage['setting_name']     = json_decode($adPriceInHomepage['setting_name'], true);

        $adPriceInDiscoverPage                 = Setting::getSettingByKey('price_ad_in_discover_page', 'web');
        $adPriceInDiscoverPage['setting_name'] = json_decode($adPriceInDiscoverPage['setting_name'], true);

        $langs = Lang::getAllLangs();


        return view('dashboard.settings.save_ads_price')->with(
        [
            'ad_price_in_homepage'      => $adPriceInHomepage,
            'ad_price_in_discover_page' => $adPriceInDiscoverPage,
            'langs'                     => $langs
        ]);

    }


    public function saveAdsPrice(SaveAdsPricesRequest $request)
    {

        $adPriceInHomepageSettingName = json_encode($request->ad_price_in_homepage['setting_name']);
        Setting::saveSettingByKey('price_ad_in_homepage', $request->ad_price_in_homepage['setting_value'], $adPriceInHomepageSettingName);

        $adPriceInDiscoverPageSettingName = json_encode($request->ad_price_in_discover_page['setting_name']);
        Setting::saveSettingByKey('price_ad_in_discover_page', $request->ad_price_in_discover_page['setting_value'], $adPriceInDiscoverPageSettingName);

        session()->flash('success', __('site.saved_successfully'));
        return redirect(route('admin.homepage'));

    }

    public function getDiameterSearch()
    {
        $diameterSearch                     = Setting::getSettingByKey('diameter_search', 'web');
        $diameterSearch['setting_name']     = json_decode($diameterSearch['setting_name'], true);


        $langs = Lang::getAllLangs();


        return view('dashboard.settings.save_diameter_search')->with(
        [
            'diameter_search' => $diameterSearch,
            'langs'           => $langs
        ]);
    }

    public function saveDiameterSearch(SaveDiameterSearchRequest $request)
    {
        $diameterSearchSettingName = json_encode($request->diameter_search['setting_name']);
        Setting::saveSettingByKey('diameter_search', $request->diameter_search['setting_value'], $diameterSearchSettingName);

        session()->flash('success', __('site.saved_successfully'));
        return redirect(route('admin.homepage'));

    }

    public function getBankAccountDetails()
    {
        $bankAccountDetails                     = Setting::getSettingByKey('bank_account_details', 'web');
        $bankAccountDetails['setting_name']     = json_decode($bankAccountDetails['setting_name'], true);
        $bankAccountDetails['setting_value']    = json_decode($bankAccountDetails['setting_value'], true);

        $langs = Lang::getAllLangs();

        return view('dashboard.settings.save_bank_account_details')->with(
        [
            'bank_account_details' => $bankAccountDetails,
            'langs'           => $langs
        ]);
    }

    public function saveBankAccountDetails(SaveBankAccountDetailsRequest $request)
    {
        $bankAccountDetailsSettingName  = json_encode($request->bank_account_details['setting_name']);
        $bankAccountDetailsSettingValue = json_encode($request->bank_account_details['setting_value']);

        Setting::saveSettingByKey('bank_account_details', $bankAccountDetailsSettingValue, $bankAccountDetailsSettingName);

        session()->flash('success', __('site.saved_successfully'));
        return redirect(route('setting.get_bank_account_details'));

    }



    public function getAppProfitPercentage()
    {
        $appProfit                  = Setting::getSettingByKey('app_profit_percentage', 'web');
        $appProfit['setting_name']  = json_decode($appProfit['setting_name'], true);


        $langs = Lang::getAllLangs();
        return view('dashboard.settings.save_app_profit_percentage')->with(
            [
                'app_profit_percentage' => $appProfit,
                'langs'           => $langs
            ]);
    }

    public function saveAppProfitPercentage(SaveAppProfitPercentageRequest $request)
    {
        $appProfitPercentageSettingName = json_encode($request->get("app_profit_percentage")['setting_name']);
        $appProfitPercentageValue       = $request->get('app_profit_percentage')['setting_value'];

        Setting::saveSettingByKey('app_profit_percentage', $appProfitPercentageValue, $appProfitPercentageSettingName);
        session()->flash('success', __('site.saved_successfully'));
        return redirect(route('admin.homepage'));

    }

    public function getAddedTaxRate()
    {
        $taxRate                  = Setting::getSettingByKey('tax_rate', 'web');
        $taxRate['setting_name']  = json_decode($taxRate['setting_name'], true);


        $langs = Lang::getAllLangs();
        return view('dashboard.settings.save_tax_rate')->with(
            [
                'tax_rate' => $taxRate,
                'langs'    => $langs
            ]);
    }

    public function saveAddedTaxRate(SaveTaxRateRequest $request)
    {
        $taxRateSettingName = json_encode($request->get("tax_rate")['setting_name']);
        $taxRateValue       = $request->get('tax_rate')['setting_value'];

        Setting::saveSettingByKey('tax_rate', $taxRateValue, $taxRateSettingName);
        session()->flash('success', __('site.saved_successfully'));
        return redirect(route('admin.homepage'));

    }
}
