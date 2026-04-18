<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        $credentials = $request->only('username', 'password');
    
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
    
            return response()->json([
                'status' => true,
                'message' => 'Login berhasil',
                'redirect' => "/dashboard"
            ]);
        }
    
        // Change this to return 200 OK with a failure message
        // instead of a 401 status code
        return response()->json([
            'status' => false,
            'message' => 'Username atau password salah'
        ], 200); // Changed from 401 to 200
    }
    
    
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function register()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.register');
    }

    public function postRegister(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:admin',
            'password' => 'required|string|min:8|confirmed',
            'nama' => 'required|string|max:255',
        ]);

        $admin = Admin::create([
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
            'nama' => $validated['nama'],
        ]);

        Auth::guard('admin')->login($admin);

        return redirect()->route('dashboard');
    }

    public function forgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Penyesuaian diperlukan jika ingin mengaktifkan fitur reset password untuk admin
        return back()->withErrors(['email' => 'Reset password belum diaktifkan untuk akun admin.']);
    }

    public function resetPassword($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Penyesuaian juga diperlukan di sini untuk fitur reset password admin
        return back()->withErrors(['email' => 'Reset password belum diaktifkan untuk akun admin.']);
    }
}