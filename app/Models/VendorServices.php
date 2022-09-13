<?php

namespace App\Models;

use App\Helpers\ImgHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class VendorServices extends Model
{
    use HasFactory;
    use AbstractionModelTrait;

    protected $table = "vendor_services";
    protected $primaryKey = "vendor_service_id";
    protected $fillable = [
        'service_id','vendor_services',
        'service_title','vendor_id',
        'service_price_at_salon', 'service_discount_price_at_salon',
        'service_price_at_home', 'service_discount_price_at_home'
    ];

    protected $dates = ['created_at', 'updated_at'];



    public static function mainCategoriesOfVendor($vendorId)
    {

        $mainCatIds = VendorDetail::getVendorCategoriesIds($vendorId);

        $mainCatIds = explode(',',$mainCatIds->vendor_categories_ids);
        $catIds = [];
        foreach ($mainCatIds as $catId){
            if (strval($catId)){
                $catIds[] = $catId;
            }
        }

        $mainCatObjs = collect(Category::mainCategoryByCatIds($catIds))->toArray();

        if (count($mainCatObjs)){
            foreach ($mainCatObjs as $key=>$cat){
                $mainCatObjs[$key]->cat_img = ImgHelper::returnImageLink($cat->cat_img);
            }
        }

        return $mainCatObjs;
    }

    public static function packagesOfVendor($vendorId, $serviceType){

        $packagesOfVendor =
            self::query()
                ->select(
                    'vendor_services.vendor_service_id as package_id',
                    self::getValueWithSpecificLang('services.service_name', app()->getLocale(), 'package_name'),
                    'services.package_services_ids',
                    "vendor_services.service_price_at_$serviceType as package_price",
                    "vendor_services.service_discount_price_at_$serviceType as package_discount_price"
                )
                ->join('services', 'services.service_id', '=', 'vendor_services.service_id')
                ->where('vendor_services.vendor_id','=',$vendorId)
                ->where('services.service_type','=','package')
                ->get()->toArray();

        $serviceIds = [];
        foreach ($packagesOfVendor as $package){
            $serviceIds = array_merge($serviceIds,explode(',' , $package['package_services_ids']));
        }

        $serviceIds  = array_unique($serviceIds);
        $serviceObjs = self::vendorServicesNamesByIds($serviceIds);
        $serviceObjs = collect($serviceObjs);



        foreach ($packagesOfVendor as $key=>$package){
            $packagesOfVendor[$key]['package_services'] = collect($serviceObjs->whereIn("service_id",explode(',' , $package['package_services_ids'])))->toArray();
        }

        return $packagesOfVendor;
    }

    public static function servicesOfVendorByCatId($catId, $vendorId, $serviceType){

        $servicesOfCategory =
            self::query()
                ->select
                (
                    'vendor_services.vendor_service_id as service_id',

                    "vendor_services.service_price_at_$serviceType as service_price",
                    "vendor_services.service_discount_price_at_$serviceType as service_discount_price",
                    self::getValueWithSpecificLang('services.service_name', app()->getLocale(), 'service_name')
                )
                ->join('services', 'services.service_id', '=', 'vendor_services.service_id')
                ->join('categories', 'categories.cat_id', '=', 'services.cat_id')
                ->where('vendor_services.vendor_id','=',$vendorId)
                ->where('services.cat_id', '=', $catId)
                ->where('services.service_type','=','service')
                ->distinct()
                ->get();

        return $servicesOfCategory;
    }

    public static function subCategoriesOfVendorByCatId($catId, $vendorId){

        $subCategories =
            self::query()
                ->select
                (
                    'categories.cat_id as sub_category_id',
                    self::getValueWithSpecificLang('categories.cat_name', app()->getLocale(), 'sub_category'),
                    'categories.cat_img as sub_category_img'
                )
                ->join('services', 'services.service_id', '=', 'vendor_services.service_id')
                ->join('categories', 'categories.cat_id', '=', 'services.cat_id')
                ->where('vendor_services.vendor_id','=',$vendorId)
                ->where('categories.parent_id', '=', $catId)

                ->where('services.service_type','=','service')
                ->distinct()
                ->get();


        foreach ($subCategories as $key=>$subCategory){

            $subCategories[$key]["sub_category_img"] = ImgHelper::returnImageLink($subCategory["sub_category_img"]);
        }

        return $subCategories;
    }

    public static function servicesPricingDataByIds($serviceIds, $vendor_id)
    {

        return self::query()
            ->select
            (
                'vendor_services.vendor_service_id',
                'vendor_services.service_price_at_salon',
                'vendor_services.service_discount_price_at_salon',
                'vendor_services.service_price_at_home',
                'vendor_services.service_discount_price_at_home',
                'services.service_type'
            )
            ->join('services','vendor_services.service_id','=', 'services.service_id')
            ->whereIn('vendor_services.vendor_service_id', $serviceIds)
            ->where('vendor_services.vendor_id', '=', $vendor_id)
            ->get()->toArray();
    }

    public static function checkIfVendorHasService($vendorId, $vendorServiceId)
    {
        $vendorService =
            self::query()
                ->select('vendor_service_id')
                ->where('vendor_service_id','=',$vendorServiceId)
                ->where('vendor_id','=',$vendorId)
                ->get()->toArray();

        if(count($vendorService) ==0){
            return false;
        }
        return true;
    }

    public static function saveVendorService($data,$vendor_id)
    {

        if(isset($data['service_price_at_salon'])){
            $service_discount_price_at_salon=$data['service_price_at_salon'];
        }
        if(isset($data['service_discount_price_at_salon']))
        {
            $service_discount_price_at_salon=$data['service_discount_price_at_salon'];
        }

        $arr=[
            'vendor_id'=>$vendor_id,
            'service_id'=>$data['service_id'],
            'service_title'=>(isset($data['service_title']))?$data['service_title']:Null,
            'service_price_at_salon'=>(isset($data['service_price_at_salon']))?$data['service_price_at_salon']:Null,
            'service_discount_price_at_salon'=>(isset($service_discount_price_at_salon))?$service_discount_price_at_salon:Null,
            'service_price_at_home'=>$data['service_price_at_home'],
            'service_discount_price_at_home'=>isset($data['service_discount_price_at_home'])?$data['service_discount_price_at_home']:$data['service_price_at_home']
        ];

        if(isset($data['vendor_service_id']))
        {
           self::where('vendor_service_id',$data['vendor_service_id'])->update($arr);
        }else{
           return self::query()->create($arr);
        }

    }

    public static function deleteService($id)
    {
        self::query()->where('vendor_service_id',$id)->delete();
    }

    public static function deleteServiceOfPackage($package_id)
    {
        self::query()->where('service_id',$package_id)->delete();
    }

    public static function getServiceById($service_id)
    {
      return self::query()->select(
            'service_id','vendor_id'
        )->where('vendor_service_id',$service_id)->first();
    }

    public static function getAllServicesOfVendor($vendor_id)
    {
        $data=self::query()
            ->select('service_id')
            ->where('vendor_id',$vendor_id)->get()->toArray();

        return collect($data)->flatten('1');

    }

    public static function createVendorPackage($data,$id)
    {
        if(isset($data['service_price_at_salon'])){
            $service_discount_price_at_salon=$data['service_price_at_salon'];
        }
        if(isset($data['service_discount_price_at_salon']))
        {
            $service_discount_price_at_salon=$data['service_discount_price_at_salon'];
        }



        self::create([
            'vendor_id'=>Auth::user()->user_id,
            'service_id'=>$id,
            'service_title'=>(isset($data['service_title']))?$data['service_title']:Null,
            'service_price_at_salon'=>(isset($data['service_price_at_salon']))?$data['service_price_at_salon']:Null,
            'service_discount_price_at_salon'=>(isset($service_discount_price_at_salon))?$service_discount_price_at_salon:Null,
            'service_price_at_home'=>$data['service_price_at_home'],
            'service_discount_price_at_home'=>isset($data['service_discount_price_at_home'])?$data['service_discount_price_at_home']:$data['service_price_at_home']
        ]);
    }

    public static function checkIfServiceProvidedInSpecificLocation($vendorId, $vendorServiceId, $serviceType)
    {
        $servicePriceColumnName = "service_price_at_$serviceType";
        $vendorService =
            self::query()
                ->select("$servicePriceColumnName")
                ->where('vendor_service_id','=', $vendorServiceId)
                ->where('vendor_id','=', $vendorId)
                ->first();

        if (is_null($vendorService->$servicePriceColumnName)){
            return false;
        }
        return true;
    }

    public static function getMostServiceOrdered($limit, $serviceType)
    {

        $mostServiceOrdered =
            DB::table('orders_items')
                ->select(
                    'vendor_services.vendor_id',
                    'users.user_name as vendor_name',
                    self::getValueWithSpecificLang('services.service_name', app()->getLocale(), 'service_name'),
                    DB::raw('COUNT(item_id) AS `item_id_repeated_num`')
                )
                ->join('vendor_services','vendor_services.vendor_service_id','=','orders_items.item_id')
                ->join('services','services.service_id','=','vendor_services.service_id')
                ->join('users','users.user_id','=','vendor_services.vendor_id')
                ->where('services.service_type', '=', $serviceType)
                ->groupBy('item_id')
                ->orderBy('item_id_repeated_num','desc')
                ->limit($limit);

                if($serviceType == 'service'){
                    $mostServiceOrdered =
                        $mostServiceOrdered
                            ->join('categories','categories.cat_id','=','services.cat_id')
                            ->addSelect('services.cat_id')
                            ->addSelect('categories.cat_img');
                }
                else{
                    $mostServiceOrdered =
                        $mostServiceOrdered
                            ->addSelect('users.user_img as vendor_logo');
                }

                $mostServiceOrdered = $mostServiceOrdered->get()->toArray();


        if(!empty($mostServiceOrdered))
        {

            foreach ($mostServiceOrdered as $key => $service) {
                if (isset($service->cat_img)){

                    $mostServiceOrdered[$key]->cat_img = ImgHelper::returnImageLink($service->cat_img);
                }
                if (isset($service->vendor_logo)){

                    $mostServiceOrdered[$key]->vendor_logo = ImgHelper::returnImageLink($service->vendor_logo);
                }
                unset($mostServiceOrdered[$key]->item_id_repeated_num);
            }

        }
        return $mostServiceOrdered;
    }


    public static function vendorServicesNamesByIds($serviceIds)
    {

        return self::query()
            ->select(
                'vendor_services.vendor_service_id as service_id',
                self::getValueWithSpecificLang('services.service_name', app()->getLocale(), 'service_name')
            )
            ->join('services','services.service_id','=','vendor_services.service_id')
            ->whereIn('vendor_services.vendor_service_id', $serviceIds)
            ->get()->toArray();
    }

}
