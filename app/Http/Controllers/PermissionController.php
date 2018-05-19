<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Permission;
use TheoryThree\LaraToaster\LaraToaster as Toaster;

class PermissionController extends Controller
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
        $this->authorize('index', [new Permission, Auth::user()]);

        $permissions = Permission::all();
        return view('manage.permissions.index', compact('permissions'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', [new Permission, Auth::user()]);

        return view('manage.permissions.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', [new Permission, Auth::user()]);

        if ($request->get('permission_type') == 'basic') {
            $this->validate($request, [
                'display_name' => 'required|max:255',
                'name' => 'required|max:255|alphadash|unique:permissions,name',
                'description' => 'sometimes|max:255'
            ]);
            $permission = new Permission;
            $this->saveSinglePermission($request, $permission);
            $toaster = new Toaster;
            $toaster->success('Permission has been successfully added');
        } elseif ($request->get('permission_type') == 'crud') {
            $this->validate($request, [
                'resource' => 'required|min:3|max:100|alpha'
            ]);
            $this->saveCrudPermissions($request);
            $toaster = new Toaster;
            $toaster->success('Permissions have all been successfully added');
        }
        return redirect()->route('permissions.index');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id)
    {
        $permission = Permission::query()->findOrFail($id);
        $this->authorize('show', [$permission, Auth::user()]);

        return view('manage.permissions.show', compact('permission'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($id)
    {
        $permission = Permission::query()->findOrFail($id);
        $this->authorize('update', [$permission, Auth::user()]);

        return view('manage.permissions.edit', compact('permission'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::query()->findOrFail($id);
        $this->authorize('update', [$permission, Auth::user()]);

        $this->validate($request, [
            'display_name' => 'required|max:255',
            'description' => 'sometimes|max:255'
        ]);
        $this->saveSinglePermission($request, $permission);

        $toaster = new Toaster;
        $toaster->success('Updated the ' . $permission->getAttribute('display_name') . ' permission.');
        return redirect()->route('permissions.show', $id);
    }

    /**
     * @param Request $request
     * @param Permission $permission
     * @return Permission
     */
    private function saveSinglePermission(Request $request, Permission $permission)
    {
        $permission->name = $request->name ?: $permission->name;

        $permission->setAttribute('display_name', $request->get('display_name'));
        $permission->setAttribute('description', $request->get('description'));
        $permission->save();
        return $permission;
    }

    /**
     * @param Request $request
     */
    private function saveCrudPermissions(Request $request): void
    {
        $crud = explode(',', $request->get('crud_selected'));
        foreach ($crud as $action) {
            $request->request->set('name', strtolower($action) . '-' . strtolower($request->get('resource')));
            $request->request->set('display_name', ucwords($action . " " . $request->get('resource')));
            $request->request->set('description', "Allows a user to " . strtoupper($action) . ' ' . ucwords($request->get('resource')));

            $permission = new Permission;
            $this->saveSinglePermission($request, $permission);
        }
    }
}
