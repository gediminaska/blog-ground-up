<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Hash;
use Session;
use App\Role;
use Toaster;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::orderBy('id', 'desc')->paginate(10);

        return view('manage.users.index')->withUsers($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::where('id', '>', 2)->get();
        return view('manage.users.create')->withRoles($roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users'
        ]);
        if (request()->has('password') && !empty($request->password)) {
            $password = trim($request->password);
        } else {
            $length = 10;
            $keyspace = '123456789abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMONPQRSTUVWXYZ';
            $str = '';
            $max = mb_strlen($keyspace, '8bit') - 1;
            for($i = 0; $i < $length; $i++) {
                $str .= $keyspace[random_int(0, $max)];
            }
            $password = $str;
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($password);


        if($user->save()) {
            if ($request->roles && !in_array(1, $request->roles)) {
                $user->syncRoles(explode(',', $request->roles));
            }
            Session::flash('success', 'User has been saved successfully.');
            return redirect()->route('users.create');
        } else {
            Session::flash('danger', "Sorry, a problem occurred when creating user.");
            return redirect()->route('users.create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::where('id', $id)->with('roles')->first();
        return view('manage.users.show')->withUser($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $roles = Role::where('id', '>', 2)->get();
        $user = User::where('id', $id)->with('roles')->first();
        return view('manage.users.edit')->withUser($user)->withRoles($roles);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(($id == 1 || $id == 2) && !Auth::user()->hasRole('superadministrator')) {
            Toaster::danger('You do not have permission to do that');
            return redirect()->back();
        }
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $id
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password_options == 'auto') {
            $length = 10;
            $keyspace = '123456789abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMONPQRSTUVWXYZ';
            $str = '';
            $max = mb_strlen($keyspace, '8bit') - 1;
            for ($i = 0; $i < $length; $i++) {
                $str .= $keyspace[random_int(0, $max)];
            }
            $user->password = Hash::make($str);
        } elseif($request->password_options == 'manual') {
            $user->password = Hash::make($request->password);
        } elseif ($request->roles && !in_array(1, explode(',', $request->roles))){
            $user->syncRoles(explode(',', $request->roles));
            }

        if($user->save()) {
            Session::flash('success', 'Changes successfully saved.');
            return redirect()->route('users.show', $user->id);
        } else {
            Session::flash('error', 'There was a problem saving changes.');
            return redirect()->route('users.show', $user->id);
        }

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
