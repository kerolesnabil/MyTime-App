<?php

namespace App\Models;

use App\Helpers\ImgHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class VendorView extends Model
{
    use HasFactory;

    protected $table = "vendor_views";
    protected $primaryKey = "view_id";
    protected $fillable = ['vendor_id', 'view_count'];




    public static function incrementMonthlyVendorViews($vendorId)
    {


        $monthlyVendorViewCounter = self::checkIfVendorHaveMonthlyViewsCounter($vendorId);
        if($monthlyVendorViewCounter['status']){

            self::updateMonthlyVendorViewsCounter($vendorId, $monthlyVendorViewCounter['data']->view_id);
        }
        else {
            $newMonthlyVendorViewCounter = self::createVendorViewCounter($vendorId);
            self::updateMonthlyVendorViewsCounter($vendorId, $newMonthlyVendorViewCounter->view_id);
        }

    }

    private static function checkIfVendorHaveMonthlyViewsCounter($vendorId)
    {
        $monthlyViewsCounter =
            self::query()
                ->select('view_id')
                ->where('vendor_id', '=', $vendorId)
                ->whereMonth('created_at', date('m'))
                ->whereYear('created_at', date('Y'))
                ->first();

        if (is_object($monthlyViewsCounter)){
            return ['data' => $monthlyViewsCounter, 'status' =>true];
        }

        return ['data' => [], 'status' => false];;

    }

    private static function createVendorViewCounter($vendorId)
    {
        return self::create([
            'vendor_id'  => $vendorId,
            'view_count' => 0
        ]);
    }

    private static function updateMonthlyVendorViewsCounter($vendorId, $viewId){

        $oldMonthlyViewCount =
            self::query()
                ->select('view_count')
                ->where('vendor_id', '=', $vendorId)
                ->where('view_id', '=', $viewId)
                ->first();


        $newMonthlyViewCount = $oldMonthlyViewCount->view_count + 1;

        self::
            where('vendor_id', '=', $vendorId)
            ->where('view_id', '=', $viewId)
            ->update(array(
                'view_count' => $newMonthlyViewCount,
            ));
    }


}
