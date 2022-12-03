<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveServiceRequest;
use App\Models\Category;
use App\Models\Lang;
use App\Models\Service;
use App\Models\SuggestedServices;
use App\Models\VendorServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{

    // unused
    public function getAllCategoriesServices()
    {
        $rootCategories = Category::getAllCategoriesTree();
        $allServices    = Service::getAllCategoriesServicesTree();
        $newCatsArr = [];

        foreach ($rootCategories["0"] as $parentId=>$parentCat){
            $childCats = isset($rootCategories[$parentCat["cat_id"]]) ? $rootCategories[$parentCat["cat_id"]] : [];
            $parentCat["child_cats"] = [];
            $parentCat["services"]   = isset($allServices[$parentCat["cat_id"]]) ? $allServices[$parentCat["cat_id"]] : [];;
            foreach ($childCats as $childId=>$child){
                $child["services"]         = isset($allServices[$child["cat_id"]]) ? $allServices[$child["cat_id"]] : [];;
                $parentCat["child_cats"][] = $child;
            }
            $newCatsArr[] = $parentCat;
        }

        return ResponsesHelper::returnData($newCatsArr,'200');
    }


    public function getMainCategoriesOfServices()
    {

        $data = Category::mainCategories();
        return ResponsesHelper::returnData($data,'200','');
    }

    public function index()
    {
        $services = Service::gelAllServices();
        return view('dashboard.services.index')->with(['services' => $services]);

    }

    public function saveService(SaveServiceRequest $request, $serviceId= null)
    {
        $data['cat_id']       = $request->cat_id;
        $data['service_name'] = json_encode($request->service_name);

        if (!is_null($serviceId)){
            /**************  edit ***************/
            Service::saveService($data, $serviceId);
            session()->flash('success', __('site.updated_successfully'));
        }
        else{
            /**************  create ***************/
            Service::saveService($data);
            session()->flash('success', __('site.created_successfully'));
        }
        return redirect(route('service.index'));
    }

    public function getService($serviceId = null)
    {

        $mainCats = collect(Category::mainCategories())->toArray();
        $subCats  = collect(Category::getAllSubCats())->toArray();
        $allCats = array_merge($mainCats, $subCats);


        $langs = Lang::getAllLangs();

        if (!is_null($serviceId)){
            //edit
            $service                 = Service::getService($serviceId, 'web');
            $service['service_name'] = json_decode($service['service_name'], true);
            return view('dashboard.services.save')->with(['service' => $service, 'langs' => $langs, 'cats' => $allCats]);
        }
        //create
        return view('dashboard.services.save')->with(['langs' => $langs, 'cats' => $allCats]);
    }

    public function destroy($id)
    {


        if(VendorServices::checkIfServiceUsedByAnyVendor($id)){
            session()->flash('warning', __('site_service.msg_warning_service_used_by_vendor'));
            return back();
        }

        Service::findOrFail($id)->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return back();
    }


    public function showSuggestedService()
    {

        $suggestedServices = SuggestedServices::showSuggestedService();
        return view('dashboard.services.show_suggested_services')->with(['services' => $suggestedServices]);
    }
}
