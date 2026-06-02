<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Tentang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

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
            return redirect()->route('users.dashboard');
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
        return redirect()->route('users.dashboard')->with('success', 'Selamat datang, ' . $request->username . '! Akun berhasil dibuat.');
    }

    // ─── GOOGLE LOGIN ─────────────────────────────────────────
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if (!$user) {
                // Get peminjam role
                $peminjamRole = Role::where('name', 'peminjam')->first();
                if (!$peminjamRole) {
                    return redirect()->route('login')->withErrors(['error' => 'Role peminjam tidak ditemukan. Hubungi administrator.']);
                }

                $user = User::create([
                    'username' => $googleUser->name ?? explode('@', $googleUser->email)[0],
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => null, // Password is null for google users
                    'roleId' => $peminjamRole->id,
                    'status' => 'ACTIVE',
                ]);
            } else {
                // Update google_id if it's missing (user registered normally before)
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }
            }

            $user->update(['lastLoginAt' => now()]);
            Auth::guard('web')->login($user);
            session()->regenerate();
            session()->forget('captcha');

            return redirect()->route('users.dashboard'); // Assuming dashboard is the default route after login

        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['error' => 'Gagal login menggunakan Google. Silakan coba lagi.']);
        }
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

