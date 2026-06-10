<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator - TurnCode</title>
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

        <!-- Right Side: Minimalist Admin Login Form -->
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

                <h1 class="auth-title">Admin Login</h1>
                <div class="auth-subtitle-row" style="max-width: 320px; line-height: 1.5; margin-bottom: 2rem;">
                    Admin Workspace
                </div>

                <!-- Session / Flash Alerts -->
                @if(session('success'))
                    <div class="status-alert-minimal">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink:0;">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22 4 12 14.01 9 11.01"/>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if(session('error'))
                    <div class="error-message-minimal" style="margin-bottom: 1.5rem; justify-content: center; width: 100%;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        <span>{{ session('error') }}</span>
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

                <form action="{{ route('admin.login.submit') }}" method="POST" style="width: 100%;">
                    @csrf

                    <!-- Email Field -->
                    <div class="form-group-minimal @error('email') has-error @enderror">
                        <label class="field-label" for="email">Email Administrator</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nama@turncode.com" required class="field-input">
                    </div>
                    @error('email')
                        <div class="error-message-minimal">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span>{{ $message }}</span>
                        </div>
                    @enderror

                    <!-- Submit Button -->
                    <button type="submit" class="btn-minimal-submit" style="display: flex; gap: 8px;">
                        <span>Masuk</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </button>
                </form>

                <!-- Back Link -->
                <a href="{{ route('home') }}" class="back-to-login-btn" style="margin-top: 1.5rem; width: 100%;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Kembali ke Landing Page</span>
                </a>
            </div>
        </div>
    </div>

</body>

</html>
