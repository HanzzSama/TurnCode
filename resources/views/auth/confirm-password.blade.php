<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Password - TurnCode</title>
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
                <img src="{{ asset('images/auth_pebbles.png') }}" class="pebbles-image" alt="Pebbles Ring Visual">
            </div>

            <div class="brand-footer">
                <a href="#" class="brand-footer-link">Terms of Service</a>
                <a href="#" class="brand-footer-link">Privacy Policy</a>
            </div>
        </div>

        <!-- Right Side: Minimalist Confirm Password Form -->
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

                <h1 class="auth-title">Konfirmasi Sandi</h1>
                <div class="auth-subtitle-row" style="max-width: 320px; line-height: 1.5; margin-bottom: 2rem;">
                    Ini adalah area aplikasi yang aman. Silakan konfirmasi password Anda terlebih dahulu sebelum melanjutkan.
                </div>

                <form method="POST" action="{{ route('password.confirm') }}" style="width: 100%;">
                    @csrf

                    <!-- Password Field -->
                    <div class="form-group-minimal @error('password') has-error @enderror">
                        <label class="field-label" for="password">Password</label>
                        <div class="field-input-wrapper">
                            <input type="password" id="password" name="password" placeholder="••••••••" required autocomplete="current-password" class="field-input">
                            <button type="button" class="password-toggle" onclick="togglePasswordVisibility('password', this)" aria-label="Tampilkan sandi">
                                <svg class="eye-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @error('password')
                        <div class="error-message-minimal">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror

                    <!-- Submit Button -->
                    <button type="submit" class="btn-minimal-submit">Konfirmasi</button>
                </form>

                <!-- Back to Home Button -->
                <a href="/" class="back-to-login-btn" style="margin-top: 1.5rem; width: 100%;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Password visibility toggle script -->
    <script>
        function togglePasswordVisibility(fieldId, button) {
            const field = document.getElementById(fieldId);
            if (!field) return;
            const isPassword = field.type === 'password';
            field.type = isPassword ? 'text' : 'password';
            
            button.innerHTML = isPassword ? `
                <svg class="eye-off-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                    <line x1="1" y1="1" x2="23" y2="23"/>
                </svg>
            ` : `
                <svg class="eye-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                </svg>
            `;
        }
    </script>
</body>
</html>
