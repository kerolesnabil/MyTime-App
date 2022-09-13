<?php

namespace App\Http\Controllers\Dashboard\Auth;

use App\Adpaters\ISMSGateway;
use App\Helpers\ResponsesHelper;
use App\Http\Controllers\Controller;
use App\Http\Services\VerificationServices;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{



    public function getViewLogin()
    {
        return view('dashboard.auth.login');
    }

    public function login(Request $request)
    {
        $rules     = [
            "email"           => "required|email",
            'password'        => 'required|string',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }



        $user= User::getUserByEmailAndPassword($request->all());
        dd($user);
        //validation
        $remember_me = $request->has('remember_me') ? true : false;

        if(auth()->attempt([
            'user_email' => $request->input("email"),
            'password' => $request->input("password")
        ],$remember_me)){

            dd($request->input("email"));


            return redirect()->route('admin.homepage');
        }

        return redirect()->back()->with(['error' => 'هناك خطا بالبيانات']);


    }


}
