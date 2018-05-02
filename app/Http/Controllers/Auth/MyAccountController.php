<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\SocialLinks;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Toaster;

class MyAccountController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return $this
     */
    public function show()
    {
        $user = User::query()->where('id', Auth::user()->id)->with('roles')->with('permissions')->first();
        return view('my-account')->with('user', $user)->with('socialMediaSites', SocialLinks::socialMediaSites);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'socialLink' => 'required|min:3|max:200',
            'socialSite' => 'required|min:3|max:30',
        ]);
        $socialLinks = new SocialLinks;

        $request->socialLink = preg_replace('#^https?://#', '', $request->socialLink);

        $socialLinks->user_id = Auth::user()->id;

        $socialLinks->link = $request->socialLink;
        $socialLinks->site_name = $request->socialSite;
        if($socialLinks->save()) {
            Toaster::success("Link added!");
        } else {
            Toaster::danger("Link could not be saved.");
        }
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $socialLink = SocialLinks::where('id', $request->link_id);
        $socialLink->delete();
        Toaster::success("Link deleted");
        return redirect()->back();
    }
}
