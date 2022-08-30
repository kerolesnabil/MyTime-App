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
                'lang_symbol',
                'lang_name',
                'lang_direction'
            )
           ->get()->toArray();

       return $langs;
    }

}
