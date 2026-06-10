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
                        @php
                        $interests = [
                            [
                                'val' => 'web-dev',
                                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>',
                                'name' => 'Web Development',
                                'desc' => 'HTML, CSS, JS, React, Laravel'
                            ],
                            [
                                'val' => 'game-dev',
                                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="6" y1="12" x2="10" y2="12"/><line x1="8" y1="10" x2="8" y2="14"/><line x1="15" y1="13" x2="15.01" y2="13"/><line x1="18" y1="11" x2="18.01" y2="11"/><rect x="2" y="6" width="20" height="12" rx="3"/></svg>',
                                'name' => 'Game Development',
                                'desc' => 'Unity, Godot, C#, GDScript'
                            ],
                            [
                                'val' => 'mobile-dev',
                                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>',
                                'name' => 'Mobile Development',
                                'desc' => 'Flutter, React Native'
                            ],
                            [
                                'val' => 'data-science',
                                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 15h3M1 9h3M1 15h3"/></svg>',
                                'name' => 'Data Science & AI',
                                'desc' => 'Python, ML, TensorFlow'
                            ],
                            [
                                'val' => 'cloud-devops',
                                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.5 19A5.5 5.5 0 0 0 22 13.5A5.5 5.5 0 0 0 16.5 8h-.5A7 7 0 1 0 2 13.5A5.5 5.5 0 0 0 7.5 19z"/></svg>',
                                'name' => 'Cloud & DevOps',
                                'desc' => 'AWS, Docker, CI/CD'
                            ],
                            [
                                'val' => 'cybersecurity',
                                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
                                'name' => 'Cybersecurity',
                                'desc' => 'Ethical hacking, Security'
                            ],
                            [
                                'val' => 'ui-ux',
                                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12c0 5.5228 4.47715 10 10 10z"/><circle cx="7.5" cy="10.5" r="1"/><circle cx="11.5" cy="7.5" r="1"/><circle cx="16.5" cy="9.5" r="1"/><circle cx="15.5" cy="14.5" r="1.5"/></svg>',
                                'name' => 'UI/UX Design',
                                'desc' => 'Figma, Design Systems'
                            ],
                            [
                                'val' => 'blockchain',
                                'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>',
                                'name' => 'Blockchain',
                                'desc' => 'Web3, Solidity, DApps'
                            ],
                        ];
                        @endphp
                        
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
