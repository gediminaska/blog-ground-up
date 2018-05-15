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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($this->userCannot('read-users')) {
            return redirect()->route('blog.index');
        }
        $users = User::query()->orderBy('id', 'desc')->paginate(10);

        return view('manage.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if ($this->userCannot('create-users')) {
            return redirect()->route('blog.index');
        }
        $roles = Role::query()->where('id', '>', 2)->get();
        return view('manage.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($this->userCannot('create-users')) {
            return redirect()->route('blog.index');
        }
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users'
        ]);
        $password = $this->setPassword($request);
        $this->saveUser($request, $password);

        $toaster = new Toaster;
        $toaster->success('User has been saved successfully.');
        return redirect()->route('users.create');
    }

    /**
     * @param Request $request
     * @return null|string
     */
    public function setPassword(Request $request)
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
     * @param null $id
     * @return User|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    private function saveUser(Request $request, $password, $id = null)
    {
        isset($id) ? $user = User::query()->findOrFail($id) : $user = new User;
        $user->setAttribute('name', $request->get('name'));
        $user->setAttribute('email', $request->get('email'));
        !isset($password) ?: $user->setAttribute('password', Hash::make($password));
        $user->save();

        if ($request->get('roles') && !in_array(1, explode(',', $request->get('roles')))) {
            $user->syncRoles(explode(',', $request->get('roles')));
        }
        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($this->userCannot('read-users')) {
            return redirect()->route('blog.index');
        }
        $user = User::query()->where('id', $id)->with('roles')->first();
        return view('manage.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if ($this->userCannot('update-users')) {
            return redirect()->route('blog.index');
        }
        $roles = Role::query()->where('id', '>', 2)->get();
        $user = User::query()->where('id', $id)->with('roles')->first();
        return view('manage.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($this->userCannot('update-users')) {
            return redirect()->route('blog.index');
        }
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

        $user = $this->saveUser($request, $password, $id);

        $toaster = new Toaster;
        $toaster->success('Changes successfully saved.');
        return redirect()->route('users.show', $user->get('id'));

    }
}
