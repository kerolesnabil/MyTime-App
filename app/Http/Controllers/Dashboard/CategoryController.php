<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ImgHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveCategoryRequest;
use App\Models\Category;
use App\Models\Lang;
use App\Models\Service;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::getAllCategoriesWithParent();
        return view('dashboard.categories.index')->with(['categories' => $categories]);
    }

    public function saveCategory(SaveCategoryRequest $request, $catId= null)
    {

        if ($request->parent_id !=0){
            // update parent category (has children col => 1)
            Category::updateHasChildrenColOfCategory($request->parent_id);
        }

        $data['cat_name']        = json_encode($request->cat_name);
        $data['cat_description'] = json_encode($request->cat_description);
        $data['parent_id']       = $request->parent_id;
        $data['cat_is_active']   = $request->cat_is_active;

        if (!is_null($catId)){
            /**************  edit ***************/
            $data['cat_id'] = $catId;
            if ($request->hasFile('cat_img')) {
                $catImg = ImgHelper::uploadImage('images', $request->cat_img);
                Category::updateCategoryData($data, $catImg);
            }
            else {
                Category::updateCategoryData($data);
            }

            session()->flash('success', __('site.updated_successfully'));
        }
        else{
            /**************  create ***************/
            $catImg = ImgHelper::uploadImage('images', $request->cat_img);
            $data['cat_img'] = $catImg;
            Category::createCategory($data);
            session()->flash('success', __('site.created_successfully'));
        }

        return redirect(route('category.index'));
    }

    public function getCategory($catId = null)
    {
        $langs = Lang::getAllLangs();
        $mainCategories = collect(Category::mainCategories())->toArray();

        if (!is_null($catId)){
            //edit
            $category                  = Category::getCategoryByCatId($catId);
            $category->cat_name        = json_decode($category->cat_name, true);
            $category->cat_description = json_decode($category->cat_description, true);
            return view('dashboard.categories.save')->with(['category' => $category, 'langs' => $langs, 'main_cats' => $mainCategories]);
        }
        //create
        return view('dashboard.categories.save')->with(['langs' => $langs, 'main_cats' => $mainCategories]);
    }

    public function updateActivationCategory(Request $request)
    {

        if (isset($request->active_status) && isset($request->cat_id)){

            if ($request->active_status == 'true'){
                Category::updateCatActivationStatus($request->cat_id, 1);
                return response()->json(['cat_id' =>$request->cat_id, 'status' => 'activate']);
            }

            Category::updateCatActivationStatus($request->cat_id, 0);
            return response()->json(['cat_id' =>$request->cat_id, 'status' => 'deactivate']);
        }
        return response()->json(false);

    }

    public function destroy($id)
    {
        Category::findOrFail($id)->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return back();
    }

    public function showSubCategoriesOfCategory($catId)
    {
        $subCats = collect(Category::getSubCategoriesMainCatId($catId));

        if (!empty($subCats)){
            return view('dashboard.categories.show_sub_categories')->with(['sub_cats'=> $subCats]);
        }
        session()->flash('warning', __('site_category.category_has_no_sub_cats'));
        return redirect(route('category.index'));

    }

    public function showServicesOfCategory($catId)
    {

        $services = Service::getServicesByCatId($catId);

        if (!empty($services)){
            return view('dashboard.categories.show_order_services')->with(['services'=> $services]);
        }

        session()->flash('warning', __('site_category.category_has_no_services'));
        return redirect(route('category.index'));

    }

}
