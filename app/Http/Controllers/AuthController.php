<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Tentang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ─── LOGIN ────────────────────────────────────────────────
    public function showLogin()
    {
        $whatsapp = Tentang::where('key', 'whatsapp')->first()?->value;
        return view('auth.login', ['whatsapp' => $whatsapp]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
            'captcha' => 'required',
            'captcha_value' => 'required|numeric',
        ]);

        // Verify captcha
        if (!session()->has('captcha') || session('captcha') != $request->captcha_value) {
            return back()->withErrors(['captcha_value' => 'Captcha tidak valid.'])->withInput();
        }

        // Find user by email or username
        $user = User::where('email', $request->login)
                    ->orWhere('username', $request->login)
                    ->first();

        if ($user && Hash::check($request->password, $user->password) && $user->status === 'ACTIVE') {
            // Update last login time
            $user->update(['lastLoginAt' => now()]);
            Auth::guard('web')->login($user);
            $request->session()->invalidate();
            $request->session()->regenerate();
            session()->forget('captcha');
            return redirect()->route('user.dashboard');
        }

        return back()->withErrors(['login' => 'Email/Username atau password salah.'])->withInput();
    }

    // ─── GENERATE CAPTCHA ─────────────────────────────────────
    public function generateCaptcha()
    {
        $captcha = rand(100000, 999999);
        session(['captcha' => $captcha]);
        return response()->json(['captcha' => $captcha]);
    }

    // ─── REGISTER ─────────────────────────────────────────────
    public function showRegister()
    {
        $whatsapp = Tentang::where('key', 'whatsapp')->first()?->value;
        return view('auth.register', ['whatsapp' => $whatsapp]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100|unique:users,username',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
        ]);

        // Get peminjam role
        $peminjamRole = Role::where('name', 'peminjam')->first();
        if (!$peminjamRole) {
            return back()->withErrors(['error' => 'Role peminjam tidak ditemukan. Hubungi administrator.']);
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'roleId' => $peminjamRole->id,
            'status' => 'ACTIVE',
        ]);

        Auth::guard('web')->login($user);
        return redirect()->route('user.dashboard')->with('success', 'Selamat datang, ' . $request->username . '! Akun berhasil dibuat.');
    }

    // ─── GOOGLE LOGIN ─────────────────────────────────────────
    public function redirectToGoogle()
    {
        // For future implementation with Laravel Socialite
        // return Socialite::driver('google')->redirect();
        return back()->with('info', 'Google login akan segera tersedia.');
    }

    public function handleGoogleCallback()
    {
        // For future implementation with Laravel Socialite
        // $user = Socialite::driver('google')->user();
        // ...
    }

    // ─── LOGOUT ───────────────────────────────────────────────
    public function logoutUser(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Berhasil keluar.');
    }
}

