<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - TurnCode</title>
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

        <!-- Right Side: Minimalist Verify Email Panel -->
        <div class="auth-right">
            <div class="auth-form-container">
                <!-- Step Indicator -->
                <div class="minimal-steps-indicator">
                    <div class="minimal-step-line done"></div>
                    <div class="minimal-step-line active"></div>
                    <div class="minimal-step-line"></div>
                    <div class="minimal-step-line"></div>
                    <div class="minimal-step-line"></div>
                </div>

                <h1 class="auth-title">Cek Emailmu!</h1>

                <div class="verify-email-text">
                    Kami sudah mengirim link verifikasi ke alamat email
                </div>

                <div class="verify-email-highlight">
                    {{ auth()->user()->email }}
                </div>

                <div class="verify-email-text" style="margin-bottom: 1.5rem;">
                    Klik link di email tersebut untuk mengaktifkan akun dan lanjut ke langkah berikutnya.
                </div>

                <!-- Session Status / Alert -->
                @if (session('status') == 'verification-link-sent')
                    <div class="status-alert-minimal">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            style="flex-shrink:0;">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                            <polyline points="22 4 12 14.01 9 11.01" />
                        </svg>
                        <span>Link verifikasi baru berhasil dikirim! Silakan periksa inbox Anda.</span>
                    </div>
                @endif

                <!-- Tips Box -->
                <div class="minimal-tips-box">
                    <div class="minimal-tips-title">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path
                                d="M15 14c.2-1 .7-1.7 1.5-2.5 1-.9 1.5-2.2 1.5-3.5A5 5 0 0 0 8 8c0 1 .3 2.2 1.5 3.5.7.7 1.3 1.5 1.5 2.5M9 18h6M10 22h4" />
                        </svg>
                        <span>Tips Penting</span>
                    </div>
                    <div class="minimal-tip-item">
                        <svg class="minimal-tip-icon" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                        </svg>
                        <span>Cek folder <strong>Spam</strong> atau <strong>Promosi</strong> jika tidak ada di
                            inbox.</span>
                    </div>
                    <div class="minimal-tip-item">
                        <svg class="minimal-tip-icon" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                        <span>Link berlaku selama <strong>60 menit</strong> setelah dikirim.</span>
                    </div>
                    <div class="minimal-tip-item">
                        <svg class="minimal-tip-icon" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67" />
                        </svg>
                        <span>Jika belum terima, klik tombol kirim ulang di bawah.</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('verification.send') }}" style="width: 100%;">
                    @csrf
                    <button type="submit" class="btn-minimal-submit">Kirim Ulang Email</button>
                </form>

                <form method="POST" action="{{ route('logout') }}" style="width: 100%;">
                    @csrf
                    <button type="submit" class="back-to-login-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span>Keluar Dari Akun</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

</body>

</html>