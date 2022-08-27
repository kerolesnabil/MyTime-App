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


    public static function subCategoriesOfVendorByCatId1($catId)
    {
        $servicesOfVendor =
            self::query()
                ->select('cat_id as sub_cat_id', 'cat_img',self::getValueWithSpecificLang('cat_name', app()->getLocale(),'sub_category_name'))
                ->where('categories.parent_id', '=', $catId)
                ->get();

        return $servicesOfVendor;

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
    public static function get_all_categories_with_parent()
    {
        $Categories=
            DB::table('categories')
                ->select
                ('categories.cat_id',
                    'categories.cat_img',
                    'categories.parent_id',
                    self::getValueWithSpecificLang('categories.cat_name', app()->getLocale(), 'cat_name'),
                    self::getValueWithSpecificLang('categories.cat_description', app()->getLocale(), 'cat_description'),
                    'categories.cat_is_active',
                    'categories.has_children',
                      self::getValueWithSpecificLang('parent_categories.cat_name', app()->getLocale(), 'parent_cat_name'),
                    self::getValueWithSpecificLang('parent_categories.cat_description', app()->getLocale(), 'parent_cat_description')
                )->leftJoin('categories as parent_categories','categories.parent_id', '=', 'parent_categories.cat_id');

        return $Categories;
    }


}
