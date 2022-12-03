<?php

namespace App\Models;

use App\Helpers\ImgHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\SoftDeletes;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class Ad extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "ads";
    protected $primaryKey = "ad_id";
    protected $fillable = [
        'ad_id',
        'vendor_id',
        'ad_title',
        'ad_start_at',
        'ad_days',
        'ad_end_at',
        'ad_cost',
        'ad_img',
        'ad_at_homepage',
        'ad_at_discover_page',
        'status'
    ];


    public static function availableAdsOnHomepage($limit = null)
    {
       return self::availableAds('ad_at_homepage',$limit);
    }

    public static function availableAdsOnDiscoverPage($limit = null)
    {

        return self::availableAds('ad_at_discover_page', $limit);
    }

    private static function availableAds($page_name, $limit =null)
    {
        // page_name => ad_at_home || ad_at_discover_page
        $current_day = Carbon::today();


        $available_ads = self::query()
            ->select('vendor_id', 'ad_title', 'ad_img')
            ->whereDate('ad_start_at','<=', $current_day)
            ->whereDate('ad_end_at','>=', $current_day)
            ->where($page_name,'=', 1);

        if(!is_null($limit)){
            $available_ads = $available_ads->limit($limit);
        }

        $available_ads = $available_ads->get()->toArray();

        foreach ($available_ads as $key=>$ad){
            $available_ads[$key]["ad_img"] = ImgHelper::returnImageLink($ad["ad_img"]);
        }

        return $available_ads;
    }

    public static function saveAd($data)
    {
        if(isset($data['ad_id']))
        {
            self::where('ad_id',$data['ad_id'])->update($data);
            return true;
        }

        return self::query()->create($data);
    }

    public static function getAd($adId)
    {
       $data=self::query()
            ->select(
                'ad_id',
                'vendor_id',
                'ad_title', 'ad_days',
                'ad_start_at',
                'ad_end_at',
                'ad_cost',
                'ad_img',
                'ad_at_homepage',
                'ad_at_discover_page',
                DB::raw('DATE_FORMAT(ads.created_at, "%Y-%m-%d %H:%i") as ad_created_at'),
                'users.user_name as vendor_name'
            )
           ->join('users','users.user_id','=','ads.vendor_id')
           ->where('ad_id','=',$adId)->first();

       if (!is_null($data)){
           $data->ad_img=ImgHelper::returnImageLink($data->ad_img);
       }

       return $data;
    }

    public static function deleteAd($id)
    {
        self::query()
            ->where('ad_id','=',$id)
            ->where('vendor_id','=',Auth::user()->user_id)
            ->delete();
        return true;
    }

    public static function getAllAvailableAds($paginate)
    {
        $current_day = Carbon::today();

        return self::query()->select(
            'ad_id',
            'ad_title',
            'ad_days',
            'ad_start_at',
            'ad_end_at',
            'ad_cost',
            'ad_at_homepage',
            'ad_at_discover_page',
            'users.user_name as vendor_name',
            'ads.created_at'
        )
        ->join('users','users.user_id','=','ads.vendor_id')
        ->whereDate('ad_start_at','<=', $current_day)
        ->whereDate('ad_end_at','>=', $current_day)
        ->paginate($paginate);

    }

    public static function getAllAds($paginate)
    {
        return self::query()->select(
            'ad_id',
                'ad_title',
                'ad_days',
                'ad_start_at',
                'ad_end_at',
                'ad_cost',
                'ad_at_homepage',
                'ad_at_discover_page',
                'users.user_name as vendor_name',
                'ads.created_at',
                'ads.status'
            )
            ->join('users','users.user_id','=','ads.vendor_id')
            ->paginate($paginate);
    }

    public static function updateAdStatus($status, $adId)
    {
        self::query()->where('ad_id','=', $adId)->
        update(['status' => $status]);

    }
}
