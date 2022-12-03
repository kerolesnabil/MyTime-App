<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

use App\Models\Category;
use App\Models\Lang;
use App\Models\SuggestedServices;
use Illuminate\Http\Request;


class SuggestedServiceController extends Controller
{

    public function showSuggestedService()
    {
        $suggestedServices = SuggestedServices::showSuggestedService();
        return view('dashboard.suggested_services.show_suggested_services')->with(['services' => $suggestedServices]);
    }

    public function rejectSuggestedService(Request $request, $suggestedServiceId)
    {
        // reject
        SuggestedServices::updateSuggestedServiceStatus(0, $suggestedServiceId);

        // redirect
        session()->flash('success', __('site.saved_successfully'));
        return redirect(route('suggested_service.show_suggested_services'));

    }

    public function acceptSuggestedService(Request $request, $suggestedServiceId)
    {
        // accept
        SuggestedServices::updateSuggestedServiceStatus(1, $suggestedServiceId);

        $mainCats = collect(Category::mainCategories())->toArray();
        $subCats  = collect(Category::getAllSubCats())->toArray();
        $allCats  = array_merge($mainCats, $subCats);
        $langs    = Lang::getAllLangs();


        $suggestedServiceObj = SuggestedServices::showSuggestedService($suggestedServiceId);

        return view('dashboard.suggested_services.accept_suggested_service')->with([
            'service' => $suggestedServiceObj,
            'cats'    => $allCats,
            'langs'   => $langs
        ]);

        // redirect

    }
}
