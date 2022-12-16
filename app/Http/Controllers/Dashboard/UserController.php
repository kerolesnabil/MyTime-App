<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        $attrs['paginate'] = 10;
        $users= User::getUsersByType('user', $attrs);


        return view('dashboard.users.index')->with(['users'=>$users]);
    }

    public function updateActivateUser(Request $request)
    {

        if (isset($request->active_status) && isset($request->user_id)){

            if ($request->active_status == 'true'){
                User::updateActivationStatus($request->user_id, 1);
                return response()->json(['user_id' =>$request->user_id, 'status' => 'activate']);
            }

            User::updateActivationStatus($request->user_id, 0);
            return response()->json(['user_id' =>$request->user_id, 'status' => 'deactivate']);
        }
        return response()->json(false);

    }


    public function showNewUsers($reportType)
    {
        $reportTypes= ['daily', 'weekly', 'monthly', 'yearly', 'all'];

        if (in_array($reportType, $reportTypes)){
            $users = User::getNewUsers(20, $reportType,'user');

            return view('dashboard.users.show_new_users')->with(['users' => $users]);
        }

        session()->flash('warning', __('site.report_type_wrong'));
        return redirect()->back();

    }
}
