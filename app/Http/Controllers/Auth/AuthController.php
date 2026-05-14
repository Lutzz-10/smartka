<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AuthController extends Controller
{
    // ─── LOGIN ────────────────────────────────────────────

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (!Auth::attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Email atau password salah. Silakan coba lagi.']);
        }

        $user = Auth::user();

        // Cek apakah email sudah diverifikasi
        if (!$user->email_verified_at) {
            // Kirim ulang OTP lalu arahkan ke halaman verifikasi
            $this->generateAndSendOtp($user);
            session(['otp_user_id' => $user->id]);
            Auth::logout();
            return redirect()->route('otp.show')
                ->with('info', 'Verifikasi email kamu terlebih dahulu.');
        }

        $request->session()->regenerate();

        // Arahkan berdasarkan role
        if ($user->role === 'admin') {
            return redirect()->intended('/admin/dashboard');
        }

        return redirect()->intended('/dashboard');
    }

    // ─── REGISTER ─────────────────────────────────────────

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'            => 'required|string|max:100',
            'email'           => 'required|email|unique:users,email',
            'phone'           => 'required|string|max:15',
            'password'        => 'required|string|min:8|confirmed',
            'class_level'     => 'required|in:6,9,12',
            'terms'           => 'accepted',
        ], [
            'name.required'        => 'Nama lengkap wajib diisi.',
            'email.unique'         => 'Email ini sudah terdaftar.',
            'password.min'         => 'Password minimal 8 karakter.',
            'password.confirmed'   => 'Konfirmasi password tidak cocok.',
            'class_level.required' => 'Pilih jenjang kelas kamu.',
            'terms.accepted'       => 'Kamu harus menyetujui syarat & ketentuan.',
        ]);

        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'password'    => Hash::make($request->password),
            'class_level' => $request->class_level,
            'role'        => 'student',
        ]);

        // Generate & kirim OTP
        $this->generateAndSendOtp($user);

        // Simpan user_id di session untuk verifikasi OTP
        session(['otp_user_id' => $user->id]);

        return redirect()->route('otp.show')
            ->with('success', 'Kode OTP telah dikirim ke email kamu.');
    }

    // ─── OTP ──────────────────────────────────────────────

    public function showOtp()
    {
        if (!session('otp_user_id')) {
            return redirect()->route('register');
        }
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ], [
            'otp.size' => 'Kode OTP harus 6 digit.',
        ]);

        $userId = session('otp_user_id');
        $user   = User::find($userId);

        if (!$user) {
            return redirect()->route('register')
                ->withErrors(['otp' => 'Sesi tidak valid. Silakan daftar ulang.']);
        }

        // Cek kode OTP (pastikan perbandingan string)
        if ((string)$user->otp_code !== (string)$request->otp) {
            return back()->withErrors(['otp' => 'Kode OTP salah. Periksa email kamu.']);
        }

        // Cek apakah OTP sudah expired (10 menit)
        if (Carbon::now()->isAfter($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa. Klik "Kirim ulang".']);
        }

        // Verifikasi berhasil
        $user->update([
            'email_verified_at' => now(),
            'otp_code'          => null,
            'otp_expires_at'    => null,
        ]);

        session()->forget('otp_user_id');

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('success', 'Akun berhasil diverifikasi! Selamat datang di SMARTKA 🎉');
    }

    public function resendOtp(Request $request)
    {
        $userId = session('otp_user_id');
        $user   = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'Sesi tidak valid.'], 422);
        }

        $this->generateAndSendOtp($user);

        return response()->json(['message' => 'Kode OTP baru telah dikirim ke email kamu.']);
    }

    // ─── FORGOT PASSWORD ──────────────────────────────────

    public function showForgot()
    {
        return view('auth.forgot-password');
    }

    public function sendReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email ini tidak terdaftar di SMARTKA.',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('success', 'Link reset password telah dikirim ke email kamu.')
            : back()->withErrors(['email' => 'Gagal mengirim link reset. Coba lagi.']);
    }

    public function showReset(string $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|string|min:8|confirmed',
        ], [
            'password.min'       => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill(['password' => Hash::make($password)])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login.')
            : back()->withErrors(['email' => __($status)]);
    }

    // ─── LOGOUT ───────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    // ─── HELPER ───────────────────────────────────────────

    private function generateAndSendOtp(User $user): void
    {
        $otp = Str::padLeft(random_int(0, 999999), 6, '0');

        $user->update([
            'otp_code'        => $otp,
            'otp_expires_at'  => Carbon::now()->addMinutes(10),
        ]);

        // Kirim email OTP
        Mail::send('emails.otp', ['user' => $user, 'otp' => $otp], function ($mail) use ($user, $otp) {
            $mail->to($user->email)
                 ->subject('Kode Verifikasi SMARTKA — ' . $otp);
        });
    }
}