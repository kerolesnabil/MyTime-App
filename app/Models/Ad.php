<?php

namespace App\Models;

use App\Helpers\ImgHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class Ad extends Model
{
    use HasFactory;

    protected $table = "ads";
    protected $primaryKey = "ad_id";
    protected $fillable = [
        'vendor_id','ad_title','ad_start_at','ad_days',
        'ad_end_at','ad_cost','ad_img',
        'ad_at_homepage','ad_at_discover_page'
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

    public static function getAd($id)
    {
       $data=self::query()
            ->select('ad_id','vendor_id','ad_title','ad_days','ad_start_at','ad_end_at','ad_cost','ad_img','ad_at_homepage','ad_at_discover_page')
            ->where('ad_id','=',$id)->first();


        $data->ad_img=ImgHelper::returnImageLink($data->ad_img);

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

    public static function countAvailableAds()
    {
        $current_day = Carbon::today();

        return count(self::whereDate('ad_start_at','<=', $current_day)
            ->whereDate('ad_end_at','>=', $current_day)
            ->get());
    }

}
