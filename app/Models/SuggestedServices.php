<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;


class SuggestedServices extends Model
{
    use HasFactory;

    protected $table = "services_suggested_by_vendor";
    protected $primaryKey = "service_suggested_id";
    protected $fillable = [
        'vendor_id',
        'main_cat_suggested',
        'sub_cat_suggested',
        'service_suggested_name',
        'service_suggested_status'
    ];


    public static function createSuggestedService($data, $vendorId)
    {
        return self::create([
            'vendor_id'              => $vendorId,
            'main_cat_suggested'     => $data['main_cat_name'],
            'sub_cat_suggested'      => $data['sub_cat_name'],
            'service_suggested_name' => $data['service_name'],
            'created_at'             => now(),
            'updated_at'             => now(),
        ]);

    }

    public static function showSuggestedService($suggestedServiceId = null)
    {
        $data = self::query()
            ->select(
                'service_suggested_id',
                'main_cat_suggested',
                'sub_cat_suggested',
                'service_suggested_name',
                DB::raw('DATE_FORMAT(services_suggested_by_vendor.created_at, "%Y-%m-%d") as created_at'),
                'user_name as vendor_name',
                'service_suggested_status'
            )
            ->join('users','users.user_id','services_suggested_by_vendor.vendor_id')
            ->orderBy('services_suggested_by_vendor.created_at', 'desc');

            if (is_null($suggestedServiceId)){
                $data = $data->get();
            }
            else{
                $data = $data->where('service_suggested_id','=', $suggestedServiceId)->first();
            }

            return $data;

    }


    public static function updateSuggestedServiceStatus($status, $suggestedServiceId)
    {
        self::query()->where('service_suggested_id','=', $suggestedServiceId)->
        update(['service_suggested_status' => $status]);

    }




}
