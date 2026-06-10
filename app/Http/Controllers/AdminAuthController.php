<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_authenticated') === true) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function submitLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = strtolower(trim($request->email));
        $adminEmails = array_change_key_case(config('admin.emails', []), CASE_LOWER);

        // Check if email is registered in config
        if (!array_key_exists($email, $adminEmails)) {
            return back()->withErrors(['email' => 'Email ini tidak terdaftar sebagai administrator.']);
        }

        $recipientGmail = $adminEmails[$email];
        $otp = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);

        // Save verification info to temporary session
        session([
            'admin_temp_email' => $email,
            'admin_temp_gmail' => $recipientGmail,
            'admin_temp_otp' => $otp,
            'admin_temp_expires' => $expiresAt,
            'admin_temp_step' => 'otp'
        ]);

        // Send OTP email
        try {
            Mail::send('emails.admin_otp', ['otp' => $otp, 'email' => $email], function ($message) use ($recipientGmail) {
                $message->to($recipientGmail)
                    ->subject('Kode Keamanan OTP Admin - TurningCode');
            });
            Log::info("Admin OTP sent to {$recipientGmail}: {$otp}");
        } catch (\Exception $e) {
            Log::error("Failed to send Admin OTP email to {$recipientGmail}: " . $e->getMessage());
            // Store fallback flag to show developers in OTP page if needed
            session(['admin_otp_mail_failed' => true]);
        }

        return redirect()->route('admin.otp')->with('success', 'Kode OTP telah dikirimkan ke Gmail terhubung.');
    }

    public function showOtp()
    {
        if (session('admin_authenticated') === true) {
            return redirect()->route('admin.dashboard');
        }

        if (!session()->has('admin_temp_step') || session('admin_temp_step') !== 'otp') {
            return redirect()->route('admin.login');
        }

        return view('admin.otp');
    }

    public function submitOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6'
        ]);

        if (!session()->has('admin_temp_otp') || !session()->has('admin_temp_expires')) {
            return redirect()->route('admin.login')->with('error', 'Sesi telah kedaluwarsa. Silakan login kembali.');
        }

        $savedOtp = session('admin_temp_otp');
        $expiresAt = session('admin_temp_expires');

        if (Carbon::now()->greaterThan($expiresAt)) {
            return redirect()->route('admin.login')->with('error', 'Kode OTP sudah kedaluwarsa. Silakan ajukan ulang.');
        }

        if ($request->otp !== (string)$savedOtp) {
            return back()->withErrors(['otp' => 'Kode OTP yang Anda masukkan salah.']);
        }

        // Advance to reCAPTCHA step
        session(['admin_temp_step' => 'recaptcha']);

        return redirect()->route('admin.recaptcha')->with('success', 'OTP terverifikasi. Silakan selesaikan reCAPTCHA.');
    }

    public function showRecaptcha()
    {
        if (session('admin_authenticated') === true) {
            return redirect()->route('admin.dashboard');
        }

        if (!session()->has('admin_temp_step') || session('admin_temp_step') !== 'recaptcha') {
            return redirect()->route('admin.login');
        }

        return view('admin.recaptcha');
    }

    public function submitRecaptcha(Request $request)
    {
        $isLocal = app()->environment('local') || env('APP_ENV') === 'local' || config('app.env') === 'local';

        $request->validate([
            'g-recaptcha-response' => $isLocal ? 'nullable' : 'required'
        ]);

        if (!session()->has('admin_temp_step') || session('admin_temp_step') !== 'recaptcha') {
            return redirect()->route('admin.login');
        }

        $recaptchaResponse = $request->input('g-recaptcha-response');
        $isValid = false;

        if ($isLocal) {
            // Auto bypass in local/development environment to guarantee absolute reliability
            $isValid = true;
            Log::info("reCAPTCHA verification bypassed automatically in local environment.");
        } else {
            $secretKey = '6LeIxAcTAAAAAGG-vFI1dfgP-tAboicFD8V4s53e'; // Google reCAPTCHA v2 Test Secret Key
            // Call Google API to verify token
            try {
                $response = Http::timeout(4)->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $secretKey,
                    'response' => $recaptchaResponse,
                    'remoteip' => $request->ip()
                ]);

                if ($response->successful()) {
                    $result = $response->json();
                    if (isset($result['success']) && $result['success'] === true) {
                        $isValid = true;
                    }
                }
            } catch (\Exception $e) {
                Log::error("reCAPTCHA validation failed: " . $e->getMessage());
            }
        }

        if ($isValid) {
            // Successfully authenticated
            session([
                'admin_authenticated' => true,
                'admin_email' => session('admin_temp_email')
            ]);

            // Clear temp session keys
            session()->forget([
                'admin_temp_email',
                'admin_temp_gmail',
                'admin_temp_otp',
                'admin_temp_expires',
                'admin_temp_step',
                'admin_otp_mail_failed'
            ]);

            return redirect()->route('admin.dashboard')->with('success', 'Selamat datang di Panel Admin TurningCode!');
        }

        return back()->withErrors(['recaptcha' => 'Verifikasi reCAPTCHA gagal. Silakan coba kembali.']);
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['admin_authenticated', 'admin_email']);
        return redirect()->route('admin.login')->with('success', 'Anda telah berhasil keluar dari panel admin.');
    }
}
