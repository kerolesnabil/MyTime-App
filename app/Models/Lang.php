<?php

namespace App\Models;

use App\Helpers\ImgHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class Lang extends Model
{
    use HasFactory;

    protected $table = "langs";
    protected $primaryKey = "lang_id";
    protected $fillable = [
        'lang_symbol',
        'lang_name',
        'lang_direction',
        'lang_img'
    ];


    public static function getAllLangs()
    {
       $langs=self::query()
            ->select
            (
                'lang_id',
                'lang_symbol',
                'lang_name',
                'lang_direction',
                'lang_is_active'
            )
           ->where('lang_is_active','=', '1')
           ->get()->toArray();

       return $langs;
    }

    public static function updateLangActivationStatus($langId, $status)
    {
        //$status => 0 || 1
        self::where('lang_id', '=', $langId)
            ->update(array(
                'lang_is_active'     => $status,
                'updated_at'    => now()
            ));

    }

    public static function getLangById($langId)
    {
        $lang =
            self::query()
                ->select(
                    'lang_id',
                    'lang_symbol',
                    'lang_name',
                    'lang_direction',
                    'lang_img',
                    'lang_is_active'
                )
                ->where('lang_id', '=', $langId)
                ->first();

        $lang["lang_img"] = ImgHelper::returnImageLink($lang["lang_img"]);
        return $lang;

    }

    public static function updateLangData($data, $img =null)
    {
        if (is_null($img)){
            return self::where('lang_id', '=', $data['lang_id'])
                ->update(array(
                    'lang_symbol'    => $data['lang_symbol'],
                    'lang_name'      => $data['lang_name'],
                    'lang_direction' => $data['lang_direction'],
                    'lang_is_active' => $data['lang_is_active']

                ));
        }
        else {
            return self::where('lang_id', '=', $data['lang_id'])
                ->update(array(
                    'lang_symbol'    => $data['lang_symbol'],
                    'lang_name'      => $data['lang_name'],
                    'lang_direction' => $data['lang_direction'],
                    'lang_is_active' => $data['lang_is_active'],
                    'lang_img'       => $img,
                ));
        }

    }

    public static function createLang($data)
    {

        return self::create([
            'lang_symbol'    => $data['lang_symbol'],
            'lang_name'      => $data['lang_name'],
            'lang_direction' => $data['lang_direction'],
            'lang_is_active' => $data['lang_is_active'],
            'lang_img'       => $data['lang_img'],
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

    }

}
