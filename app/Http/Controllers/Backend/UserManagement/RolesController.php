<?php

namespace App\Http\Controllers\Backend\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\RolesRequest;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class RolesController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('backend.userManagement.rolesManagement.index', compact('roles'));
    }

    /**
     * Show the form for creating new Role.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        if (! Gate::allows('users_manage')) {
//            return abort(401);
//        }
        $permissions = Permission::get()->pluck('name', 'name');

        return view('backend.userManagement.rolesManagement.create', compact('permissions'));
    }

    public function store(RolesRequest $request)

    {

//        if (! Gate::allows('users_manage')) {
//            return abort(401);
//        }
        $role = Role::create($request->except('permission'));
        $permissions = $request->input('permission') ? $request->input('permission') : [];
        $role->givePermissionTo($permissions);

        return redirect()->route('dashboard.role.index')->with('success','Role  Created Successfully');
    }

    public function edit(Role $role)
    {
//        if (! Gate::allows('users_manage')) {
//            return abort(401);
//        }

        $permissions = Permission::get()->pluck('name', 'name');


        return view('backend/userManagement/rolesManagement/edit', compact('role', 'permissions'));
    }

    public function update(RolesRequest $request, Role $role)
    {
//        if (! Gate::allows('users_manage')) {
//            return abort(401);
//        }

        $role->update($request->except('permission'));
        $permissions = $request->input('permission') ? $request->input('permission') : [];
        $role->syncPermissions($permissions);

        return redirect()->route('dashboard.role.index')->with('success','Role  Updated Successfully');
    }

    public function delete (Role $role)
    {
//        if (! Gate::allows('users_manage')) {
//            return abort(401);
//        }

        $role->delete();

        return redirect()->route('dashboard.role.index')->with('success','Role  Deleted Successfully');
    }


}
