<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;
use App\Helpers\ImgHelper;



class Pages extends Model
{
    use HasFactory;
    use AbstractionModelTrait;


    protected $table = "pages";
    protected $primaryKey = "page_id";
    protected $guarded = ['page_id'];
    protected $fillable =
    [
        'page_title',
        'page_content',
        'img',
        'page_position',
        'show_in_user_app',
        'show_in_vendor_app',
        'is_active'
    ];


    public static function getPageByPosition($userType, $position)
    {
        //position => first || last
        //
        $pages = self::query()
            ->select
            (
                'page_id',
                self::getValueWithSpecificLang('page_title', app()->getLocale(),'page_title'),
                self::getValueWithSpecificLang('page_content', app()->getLocale(),'page_content'),
                'img'
            )
            ->where('is_active',1);


        if ($position  === 'first'){
            $pages->where('page_position', 'first');
        }
        else {
            $pages->where('page_position', 'last');
        }

        if ($userType === 'user'){
            $pages->where('show_in_user_app', 1);
        }
        else{
            $pages->where('show_in_vendor_app', 1);
        }

        $pages = $pages->get()->toArray();


        foreach ($pages as $key => $page){

            $pages[$key]["img"] = ImgHelper::returnImageLink($page["img"]);
        }

        return $pages;
    }
}
