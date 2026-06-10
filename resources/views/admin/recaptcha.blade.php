<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Keamanan reCAPTCHA - TurnCode</title>
    @include('layouts.transition-head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
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

        <!-- Right Side: Minimalist Security Verification Form -->
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

                <h1 class="auth-title">Verifikasi Keamanan</h1>
                <div class="auth-subtitle-row" style="max-width: 320px; line-height: 1.5; margin-bottom: 2rem;">
                    Langkah terakhir. Silakan selesaikan pemeriksaan reCAPTCHA di bawah ini untuk mengonfirmasi bahwa Anda bukan robot.
                </div>

                <!-- Flash Messages / Alerts -->
                @if(session('success'))
                    <div class="status-alert-minimal">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="error-message-minimal" style="margin-bottom: 1.5rem; justify-content: center; width: 100%;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                @if(app()->environment('local') || env('APP_ENV') === 'local' || config('app.env') === 'local')
                    <div class="dev-box" style="margin-top: 0; margin-bottom: 1.5rem;">
                        <strong style="color: #fff; display: flex; align-items: center; gap: 6px; font-weight: 700; margin-bottom: 4px;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                <line x1="12" y1="9" x2="12" y2="13"></line>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                            <span>Developer Mode Active</span>
                        </strong>
                        <span style="color: var(--text-gray); font-size: 0.8rem;">
                            Untuk mempermudah pengujian di lingkungan lokal, Anda dapat langsung mengklik tombol di bawah tanpa harus mencentang reCAPTCHA.
                        </span>
                    </div>
                @endif

                <form action="{{ route('admin.recaptcha.submit') }}" method="POST" style="width: 100%;">
                    @csrf
                    
                    <div class="recaptcha-wrapper">
                        <!-- Google reCAPTCHA v2 Widget using Test Site Key -->
                        <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI" data-theme="dark"></div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-minimal-submit" style="display: flex; gap: 8px;">
                        <span>Selesai & Masuk Dashboard</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>
                </form>

                <!-- Back Link -->
                <a href="{{ route('admin.login') }}" class="back-to-login-btn" style="margin-top: 1.5rem; width: 100%;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Kembali ke Halaman Login</span>
                </a>
            </div>
        </div>
    </div>

</body>

</html>
