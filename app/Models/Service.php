<?php

namespace App\Models;


use App\Helpers\ImgHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Service extends Model
{
    use HasFactory;
    use AbstractionModelTrait;

    protected $table = "services";
    protected $primaryKey = "service_id";
    protected $fillable = [
        'cat_id', 'vendor_id', 'package_services_ids',
        'vendor_service_id', 'service_name',
        'service_type',
        'service_location'
    ];




    public static function getAllCategoriesServicesTree()
    {
        return self::query()
            ->select(
                'service_id', 'cat_id',
                self::getValueWithSpecificLang(
                    'service_name',
                    app()->getLocale(), 'service_name')
            )->where('service_type', '=', 'service')
            ->get()->groupBy('cat_id')->toArray();

    }

    public static function getService($service_id, $webOrApi)
    {
        $service = self::query()
            ->select(

                'categories.cat_id as category_id',
                self::getValueWithSpecificLang(
                    'categories.cat_name',
                    app()->getLocale(),
                    'categories_name'
                ),
                'categories.cat_img',
                'parent_cat.cat_id as category_parent_id',
                self::getValueWithSpecificLang(
                    'parent_cat.cat_name',
                    app()->getLocale(),
                    'category_parent_name'
                ),
                self::getValueWithSpecificLang(
                    'service_name',
                    app()->getLocale(),
                    'service_name'
                ),
                'service_id'
            )->where('services.service_id', $service_id)
            ->join('categories', 'categories.cat_id', '=', 'services.cat_id')
            ->leftJoin('categories as parent_cat', 'categories.parent_id', '=', 'parent_cat.cat_id');


            if($webOrApi == 'api'){
                $service = $service->addSelect(self::getValueWithSpecificLang('service_name', app()->getLocale(), 'service_name'));
            }
            else {
                $service = $service->addSelect('service_name');
            }
            $service = $service->first()->toArray();

        if(!empty($service)){
            $service['cat_img'] = ImgHelper::returnImageLink($service['cat_img']);
        }

        return $service;
    }

    public static function deleteService($id)
    {
        self::query()->where('id','=',$id)->delete();
    }

    public static function getServicesOfVendor($servicesIds)
    {
        $services =
            self::query()
            ->select(
                'service_id',
                self::getValueWithSpecificLang(
                    'service_name',
                    app()->getLocale(), 'service_name'),
                'cat_img'
            )
            ->join('categories','categories.cat_id','=','services.cat_id')
            ->where('service_type','service')
            ->whereIn('service_id',$servicesIds)
            ->get();

        if (!empty($services)){
            foreach ($services as $service){
                $service['cat_img'] = ImgHelper::returnImageLink($service['cat_img']);
            }
        }

        return $services;
    }

    public static function savePackage($data,$package_id=null)
    {

        $arr=[
            'vendor_id'=>Auth::user()->user_id,
            'service_name'=>$data['name_package'],
            'service_type'=>'package',
            'package_services_ids'=> implode(',',$data['services_ids'])
        ];

        if(isset($data['package_id']))
        {
            return self::query()->where('service_id',$data['package_id'])->update($arr);

        }else{
            return self::query()->create($arr);
        }
    }

    public static function getPackage($id)
    {
       return self::query()
           ->select(
            'service_id As package_id ',
                    'package_services_ids',
                    'service_name as package_name'
            )
            ->where('service_id',$id)
            ->where('service_type','=','package')
            ->first();

    }

    public static function deletePackage($id)
    {
        self::query()
            ->where('service_id', $id)
            ->where('service_type','=','package')
            ->delete();
    }

    public static function getAllPackageByVendor($vendor_id)
    {
        return self::query()->select(
            'services.service_id as package_id',
                    'services.service_name as package_name',
                    'services.package_services_ids',
                    'vendor_services.service_discount_price_at_salon',
                    'vendor_services.service_discount_price_at_home'
        )->join('vendor_services','vendor_services.service_id','=','services.service_id')
        ->where('services.vendor_id',$vendor_id)
        ->where('services.service_type','package')->get();
    }


    public static function getServicesByCatId($catId)
    {
        return self::query()
            ->select(
                'service_id',
                self::getValueWithSpecificLang('service_name', app()->getLocale(), 'service_name')

            )->where('cat_id', $catId)
            ->get()
            ->toArray();
    }


    public static function gelAllServices()
    {
        return self::query()->select
        (
            'services.service_id',
            self::getValueWithSpecificLang('services.service_name', app()->getLocale(), 'service_name'),
            self::getValueWithSpecificLang('categories.cat_name', app()->getLocale(), 'sub_cat_name'),
            self::getValueWithSpecificLang('parent_cats.cat_name', app()->getLocale(), 'main_cat_name')
        )
        ->join('categories','categories.cat_id','=','services.cat_id')
        ->leftJoin('categories as parent_cats','parent_cats.cat_id','=','categories.parent_id')
        ->where('services.service_type','=','service')
        ->get();
    }


    public static function saveService($data, $serviceId = null)
    {

        $data['service_type'] = 'service';
        if (!is_null($serviceId)) {
            return self::query()->where('service_id', '=', $serviceId)->update($data);
        }
        else{
            $data['created_at'] = now();
            $data['updated_at'] = now();
            return self::query()->create($data);
        }

    }


}
