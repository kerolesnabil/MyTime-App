<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ValidationUsers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        $users=User::paginate(15);

        return view('dashboard.users.index')->with(['users'=>$users]);
    }//end of index


    public function create()
    {
        return view('dashboard.users.create');
    }


    public function store(ValidationUsers $request)
    {
        //
        dd($request->all());

    }


    public function edit(User $user)
    {
        //
    }


    public function update(Request $request, User $user)
    {
        //
    }


    public function destroy(User $user)
    {
        //
    }
}
