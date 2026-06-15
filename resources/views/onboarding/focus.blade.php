<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Fokusmu - TurnCode</title>
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

        <!-- Right Side: Onboarding Focus Form -->
        <div class="auth-right" style="padding: 3rem 2rem;">
            <div class="auth-form-container" style="max-width: 540px;">
                <!-- Step Indicator -->
                <div class="minimal-steps-indicator">
                    <div class="minimal-step-line done"></div>
                    <div class="minimal-step-line done"></div>
                    <div class="minimal-step-line done"></div>
                    <div class="minimal-step-line active"></div>
                    <div class="minimal-step-line"></div>
                </div>

                <h1 class="auth-title" style="margin-bottom: 0.5rem;">Pilih Fokusmu</h1>
                
                <div style="margin-bottom: 1rem;">
                    <div class="badge-pill">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-top:-2px;">
                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                        </svg>
                        <span>{{ ucwords(str_replace('-', ' ', $interest)) }}</span>
                    </div>
                </div>

                <div class="auth-subtitle-row" style="max-width: 420px; line-height: 1.5; margin-bottom: 2rem;">
                    Sekarang, mana spesialisasi yang paling ingin kamu kuasai?
                </div>

                <!-- Session Alert -->
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

                <form method="POST" action="{{ route('onboarding.focus.store') }}" id="focusForm" style="width: 100%;">
                    @csrf
                    
                    <div class="choices-grid" style="grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));">
                        @foreach($fokusList as $f)
                        <div class="choice-item">
                            <input type="radio" name="focus" id="foc_{{ $f->val }}" value="{{ $f->val }}" {{ old('focus') == $f->val ? 'checked' : '' }}>
                            <label class="choice-label" for="foc_{{ $f->val }}">
                                <span class="choice-icon">{!! $f->icon !!}</span>
                                <span class="choice-title">{{ $f->name }}</span>
                                <span class="choice-desc">{{ $f->desc }}</span>
                                
                                @if($f->tags)
                                <div class="focus-tags">
                                    @foreach(explode(',', $f->tags) as $t)
                                        <span class="focus-tag">{{ trim($t) }}</span>
                                    @endforeach
                                </div>
                                @endif
                            </label>
                            <div class="check-badge">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="4">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-minimal-submit" id="btnNext" disabled>Lanjut ke Langkah Terakhir</button>
                </form>

                <!-- Back Link -->
                <a href="{{ route('onboarding.interest') }}" class="back-to-login-btn" style="margin-top: 1.5rem; width: 100%;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    <span>Kembali Pilih Minat</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const btn = document.getElementById('btnNext');
            const radios = document.querySelectorAll('input[name="focus"]');
            const items = document.querySelectorAll('.choice-item');

            function enableBtn() { btn.disabled = false; }

            // Check initial state
            radios.forEach(r => { if (r.checked) enableBtn(); });

            // Listen on radios
            radios.forEach(r => r.addEventListener('change', enableBtn));

            // Backup: listen on entire card click
            items.forEach(item => {
                item.addEventListener('click', function() {
                    const radio = this.querySelector('input[type="radio"]');
                    if (radio) {
                        radio.checked = true;
                        enableBtn();
                    }
                });
            });
        })();
    </script>
</body>
</html>
