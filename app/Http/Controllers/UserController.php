<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as Auth;
use Illuminate\Support\Facades\Hash as Hash;
use TheoryThree\LaraToaster\LaraToaster as Toaster;

class UserController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index()
    {
        $this->authorize('index', [new User, Auth::user()]);

        $users = User::query()->orderBy('id', 'desc')->paginate(10);

        return view('manage.users.index', compact('users'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create()
    {
        $this->authorize('create', [User::class, Auth::user()]);

        $roles = Role::query()->where('id', '>', 2)->get();
        return view('manage.users.create', compact('roles'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $user = new User;

        $this->authorize('create', [$user, Auth::user()]);

        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users'
        ]);
        $password = $this->setPassword($request);
        $this->saveUser($request, $password, $user);

        $toaster = new Toaster;
        $toaster->success('User has been saved successfully.');
        return redirect()->route('users.create');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id)
    {
        $user = User::query()->where('id', $id)->with('roles')->first();

        $this->authorize('show', [$user, Auth::user()]);

        return view('manage.users.show', compact('user'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($id)
    {
        $user = User::query()->find($id);

        $this->authorize('update', [$user, Auth::user()]);

        $roles = Role::query()->where('id', '>', 2)->get();
        $user = User::query()->where('id', $id)->with('roles')->first();
        return view('manage.users.edit', compact('user', 'roles'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $this->authorize('update', [$user, Auth::user()]);

        if (($id == 1 || $id == 2) && !Auth::user()->hasRole('superadministrator')) {
            $toaster = new Toaster;
            $toaster->danger('You do not have permission to do that');
            return redirect()->back();
        }
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $id
        ]);

        $password = $this->setPassword($request);

        $user = $this->saveUser($request, $password, $user);

        $toaster = new Toaster;
        $toaster->success('Changes successfully saved.');
        return redirect()->route('users.show', $user->id);

    }


    /**
     * @param Request $request
     * @return null|string
     */
    private function setPassword(Request $request)
    {
        if ($request->get('password_options') == 'manual' && request()->has('password') && !empty($request->get('password'))) {
            $password = trim($request->get('password'));
        } elseif ($request->get('password_options') == 'auto') {
            $password = $this->generatePassword();
        } else {
            $password = null;
        }
        return $password;
    }

    /**
     * @return string
     */
    private function generatePassword(): string
    {
        $length = 10;
        $keyspace = '123456789abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMONPQRSTUVWXYZ';
        $str = '';
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; $i++) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $str;
    }

    /**
     * @param Request $request
     * @param $password
     * @param User $user
     * @return User
     */
    private function saveUser(Request $request, $password, User $user)
    {
        $user->setAttribute('name', $request->get('name'));
        $user->setAttribute('email', $request->get('email'));
        !isset($password) ?: $user->setAttribute('password', Hash::make($password));
        $user->save();

        if ($request->get('roles') && !in_array(1, explode(',', $request->get('roles')))) {
            $user->syncRoles(explode(',', $request->get('roles')));
        }
        return $user;
    }

}
