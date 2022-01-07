<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Process for login user.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect(route('dash.dashboard'));
        }

        return back()->withErrors(['msg' => __('auth.failed')]);
    }

    /**
     * Process for logout user.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->session()->invalidate();
        Auth::logout();
        return redirect(route("auth.login.page"));
    }

    /**
     * Display dashboard
     *
     * @return void
     */
    public function index()
    {
        return view('dashboard.index', ['users' => User::all()]);
    }
}
