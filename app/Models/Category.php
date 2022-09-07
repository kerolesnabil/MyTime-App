<?php

namespace App\Models;

use App\Helpers\ImgHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class Category extends Model
{
    use HasFactory;
    use AbstractionModelTrait;

    protected $table = "categories";
    protected $primaryKey = "cat_id";
    protected $guarded = ['cat_id', 'parent_id'];
    protected $fillable = ['cat_id', 'parent_id','cat_name', 'cat_description', 'cat_img', 'cat_is_active'];



    public static function mainCategories()
    {
        $parentsCategories = self::query()
            ->select(
                'cat_id',
                self::getValueWithSpecificLang('cat_name', app()->getLocale(),'cat_name')
            )
            ->where('parent_id', '=', 0)
            ->get();

        return $parentsCategories;
    }


    public static function mainCategoryByCatId($catId)
    {
        $mainCategory =
            self::query()
                ->select(

                    'cat_id',
                    self::getValueWithSpecificLang('cat_name', app()->getLocale(),'category_name'),
                    self::getValueWithSpecificLang('cat_description', app()->getLocale(),'category_description'),
                    'cat_img'
                )
                ->where('cat_id', '=', $catId)
                ->get()->toArray();


        foreach ($mainCategory as $key=>$category){

            $mainCategory[$key]["cat_img"] = ImgHelper::returnImageLink($category["cat_img"]);
        }

        if (!count($mainCategory)){

            return $mainCategory;
        }
        return $mainCategory[0];

    }


    public static function getSubCategoriesMainCatId($catId)
    {
        $subCats =
            self::query()
                ->select(
                    'cat_id as sub_cat_id',
                    'cat_img',
                    self::getValueWithSpecificLang('cat_name', app()->getLocale(),'sub_category_name')
                )
                ->where('categories.parent_id', '=', $catId)
                ->get();

        if (!empty($subCats)){
            foreach ($subCats as $subCat){
                $subCat["cat_img"] = ImgHelper::returnImageLink($subCat["cat_img"]);
            }
        }
        return $subCats;
    }

    public static function getAllCategoriesTree()
    {
        return self::query()
            ->select('cat_id','parent_id',
                self::getValueWithSpecificLang('cat_name', app()->getLocale(),'cat_name')
            )->get()->groupBy('parent_id')->toArray();
    }

    public static function mainCategoryByCatIds($catIds)
    {
        $mainCategories=
            DB::table('categories')
                ->select
                ('cat_id',
                    'cat_img',
                    self::getValueWithSpecificLang('categories.cat_name', app()->getLocale(), 'cat_name'),
                    'categories.has_children'
                )
                ->whereIn('cat_id', $catIds)
                ->get()->toArray();

        return $mainCategories;
    }

    public static function getAllCategoriesWithParent()
    {
        return
            DB::table('categories')
                ->select
                ('categories.cat_id',
                    'categories.cat_img',
                    'categories.parent_id',
                    self::getValueWithSpecificLang('parent_categories.cat_name', app()->getLocale(), 'parent_cat_name'),
                    self::getValueWithSpecificLang('categories.cat_name', app()->getLocale(), 'cat_name'),
                    self::getValueWithSpecificLang('categories.cat_description', app()->getLocale(), 'cat_description'),
                    'categories.cat_is_active',
                    'categories.has_children',
                    self::getValueWithSpecificLang('parent_categories.cat_description', app()->getLocale(), 'parent_cat_description')
                )->leftJoin('categories as parent_categories','categories.parent_id', '=', 'parent_categories.cat_id')->get();

    }

    public static function updateCatActivationStatus($catId, $status)
    {
        //$status => 0 || 1
        self::where('cat_id', '=', $catId)
            ->orWhere('parent_id', '=', $catId)
            ->update(array(
                'cat_is_active' => $status,
                'updated_at'     => now()
            ));


    }

    public static function getCategoryByCatId($catId)
    {
        $category =
            self::query()
                ->select(
                    'cat_id',
                    'parent_id',
                    'cat_name',
                    'cat_description',
                    'cat_img',
                    'cat_is_active',
                    'has_children'
                )
                ->where('cat_id', '=', $catId)
                ->first();




        $category["cat_img"] = ImgHelper::returnImageLink($category["cat_img"]);


        return $category;
    }

    public static function updateCategoryData($data, $img =null)
    {
        if (is_null($img)){
            return self::where('cat_id', '=', $data['cat_id'])
                ->update(array(
                    'parent_id'       => $data['parent_id'],
                    'cat_name'        => $data['cat_name'],
                    'cat_description' => $data['cat_description'],
                    'cat_is_active'   => $data['cat_is_active'],
                ));
        }
        else {

            return self::where('cat_id', '=', $data['cat_id'])
                ->update(array(
                    'parent_id'       => $data['parent_id'],
                    'cat_name'        => $data['cat_name'],
                    'cat_description' => $data['cat_description'],
                    'cat_is_active'   => $data['cat_is_active'],
                    'cat_img'         => $img,
                ));
        }

    }

    public static function updateHasChildrenColOfCategory($catId)
    {
        return self::where('cat_id', '=', $catId)
            ->update(array(
                'has_children'       => 1,
            ));
    }

    public static function createCategory($data)
    {
        return self::create([
            'parent_id'       => $data['parent_id'],
            'cat_name'        => $data['cat_name'],
            'cat_description' => $data['cat_description'],
            'cat_img'         => $data['cat_img'],
            'cat_is_active'   => $data['cat_is_active'],
            'has_children'    => 0,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

    }


}
