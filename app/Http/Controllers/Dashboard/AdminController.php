<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function index()
    {
        $users= User::getUsersByType('admin');
        return view('dashboard.admins.index')->with(['admins'=> $users]);
    }


    public function savePage(Request $request, $pageId= null)
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

    public function getPage($adminId = null)
    {
        // to do

        if (!is_null($adminId)){
            //edit
            $admin = User::getUserById($adminId);

            return view('dashboard.pages.save')->with(['admin' => $admin]);
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
        User::findOrFail($id)->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return back();
    }


}
