<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Permission;
use TheoryThree\LaraToaster\LaraToaster as Toaster;

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
        if ($this->userCannot('read-permission')) {
            return redirect()->route('blog.index');
        }
        $permissions = Permission::all();
        return view('manage.permissions.index', compact('permissions'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create()
    {
        if ($this->userCannot('create-permission')) {
            return redirect()->route('blog.index');
        }
        return view('manage.permissions.create');
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if ($this->userCannot('create-permission')) {
            return redirect()->route('blog.index');
        }
        if ($request->get('permission_type') == 'basic') {
            $this->validate($request, [
                'display_name' => 'required|max:255',
                'name' => 'required|max:255|alphadash|unique:permissions,name',
                'description' => 'sometimes|max:255'
            ]);
            $this->saveSinglePermission($request);
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        if ($this->userCannot('read-permission')) {
            return redirect()->route('blog.index');
        }
        $permission = Permission::query()->findOrFail($id);
        return view('manage.permissions.show', compact('permission'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        if ($this->userCannot('update-permission')) {
            return redirect()->route('blog.index');
        }
        $permission = Permission::query()->findOrFail($id);
        return view('manage.permissions.edit', compact('permission'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        if ($this->userCannot('update-permission')) {
            return redirect()->route('blog.index');
        }
        $this->validate($request, [
            'display_name' => 'required|max:255',
            'description' => 'sometimes|max:255'
        ]);
        $permission = $this->saveSinglePermission($request, $id);

        $toaster = new Toaster;
        $toaster->success('Updated the ' . $permission->getAttribute('display_name') . ' permission.');
        return redirect()->route('permissions.show', $id);
    }

    /**
     * @param Request $request
     * @param null $id
     * @return Permission|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    private function saveSinglePermission(Request $request, $id = null)
    {
        if(isset($id)) {
            $permission = Permission::query()->findOrFail($id);
        } else {
            $permission = new Permission;
            $permission->setAttribute('name', $request->get('name'));
        }
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

            $this->saveSinglePermission($request);
        }
    }
}
