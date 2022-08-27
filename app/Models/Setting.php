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

    public static function getSettingByKey($key)
    {

        return self::query()
            ->select(
                self::getValueWithSpecificLang('setting_name', app()->getLocale(),'setting_name'),
                'setting_value'
            )
            ->where('setting_key','=', $key)
            ->first();
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

    public static function calculateCost($key,$ad_days)
    {
        $tax=self::query()->select('setting_value')->where('setting_key','=',$key)->first();

        return  $tax->setting_value*$ad_days;
    }
    public static function getCostOfAds()
    {
        return self::query()->select('setting_value','setting_key')
            ->orWhere('setting_key','=','tax_in_discover_page')
            ->orWhere('setting_key','=','tax_in_homepage')
            ->get()->toArray();

    }



}
