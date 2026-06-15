<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Minatmu - TurnCode</title>
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

        <!-- Right Side: Onboarding Form -->
        <div class="auth-right" style="padding: 3rem 2rem;">
            <div class="auth-form-container" style="max-width: 540px;">
                <!-- Step Indicator -->
                <div class="minimal-steps-indicator">
                    <div class="minimal-step-line done"></div>
                    <div class="minimal-step-line done"></div>
                    <div class="minimal-step-line active"></div>
                    <div class="minimal-step-line"></div>
                    <div class="minimal-step-line"></div>
                </div>

                <h1 class="auth-title" style="margin-bottom: 0.5rem;">Pilih Minatmu</h1>
                <div class="auth-subtitle-row" style="max-width: 420px; line-height: 1.5; margin-bottom: 2rem;">
                    Pilih minat utamamu. Kami akan menyesuaikan kurikulum dan jalur belajar khusus untukmu.
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

                <form method="POST" action="{{ route('onboarding.interest.store') }}" id="interestForm" style="width: 100%;">
                    @csrf
                    
                    <div class="choices-grid" style="grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));">
                        @foreach($interests as $item)
                        <div class="choice-item">
                            <input type="radio" name="interest" id="int_{{ $item['val'] }}" value="{{ $item['val'] }}" {{ old('interest') == $item['val'] ? 'checked' : '' }}>
                            <label class="choice-label" for="int_{{ $item['val'] }}">
                                <span class="choice-icon">{!! $item['icon'] !!}</span>
                                <span class="choice-title">{{ $item['name'] }}</span>
                                <span class="choice-desc">{{ $item['desc'] }}</span>
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
                    <button type="submit" class="btn-minimal-submit" id="btnNext" disabled>Lanjut ke Fokus Belajar</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Active State Toggle Script -->
    <script>
        (function() {
            const btn = document.getElementById('btnNext');
            const radios = document.querySelectorAll('input[name="interest"]');
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
