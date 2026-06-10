<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - TurnCode</title>
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

        <!-- Right Side: Minimalist Forgot Password Form -->
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

                <h1 class="auth-title">Reset Password</h1>
                <div class="auth-subtitle-row" style="max-width: 320px; line-height: 1.5; margin-bottom: 2rem;">
                    Masukkan alamat email Anda untuk menerima link atur ulang sandi.
                </div>

                <!-- Session Status / Alert -->
                @if (session('status'))
                    <div class="status-alert-minimal">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            style="flex-shrink:0;">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" style="width: 100%;">
                    @csrf

                    <!-- Email Field -->
                    <div class="form-group-minimal @error('email') has-error @enderror">
                        <label class="field-label" for="email">Email address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            placeholder="name@email.com" required class="field-input">
                    </div>
                    @error('email')
                        <div class="error-message-minimal">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror

                    <!-- Submit Button -->
                    <button type="submit" class="btn-minimal-submit">Kirim Link Reset</button>
                </form>

                <!-- Back to Login Link -->
                <a href="{{ route('login') }}" class="back-to-login-btn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Kembali ke Halaman Masuk</span>
                </a>
            </div>
        </div>
    </div>

</body>

</html>