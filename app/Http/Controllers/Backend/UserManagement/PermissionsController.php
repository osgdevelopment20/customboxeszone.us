<?php

namespace App\Http\Controllers\Backend\UserManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PermissionsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class PermissionsController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return view('backend.userManagement.permissionsManagement.index', compact('permissions'));
    }

    /**
     * Show the form for creating new Permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        if (! Gate::allows('users_manage')) {
//            return abort(401);
//        }
        return view('backend.userManagement.permissionsManagement.create');
    }

    /**
     * Store a newly created Permission in storage.
     *
     * @param  \App\Http\Requests\StorePermissionsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionsRequest $request)
    {
//        if (! Gate::allows('users_manage')) {
//            return abort(401);
//        }
        Permission::create($request->all());

        return redirect()->route('dashboard.permission.index')->with('success','Permission Created Successfully');
    }


    /**
     * Show the form for editing Permission.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
//        if (! Gate::allows('users_manage')) {
//            return abort(401);
//        }

        return view('backend.userManagement.permissionsManagement.edit', compact('permission'));
    }

    /**
     * Update Permission in storage.
     *
     * @param  \App\Http\Requests\UpdatePermissionsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionsRequest $request, Permission $permission)
    {
//        if (! Gate::allows('users_manage')) {
//            return abort(401);
//        }

        $permission->update($request->all());

        return redirect()->route('dashboard.permission.index')->with('success','Permission Updated Successfully');
    }


    /**
     * Remove Permission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Permission $permission)
    {
//        if (! Gate::allows('users_manage')) {
//            return abort(401);
//        }

        $permission->delete();

        return redirect()->route('dashboard.permission.index')->with('success','Permission deleted Successfully');
    }

    public function show(Permission $permission)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        return view('admin.permissions.show', compact('permission'));
    }

    /**
     * Delete all selected Permission at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        Permission::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }
}
