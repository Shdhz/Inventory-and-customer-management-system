<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class loginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $role = $user->roles->first()->name;

            switch ($role) {
                case 'admin':
                    return redirect()->intended('dashboard-admin');
                case 'supervisor':
                    return redirect()->intended('supervisor');
                case 'produksi':
                    return redirect()->intended('dashboard-produksi');
                default:
                    return redirect()->intended('/');
            }
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
