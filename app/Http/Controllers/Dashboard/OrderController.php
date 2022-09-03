<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ImgHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SavePageRequest;
use App\Models\Order;
use App\Models\Page;
use App\Models\Lang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{

    public function index()
    {
        $orders = Order::getAllOrders();
        return view('dashboard.orders.index')->with(['orders' => $orders]);
    }


    public function savePage(SavePageRequest $request, $pageId= null)
    {


        $data['page_title']         = json_encode($request->page_title);
        $data['page_content']       = json_encode($request->page_content);
        $data['show_in_user_app']   = $request->show_in_user_app;
        $data['show_in_vendor_app'] = $request->show_in_vendor_app;
        $data['page_position']      = $request->page_position;
        $data['is_active']          = $request->is_active;


        if (!is_null($pageId)){
            /**************  edit ***************/
            $data['page_id'] = $pageId;
            if (!is_null($request->img)) {
                $pageImg = ImgHelper::uploadImage('images', $request->img);
                Page::updatePageData($data, $pageImg);
            }
            else {
                Page::updatePageData($data);
            }

            session()->flash('success', __('site.updated_successfully'));
        }
        else{
            /**************  create ***************/
            $pageImg = ImgHelper::uploadImage('images', $request->img);
            $data['img'] = $pageImg;
            Page::createPage($data);
            session()->flash('success', __('site.created_successfully'));
        }

        return redirect(route('page.index'));
    }

    public function getPage($pageId = null)
    {
        $langs = Lang::getAllLangs();

        if (!is_null($pageId)){
            //edit
            $page               = Page::getPageById($pageId);
            $page->page_title   = json_decode($page->page_title, true);
            $page->page_content = json_decode($page->page_content, true);
            return view('dashboard.pages.save')->with(['page' => $page, 'langs' => $langs]);
        }
        //create
        return view('dashboard.pages.save')->with(['langs' => $langs]);
    }

    public function updateActivationPage(Request $request)
    {

        if (isset($request->active_status) && isset($request->page_id)){

            if ($request->active_status == 'true'){
                Page::updatePageActivationStatus($request->page_id, 1);
                return response()->json(['page_id' =>$request->page_id, 'status' => 'activate']);
            }

            Page::updatePageActivationStatus($request->page_id, 0);
            return response()->json(['page_id' =>$request->page_id, 'status' => 'deactivate']);
        }
        return response()->json(false);

    }


    public function destroy($id)
    {
        Page::findOrFail($id)->delete();
        return back();
    }

    public function translationOrderStatus($status)
    {

    }

}
