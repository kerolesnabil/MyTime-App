<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\DB;
use App\Helpers\ImgHelper;



class Page extends Model
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
        'images',
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
                'images'
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

            $pages[$key]["images"] = ImgHelper::returnImageLink($page["images"]);
        }

        return $pages;
    }

    public static function getAllPages()
    {
        return self::query()
            ->select
            (
                'page_id',
                self::getValueWithSpecificLang('page_title', app()->getLocale(),'page_title'),
                'images',
                'page_position',
                'show_in_user_app',
                'show_in_vendor_app',
                'is_active'
            )->get();
    }

    public static function getPageById($pageId)
    {
        $page =
            self::query()
                ->select(
                    'page_id',
                    'page_title',
                    'page_content',
                    'images',
                    'page_position',
                    'show_in_user_app',
                    'show_in_vendor_app',
                    'is_active'
                )
                ->where('page_id', '=', $pageId)
                ->first();

        $page["images"] = ImgHelper::returnImageLink($page["images"]);
        return $page;
    }

    public static function updatePageData($data, $img =null)
    {
        if (is_null($img)){
            return self::where('page_id', '=', $data['page_id'])
                ->update(array(
                    'page_title'         => $data['page_title'],
                    'page_content'       => $data['page_content'],
                    'show_in_user_app'   => $data['show_in_user_app'],
                    'show_in_vendor_app' => $data['show_in_vendor_app'],
                    'page_position'      => $data['page_position'],
                    'is_active'          => $data['is_active'],
                ));
        }
        else {
            return self::where('page_id', '=', $data['page_id'])
                ->update(array(
                    'page_title'         => $data['page_title'],
                    'page_content'       => $data['page_content'],
                    'show_in_user_app'   => $data['show_in_user_app'],
                    'show_in_vendor_app' => $data['show_in_vendor_app'],
                    'page_position'      => $data['page_position'],
                    'is_active'          => $data['is_active'],
                    'images'                => $img,
                ));
        }

    }


    public static function createPage($data)
    {
        return self::create([
            'page_title'         => $data['page_title'],
            'page_content'       => $data['page_content'],
            'show_in_user_app'   => $data['show_in_user_app'],
            'show_in_vendor_app' => $data['show_in_vendor_app'],
            'page_position'      => $data['page_position'],
            'is_active'          => $data['is_active'],
            'images'                => $data['images'],
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

    }


    public static function updatePageActivationStatus($pageId, $status)
    {
        //$status => 0 || 1
        self::where('page_id', '=', $pageId)
            ->update(array(
                'is_active'     => $status,
                'updated_at'    => now()
            ));


    }
}
