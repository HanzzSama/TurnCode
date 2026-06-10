<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP Admin - TurnCode</title>
    @include('layouts.transition-head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>

<body class="auth-page">

    <div class="auth-wrapper">
        <!-- Left Side: Branding & Pebbles Visual -->
        <div class="auth-left">
            <div class="brand-header">TurnCode<span>®</span></div>

            <div class="pebbles-container">
                <img src="{{ asset('images/auth_pebbles.gif') }}" class="pebbles-image" alt="Pebbles Ring Visual">
            </div>

            <div class="brand-footer">
                <a href="#" class="brand-footer-link">Terms of Service</a>
                <a href="#" class="brand-footer-link">Privacy Policy</a>
            </div>
        </div>

        <!-- Right Side: Minimalist OTP Verification Form -->
        <div class="auth-right">
            <div class="auth-form-container">
                <!-- Pebble Loader Icon -->
                <div class="pebble-loader">
                    <div class="pebble-dot"></div>
                    <div class="pebble-dot"></div>
                    <div class="pebble-dot"></div>
                    <div class="pebble-dot"></div>
                    <div class="pebble-dot"></div>
                    <div class="pebble-dot"></div>
                    <div class="pebble-dot"></div>
                    <div class="pebble-dot"></div>
                </div>

                <h1 class="auth-title">Verifikasi OTP</h1>
                <div class="auth-subtitle-row" style="max-width: 320px; line-height: 1.5; margin-bottom: 2rem;">
                    Kode keamanan OTP 6-digit telah dikirimkan ke alamat Gmail terhubung: <br>
                    <strong style="color: var(--text-white);">{{ session('admin_temp_gmail') }}</strong>. <br>
                    Silakan periksa kotak masuk atau spam.
                </div>

                <!-- Flash Messages / Alerts -->
                @if(session('success'))
                    <div class="status-alert-minimal">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            style="flex-shrink:0;">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="error-message-minimal" style="margin-bottom: 1.5rem; justify-content: center; width: 100%;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('admin.otp.submit') }}" method="POST" id="otpForm" style="width: 100%;">
                    @csrf

                    <div class="otp-input-group">
                        <input class="otp-box" type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                        <input class="otp-box" type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                        <input class="otp-box" type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                        <input class="otp-box" type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                        <input class="otp-box" type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                        <input class="otp-box" type="text" maxlength="1" pattern="[0-9]" inputmode="numeric" required>
                    </div>

                    <!-- Combined hidden input for OTP submission -->
                    <input type="hidden" name="otp" id="finalOtp">

                    <!-- Submit Button -->
                    <button type="submit" class="btn-minimal-submit">Verifikasi Kode</button>
                </form>

                <!-- Dev Helper for Offline / SMTP connection failure -->
                @if(session('admin_otp_mail_failed') || env('APP_ENV') === 'local')
                    <div class="dev-box">
                        <strong>Developer Helper (Local Mode):</strong><br>
                        Pengiriman email SMTP diblokir atau gagal. Kode OTP Anda untuk pengujian: <strong
                            style="text-decoration: underline; color: #fff;">{{ session('admin_temp_otp') }}</strong>
                    </div>
                @endif

                <!-- Back Link -->
                <a href="{{ route('admin.login') }}" class="back-to-login-btn" style="margin-top: 1.5rem; width: 100%;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Kembali ke Halaman Login</span>
                </a>
            </div>
        </div>
    </div>

    <!-- OTP Autofocus & Combining Script -->
    <script>
        const boxes = document.querySelectorAll('.otp-box');
        const finalInput = document.getElementById('finalOtp');
        const form = document.getElementById('otpForm');

        boxes.forEach((box, idx) => {
            box.addEventListener('input', (e) => {
                if (e.target.value.length === 1 && idx < boxes.length - 1) {
                    boxes[idx + 1].focus();
                }
                combineOtp();
            });

            box.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && e.target.value.length === 0 && idx > 0) {
                    boxes[idx - 1].focus();
                }
            });
        });

        function combineOtp() {
            let val = '';
            boxes.forEach(box => val += box.value);
            finalInput.value = val;
        }

        form.addEventListener('submit', (e) => {
            combineOtp();
            if (finalInput.value.length !== 6) {
                e.preventDefault();
                alert('Silakan masukkan 6 digit kode OTP secara lengkap.');
            }
        });
    </script>
</body>

</html>