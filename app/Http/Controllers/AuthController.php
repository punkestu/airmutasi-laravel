<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginView()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            Auth::login(Auth::user());
            return redirect()->route('landing')->with('justlogin', true);
        }
        return redirect()->route('login')->with('invalid', 'Email atau password salah');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('landing');
    }
}
