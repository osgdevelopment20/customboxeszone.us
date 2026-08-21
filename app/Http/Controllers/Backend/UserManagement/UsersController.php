<?php

namespace App\Http\Controllers\Backend\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\StoreUserRequest;
use App\Http\Requests\Backend\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{


    public function index()
    {
        $users = User::with('roles')->get();
        return view('backend.userManagement.users.index',compact('users'));
    }

    public function create()
    {
        $roles = Role::get()->pluck('name', 'name');
        return view('backend.userManagement.users.create',compact('roles'));
    }


    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->all());
        $roles = $request->input('role') ? $request->input('role') : [];
        $user->assignRole($roles);

        return redirect()->route('dashboard.user.index')->with('success','User Created Successfully');
    }

    public function edit(User $user)
    {
//        if (! Gate::allows('users_manage')) {
//            return abort(401);
//        }

        $roles = Role::get()->pluck('name', 'name');

        return view('backend.userManagement.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
//        if (! Gate::allows('users_manage')) {
//            return abort(401);
//        }

        $user->update($request->all());
        $roles = $request->input('role') ? $request->input('role') : [];
        $user->syncRoles($roles);

        return redirect()->route('dashboard.user.index')->with('success','User  Updated Successfully');
    }
    public function delete(User $user)
    {
//        if (! Gate::allows('users_manage')) {
//            return abort(401);
//        }

        $user->delete();

        return redirect()->route('dashboard.user.index')->with('success','User  Deleted Successfully');
    }

}
