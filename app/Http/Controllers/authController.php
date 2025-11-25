<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class authController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        // Redirect if already authenticated
        if (Auth::guard('administrator')->check()) {
            return redirect('/dashboard');
        }

        return view('Adminstration.auth.adminstration_login');
    }

    /**
     * Handle administrator login
     */
    public function administrator_login(Request $request)
    {
        // Validate credentials
        $credentials = $request->validate([
            'name' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // Attempt authentication
        if (Auth::guard('administrator')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        // Authentication failed
        return back()->withErrors([
            'name' => 'بيانات الدخول غير صحيحة',
        ])->withInput($request->only('name'));
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::guard('administrator')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/auth');
    }
}
