<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;
use function Symfony\Component\Translation\t;


class Setting extends Model
{
    use HasFactory;
    use AbstractionModelTrait;

    protected $table = "settings";
    protected $primaryKey = "setting_id";
    protected $fillable =
    [
        'setting_name',
        'setting_key',
        'setting_value'
    ];

    public static function getSettingByKey($key, $apiOrWeb)
    {
        // $apiOrWeb => api || web

        $setting = self::query()
            ->select(
                'setting_id',
                'setting_value'
            )
            ->where('setting_key','=', $key);

            if($apiOrWeb == 'api'){
                $setting = $setting->addSelect(self::getValueWithSpecificLang('setting_name', app()->getLocale(),'setting_name'));
            }
            else{
                $setting = $setting->addSelect('setting_name');
            }

            return $setting->first();
    }

    public static function getTax()
    {
        // This is a temporary function to get the tax value
        $tax = "10";
        $taxValue = intval($tax) /100;

        $data['tax_rate'] = $tax;
        $data['tax_value'] = $taxValue;
        return $data;
    }

    public static function calculateCost($key, $ad_days)
    {
        $tax = self::query()->select('setting_value')->where('setting_key','=', $key)->first();

        return  $tax->setting_value * $ad_days;
    }

    public static function getCostOfAds()
    {
        return self::query()->select('setting_value','setting_key')
            ->orWhere('setting_key','=','price_ad_in_homepage')
            ->orWhere('setting_key','=','price_ad_in_discover_page')
            ->get()->toArray();
    }

    public static function saveSettingByKey($key, $settingValue, $settingName = null)
    {
        if (is_null($settingName) && !is_null($settingValue)) {

            self::where('setting_key', '=', $key)
                ->update(array(
                    'setting_value' => $settingValue,
                    'updated_at'    => now()
                ));
        }
        elseif(!is_null($settingName) && !is_null($settingValue)) {

            self::where('setting_key', '=', $key)
                ->update(array(
                    'setting_value' => $settingValue,
                    'setting_name'  => $settingName,
                    'updated_at'    => now()
                ));

        }
        elseif (!is_null($settingName) && is_null($settingValue)){
            self::where('setting_key', '=', $key)
                ->update(array(
                    'setting_name' => $settingName,
                    'updated_at'    => now()
                ));
        }
    }



}
