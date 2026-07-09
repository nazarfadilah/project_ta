<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Tentang;
use App\Models\Guest;
use App\Models\UnblockRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;
use App\Mail\RegisterVerificationMail;
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
            if ($user->status === 'INACTIVE') {
                return back()->withErrors(['login' => 'Akun Anda dinonaktifkan. Hubungi admin.'])->withInput();
            }
            if ($user->status === 'SUSPENDED') {
                $unblockLink = route('register.unblock', ['email' => $user->email]);
                return back()->withErrors(['login' => 'Akun Anda ditangguhkan (Di Blokir Sementara). Silakan <a href="' . $unblockLink . '" class="fw-bold text-decoration-underline" style="color: #c90000;">Klik di sini</a> untuk mengajukan pembukaan blokir akun. Alasan blokir: "' . ($user->blocked_reason ?? 'Tidak ada alasan spesifik') . '"'])->withInput();
            }
            if ($user->status === 'SUSPENDED_PERMANENT') {
                return back()->withErrors(['login' => 'Akun Anda telah diblokir secara permanen. Anda tidak diperbolehkan mengajukan pembukaan blokir kembali. Alasan blokir: "' . ($user->blocked_reason ?? 'Tidak ada alasan spesifik') . '"'])->withInput();
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

        $code = rand(100000, 999999);

        // Save data and verification status in user session
        session([
            'register_data' => [
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
            ],
            'register_code' => $code,
            'register_expires' => Carbon::now()->addMinutes(10),
        ]);

        // Send OTP email
        try {
            Mail::to($request->email)->send(new RegisterVerificationMail($code));
        } catch (\Exception $e) {
            session()->forget(['register_data', 'register_code', 'register_expires']);
            return back()->withErrors(['email' => 'Gagal mengirim email verifikasi: ' . $e->getMessage()])->withInput();
        }

        return redirect()->route('register.verify')->with('success', 'Kode verifikasi pendaftaran telah dikirim ke email Anda.');
    }

    public function showVerifyRegister()
    {
        if (!session()->has('register_data')) {
            return redirect()->route('register')->withErrors(['error' => 'Sesi registrasi Anda telah berakhir atau belum dimulai. Silakan daftar kembali.']);
        }
        $whatsapp = Tentang::where('key', 'whatsapp')->first()?->value;
        return view('auth.verify_register', ['whatsapp' => $whatsapp]);
    }

    public function verifyRegister(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric',
        ], [
            'code.required' => 'Kode verifikasi harus diisi.',
            'code.numeric' => 'Kode verifikasi harus berupa angka.',
        ]);

        if (!session()->has('register_data') || !session()->has('register_code') || !session()->has('register_expires')) {
            return redirect()->route('register')->withErrors(['error' => 'Sesi registrasi Anda telah berakhir. Silakan daftar kembali.']);
        }

        $expires = Carbon::parse(session('register_expires'));
        if (Carbon::now()->gt($expires)) {
            return back()->withErrors(['code' => 'Kode verifikasi telah kedaluwarsa. Silakan kirim ulang kode.']);
        }

        if (session('register_code') != $request->code) {
            return back()->withErrors(['code' => 'Kode verifikasi yang Anda masukkan salah.']);
        }

        // Get Tamu role
        $peminjamRole = Role::where('name', 'Tamu')->first() ?? Role::find(4);
        if (!$peminjamRole) {
            return redirect()->route('register')->withErrors(['error' => 'Role Tamu tidak ditemukan. Hubungi administrator.']);
        }

        $regData = session('register_data');

        // Double check email uniqueness
        if (User::where('email', $regData['email'])->exists()) {
            return redirect()->route('register')->withErrors(['email' => 'Alamat email sudah terdaftar.']);
        }

        $user = User::create([
            'username' => $regData['username'],
            'email' => $regData['email'],
            'password' => $regData['password'],
            'phone' => $regData['phone'],
            'roleId' => $peminjamRole->id,
            'status' => 'ACTIVE',
        ]);

        // Clear session register data
        session()->forget(['register_data', 'register_code', 'register_expires']);

        Auth::guard('web')->login($user);
        return redirect()->route('users.dashboard')->with('success', 'Selamat datang, ' . $user->username . '! Akun berhasil dibuat.');
    }

    public function resendVerifyRegister()
    {
        if (!session()->has('register_data')) {
            return redirect()->route('register')->withErrors(['error' => 'Sesi registrasi Anda telah berakhir. Silakan daftar kembali.']);
        }

        $regData = session('register_data');
        $code = rand(100000, 999999);

        // Update session
        session([
            'register_code' => $code,
            'register_expires' => Carbon::now()->addMinutes(10),
        ]);

        // Send email
        try {
            Mail::to($regData['email'])->send(new RegisterVerificationMail($code));
        } catch (\Exception $e) {
            return back()->withErrors(['code' => 'Gagal mengirim email verifikasi: ' . $e->getMessage()]);
        }

        return back()->with('success', 'Kode verifikasi baru telah dikirim ke email Anda.');
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

                // Create User record with guestId = null
                $user = User::create([
                    'username' => $username,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => null, // Password is null for google users
                    'roleId' => $peminjamRole->id,
                    'guestId' => null,
                    'status' => 'ACTIVE',
                ]);
            } else {
                // Update google_id if it's missing (user registered normally before)
                $updates = [];
                if (!$user->google_id) {
                    $updates['google_id'] = $googleUser->id;
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

    // ─── UNBLOCK ACCOUNT FLOW ─────────────────────────────────
    public function showRequestUnblock(Request $request)
    {
        $email = $request->query('email');
        $whatsapp = Tentang::where('key', 'whatsapp')->first()?->value;
        return view('auth.request_unblock', compact('email', 'whatsapp'));
    }

    public function submitRequestUnblock(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Alamat email tidak terdaftar.',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        if ($user->status === 'SUSPENDED_PERMANENT') {
            return back()->withErrors(['email' => 'Akun Anda telah diblokir secara permanen dan tidak diperbolehkan mengajukan pembukaan blokir kembali.'])->withInput();
        }

        if ($user->status !== 'SUSPENDED') {
            return back()->withErrors(['email' => 'Akun Anda tidak dalam status terblokir sementara.'])->withInput();
        }

        // Check if there is already a rejected unblock request for this user
        $hasRejected = UnblockRequest::where('user_id', $user->id)
            ->where('status', 'REJECTED')
            ->exists();

        if ($hasRejected) {
            return back()->withErrors(['email' => 'Permohonan buka blokir Anda sebelumnya telah ditolak permanen.'])->withInput();
        }

        // Generate 6 digit OTP
        $otp = (string)rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(15);

        // Save or update unblock request
        UnblockRequest::updateOrCreate(
            ['user_id' => $user->id, 'status' => 'PENDING'],
            [
                'verification_code' => $otp,
                'expires_at' => $expiresAt,
            ]
        );

        // Send email
        try {
            Mail::to($user->email)->send(new \App\Mail\UnblockOTPVerificationMail($otp));
        } catch (\Exception $e) {
            \Log::error('Failed to send unblock OTP: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Gagal mengirim email verifikasi OTP: ' . $e->getMessage()]);
        }

        return redirect()->route('register.unblock.verify', ['email' => $user->email])
            ->with('success', 'Kode verifikasi OTP telah dikirim ke email Anda. Silakan periksa kotak masuk.');
    }

    public function showVerifyUnblock(Request $request)
    {
        $email = $request->query('email');
        if (!$email) {
            return redirect()->route('register.unblock')->withErrors(['email' => 'Email wajib dicantumkan.']);
        }
        $whatsapp = Tentang::where('key', 'whatsapp')->first()?->value;
        return view('auth.verify_unblock', compact('email', 'whatsapp'));
    }

    public function submitVerifyUnblock(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|numeric',
            'reason' => 'required|string|min:10|max:1000',
        ], [
            'email.required' => 'Email wajib diisi.',
            'code.required' => 'Kode verifikasi wajib diisi.',
            'code.numeric' => 'Kode verifikasi harus berupa angka.',
            'reason.required' => 'Catatan alasan wajib diisi.',
            'reason.min' => 'Alasan minimal harus 10 karakter.',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        $unblockReq = UnblockRequest::where('user_id', $user->id)
            ->where('status', 'PENDING')
            ->first();

        if (!$unblockReq) {
            return back()->withErrors(['code' => 'Sesi verifikasi tidak ditemukan atau telah kedaluwarsa.'])->withInput();
        }

        if ($unblockReq->verification_code !== $request->code) {
            return back()->withErrors(['code' => 'Kode verifikasi OTP salah.'])->withInput();
        }

        if (Carbon::now()->gt($unblockReq->expires_at)) {
            return back()->withErrors(['code' => 'Kode verifikasi OTP telah kedaluwarsa. Silakan kirim ulang.'])->withInput();
        }

        // Update reason and keep PENDING status (until admin validates)
        $unblockReq->update([
            'reason' => $request->reason,
        ]);

        // Send notification email to admin
        try {
            $adminEmail = 'admin@gmail.com'; // Default admin email
            Mail::to($adminEmail)->send(new \App\Mail\AdminUnblockNotificationMail($user, $request->reason));
        } catch (\Exception $e) {
            \Log::error('Failed to notify admin about unblock request: ' . $e->getMessage());
        }

        return redirect()->route('login')
            ->with('success', 'Permohonan buka blokir berhasil diajukan. Kami telah mengirimkan email pemberitahuan ke Admin. Silakan tunggu peninjauan Admin.');
    }
}
