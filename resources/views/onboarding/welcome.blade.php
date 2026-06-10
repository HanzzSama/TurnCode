<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - TurnCode</title>
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

        <!-- Right Side: Welcome Panel -->
        <div class="auth-right" style="padding: 3rem 2rem;">
            <div class="auth-form-container" style="max-width: 540px;">
                <!-- Step Indicator -->
                <div class="minimal-steps-indicator">
                    <div class="minimal-step-line done"></div>
                    <div class="minimal-step-line done"></div>
                    <div class="minimal-step-line done"></div>
                    <div class="minimal-step-line done"></div>
                    <div class="minimal-step-line active"></div>
                </div>

                <span class="emoji-hero">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </span>

                <h1 class="auth-title" style="margin-bottom: 0.5rem; line-height: 1.25;">
                    Selamat Datang,<br><span style="background: linear-gradient(135deg, #fff 30%, #716e75 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ $user->name }}!</span>
                </h1>
                
                <div class="auth-subtitle-row" style="max-width: 420px; line-height: 1.5; margin-bottom: 1.5rem;">
                    Profilmu sudah siap! Kami telah menyiapkan jalur belajar yang dipersonalisasi khusus untukmu.
                </div>

                <!-- Selected Path Summary -->
                <div class="welcome-summary">
                    <div class="welcome-pill">
                        <div class="dot"></div>
                        <span>{{ ucwords(str_replace('-', ' ', $user->interest)) }}</span>
                    </div>
                    <div class="welcome-pill">
                        <div class="dot"></div>
                        <span>{{ ucwords(str_replace('-', ' ', $user->focus)) }}</span>
                    </div>
                </div>

                <!-- Features Preview Grid -->
                <div class="welcome-preview-grid">
                    <div class="preview-card">
                        <span class="preview-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M4 4.5A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5v-15z"/></svg>
                        </span>
                        <div class="preview-info">
                            <strong>Kurikulum Personal</strong>
                            <span>Materi disesuaikan dengan jalurmu</span>
                        </div>
                    </div>
                    <div class="preview-card">
                        <span class="preview-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                        </span>
                        <div class="preview-info">
                            <strong>Langsung Mulai</strong>
                            <span>Tidak perlu setup rumit</span>
                        </div>
                    </div>
                    <div class="preview-card">
                        <span class="preview-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6"/><path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18"/><path d="M4 22h16"/><path d="M10 14.66V17c0 .55-.45 1-1 1H4v2h16v-2h-5c-.55 0-1-.45-1-1v-2.34"/><path d="M12 2a4 4 0 0 0-4 4v7h8V6a4 4 0 0 0-4-4z"/></svg>
                        </span>
                        <div class="preview-info">
                            <strong>Sistem Gamifikasi</strong>
                            <span>Kumpulkan poin & badge</span>
                        </div>
                    </div>
                    <div class="preview-card">
                        <span class="preview-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        </span>
                        <div class="preview-info">
                            <strong>Komunitas</strong>
                            <span>Belajar bersama ribuan member</span>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <a href="{{ route('dashboard') }}" class="btn-minimal-submit" style="width: 100%; text-decoration: none; display: flex; gap: 8px;">
                    <span>Masuk ke Dashboard</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Confetti Rain Script -->
    <script>
        function createParticle() {
            const p = document.createElement('div');
            p.className = 'particle';
            const size = Math.random() * 8 + 5;
            const colors = ['#8b5cf6', '#3b82f6', '#06b6d4', '#10b981', '#ec4899', '#f59e0b', '#ffffff'];
            const randomColor = colors[Math.floor(Math.random() * colors.length)];
            
            p.style.cssText = `
                width: ${size}px;
                height: ${size}px;
                left: ${Math.random() * 100}vw;
                background: ${randomColor};
                animation-duration: ${Math.random() * 3 + 2.5}s;
                animation-delay: ${Math.random() * 0.5}s;
                border-radius: ${Math.random() > 0.5 ? '50%' : '3px'};
            `;
            document.body.appendChild(p);
            setTimeout(() => p.remove(), 5000);
        }
        
        // Emit confetti
        for(let i = 0; i < 70; i++) {
            setTimeout(createParticle, i * 75);
        }
    </script>
</body>
</html>
