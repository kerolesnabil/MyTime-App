<?php

namespace App\Http\Controllers\Dashboard;

use App\Helpers\ImgHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveAdminRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function index()
    {
        $users= User::getUsersByType('admin');
        return view('dashboard.admins.index')->with(['admins'=> $users]);
    }


    public function saveAdmin(SaveAdminRequest $request, $adminId = null)
    {

        $data['user_name']          = $request->user_name;
        $data['user_address']       = $request->user_address;
        $data['user_phone']         = $request->user_phone;
        $data['user_email']         = $request->user_email;
        $data['user_date_of_birth'] = $request->user_date_of_birth;
        $data['user_is_active']     = $request->user_is_active;

        $options =[];
        if(!empty($request->password)){
            $options['password'] = bcrypt($request->password);
        }

        if (!is_null($adminId)){
            /**************  edit ***************/

            if (!is_null($request->user_img)) {
                $adminImg = ImgHelper::uploadImage('images', $request->user_img);
                $options['user_img'] = $adminImg;
            }
            User::saveAdminData($data, $adminId, $options);
            session()->flash('success', __('site.updated_successfully'));
        }
        else{
            /**************  create ***************/
            if (!is_null($request->user_img)) {
                $adminImg = ImgHelper::uploadImage('images', $request->user_img);
                $options['user_img'] = $adminImg;
            }
            User::saveAdminData($data, null, $options);
            session()->flash('success', __('site.created_successfully'));
        }

        return redirect(route('admin.index'));
    }

    public function getAdmin($adminId = null)
    {
        // to do

        if (!is_null($adminId)){
            //edit
            $admin = User::getUserWithTypeAndById($adminId,'admin');

            if (is_null($admin)){
                session()->flash('warning', __('site_admin.admin_id_not_valid'));
                return redirect(route('admin.index'));
            }
            return view('dashboard.admins.save')->with(['admin' => $admin]);
        }
        //create
        return view('dashboard.admins.save');
    }



    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return back();
    }


}
