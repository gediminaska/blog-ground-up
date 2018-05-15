<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RoleController extends Controller
{
    public function __construct()
    {
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        if ($this->userCannot('read-roles')) {
            return redirect()->route('blog.index');
        }
        $roles = Role::all();
        return view('manage.roles.index', compact('roles'));


    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        if ($this->userCannot('create-roles')) {
            return redirect()->route('blog.index');
        }
        $permissions = Permission::all();
        return view('manage.roles.create', compact('permissions'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if ($this->userCannot('create-roles')) {
            return redirect()->route('blog.index');
        }
        $this->validate($request, [
            'display_name' => 'required|max:255',
            'name' => 'required|alphadash|max:50|unique:roles,name',
            'description' => 'sometimes|max:255'
        ]);
        $role = Role::create($request->all());

        if ($request->permissions) {
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
        if ($this->userCannot('read-roles')) {
            return redirect()->route('blog.index');
        }
        $role = Role::query()->where('id', $id)->first();
        return view('manage.roles.show', compact('role'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if ($this->userCannot('update-roles')) {
            return redirect()->route('blog.index');
        }
        $role = Role::query()->where('id', $id)->with('permissions')->first();
        $permissions = Permission::all();
        return view('manage.roles.edit', compact('role', 'permissions'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if ($this->userCannot('update-roles')) {
            return redirect()->route('blog.index');
        } elseif($id < 3 && !Auth::user()->hasRole('superadministrator')) {
            $this->rejectUnauthorizedTo('update-roles', 'update this role');
            return redirect()->route('blog.index');
        }

        $this->validate($request, [
            'display_name' => 'required|max:255',
            'description' => 'sometimes|max:255',
        ]);

        $role = Role::query()->findOrFail($id);
        $role->update($request->all());

        if ($request->permissions) {
            $role->syncPermissions(explode(',', $request->permissions));
        }
        Session::flash('success', 'Successfully updated the ' . $role->display_name . ' role in the database.');
        return redirect()->route('roles.show', $id);
    }

}
