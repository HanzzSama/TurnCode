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
                        @php
                        $focusMap = [
                            'web-dev' => [
                                ['val'=>'frontend','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="9" y1="9" x2="21" y2="9"/></svg>','name'=>'Frontend Dev','desc'=>'Tampilan & interaksi user','tags'=>['HTML','CSS','JS','React']],
                                ['val'=>'backend','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/><path d="M3 12c0 1.66 4 3 9 3s9-1.34 9-3"/></svg>','name'=>'Backend Dev','desc'=>'Server, API & database','tags'=>['PHP','Node.js','Laravel']],
                                ['val'=>'fullstack','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polygon points="2 17 12 22 22 17"/><polygon points="2 12 12 17 22 12"/></svg>','name'=>'Fullstack Dev','desc'=>'Frontend + Backend sekaligus','tags'=>['React','Laravel','MySQL']],
                                ['val'=>'ui-ux','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>','name'=>'UI/UX Web','desc'=>'Desain antarmuka web','tags'=>['Figma','CSS','Animation']],
                            ],
                            'game-dev' => [
                                ['val'=>'unity-dev','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="6" y1="12" x2="10" y2="12"/><line x1="8" y1="10" x2="8" y2="14"/><line x1="15" y1="13" x2="15.01" y2="13"/><line x1="18" y1="11" x2="18.01" y2="11"/><rect x="2" y="6" width="20" height="12" rx="3"/></svg>','name'=>'Unity Developer','desc'=>'Game 2D & 3D dengan Unity','tags'=>['C#','Unity','2D/3D']],
                                ['val'=>'godot-dev','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/><line x1="12" y1="2" x2="12" y2="8"/></svg>','name'=>'Godot Developer','desc'=>'Game open-source dengan Godot','tags'=>['GDScript','Godot']],
                                ['val'=>'game-design','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>','name'=>'Game Designer','desc'=>'Desain mekanik & level game','tags'=>['Design','Level Design']],
                                ['val'=>'game-art','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>','name'=>'Game Artist','desc'=>'Aset visual untuk game','tags'=>['Pixel Art','Blender']],
                            ],
                            'mobile-dev' => [
                                ['val'=>'flutter','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>','name'=>'Flutter','desc'=>'Satu kode iOS & Android','tags'=>['Dart','Flutter','Firebase']],
                                ['val'=>'react-native','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="12" rx="10" ry="4" transform="rotate(45 12 12)"/><ellipse cx="12" cy="12" rx="10" ry="4" transform="rotate(-45 12 12)"/><circle cx="12" cy="12" r="1.5"/></svg>','name'=>'React Native','desc'=>'Mobile dengan React','tags'=>['JS','React','Expo']],
                                ['val'=>'android','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="9" width="16" height="11" rx="2"/><path d="M9 9V5a3 3 0 0 1 6 0v4"/><circle cx="8" cy="14" r="1"/><circle cx="16" cy="14" r="1"/></svg>','name'=>'Android Native','desc'=>'Aplikasi Android murni','tags'=>['Kotlin','Android Studio']],
                                ['val'=>'ios','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20.94c1.38-1.35 1.79-2.31 2.5-3.32.72.69 1.5 1 2.5 1s1.5-1 2.5-1.5c-1-1.5-1.5-2-1.5-3.5 0-2 1.5-3.5 2.5-4C19 8 17 8 16 9c-1-1.5-2-1.5-3.5-1.5S10 8 9 9c-1-1-3-1-4.5.5C5.5 10 7 11.5 7 13.5c0 1.5-.5 2-1.5 3.5 1 .5 1.5 1.5 2.5 1.5s1.78-.31 2.5-1c.71 1.01 1.12 1.97 2.5 3.44z"/><path d="M12 7.5V2"/></svg>','name'=>'iOS Native','desc'=>'Aplikasi Apple native','tags'=>['Swift','Xcode']],
                            ],
                            'data-science' => [
                                ['val'=>'data-analyst','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>','name'=>'Data Analyst','desc'=>'Analisis & visualisasi data','tags'=>['Python','Pandas','Tableau']],
                                ['val'=>'ml-engineer','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96-.44 2.5 2.5 0 0 1 0-3.12 3 3 0 0 1 0-3.88 2.5 2.5 0 0 1 0-3.12A2.5 2.5 0 0 1 9.5 2z"/><path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96-.44 2.5 2.5 0 0 0 0-3.12 3 3 0 0 0 0-3.88 2.5 2.5 0 0 0 0-3.12A2.5 2.5 0 0 0 14.5 2z"/></svg>','name'=>'ML Engineer','desc'=>'Membangun model ML','tags'=>['TensorFlow','PyTorch']],
                                ['val'=>'data-engineer','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>','name'=>'Data Engineer','desc'=>'Pipeline & infrastruktur data','tags'=>['Spark','Kafka','SQL']],
                                ['val'=>'ai-researcher','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="10" rx="2"/><circle cx="12" cy="5" r="2"/><path d="M12 7v4M8 15h.01M16 15h.01"/></svg>','name'=>'AI Researcher','desc'=>'Penelitian kecerdasan buatan','tags'=>['Deep Learning','NLP']],
                            ],
                            'cloud-devops' => [
                                ['val'=>'aws','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.5 19A5.5 5.5 0 0 0 22 13.5A5.5 5.5 0 0 0 16.5 8h-.5A7 7 0 1 0 2 13.5A5.5 5.5 0 0 0 7.5 19z"/></svg>','name'=>'AWS Cloud','desc'=>'Layanan cloud Amazon','tags'=>['AWS','EC2','S3','Lambda']],
                                ['val'=>'devops','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 2.086a10 10 0 1 0 5 8.914M22 3v5h-5"/></svg>','name'=>'DevOps','desc'=>'CI/CD & otomasi deployment','tags'=>['Docker','Jenkins','GitHub Actions']],
                                ['val'=>'kubernetes','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>','name'=>'Kubernetes','desc'=>'Orkestrasi container','tags'=>['K8s','Helm','Prometheus']],
                                ['val'=>'sre','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.55a11 11 0 0 1 14.08 0M1.42 9a16 16 0 0 1 21.16 0M8.59 16.11a6 6 0 0 1 6.82 0M12 20h.01"/></svg>','name'=>'SRE','desc'=>'Reliabilitas sistem skala besar','tags'=>['Monitoring','SLO','Chaos']],
                            ],
                            'cybersecurity' => [
                                ['val'=>'pentest','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>','name'=>'Penetration Testing','desc'=>'Uji keamanan sistem','tags'=>['Kali Linux','Metasploit']],
                                ['val'=>'appsec','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>','name'=>'AppSec','desc'=>'Keamanan aplikasi','tags'=>['OWASP','Burp Suite']],
                                ['val'=>'netsec','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"/><rect x="2" y="14" width="20" height="8" rx="2" ry="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg>','name'=>'Network Security','desc'=>'Keamanan jaringan','tags'=>['Wireshark','Firewall']],
                                ['val'=>'soc','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>','name'=>'SOC Analyst','desc'=>'Monitoring ancaman siber','tags'=>['SIEM','Incident Response']],
                            ],
                            'ui-ux' => [
                                ['val'=>'ui-design','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12c0 5.5228 4.47715 10 10 10z"/><circle cx="7.5" cy="10.5" r="1"/><circle cx="11.5" cy="7.5" r="1"/><circle cx="16.5" cy="9.5" r="1"/><circle cx="15.5" cy="14.5" r="1.5"/></svg>','name'=>'UI Design','desc'=>'Desain antarmuka visual','tags'=>['Figma','Design System']],
                                ['val'=>'ux-research','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>','name'=>'UX Research','desc'=>'Riset pengalaman pengguna','tags'=>['User Testing','Prototype']],
                                ['val'=>'motion','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>','name'=>'Motion Design','desc'=>'Animasi & micro-interaction','tags'=>['After Effects','Framer']],
                                ['val'=>'product-design','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><polygon points="12 22.08 12 12 3 6.92 3 17.08 12 22.08"/><polygon points="12 22.08 21 17.08 21 6.92 12 12 12 22.08"/><polygon points="12 12 21 6.92 12 1.84 3 6.92 12 12"/></svg>','name'=>'Product Design','desc'=>'Desain produk end-to-end','tags'=>['Strategy','Wireframe']],
                            ],
                            'blockchain' => [
                                ['val'=>'smart-contract','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>','name'=>'Smart Contract','desc'=>'Kontrak pintar Ethereum','tags'=>['Solidity','EVM','Hardhat']],
                                ['val'=>'dapp','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>','name'=>'DApp Developer','desc'=>'Aplikasi terdesentralisasi','tags'=>['Web3.js','React','IPFS']],
                                ['val'=>'defi','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8"/><line x1="12" y1="6" x2="12" y2="18"/></svg>','name'=>'DeFi','desc'=>'Keuangan terdesentralisasi','tags'=>['Uniswap','Yield','AMM']],
                                ['val'=>'nft','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><rect x="7" y="7" width="10" height="10"/></svg>','name'=>'NFT & Web3','desc'=>'NFT marketplace & tokens','tags'=>['ERC-721','OpenSea','Metadata']],
                            ],
                        ];
                        
                        $currentFocuses = $focusMap[$interest] ?? [
                            ['val'=>'general','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M4 4.5A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1-2.5-2.5v-15z"/></svg>','name'=>'General','desc'=>'Belajar secara umum','tags'=>['Fundamentals']],
                            ['val'=>'applied','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>','name'=>'Applied','desc'=>'Langsung terapkan','tags'=>['Practice']],
                            ['val'=>'research','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18h8M3 22h18M14 22a7 7 0 1 0 0-14h-1M14 14h2M9 14h2M12 2v6M12 5h6"/></svg>','name'=>'Research','desc'=>'Riset mendalam','tags'=>['Theory']],
                            ['val'=>'project','icon'=>'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4.5 16.5c-1.5 1.25-2.5 3.5-2.5 3.5s2.25-1 3.5-2.5L16.5 6.5c1-1 1.5-2.5 1.5-2.5s-1.5.5-2.5 1.5L4.5 16.5z"/><path d="M12 5l3 3M9 8l3 3M3.5 20.5l1-1"/></svg>','name'=>'Project Based','desc'=>'Belajar via proyek','tags'=>['Hands-on']],
                        ];
                        @endphp
                        
                        @foreach($currentFocuses as $f)
                        <div class="choice-item">
                            <input type="radio" name="focus" id="foc_{{ $f['val'] }}" value="{{ $f['val'] }}" {{ old('focus') == $f['val'] ? 'checked' : '' }}>
                            <label class="choice-label" for="foc_{{ $f['val'] }}">
                                <span class="choice-icon">{!! $f['icon'] !!}</span>
                                <span class="choice-title">{{ $f['name'] }}</span>
                                <span class="choice-desc">{{ $f['desc'] }}</span>
                                
                                <div class="focus-tags">
                                    @foreach($f['tags'] as $t)
                                        <span class="focus-tag">{{ $t }}</span>
                                    @endforeach
                                </div>
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
