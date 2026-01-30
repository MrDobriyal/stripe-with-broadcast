<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /* Show login page */
    public function showLogin()
    {
        return view('frontend.auth.login');
    }

    /* Handle login */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Invalid credentials'
            ])->withInput();
        }

        $request->session()->regenerate();

        return redirect()->route('frontend.home')
            ->with('success', 'Logged in successfully');
    }

    /* Show register page */
    public function showRegister()
    {
        return view('frontend.auth.register');
    }

    /* Handle register */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);

        return redirect()->route('frontend.home')
            ->with('success', 'Account created successfully');
    }

    /* Logout */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('frontend.login')
            ->with('success', 'Logged out successfully');
    }
}
