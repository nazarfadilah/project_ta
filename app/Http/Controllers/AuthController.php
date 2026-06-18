<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Tentang;
use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use Carbon\Carbon;

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

        if ($user) {
            if ($user->status !== 'ACTIVE') {
                return back()->withErrors(['login' => 'Akun Anda dinonaktifkan atau ditangguhkan. Hubungi admin.'])->withInput();
            }

            if (Hash::check($request->password, $user->password)) {
                // Update last login time
                $user->update(['lastLoginAt' => now()]);
                
                Auth::guard('web')->login($user);
                $request->session()->regenerate();
                session()->forget('captcha');

                // Role-based redirection
                if (in_array($user->roleId, [1, 2, 3])) {
                    return redirect()->route('admin.dashboard');
                } else {
                    return redirect()->route('users.dashboard');
                }
            } else {
                return back()->withErrors(['password' => 'Password yang Anda masukkan salah.'])->withInput();
            }
        }

        return back()->withErrors(['login' => 'Email atau Username tidak ditemukan.'])->withInput();
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
            'email' => 'required|email|unique:users,email|max:255|ends_with:@gmail.com',
            'password' => 'required|min:6|confirmed',
            'phone' => 'nullable|numeric|digits_between:12,15',
        ], [
            'username.required' => 'Username harus diisi.',
            'username.unique' => 'Username sudah terdaftar.',
            'email.required' => 'Alamat email harus diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email sudah terdaftar.',
            'email.ends_with' => 'Email harus menggunakan domain @gmail.com.',
            'password.required' => 'Password harus diisi.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'phone.numeric' => 'Nomor telepon harus berupa angka saja.',
            'phone.digits_between' => 'Nomor telepon harus terdiri dari 12 sampai 15 digit.',
        ]);

        // Get Tamu role
        $peminjamRole = Role::where('name', 'Tamu')->first() ?? Role::find(4);
        if (!$peminjamRole) {
            return back()->withErrors(['error' => 'Role Tamu tidak ditemukan. Hubungi administrator.']);
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

            // Find user by Google ID or Email
            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            // Get Tamu role
            $peminjamRole = Role::where('name', 'Tamu')->first() ?? Role::find(4);
            if (!$peminjamRole) {
                return redirect()->route('login')->withErrors(['error' => 'Role Tamu tidak ditemukan. Hubungi administrator.']);
            }

            if (!$user) {
                // Generate a unique username
                $baseUsername = $googleUser->name ?? explode('@', $googleUser->email)[0];
                // Sanitize username (remove special characters except alphanumeric, limit length)
                $baseUsername = preg_replace('/[^A-Za-z0-9]/', '', $baseUsername);
                if (empty($baseUsername)) {
                    $baseUsername = 'user';
                }
                $baseUsername = substr($baseUsername, 0, 90);
                $username = $baseUsername;
                $counter = 1;
                while (User::where('username', $username)->exists()) {
                    $username = $baseUsername . $counter;
                    $counter++;
                }

                // Generate a unique 16-digit numeric NIK to satisfy validation constraints
                do {
                    $nik = '12' . sprintf('%014d', mt_rand(0, 99999999999999));
                } while (Guest::where('nik', $nik)->exists());

                // Create Guest record first
                $guest = Guest::create([
                    'nik' => $nik,
                    'name' => $googleUser->name ?? $username,
                    'gender' => 'MALE',
                    'address' => '-',
                ]);

                // Create User record linked to the new Guest record
                $user = User::create([
                    'username' => $username,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => null, // Password is null for google users
                    'roleId' => $peminjamRole->id,
                    'guestId' => $guest->id,
                    'status' => 'ACTIVE',
                ]);
            } else {
                // Update google_id if it's missing (user registered normally before)
                $updates = [];
                if (!$user->google_id) {
                    $updates['google_id'] = $googleUser->id;
                }

                // Ensure a Guest record exists for this Tamu user
                if ($user->roleId == $peminjamRole->id && (!$user->guestId || !Guest::where('id', $user->guestId)->exists())) {
                    do {
                        $nik = '12' . sprintf('%014d', mt_rand(0, 99999999999999));
                    } while (Guest::where('nik', $nik)->exists());

                    $guest = Guest::create([
                        'nik' => $nik,
                        'name' => $user->username,
                        'gender' => 'MALE',
                        'address' => '-',
                        'phone' => $user->phone,
                    ]);
                    $updates['guestId'] = $guest->id;
                }

                if (!empty($updates)) {
                    $user->update($updates);
                }
            }

            $user->update(['lastLoginAt' => now()]);
            Auth::guard('web')->login($user);
            session()->regenerate();
            session()->forget('captcha');

            // Role-based redirection
            if (in_array($user->roleId, [1, 2, 3])) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('users.dashboard');
            }

        } catch (\Exception $e) {
            \Log::error('Google Auth Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->route('login')->withErrors(['error' => 'Gagal login menggunakan Google. Detail: ' . $e->getMessage()]);
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

    // ─── FORGOT PASSWORD ──────────────────────────────────────
    public function showForgotPassword()
    {
        $whatsapp = Tentang::where('key', 'whatsapp')->first()?->value;
        return view('auth.forgot-password', ['whatsapp' => $whatsapp]);
    }

    public function sendResetCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Alamat email tidak terdaftar di sistem.',
        ]);

        $code = (string)rand(100000, 999999);

        // Save code to database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $code,
                'created_at' => now(),
            ]
        );

        // Send email
        try {
            Mail::to($request->email)->send(new ResetPasswordMail($code));
        } catch (\Exception $e) {
            \Log::error('Failed to send reset password email: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Gagal mengirim email verifikasi. Silakan coba lagi nanti.'])->withInput();
        }

        return redirect()->route('password.reset', ['email' => $request->email])
            ->with('success', 'Kode verifikasi reset password telah dikirim ke email Anda.');
    }

    public function showResetPassword(Request $request)
    {
        $email = $request->input('email') ?? session('email');
        $whatsapp = Tentang::where('key', 'whatsapp')->first()?->value;
        return view('auth.reset-password', ['email' => $email, 'whatsapp' => $whatsapp]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|numeric',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.exists' => 'Email tidak terdaftar.',
            'code.required' => 'Kode verifikasi wajib diisi.',
            'code.numeric' => 'Kode verifikasi harus berupa angka.',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$record || $record->token !== $request->code || Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            return back()->withErrors(['code' => 'Kode verifikasi salah atau telah kedaluwarsa (berlaku 15 menit).'])->withInput();
        }

        // Reset password to default 'password'
        $user = User::where('email', $request->email)->firstOrFail();
        $user->update([
            'password' => Hash::make('password')
        ]);

        // Delete used token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')
            ->with('success', 'Password Anda telah berhasil direset ke default: "password". Silakan masuk menggunakan password tersebut dan segera ubah password Anda di halaman profil.');
    }
}

