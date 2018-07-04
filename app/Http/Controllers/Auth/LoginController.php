<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use TheoryThree\LaraToaster\LaraToaster as Toaster;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * @return mixed
     */
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleFacebookCallback()
    {
        $user = Socialite::driver('facebook')->user();

        $authUser = $this->findOrCreateUser($user);
        if(!$authUser) {
            $toaster = new Toaster;
            $toaster->warning('You already have an account. Please use another login method.');
            return redirect()->route('login');
        }
        Auth::login($authUser, true);
        return redirect($this->redirectTo);
    }

    /**
     * @return mixed
     */
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handleGithubCallback()
    {
        $user = Socialite::driver('github')->user();

        $authUser = $this->findOrCreateUser($user);
        if(!$authUser) {
            $toaster = new Toaster;
            $toaster->warning('You already have an account. Please use another login method.');
            return redirect()->route('login');
        }
        Auth::login($authUser, true);
        return redirect($this->redirectTo);
    }

    /**
     * @param $user
     * @return mixed
     */
    public function findOrCreateUser($user)
    {
        $authUser = User::query()->where('provider_id', $user->id)->first();
        if ($authUser) {
            return $authUser;
        } elseif(count(User::where('email', $user->email)->get())>0) {
            return null;
        }
        return User::create([
            'name' => $user->name ?: $user->nickname,
            'email' => $user->email,
            'provider_id' => $user->id
        ]);
    }
}
