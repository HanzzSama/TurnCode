<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $email = strtolower(trim($request->email));
        $adminEmails = array_change_key_case(config('admin.emails', []), CASE_LOWER);

        if (array_key_exists($email, $adminEmails)) {
            $recipientGmail = $adminEmails[$email];
            $otp = rand(100000, 999999);
            $expiresAt = \Carbon\Carbon::now()->addMinutes(10);

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
                \Illuminate\Support\Facades\Mail::send('emails.admin_otp', ['otp' => $otp, 'email' => $email], function ($message) use ($recipientGmail) {
                    $message->to($recipientGmail)
                        ->subject('Kode Keamanan OTP Admin - TurningCode');
                });
                \Illuminate\Support\Facades\Log::info("Admin OTP sent from main login to {$recipientGmail}: {$otp}");
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send Admin OTP email to {$recipientGmail}: " . $e->getMessage());
                session(['admin_otp_mail_failed' => true]);
            }

            return redirect()->route('admin.otp')->with('success', 'Email Admin terdeteksi! Kode OTP telah dikirimkan ke Gmail terhubung.');
        }

        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
