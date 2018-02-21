<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Permission;
use Illuminate\Support\Facades\Session;
use Auth;

class PermissionController extends Controller
{
    public function __construct()
    {
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $neededPermission = 'read-permission';
        if (!Auth::user()->hasPermission($neededPermission)) {
            return $this->rejectUnauthorized($neededPermission);
        }
        $permissions = Permission::all();
        return view('manage.permissions.index')->withPermissions($permissions);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create()
    {
        $neededPermission = 'create-permission';
        if (!Auth::user()->hasPermission($neededPermission)) {
            return $this->rejectUnauthorized($neededPermission);
        }
        return view('manage.permissions.create');
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $neededPermission = 'create-permission';
        if (!Auth::user()->hasPermission($neededPermission)) {
            return $this->rejectUnauthorized($neededPermission);
        }
        if ($request->permission_type == 'basic') {
            $this->validate($request, [
                'display_name' => 'required|max:255',
                'name' => 'required|max:255|alphadash|unique:permissions,name',
                'description' => 'sometimes|max:255'
            ]);

            $permission = new Permission();
            $permission->name = $request->name;
            $permission->display_name = $request->display_name;
            $permission->description = $request->description;
            $permission->save();

            Session::flash('success', 'Permission has been successfully added');
            return redirect()->route('permissions.index');
        } elseif ($request->permission_type == 'crud') {
            $this->validate($request, [
                'resource' => 'required|min:3|max:100|alpha'
            ]);


            $crud = explode(',', $request->crud_selected);
            if (count($crud) > 0) {
                foreach ($crud as $action) {
                    $slug = strtolower($action) . '-' . strtolower($request->resource);
                    $display_name = ucwords($action . " " . $request->resource);
                    $description = "Allows a user to " . strtoupper($action) . ucwords($request->resource);

                    $permission = new Permission();
                    $permission->name = $slug;
                    $permission->display_name = $display_name;
                    $permission->description = $description;
                    $permission->save();
                }
                Session::flash('success', 'Permissions were all successfully added');
                return redirect()->route('permissions.index');
            }
        } else {
            return redirect()->route('permissions.create')->withInput();
        }
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $neededPermission = 'read-permission';
        if (!Auth::user()->hasPermission($neededPermission)) {
            return $this->rejectUnauthorized($neededPermission);
        }
        $permission = Permission::findOrFail($id);
        return view('manage.permissions.show')->withPermission($permission);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $neededPermission = 'update-permission';
        if (!Auth::user()->hasPermission($neededPermission)) {
            return $this->rejectUnauthorized($neededPermission);
        }
        $permission = Permission::findOrFail($id);
        return view('manage.permissions.edit')->withPermission($permission);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $neededPermission = 'update-permission';
        if (!Auth::user()->hasPermission($neededPermission)) {
            return $this->rejectUnauthorized($neededPermission);
        }
        $this->validate($request, [
            'display_name' => 'required|max:255',
            'description' => 'sometimes|max:255'
        ]);
        $permission = Permission::findOrFail($id);
        $permission->display_name = $request->display_name;
        $permission->description = $request->description;
        $permission->save();

        Session::flash('success', 'Updated the ' . $permission->display_name . ' permission.');
        return redirect()->route('permissions.show', $id);
    }
}
