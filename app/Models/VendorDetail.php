<?php

namespace App\Models;

use App\Helpers\ArraysProcessHelper;
use App\Helpers\ImgHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class VendorDetail extends Model
{
    use HasFactory;

    protected $table = 'vendor_details';

    public $timestamps = false;

    protected $primaryKey = 'vendor_details_id';

    protected $fillable = [
        'vendor_slider', 'vendor_description', 'vendor_tax_num',
        'vendor_commercial_registration_num', 'vendor_end_time', 'vendor_start_time',
        'vendor_available_days', 'vendor_service_location', 'vendor_slider', 'user_id'
    ];


    public static function createUserDetails($user_id, $data)
    {

        return self::create([
            'user_id'                            => $user_id,
            'vendor_type'                        => $data->vendor_type,
            'vendor_available_days'              => $data->vendor_available_days,
            'vendor_start_time'                  => $data->vendor_start_time,
            'vendor_end_time'                    => $data->vendor_end_time,
            'vendor_commercial_registration_num' => $data->vendor_commercial_registration_num,
            'vendor_tax_num'                     => $data->vendor_tax_num,
        ]);

    }

    public static function vendorDetails($vendorId)
    {

        $vendorDetails =
            self::query()
                ->select(
                    'users.user_name as vendor_name',
                    'users.user_address as vendor_address',
                    'vendor_details.vendor_start_time',
                    'vendor_details.vendor_end_time',
                    'vendor_details.vendor_slider',
                    'vendor_details.vendor_description',
                    'users.user_img as vendor_logo',
                    'vendor_details.vendor_reviews_count',
                    DB::raw('vendor_details.vendor_reviews_sum / vendor_details.vendor_reviews_count as vendor_rate'),
                    'vendor_details.vendor_type',
                    'vendor_details.vendor_views_count',
                    'users.user_phone as vendor_phone'
                )
                ->join('users', 'vendor_details.user_id', '=', 'users.user_id')
                ->where('vendor_details.user_id', '=', $vendorId)
                ->groupBy('vendor_details.user_id')
                ->get()->first();


        if ($vendorDetails !=null){
            $vendorDetails  = collect($vendorDetails)->toArray();
            $vendorDetails['vendor_rate'] = number_format($vendorDetails['vendor_rate'],2);

            $vendorDetails["vendor_logo"] = ImgHelper::returnImageLink($vendorDetails["vendor_logo"]);

            if (!is_null($vendorDetails["vendor_slider"])){
                $vendorDetails["vendor_slider"] = ImgHelper::returnSliderLinks($vendorDetails["vendor_slider"]);
            }
            return (object)$vendorDetails;
        }


        return $vendorDetails;

    }

    public static function vendorsByType($vendorType, $userLat, $userLong, $filterCatId = 0)
    {
        $distanceKm = 50000;

        $vendors =
            self::query()
                ->select(
                    'vendor_details.user_id as vendor_id',
                    'users.user_name as vendor_name',
                    'users.user_address as vendor_address',
                    'users.user_img as vendor_logo',
                    DB::raw('vendor_details.vendor_reviews_sum / vendor_details.vendor_reviews_count as vendor_rate'),
                    DB::raw("ST_Distance_Sphere(point($userLong, $userLat), point(users.user_long, users.user_lat)) * 0.001 as `distance_in_kilometer` ")
                )
                ->join('users', 'vendor_details.user_id', '=', 'users.user_id')
                ->where('users.user_type', '=', 'vendor')
                ->where('vendor_details.vendor_type', '=', $vendorType);



        if ($filterCatId != 0) {
            $vendors = $vendors->whereRaw('vendor_details.vendor_categories_ids like "%,'.$filterCatId.',%"');
        }

        $vendors = $vendors
                ->having('distance_in_kilometer', '<=', $distanceKm)
                ->orderBy('distance_in_kilometer', 'asc')
                ->get()->toArray();

        foreach ($vendors as $key => $vendor) {
            $vendors[$key]["vendor_rate"]           = number_format($vendors[$key]["vendor_rate"], 2);
            $vendors[$key]["vendor_logo"]           = ImgHelper::returnImageLink($vendor["vendor_logo"]);
            $vendors[$key]["distance_in_kilometer"] = number_format(round($vendors[$key]["distance_in_kilometer"],2));
        }

        return $vendors;

    }

    public static function getVendorById($vendor_id)
    {
        return self::query()
            ->select(
                'vendor_reviews_count',
                'vendor_views_count',
                'vendor_type',
                DB::raw('vendor_reviews_sum / vendor_reviews_count as vendor_rate')
            )->where('vendor_details_id', '=', $vendor_id)->first();
    }

    public static function incrementVendorViewsCount($vendorId)
    {
        $oldViewCount =
            self::query()
                ->select('vendor_views_count'
                )->where('user_id', '=', $vendorId)->first();


        $newViewCount = $oldViewCount->vendor_views_count + 1;

        self::where('user_id', '=', $vendorId)
            ->update(array(
                'vendor_views_count' => $newViewCount,
            ));
    }

    public static function recentVendor($limit)
    {
        $recentVendors =
            self::query()
                ->select(
                    'users.user_id as vendor_id',
                    'users.user_name as vendor_name',
                    'users.user_address as vendor_address',
                    'users.user_img as vendor_logo'
                )
                ->join('users', 'vendor_details.user_id', '=', 'users.user_id')
                ->orderBy('vendor_details_id', 'desc')
                ->limit($limit)
                ->get()
                ->toArray();

        foreach ($recentVendors as $key => $vendor) {
            $recentVendors[$key]["vendor_logo"]  = ImgHelper::returnImageLink($vendor["vendor_logo"]);
        }

        return $recentVendors;
    }

    public static function getVendorCategoriesIds($vendorId)
    {
        return self::query()->select('vendor_categories_ids')->where('user_id',$vendorId)->first();

    }

    public static function getVendorDetailProfile($vendorId)
    {
        $vendorDetails =
            self::query()
                ->select(
                    'vendor_available_days',
                    'vendor_start_time',
                    'vendor_end_time',
                    'vendor_slider',
                    'vendor_description',
                    'vendor_commercial_registration_num',
                    'vendor_tax_num'
                )
                ->where('vendor_details.user_id', '=', $vendorId)
                ->groupBy('vendor_details.user_id')
                ->get()->first()->toArray();

        if (!is_null($vendorDetails["vendor_slider"])){

            $vendorDetails["vendor_slider"] = ImgHelper::returnSliderLinks($vendorDetails["vendor_slider"]);
        }


        return (object)$vendorDetails;

    }

    public static function updateVendorDetails($data)
    {

        self::where('user_id', '=', $data->user_id)
            ->update(array(
                'vendor_available_days' => $data->vendor_available_days,
                'vendor_start_time'     => $data->vendor_start_time,
                'vendor_end_time'       => $data->vendor_end_time,
                'vendor_description'    => $data->vendor_description,
                'vendor_slider'         => $data->vendor_slider,
            ));
    }

    public static function countVendorsByType($vendorType)
    {
        // $vendorType => salon or specialist
        return count(self::where('vendor_type', '=', $vendorType)->get());

    }

    public static function getVendorSlider($vendorId)
    {
        $slider =
            self::query()
                ->select('vendor_slider')

                ->where('user_id', '=', $vendorId)
                ->get()->first()->toArray();

        if (!is_null($slider['vendor_slider'])){
            $slider["vendor_slider"] = ImgHelper::returnSliderLinks($slider["vendor_slider"]);
        }

        return $slider;

    }

}
