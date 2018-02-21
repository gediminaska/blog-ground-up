<?php

namespace App\Http\Controllers;

use App\Role;
use App\Permission;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Auth;

class RoleController extends Controller
{
    public function __construct(){
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $neededPermission = 'read-roles';
        if (!Auth::user()->hasPermission($neededPermission)) {
            return $this->rejectUnauthorized($neededPermission);
        }
        $roles = Role::all();
        return view('manage.roles.index')->withRoles($roles);
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        $neededPermission = 'create-roles';
        if (!Auth::user()->hasPermission($neededPermission)) {
            return $this->rejectUnauthorized($neededPermission);
        }
        $permissions = Permission::all();
        return view('manage.roles.create')->withPermissions($permissions);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $neededPermission = 'create-roles';
        if (!Auth::user()->hasPermission($neededPermission)) {
            return $this->rejectUnauthorized($neededPermission);
        }
        $this->validate($request, [
            'display_name' => 'required|max:255',
            'name' => 'required|alphadash|max:50|unique:roles,name',
            'description' => 'sometimes|max:255'
        ]);

        $role = new Role;
        $role->display_name = $request->display_name;
        $role->name = $request->name;
        $role->description = $request->description;
        $role->save();

        if($request->permissions) {
            $role->syncPermissions(explode(',', $request->permissions));
        }
        Session::flash('success', 'Successfully created the ' . $role->display_name . ' role.');
        return redirect()->route('roles.index');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $neededPermission = 'read-roles';
        if (!Auth::user()->hasPermission($neededPermission)) {
            return $this->rejectUnauthorized($neededPermission);
        }
        $role = Role::where('id', $id)->first();
        return view('manage.roles.show')->withRole($role);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $neededPermission = 'update-roles';
        if (!Auth::user()->hasPermission($neededPermission)) {
            return $this->rejectUnauthorized($neededPermission);
        }
        $role = Role::where('id', $id)->with('permissions')->first();
        $permissions = Permission::all();
        return view('manage.roles.edit')->withRole($role)->withPermissions($permissions);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $neededPermission = 'update-roles';
        if (!Auth::user()->hasPermission($neededPermission)) {
            return $this->rejectUnauthorized($neededPermission);
        }
        $this->validate($request, [
           'display_name' => 'required|max:255',
           'description' => 'sometimes|max:255'
        ]);

        $role = Role::findOrFail($id);
        $role->display_name = $request->display_name;
        $role->description = $request->description;
        $role->save();

        if($request->permissions) {
            $role->syncPermissions(explode(',', $request->permissions));
        }
        Session::flash('success', 'Successfully updated the ' . $role->display_name . ' role in the database.');
        return redirect()->route('roles.show', $id);
    }

}
