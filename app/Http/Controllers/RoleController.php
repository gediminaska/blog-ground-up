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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', [new Role, Auth::user()]);

        $roles = Role::all();
        return view('manage.roles.index', compact('roles'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', [Role::class, Auth::user()]);

        $permissions = Permission::all();
        return view('manage.roles.create', compact('permissions'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', [new Role, Auth::user()]);

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id)
    {
        $role = Role::query()->where('id', $id)->first();

        $this->authorize('show', [$role, Auth::user()]);

        return view('manage.roles.show', compact('role'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($id)
    {
        $role = Role::query()->where('id', $id)->with('permissions')->first();

        $this->authorize('update', [$role, Auth::user()]);

        $permissions = Permission::all();
        return view('manage.roles.edit', compact('role', 'permissions'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $this->authorize('update', [$role, Auth::user()]);

        $this->validate($request, [
            'display_name' => 'required|max:255',
            'description' => 'sometimes|max:255',
        ]);

        $role->update($request->all());

        if ($request->permissions) {
            $role->syncPermissions(explode(',', $request->permissions));
        }
        Session::flash('success', 'Successfully updated the ' . $role->display_name . ' role in the database.');
        return redirect()->route('roles.show', $id);
    }

}
