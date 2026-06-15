<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard Administrator - TurnCode</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
    <script>
        // Check local storage immediately to avoid FOUC

        (function () {
            const isCollapsed = localStorage.getItem('admin_sidebar_collapsed') === 'true';
            if (isCollapsed) {
                document.documentElement.classList.add('sidebar-collapsed-init');
                document.addEventListener("DOMContentLoaded", function () {
                    document.body.classList.add('sidebar-collapsed');
                    document.documentElement.classList.remove('sidebar-collapsed-init');
                });
            }
        })();
    </script>
</head>
<body>
    <!-- SIDEBAR WRAPPER -->
    <div class="sidebar-wrapper" id="adminSidebar">
        <!-- Main Sidebar Card (Panel 1) -->
        <div class="sidebar-main-card">
            <!-- Brand Section -->
            <div class="brand-card">
                <div class="brand-logo-wrap">
                    <img class="brand-logo-icon" src="{{ asset('images/logo/Logo-TurnCode-white.png') }}"
                        alt="TurnCode" />
                    <span class="brand-name-text">TurnCode</span>
                </div>
                <button class="menu-toggle-btn" type="button" onclick="toggleSidebar()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>
            <!-- Collapsible Content -->
            <div class="sidebar-collapsible-content">
                <ul class="nav-links">
                    <li class="nav-item {{ $activeTab === 'dashboard' ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard', ['tab' => 'dashboard']) }}">
                            <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="7" height="9"></rect>
                                <rect x="14" y="3" width="7" height="5"></rect>
                                <rect x="14" y="12" width="7" height="9"></rect>
                                <rect x="3" y="16" width="7" height="5"></rect>
                            </svg>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item {{ $activeTab === 'interests' ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard', ['tab' => 'interests']) }}">
                            <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                            </svg>
                            <span>Main Materi</span>
                            <span class="nav-badge">{{ $interests->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{ $activeTab === 'fokus' ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard', ['tab' => 'fokus']) }}">
                            <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                                <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                            </svg>
                            <span>Materi</span>
                            <span class="nav-badge">{{ $fokusList->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{ $activeTab === 'submateri' ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard', ['tab' => 'submateri']) }}">
                            <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            <span>Sub Materi</span>
                            <span class="nav-badge">{{ $submateris->count() }}</span>
                        </a>
                    </li>
                    <!-- Horizontal Divider Line -->
                    <li class="nav-divider"></li>
                    <li class="nav-item {{ $activeTab === 'quizzes' ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard', ['tab' => 'quizzes']) }}">
                            <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                            <span>Bank Soal</span>
                            <span class="nav-badge">{{ $quizzes->count() }}</span>
                        </a>
                    </li>
                    <li class="nav-item {{ $activeTab === 'database' ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard', ['tab' => 'database']) }}">
                            <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                                <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                                <path d="M3 12c0 1.66 4 3 9 3s9-1.34 9-3"></path>
                            </svg>
                            <span>Struktur Database</span>
                        </a>
                    </li>
                    <li class="nav-item {{ $activeTab === 'fitur' ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard', ['tab' => 'fitur']) }}">
                            <svg class="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="4" y1="21" x2="4" y2="14"></line>
                                <line x1="4" y1="10" x2="4" y2="3"></line>
                                <line x1="12" y1="21" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12" y2="3"></line>
                                <line x1="20" y1="21" x2="20" y2="16"></line>
                                <line x1="20" y1="12" x2="20" y2="3"></line>
                                <line x1="1" y1="14" x2="7" y2="14"></line>
                                <line x1="9" y1="8" x2="15" y2="8"></line>
                                <line x1="17" y1="16" x2="23" y2="16"></line>
                            </svg>
                            <span>Manajemen Fitur</span>
                        </a>
                    </li>
                </ul>
                <div class="admin-profile-card">
                    <div class="admin-avatar">AD</div>
                    <div class="admin-info">
                        <span class="admin-name">Hanzz</span>
                        <span class="admin-email">{{ session('admin_email', 'hanzz@turncode.com') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Separate Logout Card (Panel 2, floats below Main Card) -->
        <div class="sidebar-logout-card">
            <form action="{{ route('admin.logout') }}" method="POST" class="logout-form">
                @csrf
                <button type="submit" class="logout-btn-outline">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                        <polyline points="16 17 21 12 16 7"></polyline>
                        <line x1="21" y1="12" x2="9" y2="12"></line>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
    <!-- MAIN CONTENT -->
    <div class="main-content">
        <div class="header-section">
            <div>
                <h1 class="welcome-greet">Hi, Admin Hanzz</h1>
                <p class="welcome-sub">welcome back</p>
            </div>
            <div class="header-right-action">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
            </div>
        </div>
        <!-- System Alerts -->
        @if(session('success'))
            <div class="alert-toast alert-toast-success">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="alert-toast alert-toast-error">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        {{-- ═══════════════════════════════════════════════════ --}}
        {{-- TAB: DASHBOARD --}}
        {{-- ═══════════════════════════════════════════════════ --}}
        <div class="tab-panel {{ $activeTab === 'dashboard' ? 'active' : '' }}" id="tab-dashboard">
            <!-- Dashboard Header Row (Stats 2x2 + Peminatan + Chart) -->
            <div class="dashboard-header-row">
                <!-- Stats Grid (2x2) -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <span class="stat-num">{{ sprintf('%03d', $totalUsers) }}</span>
                        <div class="stat-label-group">
                            <span class="stat-label-title">User Account</span>
                            <div class="stat-label-sub">
                                <div class="active-dot"></div>
                                <span>user_actived</span>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <span class="stat-num">{{ sprintf('%03d', $totalChapters) }}</span>
                        <div class="stat-label-group">
                            <span class="stat-label-title">Bab Materi</span>
                            <div class="stat-label-sub">
                                <div class="active-dot"></div>
                                <span>sub_directories</span>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <span class="stat-num">{{ sprintf('%03d', $totalCourses) }}</span>
                        <div class="stat-label-group">
                            <span class="stat-label-title">Core Moduls</span>
                            <div class="stat-label-sub">
                                <div class="active-dot"></div>
                                <span>strukture_moduls</span>
                            </div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <span class="stat-num">{{ sprintf('%03d', $totalUsers) }}</span>
                        <div class="stat-label-group">
                            <span class="stat-label-title">User Count</span>
                            <div class="stat-label-sub">
                                <div class="active-dot"></div>
                                <span>user_auth_0</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Data Peminatan Column -->
                <div class="peminatan-card">
                    <div class="section-header">
                        <h3 class="section-title">Data Peminatan</h3>
                        <p class="section-subtitle">data statistik jumlah peminatan</p>
                    </div>
                    <div class="peminatan-list">
                        <div class="peminatan-item active"
                            onclick="switchInterestChart(this, 'web-dev', 'Web Developer')">Web Developer</div>
                        <div class="peminatan-item" onclick="switchInterestChart(this, 'app-dev', 'App Developer')">App
                            Developer</div>
                        <div class="peminatan-item" onclick="switchInterestChart(this, 'game-dev', 'Game Developer')">
                            Game Developer</div>
                        <div class="peminatan-item" onclick="switchInterestChart(this, 'cyber-sec', 'Cyber Security')">
                            Cyber Security</div>
                    </div>
                </div>
                <!-- Interactive SVG Chart -->
                <div class="chart-card">
                    <div class="section-header">
                        <h3 class="section-title" id="chartInterestTitle">Web Developer</h3>
                    </div>
                    <div class="chart-container">
                        <svg class="chart-svg" viewBox="0 0 600 150">
                            <!-- Helper Lines -->
                            <line x1="0" y1="20" x2="600" y2="20" stroke="rgba(255,255,255,0.02)" stroke-width="1">
                            </line>
                            <line x1="0" y1="50" x2="600" y2="50" stroke="rgba(255,255,255,0.02)" stroke-width="1">
                            </line>
                            <line x1="0" y1="80" x2="600" y2="80" stroke="rgba(255,255,255,0.02)" stroke-width="1">
                            </line>
                            <line x1="0" y1="110" x2="600" y2="110" stroke="rgba(255,255,255,0.02)" stroke-width="1">
                            </line>
                            <!-- Graph Path -->
                            <path class="chart-path" id="interestChartPath"
                                d="M 10 40 L 45 80 L 80 115 L 115 70 L 150 45 L 185 85 L 220 50 L 255 60 L 290 85 L 325 50 L 360 85 L 395 110 L 430 45 L 465 90 L 500 110 L 535 70 L 570 85 L 600 50">
                            </path>
                            <!-- Dots -->
                            <g class="chart-dots" id="chartDots">
                                <circle cx="10" cy="40"></circle>
                                <circle cx="45" cy="80"></circle>
                                <circle cx="80" cy="115"></circle>
                                <circle cx="115" cy="70"></circle>
                                <circle cx="150" cy="45"></circle>
                                <circle cx="185" cy="85"></circle>
                                <circle cx="220" cy="50"></circle>
                                <circle cx="255" cy="60"></circle>
                                <circle cx="290" cy="85"></circle>
                                <circle cx="325" cy="50"></circle>
                                <circle cx="360" cy="85"></circle>
                                <circle cx="395" cy="110"></circle>
                                <circle cx="430" cy="45"></circle>
                                <circle cx="465" cy="90"></circle>
                                <circle cx="500" cy="110"></circle>
                                <circle cx="535" cy="70"></circle>
                                <circle cx="570" cy="85"></circle>
                                <circle cx="600" cy="50"></circle>
                            </g>
                        </svg>
                    </div>
                </div>
            </div>
            <!-- Progress section -->
            <div class="progress-section">
                <div class="progress-card">
                    <div class="progress-icon-box">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                    </div>
                    <div class="progress-card-info">
                        <div class="progress-label-row">
                            <span class="progress-card-title">Materi Publish</span>
                            <span class="progress-card-val">2/9</span>
                        </div>
                        <div class="progress-track-wrapper">
                            <div class="progress-fill" style="width: 30%;">
                                <div class="progress-badge">30%</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="progress-card">
                    <div class="progress-icon-box">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                        </svg>
                    </div>
                    <div class="progress-card-info">
                        <div class="progress-label-row">
                            <span class="progress-card-title">Materi Draft</span>
                            <span class="progress-card-val">7/9</span>
                        </div>
                        <div class="progress-track-wrapper">
                            <div class="progress-fill" style="width: 75%;">
                                <div class="progress-badge">75%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Bottom Row (Laporan + Ulasan User) -->
            <div class="bottom-layout">
                <div class="bottom-card">
                    <div class="section-header">
                        <h3 class="section-title">Laporan</h3>
                        <p class="section-subtitle">Data laporan dari semua user</p>
                    </div>
                    <div class="bottom-list">
                        <div class="bottom-item">
                            <div class="bottom-icon-box">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                </svg>
                            </div>
                            <div class="bottom-item-content">
                                <span class="bottom-item-title">Laporan</span>
                                <span class="bottom-item-desc">Data laporan dari semua user</span>
                            </div>
                        </div>
                        <div class="bottom-item">
                            <div class="bottom-icon-box">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                </svg>
                            </div>
                            <div class="bottom-item-content">
                                <span class="bottom-item-title">Laporan</span>
                                <span class="bottom-item-desc">Data laporan dari semua user</span>
                            </div>
                        </div>
                        <div class="bottom-item">
                            <div class="bottom-icon-box">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                </svg>
                            </div>
                            <div class="bottom-item-content">
                                <span class="bottom-item-title">Laporan</span>
                                <span class="bottom-item-desc">Data laporan dari semua user</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bottom-card">
                    <div class="section-header">
                        <h3 class="section-title">Ulasan User</h3>
                        <p class="section-subtitle">Data ulasan dari semua user</p>
                    </div>
                    <div class="bottom-list">
                        <div class="bottom-item">
                            <div class="bottom-icon-box">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                </svg>
                            </div>
                            <div class="bottom-item-content">
                                <span class="bottom-item-title">Ulasan - hanzz</span>
                                <span class="bottom-item-desc">keren ok</span>
                            </div>
                        </div>
                        <div class="bottom-item">
                            <div class="bottom-icon-box">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                </svg>
                            </div>
                            <div class="bottom-item-content">
                                <span class="bottom-item-title">Ulasan - Vreya</span>
                                <span class="bottom-item-desc">membantu banget lah pokok nya</span>
                            </div>
                        </div>
                        <div class="bottom-item">
                            <div class="bottom-icon-box">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                                    <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                                </svg>
                            </div>
                            <div class="bottom-item-content">
                                <span class="bottom-item-title">Ulasan - Milim</span>
                                <span class="bottom-item-desc">Seru bisa kompe</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- ═══════════════════════════════════════════════════ --}}
        {{-- TAB: INTERESTS (MAIN MATERI) --}}
        {{-- ═══════════════════════════════════════════════════ --}}
        <div class="tab-panel {{ $activeTab === 'interests' ? 'active' : '' }}" id="tab-interests">
            <div class="management-card">
                <div class="section-header" style="margin-bottom: 24px;">
                    <h2 class="section-title">Kelola Main Materi (Interest)</h2>
                    <p class="section-subtitle">Kelola kategori peminatan utama bagi alur onboarding pengguna baru</p>
                </div>
                <div class="management-layout">
                    <!-- Form Input Data (Left Column) -->
                    <div class="form-card-premium">
                        <h3 class="form-card-title" id="interestFormTitle">Tambah Interest Baru</h3>
                        <p class="form-card-subtitle" id="interestFormSubtitle">Masukkan data untuk menambahkan kategori
                            peminatan baru</p>
                        <form action="{{ route('admin.interests.store') }}" method="POST" id="interestForm">
                            @csrf
                            <input type="hidden" name="_method" value="POST" id="interestMethodInput">
                            <div class="form-grid-inputs">
                                <div class="form-group" id="interestValGroup">
                                    <label class="form-label" for="interestInputVal">Value Code (unik)</label>
                                    <input class="form-input" type="text" name="val" id="interestInputVal"
                                        placeholder="e.g. web-dev" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="interestInputName">Nama Peminatan</label>
                                    <input class="form-input" type="text" name="name" id="interestInputName"
                                        placeholder="e.g. Web Development" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="interestInputDesc">Deskripsi Ringkas</label>
                                    <input class="form-input" type="text" name="desc" id="interestInputDesc"
                                        placeholder="e.g. HTML, CSS, JS, React">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="interestInputIcon">Icon (Emoji / SVG)</label>
                                    <input class="form-input" type="text" name="icon" id="interestInputIcon"
                                        placeholder="e.g. 🎨 atau SVG string">
                                </div>
                            </div>
                            <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 8px;">
                                <button type="button" class="btn-modal btn-modal-cancel" id="interestCancelBtn"
                                    onclick="resetInterestForm()"
                                    style="display: none; width: auto; min-width: 100px; padding: 10px 20px;">Batal</button>
                                <button type="submit" class="btn-modal btn-modal-submit"
                                    style="width: auto; min-width: 120px; padding: 10px 20px;">Simpan</button>
                            </div>
                        </form>
                    </div>
                    <!-- List Table (Right Column) -->
                    <div class="table-responsive"
                        style="border: 1px solid var(--border-color); border-radius: 20px; background: rgba(255, 255, 255, 0.01); padding: 16px 20px;">
                        <table class="premium-table">
                            <thead>
                                <tr>
                                    <th>Icon</th>
                                    <th>Nama</th>
                                    <th>Value Code</th>
                                    <th>Deskripsi</th>
                                    <th>Jumlah Fokus</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($interests as $int)
                                    <tr>
                                        <td>
                                            <div
                                                style="font-size: 20px; display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); border-radius: 10px;">
                                                {!! $int->icon ?? '⭐' !!}
                                            </div>
                                        </td>
                                        <td style="font-weight: 700; color: #ffffff;">{{ $int->name }}</td>
                                        <td><span class="badge-pill-accent">{{ $int->val }}</span></td>
                                        <td
                                            style="color: var(--text-muted); max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ $int->desc ?? '-' }}
                                        </td>
                                        <td>
                                            <span class="badge-pill-muted">{{ $int->focusItems->count() }} Fokus</span>
                                        </td>
                                        <td>
                                            <div class="actions-cell">
                                                <button class="btn-action-outline btn-action-outline-edit"
                                                    data-id="{{ $int->id }}" data-name="{{ $int->name }}"
                                                    data-desc="{{ $int->desc }}" data-icon="{{ $int->icon }}"
                                                    onclick="setInterestEditMode(
                                                                                                                                                                                                                this.getAttribute('data-id'),
                                                                                                                                                                                                                this.getAttribute('data-name'),
                                                                                                                                                                                                                this.getAttribute('data-desc'),
                                                                                                                                                                                                                this.getAttribute('data-icon')
                                                                                                                                                                                                            )">Edit</button>
                                                <form action="{{ route('admin.interests.delete', $int->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus interest {{ addslashes($int->name) }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn-action-outline btn-action-outline-delete">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"
                                            style="text-align: center; color: var(--text-muted); padding: 50px 0;">
                                            Belum ada data Interest terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- ═══════════════════════════════════════════════════ --}}
        {{-- TAB: MATERI (FOKUS) --}}
        {{-- ═══════════════════════════════════════════════════ --}}
        <div class="tab-panel {{ $activeTab === 'fokus' ? 'active' : '' }}" id="tab-fokus">
            <div class="management-card">
                <div class="section-header" style="margin-bottom: 24px;">
                    <h2 class="section-title">Kelola Materi (Fokus)</h2>
                    <p class="section-subtitle">Kelola data spesialisasi fokus pembelajaran yang dikaitkan ke Peminatan
                    </p>
                </div>
                <div class="management-layout">
                    <!-- Form Input Data (Left Column) -->
                    <div class="form-card-premium">
                        <h3 class="form-card-title" id="fokusFormTitle">Tambah Fokus Baru</h3>
                        <p class="form-card-subtitle" id="fokusFormSubtitle">Masukkan data untuk menambahkan
                            spesialisasi fokus belajar baru</p>
                        <form action="{{ route('admin.fokus.store') }}" method="POST" id="fokusForm">
                            @csrf
                            <input type="hidden" name="_method" value="POST" id="fokusMethodInput">
                            <div class="form-grid-inputs">
                                <div class="form-group">
                                    <label class="form-label" for="fokusInputInterestVal">Parent Interest</label>
                                    <select class="form-select" name="interest_val" id="fokusInputInterestVal" required>
                                        <option value="">-- Pilih Parent Interest --</option>
                                        @foreach($interests as $int)
                                            <option value="{{ $int->val }}">{{ $int->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group" id="fokusValGroup">
                                    <label class="form-label" for="fokusInputVal">Value Code (unik)</label>
                                    <input class="form-input" type="text" name="val" id="fokusInputVal"
                                        placeholder="e.g. frontend" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="fokusInputName">Nama Fokus</label>
                                    <input class="form-input" type="text" name="name" id="fokusInputName"
                                        placeholder="e.g. Frontend Developer" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="fokusInputDesc">Deskripsi</label>
                                    <input class="form-input" type="text" name="desc" id="fokusInputDesc"
                                        placeholder="e.g. Tampilan & interaksi user">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="fokusInputTags">Tags (pisahkan koma)</label>
                                    <input class="form-input" type="text" name="tags" id="fokusInputTags"
                                        placeholder="e.g. HTML,CSS,JS,React">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="fokusInputIcon">Icon (Emoji / SVG)</label>
                                    <input class="form-input" type="text" name="icon" id="fokusInputIcon"
                                        placeholder="e.g. ⚡">
                                </div>
                            </div>
                            <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 8px;">
                                <button type="button" class="btn-modal btn-modal-cancel" id="fokusCancelBtn"
                                    onclick="resetFokusForm()"
                                    style="display: none; width: auto; min-width: 100px; padding: 10px 20px;">Batal</button>
                                <button type="submit" class="btn-modal btn-modal-submit"
                                    style="width: auto; min-width: 120px; padding: 10px 20px;">Simpan</button>
                            </div>
                        </form>
                    </div>
                    <!-- List Table (Right Column) -->
                    <div class="table-responsive"
                        style="border: 1px solid var(--border-color); border-radius: 20px; background: rgba(255, 255, 255, 0.01); padding: 16px 20px;">
                        <table class="premium-table">
                            <thead>
                                <tr>
                                    <th>Icon</th>
                                    <th>Nama</th>
                                    <th>Value Code</th>
                                    <th>Main Interest</th>
                                    <th>Tags</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fokusList as $fk)
                                    <tr>
                                        <td>
                                            <div
                                                style="font-size: 20px; display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); border-radius: 10px;">
                                                {!! $fk->icon ?? '⚡' !!}
                                            </div>
                                        </td>
                                        <td style="font-weight: 700; color: #ffffff;">{{ $fk->name }}</td>
                                        <td><span class="badge-pill-accent">{{ $fk->val }}</span></td>
                                        <td>
                                            <span
                                                class="badge-pill-muted">{{ $fk->interest ? $fk->interest->name : $fk->interest_val }}</span>
                                        </td>
                                        <td>
                                            @if($fk->tags)
                                                @foreach(explode(',', $fk->tags) as $tag)
                                                    <span class="badge-tag-item">{{ trim($tag) }}</span>
                                                @endforeach
                                            @else
                                                <span style="color: var(--text-muted);">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="actions-cell">
                                                <button class="btn-action-outline btn-action-outline-edit"
                                                    data-id="{{ $fk->id }}" data-interest-val="{{ $fk->interest_val }}"
                                                    data-name="{{ $fk->name }}" data-desc="{{ $fk->desc }}"
                                                    data-icon="{{ $fk->icon }}" data-tags="{{ $fk->tags }}"
                                                    onclick="setFokusEditMode(
                                                                                                                                                                                                                this.getAttribute('data-id'),
                                                                                                                                                                                                                this.getAttribute('data-interest-val'),
                                                                                                                                                                                                                this.getAttribute('data-name'),
                                                                                                                                                                                                                this.getAttribute('data-desc'),
                                                                                                                                                                                                                this.getAttribute('data-icon'),
                                                                                                                                                                                                                this.getAttribute('data-tags')
                                                                                                                                                                                                            )">Edit</button>
                                                <form action="{{ route('admin.fokus.delete', $fk->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus fokus {{ addslashes($fk->name) }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn-action-outline btn-action-outline-delete">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"
                                            style="text-align: center; color: var(--text-muted); padding: 50px 0;">
                                            Belum ada data Fokus terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- ═══════════════════════════════════════════════════ --}}
        {{-- TAB: SUB MATERI (SUBMATERI) --}}
        {{-- ═══════════════════════════════════════════════════ --}}
        <div class="tab-panel {{ $activeTab === 'submateri' ? 'active' : '' }}" id="tab-submateri">
            <div class="management-card">
                <div class="section-header" style="margin-bottom: 24px;">
                    <h2 class="section-title">Kelola Sub Materi</h2>
                    <p class="section-subtitle">Kelola materi sub-materi belajar yang terkait dengan Modul Utama
                        (Course)</p>
                </div>
                <div class="management-layout">
                    <!-- Form Input Data (Left Column) -->
                    <div class="form-card-premium">
                        <h3 class="form-card-title" id="submateriFormTitle">Tambah Sub Materi</h3>
                        <p class="form-card-subtitle" id="submateriFormSubtitle">Masukkan data untuk menambahkan sub
                            materi belajar baru</p>
                        <form action="{{ route('admin.submateri.store') }}" method="POST" id="submateriForm">
                            @csrf
                            <input type="hidden" name="_method" value="POST" id="submateriMethodInput">
                            <!-- Metadata fields -->
                            <div class="form-grid-inputs" style="margin-bottom: 20px;">
                                <div class="form-group">
                                    <label class="form-label" for="submateriInputCourseId">Modul Utama (Course)</label>
                                    <select class="form-select" name="course_id" id="submateriInputCourseId" required>
                                        <option value="">-- Pilih Modul Utama --</option>
                                        @foreach($courses as $c)
                                            <option value="{{ $c->id }}">{{ $c->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="submateriInputIcon">Icon (Emoji / Karakter)</label>
                                    <input class="form-input" type="text" name="icon" id="submateriInputIcon"
                                        placeholder="e.g. 🌐 atau 🐘">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="submateriInputOrder">Urutan (Order)</label>
                                    <input class="form-input" type="number" name="order" id="submateriInputOrder"
                                        value="1" min="0" required>
                                </div>
                            </div>
                            <!-- Document Editor Sheet -->
                            <div class="document-editor">
                                <!-- Word-like Toolbar -->
                                <div class="doc-toolbar">
                                    <!-- History -->
                                    <button type="button" class="doc-toolbar-btn" onclick="formatDoc('undo')"
                                        title="Undo (Ctrl+Z)">↶</button>
                                    <button type="button" class="doc-toolbar-btn" onclick="formatDoc('redo')"
                                        title="Redo (Ctrl+Y)">↷</button>
                                    <div class="doc-toolbar-divider"></div>
                                    <!-- Styling -->
                                    <button type="button" class="doc-toolbar-btn" style="font-weight: bold;"
                                        onclick="formatDoc('bold')" title="Tebal (Ctrl+B)">B</button>
                                    <button type="button" class="doc-toolbar-btn" style="font-style: italic;"
                                        onclick="formatDoc('italic')" title="Miring (Ctrl+I)">I</button>
                                    <button type="button" class="doc-toolbar-btn" style="text-decoration: underline;"
                                        onclick="formatDoc('underline')" title="Garis Bawah (Ctrl+U)">U</button>
                                    <div class="doc-toolbar-divider"></div>
                                    <!-- Blocks -->
                                    <button type="button" class="doc-toolbar-btn"
                                        style="font-weight: bold; font-size:11px;" onclick="addDocBlock('p')"
                                        title="Paragraf (P)">P</button>
                                    <button type="button" class="doc-toolbar-btn"
                                        style="font-weight: 700; font-size:11px;" onclick="addDocBlock('h2')"
                                        title="Judul Utama (H2)">H2</button>
                                    <button type="button" class="doc-toolbar-btn"
                                        style="font-weight: 600; font-size:11px;" onclick="addDocBlock('h3')"
                                        title="Subjudul (H3)">H3</button>
                                    <button type="button" class="doc-toolbar-btn" onclick="addDocBlock('blockquote')"
                                        title="Kutipan">“ ”</button>
                                    <div class="doc-toolbar-divider"></div>
                                    <!-- Insert tools -->
                                    <button type="button" class="doc-toolbar-btn" onclick="addDocBlock('pre')"
                                        title="Sisipkan Kode Block">&lt;/&gt;</button>
                                    <button type="button" class="doc-toolbar-btn" onclick="insertTableBlock()"
                                        title="Sisipkan Tabel">田</button>
                                    <button type="button" class="doc-toolbar-btn"
                                        onclick="addDocBlock('ul', '<li>Butir list...</li>')" title="Point List Bab">•
                                        List</button>
                                    <button type="button" class="doc-toolbar-btn"
                                        onclick="addDocBlock('ol', '<li>Butir list...</li>')"
                                        title="Point List Number">1. List</button>
                                    <div class="doc-toolbar-divider"></div>
                                    <!-- Media & Others -->
                                    <button type="button" class="doc-toolbar-btn" onclick="addDocBlock('image')"
                                        title="Sisipkan Gambar" style="font-size: 11px;">▣ Gambar</button>
                                    <button type="button" class="doc-toolbar-btn" onclick="addDocBlock('video')"
                                        title="Sisipkan Video" style="font-size: 11px;">▶ Video</button>
                                    <button type="button" class="doc-toolbar-btn" onclick="addDocBlock('callout')"
                                        title="Sisipkan Info Box" style="font-size: 11px;">ⓘ Info Box</button>
                                    <div class="doc-toolbar-divider"></div>
                                    <div class="doc-toolbar-divider"></div>
                                    <!-- Clear -->
                                    <button type="button" class="doc-toolbar-btn" onclick="formatDoc('removeFormat')"
                                        title="Hapus Format">Tx</button>
                                </div>
                                <!-- Main Editor Container (Sheet and Sidebar) -->
                                <div class="doc-main-container">
                                    <!-- Document Sheet Page -->
                                    <div class="doc-sheet-container">
                                        <div class="doc-sheet">
                                            <!-- Row 1: Judul -->
                                            <div class="doc-sheet-row title-row">
                                                <input type="text" class="doc-inline-input title-input" name="title"
                                                    id="submateriInputTitle" placeholder="Judul Submateri" required
                                                    autocomplete="off">
                                            </div>
                                            <!-- Dynamic Blocks Container -->
                                            <div id="docBlocksContainer"></div>
                                        </div>
                                    </div>
                                    <!-- Right Sidebar (Struktur) -->
                                    <div class="doc-sidebar">
                                        <div class="doc-sidebar-title">Struktur</div>
                                        <div id="docSidebarOutline"
                                            style="display: flex; flex-direction: column; overflow-y: auto; max-height: 550px; padding-right: 4px; gap: 8px;">
                                            <!-- Dynamic outline items will be rendered here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Hidden input to store the raw HTML description -->
                            <input type="hidden" name="description" id="submateriInputDesc">
                            <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 8px;">
                                <button type="button" class="btn-modal btn-modal-cancel" id="submateriCancelBtn"
                                    onclick="resetSubmateriForm()"
                                    style="display: none; width: auto; min-width: 100px; padding: 10px 20px;">Batal</button>
                                <button type="submit" class="btn-modal btn-modal-submit"
                                    style="width: auto; min-width: 120px; padding: 10px 20px;">Simpan</button>
                            </div>
                        </form>
                    </div>
                    <!-- List Table (Right Column) -->
                    <div class="table-responsive"
                        style="border: 1px solid var(--border-color); border-radius: 20px; background: rgba(255, 255, 255, 0.01); padding: 16px 20px;">
                        <table class="premium-table">
                            <thead>
                                <tr>
                                    <th>Icon</th>
                                    <th>Judul Submateri</th>
                                    <th>Modul Utama (Course)</th>
                                    <th>Order Ke-</th>
                                    <th>Deskripsi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($submateris as $sub)
                                    <tr>
                                        <td>
                                            <div
                                                style="font-size: 20px; display: inline-flex; align-items: center; justify-content: center; width: 38px; height: 38px; background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); border-radius: 10px;">
                                                {{ $sub->icon ?? '📂' }}
                                            </div>
                                        </td>
                                        <td style="font-weight: 700; color: #ffffff;">{{ $sub->title }}</td>
                                        <td>
                                            <span
                                                class="badge-pill-accent">{{ $sub->course ? $sub->course->title : 'N/A' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge-pill-muted">Urutan {{ $sub->order }}</span>
                                        </td>
                                        <td
                                            style="color: var(--text-muted); max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ strip_tags($sub->description) ? Str::limit(strip_tags($sub->description), 80) : '-' }}
                                        </td>
                                        <td>
                                            <div class="actions-cell">
                                                <button class="btn-action-outline btn-action-outline-edit"
                                                    data-id="{{ $sub->id }}" data-course-id="{{ $sub->course_id }}"
                                                    data-title="{{ $sub->title }}"
                                                    data-description="{{ $sub->description }}" data-icon="{{ $sub->icon }}"
                                                    data-order="{{ $sub->order }}"
                                                    onclick="setSubmateriEditMode(
                                                                                                                                                                                                                this.getAttribute('data-id'),
                                                                                                                                                                                                                this.getAttribute('data-course-id'),
                                                                                                                                                                                                                this.getAttribute('data-title'),
                                                                                                                                                                                                                this.getAttribute('data-description'),
                                                                                                                                                                                                                this.getAttribute('data-icon'),
                                                                                                                                                                                                                this.getAttribute('data-order')
                                                                                                                                                                                                            )">Edit</button>
                                                <form action="{{ route('admin.submateri.delete', $sub->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus submateri {{ addslashes($sub->title) }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn-action-outline btn-action-outline-delete">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"
                                            style="text-align: center; color: var(--text-muted); padding: 50px 0;">
                                            Belum ada data Submateri terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- ═══════════════════════════════════════════════════ --}}
        {{-- TAB: BANK SOAL (QUIZZES) --}}
        {{-- ═══════════════════════════════════════════════════ --}}
        <div class="tab-panel {{ $activeTab === 'quizzes' ? 'active' : '' }}" id="tab-quizzes">
            <div class="management-card">
                <div class="section-header" style="margin-bottom: 24px;">
                    <h2 class="section-title">Kelola Bank Soal (Kuis)</h2>
                    <p class="section-subtitle">Tambah dan ubah soal-soal kuis yang terhubung dengan materi pembelajaran
                    </p>
                </div>
                <div class="management-layout">
                    <!-- Form Input Data (Top Column) -->
                    <div class="form-card-premium">
                        <h3 class="form-card-title" id="quizFormTitle">Tambah Soal Baru</h3>
                        <p class="form-card-subtitle" id="quizFormSubtitle">Masukkan data untuk menambahkan kuis pilihan
                            ganda baru</p>
                        <form action="{{ route('admin.quizzes.store') }}" method="POST" id="quizForm">
                            @csrf
                            <input type="hidden" name="_method" value="POST" id="quizMethodInput">
                            <div class="form-grid-inputs">
                                <div class="form-group">
                                    <label class="form-label" for="qzLessonId">Materi Sesi (Lesson)</label>
                                    <select class="form-select" name="lesson_id" id="qzLessonId" required>
                                        <option value="">-- Pilih Materi Sesi --</option>
                                        @foreach($lessons as $l)
                                            <option value="{{ $l->id }}">{{ $l->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="qzCorrect">Kunci Jawaban Benar</label>
                                    <select class="form-select" name="correct_answer" id="qzCorrect" required>
                                        <option value="a">Pilihan A</option>
                                        <option value="b">Pilihan B</option>
                                        <option value="c">Pilihan C</option>
                                        <option value="d">Pilihan D</option>
                                    </select>
                                </div>
                                <div class="form-group" style="grid-column: 1 / -1;">
                                    <label class="form-label" for="qzQuestion">Pertanyaan Kuis</label>
                                    <textarea class="form-input" name="question" id="qzQuestion"
                                        placeholder="Masukkan teks pertanyaan..." required
                                        style="height: 80px; min-height: 80px; resize: vertical;"></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="qzOptA">Pilihan A</label>
                                    <input class="form-input" type="text" name="option_a" id="qzOptA"
                                        placeholder="Jawaban A" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="qzOptB">Pilihan B</label>
                                    <input class="form-input" type="text" name="option_b" id="qzOptB"
                                        placeholder="Jawaban B" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="qzOptC">Pilihan C</label>
                                    <input class="form-input" type="text" name="option_c" id="qzOptC"
                                        placeholder="Jawaban C" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="qzOptD">Pilihan D</label>
                                    <input class="form-input" type="text" name="option_d" id="qzOptD"
                                        placeholder="Jawaban D" required>
                                </div>
                                <div class="form-group" style="grid-column: 1 / -1;">
                                    <label class="form-label" for="qzExpl">Penjelasan / Pembahasan (Opsional)</label>
                                    <textarea class="form-input" name="explanation" id="qzExpl"
                                        placeholder="Masukkan pembahasan jawaban..."
                                        style="height: 80px; min-height: 80px; resize: vertical;"></textarea>
                                </div>
                            </div>
                            <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 8px;">
                                <button type="button" class="btn-modal btn-modal-cancel" id="quizCancelBtn"
                                    onclick="resetQuizForm()"
                                    style="display: none; width: auto; min-width: 100px; padding: 10px 20px;">Batal</button>
                                <button type="submit" class="btn-modal btn-modal-submit"
                                    style="width: auto; min-width: 120px; padding: 10px 20px;">Simpan</button>
                            </div>
                        </form>
                    </div>
                    <!-- List Table (Bottom Column) -->
                    <div class="table-responsive"
                        style="border: 1px solid var(--border-color); border-radius: 20px; background: rgba(255, 255, 255, 0.01); padding: 16px 20px;">
                        <table class="premium-table">
                            <thead>
                                <tr>
                                    <th>Materi Sesi (Lesson)</th>
                                    <th>Pertanyaan Soal</th>
                                    <th>Pilihan Jawaban (A/B/C/D)</th>
                                    <th>Kunci Jawaban</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($quizzes as $qz)
                                    <tr>
                                        <td
                                            style="font-weight: 700; color: #ffffff; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ $qz->lesson ? $qz->lesson->title : 'N/A' }}
                                        </td>
                                        <td
                                            style="color: #ffffff; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                            {{ $qz->question }}
                                        </td>
                                        <td>
                                            @if(is_array($qz->options) && count($qz->options) >= 4)
                                                <div
                                                    style="font-size: 11px; display: flex; flex-direction: column; gap: 2px; color: var(--text-muted);">
                                                    <span>A: {{ $qz->options[0] }}</span>
                                                    <span>B: {{ $qz->options[1] }}</span>
                                                    <span>C: {{ $qz->options[2] }}</span>
                                                    <span>D: {{ $qz->options[3] }}</span>
                                                </div>
                                            @else
                                                <span style="color: var(--danger-red);">Data pilihan tidak valid</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge-pill-accent"
                                                style="text-transform: uppercase;">{{ $qz->correct_answer }}</span>
                                        </td>
                                        <td>
                                            <div class="actions-cell">
                                                @php
                                                    $optA = isset($qz->options[0]) ? $qz->options[0] : '';
                                                    $optB = isset($qz->options[1]) ? $qz->options[1] : '';
                                                    $optC = isset($qz->options[2]) ? $qz->options[2] : '';
                                                    $optD = isset($qz->options[3]) ? $qz->options[3] : '';
                                                @endphp
                                                <button class="btn-action-outline btn-action-outline-edit"
                                                    data-id="{{ $qz->id }}" data-lesson-id="{{ $qz->lesson_id }}"
                                                    data-question="{{ $qz->question }}"
                                                    data-correct-answer="{{ $qz->correct_answer }}"
                                                    data-explanation="{{ $qz->explanation }}" data-opta="{{ $optA }}"
                                                    data-optb="{{ $optB }}" data-optc="{{ $optC }}" data-optd="{{ $optD }}"
                                                    onclick="setQuizEditMode(
                                                                                                                                                                                                                this.getAttribute('data-id'),
                                                                                                                                                                                                                this.getAttribute('data-lesson-id'),
                                                                                                                                                                                                                this.getAttribute('data-question'),
                                                                                                                                                                                                                this.getAttribute('data-correct-answer'),
                                                                                                                                                                                                                this.getAttribute('data-explanation'),
                                                                                                                                                                                                                this.getAttribute('data-opta'),
                                                                                                                                                                                                                this.getAttribute('data-optb'),
                                                                                                                                                                                                                this.getAttribute('data-optc'),
                                                                                                                                                                                                                this.getAttribute('data-optd')
                                                                                                                                                                                                            )">Edit</button>
                                                <form action="{{ route('admin.quizzes.delete', $qz->id) }}" method="POST"
                                                    onsubmit="return confirm('Hapus soal kuis ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn-action-outline btn-action-outline-delete">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"
                                            style="text-align: center; color: var(--text-muted); padding: 50px 0;">
                                            Belum ada bank soal terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        {{-- ═══════════════════════════════════════════════════ --}}
        {{-- TAB: DATABASE STRUCTURE --}}
        {{-- ═══════════════════════════════════════════════════ --}}
        <div class="tab-panel {{ $activeTab === 'database' ? 'active' : '' }}" id="tab-database">
            <div class="management-card">
                <div class="section-header" style="margin-bottom: 20px;">
                    <h2 class="section-title">Struktur Database Sistem</h2>
                    <p class="section-subtitle">Visualisasi baris tabel utama database TurnCode saat ini</p>
                </div>
                <!-- Mode Toolbar -->
                <div class="db-mode-toolbar">
                    <button class="db-mode-btn active" data-mode="1" onclick="switchDbMode(1)">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="8" y1="6" x2="21" y2="6"></line>
                            <line x1="8" y1="12" x2="21" y2="12"></line>
                            <line x1="8" y1="18" x2="21" y2="18"></line>
                            <line x1="3" y1="6" x2="3.01" y2="6"></line>
                            <line x1="3" y1="12" x2="3.01" y2="12"></line>
                            <line x1="3" y1="18" x2="3.01" y2="18"></line>
                        </svg>
                        <span>Mode 1: Grid 1</span>
                    </button>
                    <button class="db-mode-btn" data-mode="2" onclick="switchDbMode(2)">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                        <span>Mode 2: Grid 2</span>
                    </button>
                    <button class="db-mode-btn" data-mode="3" onclick="switchDbMode(3)">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                        </svg>
                        <span>Mode 3: Roadmap (Designer)</span>
                    </button>
                </div>
                @php
                    $coords = [
                        'users' => ['x' => '2%', 'y' => '20px'],
                        'schedules' => ['x' => '27%', 'y' => '20px'],
                        'notifications' => ['x' => '52%', 'y' => '20px'],
                        'sessions' => ['x' => '76.5%', 'y' => '20px'],
                        'interests' => ['x' => '2%', 'y' => '330px'],
                        'fokus' => ['x' => '27%', 'y' => '330px'],
                        'courses' => ['x' => '52%', 'y' => '330px'],
                        'submateris' => ['x' => '76.5%', 'y' => '330px'],
                        'lesson_user' => ['x' => '2%', 'y' => '560px'],
                        'quizzes' => ['x' => '27%', 'y' => '560px'],
                        'lessons' => ['x' => '52%', 'y' => '560px'],
                        'chapters' => ['x' => '76.5%', 'y' => '560px'],
                        'migrations' => ['x' => '2%', 'y' => '790px'],
                        'failed_jobs' => ['x' => '27%', 'y' => '790px'],
                        'password_reset_tokens' => ['x' => '52%', 'y' => '790px'],
                        'personal_access_tokens' => ['x' => '76.5%', 'y' => '790px'],
                    ];
                @endphp
                <div id="db-grid-container" class="mode-1">
                    <!-- Grid View Cards (Mode 1 & Mode 2) -->
                    @foreach($tablesDetail as $tableName => $table)
                        <div class="database-table-card">
                            <div style="display: flex; align-items: center; gap: 16px;">
                                <div class="database-table-icon"
                                    style="width: 48px; height: 48px; border-radius: 50%; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.02); display: flex; align-items: center; justify-content: center; color: #ffffff; flex-shrink: 0;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                                        <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                                        <path d="M3 12c0 1.66 4 3 9 3s9-1.34 9-3"></path>
                                    </svg>
                                </div>
                                <div class="database-table-info">
                                    <span class="database-table-name"
                                        style="font-family: monospace; font-size: 15px; font-weight: 700; color: #ffffff;">{{ $table['title'] }}</span>
                                    <span class="database-table-desc"
                                        style="font-size: 11.5px; color: var(--text-muted);">{{ $table['desc'] }}</span>
                                </div>
                            </div>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <span class="database-table-count"
                                    style="font-size: 13px; white-space: nowrap; font-weight: 700; color: #ffffff; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(255, 255, 255, 0.08); padding: 6px 14px; border-radius: 10px; height: 38px; display: inline-flex; align-items: center;">{{ number_format($table['count'], 0, ',', '.') }}
                                    Baris</span>
                                <button class="btn-action-outline btn-action-outline-edit"
                                    onclick="showTableStructure('{{ $tableName }}')"
                                    style="height: 38px; padding: 0 16px;">Struktur</button>
                                <button class="btn-premium" onclick="showTableData('{{ $tableName }}')"
                                    style="height: 38px; padding: 0 16px; font-size: 12.5px;">Data</button>
                            </div>
                        </div>
                    @endforeach
                    <!-- phpMyAdmin Designer View (Mode 3) -->
                    <div class="db-roadmap-scroll-wrap">
                        <div class="db-roadmap-canvas">
                            <svg class="db-relations-svg" id="db-relations-svg">
                                <!-- Dynamic relation lines drawn by JS -->
                            </svg>
                            @foreach($tablesDetail as $tableName => $table)
                                @php
                                    $pos = $coords[$tableName] ?? ['x' => '0%', 'y' => '0px'];
                                @endphp
                                <div class="db-table-box" id="db-box-{{ $tableName }}"
                                    style="left: {{ $pos['x'] }}; top: {{ $pos['y'] }};">
                                    <div class="db-table-box-header">
                                        <span class="db-table-box-title">{{ $table['title'] }}</span>
                                        <span
                                            class="db-table-box-badge">{{ number_format($table['count'], 0, ',', '.') }}</span>
                                    </div>
                                    <div class="db-table-box-body">
                                        @foreach($table['columns'] as $col)
                                            <div class="db-table-box-row {{ $col['key'] ? 'has-key' : '' }}"
                                                id="node-{{ $tableName }}-{{ $col['name'] }}" @if($col['key'] === 'foreign')
                                                data-ref="{{ $col['ref'] }}" @endif>
                                                <span class="db-col-name">
                                                    @if($col['key'] === 'primary')<span
                                                    class="db-key-badge pk">PK</span>@elseif($col['key'] === 'foreign')<span
                                                    class="db-key-badge fk">FK</span>@elseif($col['key'] === 'unique')<span
                                                        class="db-key-badge uq">UQ</span>@endif
                                                    {{ $col['name'] }}
                                                </span>
                                                <span class="db-col-type">{{ $col['type'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- ═══════════════════════════════════════════════════ --}}
        {{-- TAB: MANAJEMEN FITUR --}}
        {{-- TAB: MANAJEMEN FITUR --}}
        {{-- ═══════════════════════════════════════════════════ --}}
        <div class="tab-panel {{ $activeTab === 'fitur' ? 'active' : '' }}" id="tab-fitur">
            <div class="management-card">
                <div class="section-header" style="margin-bottom: 30px;">
                    <h2 class="section-title">Manajemen Akun User & Fitur</h2>
                    <p class="section-subtitle">Daftar akun pengguna terdaftar dengan fungsionalitas pengeditan EXP
                        akumulasi</p>
                </div>
                <div class="table-responsive">
                    <table class="premium-table">
                        <thead>
                            <tr>
                                <th>Nama Pengguna</th>
                                <th>Email</th>
                                <th>Level</th>
                                <th>Tier</th>
                                <th>Interest Pilihan</th>
                                <th>Akumulasi EXP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paginatedUsers as $u)
                                <tr>
                                    <td style="font-weight: 700; color: #ffffff;">{{ $u->name }}</td>
                                    <td style="color: var(--text-muted);">{{ $u->email }}</td>
                                    <td>
                                        <span class="badge-pill-accent">LV. {{ $u->level }}</span>
                                    </td>
                                    <td>
                                        <span class="badge-pill-muted">{{ $u->tier }}</span>
                                    </td>
                                    <td>
                                        <span class="badge-pill-accent"
                                            style="font-size: 11px; text-transform: uppercase;">{{ $u->interest ?? '-' }}</span>
                                    </td>
                                    <td style="font-weight: 800; color: #ffffff;">{{ number_format($u->exp, 0, ',', '.') }}
                                        EXP</td>
                                    <td>
                                        <div class="actions-cell">
                                            <button class="btn-action-outline btn-action-outline-edit"
                                                onclick="openEditExp('{{ $u->id }}', '{{ addslashes($u->name) }}', '{{ $u->exp }}')">Ubah
                                                EXP</button>
                                            <form action="{{ route('admin.users.delete', $u->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus pengguna {{ addslashes($u->name) }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn-action-outline btn-action-outline-delete">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 50px 0;">
                                        Belum ada akun pengguna terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Links -->
                @if($paginatedUsers->hasPages())
                    <div class="pagination-wrap">
                        <nav>
                            @if ($paginatedUsers->onFirstPage())
                                <span style="opacity: 0.4; pointer-events: none;">&laquo;</span>
                            @else
                                <a href="{{ $paginatedUsers->appends(['tab' => 'fitur'])->previousPageUrl() }}"
                                    rel="prev">&laquo;</a>
                            @endif
                            @foreach ($paginatedUsers->appends(['tab' => 'fitur'])->getUrlRange(1, $paginatedUsers->lastPage()) as $page => $url)
                                @if ($page == $paginatedUsers->currentPage())
                                    <span class="active">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}">{{ $page }}</a>
                                @endif
                            @endforeach
                            @if ($paginatedUsers->hasMorePages())
                                <a href="{{ $paginatedUsers->appends(['tab' => 'fitur'])->nextPageUrl() }}"
                                    rel="next">&raquo;</a>
                            @else
                                <span style="opacity: 0.4; pointer-events: none;">&raquo;</span>
                            @endif
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </div>
    {{-- ═══════════════════════════════════════════════════ --}}
    {{-- MODALS --}}
    {{-- ═══════════════════════════════════════════════════ --}}
    <!-- UBAH EXP MODAL -->
    <div id="editExpModal" class="modal">
        <div class="modal-card">
            <div class="modal-header">
                <h3 class="modal-title">Ubah EXP Pengguna</h3>
                <p class="modal-subtitle" id="expModalDesc">Perbarui jumlah EXP akumulasi user</p>
            </div>
            <form action="" method="POST" id="editExpForm">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label class="form-label" for="expInput">Jumlah EXP</label>
                    <input class="form-input" type="number" name="exp" id="expInput" min="0" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal btn-modal-cancel"
                        onclick="closeModal('editExpModal')">Batal</button>
                    <button type="submit" class="btn-modal btn-modal-submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
    <!-- TABLE STRUCTURE MODAL -->
    <div id="tableStructureModal" class="modal">
        <div class="modal-card" style="max-width: 680px;">
            <div id="structureToast" class="toast-copy-feedback">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>Berhasil disalin!</span>
            </div>
            <div class="modal-header" style="margin-bottom: 10px;">
                <div class="modal-header-top">
                    <h3 class="modal-title" id="structureModalTitle">Struktur Tabel</h3>
                    <div class="modal-controls">
                        <button type="button" class="modal-control-btn"
                            onclick="toggleModalFullscreen('tableStructureModal')" title="Maximize / Restore">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3">
                                </path>
                            </svg>
                        </button>
                        <button type="button" class="modal-control-btn close-btn"
                            onclick="closeModal('tableStructureModal')" title="Tutup">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                </div>
                <p class="modal-subtitle">Daftar kolom, tipe data, dan atribut key</p>
                <div class="popup-toolbar">
                    <div class="popup-search-wrap">
                        <span class="popup-search-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </span>
                        <input type="text" class="popup-search-input" placeholder="Cari kolom..."
                            id="structureSearchInput" oninput="filterStructureModal()">
                    </div>
                    <div class="popup-actions">
                        <button type="button" class="popup-action-btn accent" onclick="copyTableDataAsJson('structure')"
                            title="Salin Struktur (JSON)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                            </svg>
                            Salin JSON
                        </button>
                        <button type="button" class="popup-action-btn" onclick="copyTableDataAsCsv('structure')"
                            title="Salin Struktur (CSV)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                            </svg>
                            CSV
                        </button>
                    </div>
                </div>
            </div>
            <div class="table-responsive"
                style="max-height: 400px; border-left: 1px solid var(--border-color); border-right: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); border-top: none; border-radius: 0 0 12px 12px; overflow-y: auto;">
                <table class="popup-db-table" id="structureModalTable">
                    <thead>
                        <tr>
                            <th>Atribut Key</th>
                            <th>Nama Kolom</th>
                            <th>Tipe Data</th>
                        </tr>
                    </thead>
                    <tbody id="structureModalBody">
                        <!-- Filled by JS -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer" style="margin-top: 15px;">
                <button type="button" class="btn-modal btn-modal-cancel"
                    onclick="closeModal('tableStructureModal')">Tutup</button>
            </div>
        </div>
    </div>
    <!-- TABLE DATA MODAL -->
    <div id="tableDataModal" class="modal">
        <div class="modal-card" style="max-width: 850px;">
            <div id="dataToast" class="toast-copy-feedback">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                <span>Berhasil disalin!</span>
            </div>
            <div class="modal-header" style="margin-bottom: 10px;">
                <div class="modal-header-top">
                    <h3 class="modal-title" id="dataModalTitle">Isi Data Tabel</h3>
                    <div class="modal-controls">
                        <button type="button" class="modal-control-btn"
                            onclick="toggleModalFullscreen('tableDataModal')" title="Maximize / Restore">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path
                                    d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3">
                                </path>
                            </svg>
                        </button>
                        <button type="button" class="modal-control-btn close-btn" onclick="closeModal('tableDataModal')"
                            title="Tutup">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                </div>
                <p class="modal-subtitle">Menampilkan maksimal 15 baris pertama data saat ini</p>
                <div class="popup-toolbar">
                    <div class="popup-search-wrap">
                        <span class="popup-search-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </span>
                        <input type="text" class="popup-search-input" placeholder="Cari data..." id="dataSearchInput"
                            oninput="filterDataModal()">
                    </div>
                    <div class="popup-actions">
                        <button type="button" class="popup-action-btn accent" onclick="copyTableDataAsJson('data')"
                            title="Salin Data (JSON)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                            </svg>
                            Salin JSON
                        </button>
                        <button type="button" class="popup-action-btn" onclick="copyTableDataAsCsv('data')"
                            title="Salin Data (CSV)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                            </svg>
                            CSV
                        </button>
                    </div>
                </div>
            </div>
            <div class="table-responsive"
                style="max-height: 400px; border-left: 1px solid var(--border-color); border-right: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color); border-top: none; border-radius: 0 0 12px 12px; background: transparent; overflow-y: auto;">
                <table class="popup-db-table" id="dataModalTable">
                    <thead id="dataModalHead">
                        <!-- Columns filled by JS -->
                    </thead>
                    <tbody id="dataModalBody">
                        <!-- Rows filled by JS -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer" style="margin-top: 15px;">
                <button type="button" class="btn-modal btn-modal-cancel"
                    onclick="closeModal('tableDataModal')">Tutup</button>
            </div>
        </div>
    </div>
    <script>
        const dbTablesSchema = @json($tablesDetail);
        let currentTableName = '';
        let currentStructureColumns = [];
        let currentDataColumns = [];
        let currentDataRows = [];
        function showTableStructure(tableName) {
            const table = dbTablesSchema[tableName];
            if (!table) return;
            currentTableName = tableName;
            currentStructureColumns = table.columns;
            // Reset search input

            document.getElementById('structureSearchInput').value = '';
            // Reset Fullscreen class

            document.getElementById('tableStructureModal').querySelector('.modal-card').classList.remove('is-fullscreen');
            document.getElementById('structureModalTitle').innerText = 'Struktur Tabel: ' + tableName;
            renderStructureTable(currentStructureColumns);
            openModal('tableStructureModal');
        }

        function renderStructureTable(cols) {
            const tbody = document.getElementById('structureModalBody');
            tbody.innerHTML = '';
            if (cols.length === 0) {
                tbody.innerHTML = '<tr><td colspan="3" style="text-align: center; color: var(--text-muted); padding: 20px 0;">Kolom tidak ditemukan</td></tr>';
                return;
            }

            cols.forEach(col => {
                let badge = '';
                if (col.key === 'primary') {
                    badge = '<span class="db-key-badge pk">PK</span>';
                } else if (col.key === 'foreign') {
                    badge = '<span class="db-key-badge fk">FK</span>';
                } else if (col.key === 'unique') {
                    badge = '<span class="db-key-badge uq">UQ</span>';
                } else {
                    badge = '<span style="color: var(--text-muted); font-size: 10px; font-weight: 500; padding: 1px 4px;">-</span>';
                }

                // If nullable

                let nullableBadge = '';
                if (col.nullable) {
                    nullableBadge = '<span class="db-col-nullable">NULL</span>';
                }

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td style="font-family: sans-serif; width: 120px; vertical-align: middle;">
                        <div style="display: flex; align-items: center; gap: 6px;">
                            ${badge}

                            ${nullableBadge}

                        </div>
                    </td>
                    <td style="font-family: monospace; font-weight: 600; color: #ffffff;">${col.name}</td>
                    <td style="font-family: monospace; color: var(--text-muted);">${col.type}</td>
                `;
                tbody.appendChild(tr);
            });
        }

        function filterStructureModal() {
            const query = document.getElementById('structureSearchInput').value.toLowerCase().trim();
            if (!query) {
                renderStructureTable(currentStructureColumns);
                return;
            }

            const filtered = currentStructureColumns.filter(col => {
                const nameMatch = col.name.toLowerCase().includes(query);
                const typeMatch = col.type.toLowerCase().includes(query);
                const keyMatch = (col.key || '').toLowerCase().includes(query);
                return nameMatch || typeMatch || keyMatch;
            });
            renderStructureTable(filtered);
        }

        function showTableData(tableName) {
            currentTableName = tableName;
            currentDataColumns = [];
            currentDataRows = [];
            // Reset search input

            document.getElementById('dataSearchInput').value = '';
            // Reset Fullscreen class

            document.getElementById('tableDataModal').querySelector('.modal-card').classList.remove('is-fullscreen');
            document.getElementById('dataModalTitle').innerText = 'Isi Data Tabel: ' + tableName;
            const thead = document.getElementById('dataModalHead');
            const tbody = document.getElementById('dataModalBody');
            thead.innerHTML = '<tr><th style="padding: 14px 16px; text-align: center; border-bottom: none;">Memuat data dari database...</th></tr>';
            thead.querySelector('tr').style.borderLeft = 'none';
            tbody.innerHTML = '';
            openModal('tableDataModal');
            fetch(`/admin/database/table-data/${tableName}`)
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        thead.innerHTML = `<tr><th style="padding: 14px 16px; color: var(--danger-red); text-align: center; border-bottom: none;">Gagal memuat data: ${data.error}</th></tr>`;
                        return;
                    }

                    if (data.columns.length === 0) {
                        thead.innerHTML = '<tr><th style="padding: 14px 16px; text-align: center; border-bottom: none;">Tabel tidak memiliki kolom</th></tr>';
                        return;
                    }

                    currentDataColumns = data.columns;
                    currentDataRows = data.rows;
                    renderDataTable(currentDataColumns, currentDataRows);
                })
                .catch(err => {
                    thead.innerHTML = `<tr><th style="padding: 14px 16px; color: var(--danger-red); text-align: center; border-bottom: none;">Koneksi gagal: ${err.message}</th></tr>`;
                });
        }

        function renderDataTable(columns, rows) {
            const thead = document.getElementById('dataModalHead');
            const tbody = document.getElementById('dataModalBody');
            thead.innerHTML = '';
            tbody.innerHTML = '';
            // Render headers

            let headRow = '<tr>';
            columns.forEach(col => {
                headRow += `<th style="font-family: monospace;">${col}</th>`;
            });
            headRow += '</tr>';
            thead.innerHTML = headRow;
            // Render rows

            if (rows.length === 0) {
                tbody.innerHTML = `<tr><td colspan="${columns.length}" style="text-align: center; color: var(--text-muted); padding: 40px 0;">Tabel ini kosong (belum ada baris data).</td></tr>`;
                return;
            }

            rows.forEach(row => {
                let trContent = '<tr>';
                columns.forEach(col => {
                    let cellVal = row[col];
                    let formattedVal = '';
                    let displayVal = '';
                    if (cellVal === null) {
                        displayVal = '<span style="color: var(--text-muted); font-style: italic; font-size: 11px;">NULL</span>';
                    } else if (typeof cellVal === 'object') {
                        formattedVal = JSON.stringify(cellVal);
                        displayVal = `<span style="color: var(--text-muted); font-family: monospace; font-size: 11px;">${formattedVal}</span>`;
                    } else {
                        formattedVal = String(cellVal);
                        if (formattedVal.length > 50) {
                            displayVal = `<span title="${formattedVal.replace(/"/g, '&quot;')}">${formattedVal.substring(0, 47)}...</span>`;
                        } else {
                            displayVal = formattedVal;
                        }
                    }

                    trContent += `<td style="font-family: monospace; max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${displayVal}</td>`;
                });
                trContent += '</tr>';
                const tr = document.createElement('tr');
                tr.innerHTML = trContent;
                tbody.appendChild(tr);
            });
        }

        function filterDataModal() {
            const query = document.getElementById('dataSearchInput').value.toLowerCase().trim();
            if (!query) {
                renderDataTable(currentDataColumns, currentDataRows);
                return;
            }

            const filteredRows = currentDataRows.filter(row => {
                return currentDataColumns.some(col => {
                    const cellVal = row[col];
                    if (cellVal === null) return false;
                    return String(cellVal).toLowerCase().includes(query);
                });
            });
            renderDataTable(currentDataColumns, filteredRows);
        }

        function toggleModalFullscreen(modalId) {
            const modal = document.getElementById(modalId);
            if (!modal) return;
            const card = modal.querySelector('.modal-card');
            if (card) {
                card.classList.toggle('is-fullscreen');
            }
        }

        function copyTableDataAsJson(type) {
            let jsonString = '';
            let toastId = '';
            if (type === 'structure') {
                const query = document.getElementById('structureSearchInput').value.toLowerCase().trim();
                const dataToCopy = query
                    ? currentStructureColumns.filter(col => col.name.toLowerCase().includes(query) || col.type.toLowerCase().includes(query))
                    : currentStructureColumns;
                jsonString = JSON.stringify(dataToCopy, null, 2);
                toastId = 'structureToast';
            } else {
                const query = document.getElementById('dataSearchInput').value.toLowerCase().trim();
                const filteredRows = query
                    ? currentDataRows.filter(row => currentDataColumns.some(col => String(row[col] || '').toLowerCase().includes(query)))
                    : currentDataRows;
                jsonString = JSON.stringify(filteredRows, null, 2);
                toastId = 'dataToast';
            }

            navigator.clipboard.writeText(jsonString)
                .then(() => showToastFeedback(toastId))
                .catch(err => alert('Gagal menyalin text: ' + err));
        }

        function copyTableDataAsCsv(type) {
            let csvContent = '';
            let toastId = '';
            if (type === 'structure') {
                const query = document.getElementById('structureSearchInput').value.toLowerCase().trim();
                const dataToCopy = query
                    ? currentStructureColumns.filter(col => col.name.toLowerCase().includes(query) || col.type.toLowerCase().includes(query))
                    : currentStructureColumns;
                csvContent = 'Key,Column Name,Data Type,Nullable\n';
                dataToCopy.forEach(col => {
                    csvContent += `"${col.key || ''}","${col.name}","${col.type}","${col.nullable ? 'YES' : 'NO'}"\n`;
                });
                toastId = 'structureToast';
            } else {
                const query = document.getElementById('dataSearchInput').value.toLowerCase().trim();
                const filteredRows = query
                    ? currentDataRows.filter(row => currentDataColumns.some(col => String(row[col] || '').toLowerCase().includes(query)))
                    : currentDataRows;
                csvContent = currentDataColumns.join(',') + '\n';
                filteredRows.forEach(row => {
                    const rowVals = currentDataColumns.map(col => {
                        let val = row[col];
                        if (val === null) return 'NULL';
                        if (typeof val === 'object') val = JSON.stringify(val);
                        // Escape quotes

                        val = String(val).replace(/"/g, '""');
                        return `"${val}"`;
                    });
                    csvContent += rowVals.join(',') + '\n';
                });
                toastId = 'dataToast';
            }

            navigator.clipboard.writeText(csvContent)
                .then(() => showToastFeedback(toastId))
                .catch(err => alert('Gagal menyalin text: ' + err));
        }

        function showToastFeedback(toastId) {
            const toast = document.getElementById(toastId);
            if (!toast) return;
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 2000);
        }

        // Interest Chart SVG Data Map

        const chartDataMap = {
            'web-dev': {
                path: 'M 10 40 L 45 80 L 80 115 L 115 70 L 150 45 L 185 85 L 220 50 L 255 60 L 290 85 L 325 50 L 360 85 L 395 110 L 430 45 L 465 90 L 500 110 L 535 70 L 570 85 L 600 50',
                points: [
                    { x: 10, y: 40 }, { x: 45, y: 80 }, { x: 80, y: 115 }, { x: 115, y: 70 }, { x: 150, y: 45 },
                    { x: 185, y: 85 }, { x: 220, y: 50 }, { x: 255, y: 60 }, { x: 290, y: 85 }, { x: 325, y: 50 },
                    { x: 360, y: 85 }, { x: 395, y: 110 }, { x: 430, y: 45 }, { x: 465, y: 90 }, { x: 500, y: 110 },
                    { x: 535, y: 70 }, { x: 570, y: 85 }, { x: 600, y: 50 }
                ]
            },
            'app-dev': {
                path: 'M 10 110 L 45 95 L 80 100 L 115 80 L 150 70 L 185 60 L 220 85 L 255 55 L 290 65 L 325 45 L 360 30 L 395 50 L 430 35 L 465 45 L 500 25 L 535 30 L 570 15 L 600 20',
                points: [
                    { x: 10, y: 110 }, { x: 45, y: 95 }, { x: 80, y: 100 }, { x: 115, y: 80 }, { x: 150, y: 70 },
                    { x: 185, y: 60 }, { x: 220, y: 85 }, { x: 255, y: 55 }, { x: 290, y: 65 }, { x: 325, y: 45 },
                    { x: 360, y: 30 }, { x: 395, y: 50 }, { x: 430, y: 35 }, { x: 465, y: 45 }, { x: 500, y: 25 },
                    { x: 535, y: 30 }, { x: 570, y: 15 }, { x: 600, y: 20 }
                ]
            },
            'game-dev': {
                path: 'M 10 90 L 45 105 L 80 80 L 115 110 L 150 75 L 185 90 L 220 115 L 255 60 L 290 50 L 325 80 L 360 40 L 395 70 L 430 30 L 465 65 L 500 55 L 535 85 L 570 45 L 600 35',
                points: [
                    { x: 10, y: 90 }, { x: 45, y: 105 }, { x: 80, y: 80 }, { x: 115, y: 110 }, { x: 150, y: 75 },
                    { x: 185, y: 90 }, { x: 220, y: 115 }, { x: 255, y: 60 }, { x: 290, y: 50 }, { x: 325, y: 80 },
                    { x: 360, y: 40 }, { x: 395, y: 70 }, { x: 430, y: 30 }, { x: 465, y: 65 }, { x: 500, y: 55 },
                    { x: 535, y: 85 }, { x: 570, y: 45 }, { x: 600, y: 35 }
                ]
            },
            'cyber-sec': {
                path: 'M 10 50 L 45 35 L 80 65 L 115 50 L 150 90 L 185 80 L 220 70 L 255 115 L 290 85 L 325 100 L 360 60 L 395 45 L 430 75 L 465 55 L 500 80 L 535 40 L 570 50 L 600 25',
                points: [
                    { x: 10, y: 50 }, { x: 45, y: 35 }, { x: 80, y: 65 }, { x: 115, y: 50 }, { x: 150, y: 90 },
                    { x: 185, y: 80 }, { x: 220, y: 70 }, { x: 255, y: 115 }, { x: 290, y: 85 }, { x: 325, y: 100 },
                    { x: 360, y: 60 }, { x: 395, y: 45 }, { x: 430, y: 75 }, { x: 465, y: 55 }, { x: 500, y: 80 },
                    { x: 535, y: 40 }, { x: 570, y: 50 }, { x: 600, y: 25 }
                ]
            }
        };
        function switchInterestChart(element, dataKey, titleName) {
            // Remove active classes

            document.querySelectorAll('.peminatan-item').forEach(item => item.classList.remove('active'));
            // Set active class

            element.classList.add('active');
            // Set title

            document.getElementById('chartInterestTitle').innerText = titleName;
            // Update path & dots

            const chartData = chartDataMap[dataKey];
            if (chartData) {
                // Update SVG path

                document.getElementById('interestChartPath').setAttribute('d', chartData.path);
                // Update dots cx & cy

                const dotElements = document.querySelectorAll('#chartDots circle');
                chartData.points.forEach((pt, index) => {
                    if (dotElements[index]) {
                        dotElements[index].setAttribute('cx', pt.x);
                        dotElements[index].setAttribute('cy', pt.y);
                    }
                });
            }
        }

        // Sidebar Toggle Collapse/Expand

        function toggleSidebar() {
            const body = document.body;
            body.classList.toggle('sidebar-collapsed');
            const isCollapsed = body.classList.contains('sidebar-collapsed');
            localStorage.setItem('admin_sidebar_collapsed', isCollapsed);
        }

        // Modals management

        function openModal(id) {
            document.getElementById(id).style.display = 'flex';
        }

        function closeModal(id) {
            document.getElementById(id).style.display = 'none';
        }

        // Close modal when clicking on backdrop

        document.querySelectorAll('.modal').forEach(function (modal) {
            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    modal.style.display = 'none';
                }
            });
        });
        // Edit handlers

        function setInterestEditMode(id, name, desc, icon) {
            document.getElementById('interestForm').action = '/admin/interests/' + id;
            document.getElementById('interestMethodInput').value = 'PUT';
            // Hide val group for editing

            document.getElementById('interestValGroup').style.display = 'none';
            document.getElementById('interestInputVal').removeAttribute('required');
            document.getElementById('interestInputName').value = name;
            document.getElementById('interestInputDesc').value = desc;
            document.getElementById('interestInputIcon').value = icon;
            document.getElementById('interestFormTitle').innerText = 'Ubah Interest';
            document.getElementById('interestFormSubtitle').innerText = 'Perbarui data kategori peminatan ini';
            document.getElementById('interestCancelBtn').style.display = 'block';
            document.getElementById('interestFormTitle').scrollIntoView({ behavior: 'smooth' });
        }

        function resetInterestForm() {
            document.getElementById('interestForm').action = '{{ route("admin.interests.store") }}';
            document.getElementById('interestMethodInput').value = 'POST';
            document.getElementById('interestValGroup').style.display = 'block';
            document.getElementById('interestInputVal').setAttribute('required', 'required');
            document.getElementById('interestForm').reset();
            document.getElementById('interestFormTitle').innerText = 'Tambah Interest Baru';
            document.getElementById('interestFormSubtitle').innerText = 'Masukkan data untuk menambahkan kategori peminatan baru';
            document.getElementById('interestCancelBtn').style.display = 'none';
        }

        function setFokusEditMode(id, interestVal, name, desc, icon, tags) {
            document.getElementById('fokusForm').action = '/admin/fokus/' + id;
            document.getElementById('fokusMethodInput').value = 'PUT';
            // Hide val group for editing

            document.getElementById('fokusValGroup').style.display = 'none';
            document.getElementById('fokusInputVal').removeAttribute('required');
            document.getElementById('fokusInputInterestVal').value = interestVal;
            document.getElementById('fokusInputName').value = name;
            document.getElementById('fokusInputDesc').value = desc;
            document.getElementById('fokusInputTags').value = tags;
            document.getElementById('fokusInputIcon').value = icon;
            document.getElementById('fokusFormTitle').innerText = 'Ubah Fokus';
            document.getElementById('fokusFormSubtitle').innerText = 'Perbarui data spesialisasi fokus belajar ini';
            document.getElementById('fokusCancelBtn').style.display = 'block';
            document.getElementById('fokusFormTitle').scrollIntoView({ behavior: 'smooth' });
        }

        function resetFokusForm() {
            document.getElementById('fokusForm').action = '{{ route("admin.fokus.store") }}';
            document.getElementById('fokusMethodInput').value = 'POST';
            document.getElementById('fokusValGroup').style.display = 'block';
            document.getElementById('fokusInputVal').setAttribute('required', 'required');
            document.getElementById('fokusForm').reset();
            document.getElementById('fokusFormTitle').innerText = 'Tambah Fokus Baru';
            document.getElementById('fokusFormSubtitle').innerText = 'Masukkan data untuk menambahkan spesialisasi fokus belajar baru';
            document.getElementById('fokusCancelBtn').style.display = 'none';
        }

        function addDocBlock(type, content = '') {
            const container = document.getElementById('docBlocksContainer');
            if (!container) return;
            const block = document.createElement('div');
            block.className = 'doc-sheet-row doc-block';
            block.setAttribute('data-type', type);
            let placeholder = 'Tulis sesuatu...';
            let contentClass = '';
            let blockContent = '';
            let isEditable = true;
            let src = '';
            let caption = '';
            let style = 'info';
            let icon = 'ⓘ';
            let text = '';
            if (type === 'image' || type === 'video') {
                isEditable = false;
                if (typeof content === 'object' && content !== null) {
                    src = content.src || '';
                    caption = content.caption || '';
                } else if (typeof content === 'string' && content.trim() !== '') {
                    try {
                        const parsed = JSON.parse(content);
                        src = parsed.src || '';
                        caption = parsed.caption || '';
                    } catch (e) {
                        src = content;
                    }
                }
            } else if (type === 'callout') {
                isEditable = false;
                if (typeof content === 'object' && content !== null) {
                    style = content.style || 'info';
                    icon = mapToMonochromeIcon(content.icon, 'ⓘ');
                    text = content.text || '';
                } else if (typeof content === 'string' && content.trim() !== '') {
                    try {
                        const parsed = JSON.parse(content);
                        style = parsed.style || 'info';
                        icon = mapToMonochromeIcon(parsed.icon, 'ⓘ');
                        text = parsed.text || '';
                    } catch (e) {
                        text = content;
                    }
                }
            }

            if (type === 'p') {
                placeholder = 'Tulis paragraf...';
                contentClass = 'p-block';
                blockContent = content;
            } else if (type === 'h2') {
                placeholder = 'Judul H2...';
                contentClass = 'h2-block';
                blockContent = content;
            } else if (type === 'h3') {
                placeholder = 'Subjudul H3...';
                contentClass = 'h3-block';
                blockContent = content;
            } else if (type === 'blockquote') {
                placeholder = 'Tulis kutipan...';
                contentClass = 'blockquote-block';
                blockContent = content;
            } else if (type === 'ol') {
                placeholder = '1. List item...';
                contentClass = 'ol-block';
                blockContent = content;
            } else if (type === 'ul') {
                placeholder = '• List item...';
                contentClass = 'ul-block';
                blockContent = content;
            } else if (type === 'pre') {
                placeholder = 'Kode program...';
                contentClass = 'pre-block';
                blockContent = content;
            } else if (type === 'table') {
                placeholder = 'Tabel HTML...';
                contentClass = 'table-block';
                blockContent = content;
            } else if (type === 'image') {
                placeholder = 'Keterangan gambar...';
                contentClass = 'image-block';
                const isUploaded = src ? true : false;
                blockContent = `
                    <div class="media-editor-container" contenteditable="false">
                        <div class="media-upload-area" style="display: ${isUploaded ? 'none' : 'flex'};">
                            <div class="media-upload-icon">📷</div>
                            <div class="media-upload-title">Sisipkan Gambar</div>
                            <div class="media-upload-desc">Pilih berkas gambar atau tempel tautan URL</div>
                            <div class="media-upload-actions">
                                <button type="button" class="btn-action-outline btn-media-upload" onclick="triggerBlockFileInput(this)">Pilih File</button>
                                <input type="file" accept="image/*" class="media-file-input-raw" style="display: none;" onchange="handleBlockImageUpload(this)">
                            </div>
                            <div class="media-upload-url-row">
                                <input type="text" class="form-input media-url-input-raw" placeholder="Tempel URL gambar (https://...)">
                                <button type="button" class="btn-action-outline" onclick="handleBlockImageUrlInput(this)">Terapkan</button>
                            </div>
                        </div>
                        <div class="media-preview-area" style="display: ${isUploaded ? 'block' : 'none'};">
                            <img class="media-preview-element" src="${src}" alt="Preview Gambar">
                            <button type="button" class="media-action-badge" onclick="resetMediaBlock(this)" title="Hapus Gambar">Hapus</button>
                        </div>
                        <div class="media-caption-editor" contenteditable="true" placeholder="Tulis keterangan gambar (opsional)...">${caption}</div>
                    </div>
                `;
            } else if (type === 'video') {
                placeholder = 'Keterangan video...';
                contentClass = 'video-block';
                const isUploaded = src ? true : false;
                const isEmbed = isUploaded && isVideoEmbed(src);
                const embedUrl = isEmbed ? getEmbedUrl(src) : '';
                blockContent = `
                    <div class="media-editor-container" contenteditable="false">
                        <div class="media-upload-area" style="display: ${isUploaded ? 'none' : 'flex'};">
                            <div class="media-upload-icon">🎥</div>
                            <div class="media-upload-title">Sisipkan Video</div>
                            <div class="media-upload-desc">Pilih video (MP4/WebM) atau tempel tautan video/YouTube</div>
                            <div class="media-upload-actions">
                                <button type="button" class="btn-action-outline btn-media-upload" onclick="triggerBlockFileInput(this)">Pilih File</button>
                                <input type="file" accept="video/*" class="media-file-input-raw" style="display: none;" onchange="handleBlockVideoUpload(this)">
                            </div>
                            <div class="media-upload-url-row">
                                <input type="text" class="form-input media-url-input-raw" placeholder="Tempel URL video / YouTube (https://...)">
                                <button type="button" class="btn-action-outline" onclick="handleBlockVideoUrlInput(this)">Terapkan</button>
                            </div>
                        </div>
                        <div class="media-preview-area" style="display: ${isUploaded ? 'block' : 'none'};">
                            <div class="media-video-container-element">
                                ${isEmbed ? `<iframe class="media-preview-iframe" src="${embedUrl}" frameborder="0" allowfullscreen></iframe>` : `<video class="media-preview-video-tag" src="${src}" controls></video>`}

                            </div>
                            <button type="button" class="media-action-badge" onclick="resetMediaBlock(this)" title="Hapus Video">Hapus</button>
                        </div>
                        <div class="media-caption-editor" contenteditable="true" placeholder="Tulis keterangan video (opsional)...">${caption}</div>
                    </div>
                `;
            } else if (type === 'callout') {
                placeholder = 'Tulis info box...';
                contentClass = 'callout-block';
                blockContent = `
                    <div class="callout-editor-container" contenteditable="false">
                        <div class="callout-editor-header">
                            <select class="form-select callout-style-select" onchange="handleCalloutStyleChange(this)">
                                <option value="info" ${style === 'info' ? 'selected' : ''}>ⓘ Info</option>
                                <option value="warning" ${style === 'warning' ? 'selected' : ''}>⚠ Peringatan</option>
                                <option value="success" ${style === 'success' ? 'selected' : ''}>✓ Sukses / Tip</option>
                                <option value="danger" ${style === 'danger' ? 'selected' : ''}>✖ Bahaya</option>
                            </select>
                        </div>
                        <div class="callout-editor-body callout-${style}">
                            <input type="text" class="callout-icon-input" value="${icon}" placeholder="ⓘ" onchange="handleCalloutIconChange(this)">
                            <div class="callout-editable-content" contenteditable="true" placeholder="Masukkan konten info box di sini...">${text}</div>
                        </div>
                    </div>
                `;
            }

            block.innerHTML = `
                <div class="doc-row-controls">
                    <button type="button" class="doc-row-control-btn" onclick="moveBlockRow(this, 'up')" title="Pindahkan ke atas">▲</button>
                    <button type="button" class="doc-row-control-btn" onclick="moveBlockRow(this, 'down')" title="Pindahkan ke bawah">▼</button>
                </div>
                <div class="doc-block-content ${contentClass}" ${isEditable ? 'contenteditable="true"' : ''} placeholder="${placeholder}">${blockContent}</div>
                <button type="button" class="doc-row-remove-btn" onclick="removeBlock(this)" title="Hapus Blok">&times;</button>
            `;
            container.appendChild(block);
            // Focus the editable part

            if (isEditable) {
                const editable = block.querySelector('.doc-block-content');
                if (editable) editable.focus();
            } else {
                if (type === 'callout') {
                    const calloutEditable = block.querySelector('.callout-editable-content');
                    if (calloutEditable) calloutEditable.focus();
                } else {
                    const captionEditable = block.querySelector('.media-caption-editor');
                    if (captionEditable) captionEditable.focus();
                }
            }

            // Update sidebar outline dynamically

            updateDocSidebarOutline();
        }

        function removeBlock(button) {
            const block = button.closest('.doc-block');
            if (block) {
                block.remove();
                updateDocSidebarOutline();
            }
        }

        function insertTableBlock() {
            const rows = prompt("Masukkan jumlah baris:", "3");
            const cols = prompt("Masukkan jumlah kolom:", "3");
            if (!rows || !cols) return;
            let tableHtml = '<table style="width:100%; border-collapse:collapse; margin:16px 0;">';
            for (let r = 0; r < parseInt(rows); r++) {
                tableHtml += '<tr>';
                for (let c = 0; c < parseInt(cols); c++) {
                    tableHtml += '<td style="border:1px solid rgba(255,255,255,0.08); padding:8px; min-width:50px;">Sel</td>';
                }

                tableHtml += '</tr>';
            }

            tableHtml += '</table>';
            addDocBlock('table', tableHtml);
        }

        function setSubmateriEditMode(id, courseId, title, description, icon, order) {
            document.getElementById('submateriForm').action = '/admin/submateri/' + id;
            document.getElementById('submateriMethodInput').value = 'PUT';
            document.getElementById('submateriInputCourseId').value = courseId;
            document.getElementById('submateriInputTitle').value = title;
            document.getElementById('submateriInputIcon').value = icon;
            document.getElementById('submateriInputOrder').value = order;
            document.getElementById('submateriInputDesc').value = description;
            const container = document.getElementById('docBlocksContainer');
            if (container) {
                container.innerHTML = ''; // Clear existing blocks
            }

            // Parse description for Block structure

            if (description) {
                const parser = new DOMParser();
                const doc = parser.parseFromString(description, 'text/html');
                // Inspect child nodes in the parsed document body

                doc.body.childNodes.forEach(node => {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        const tag = node.tagName.toLowerCase();
                        const content = node.innerHTML;
                        if (tag === 'figure' && node.classList.contains('doc-media-image')) {
                            const img = node.querySelector('img');
                            const figcaption = node.querySelector('figcaption');
                            const src = img ? img.getAttribute('src') : '';
                            const caption = figcaption ? figcaption.innerHTML : '';
                            addDocBlock('image', { src: src, caption: caption });
                        } else if (tag === 'figure' && node.classList.contains('doc-media-video')) {
                            const video = node.querySelector('video');
                            const iframe = node.querySelector('iframe');
                            const figcaption = node.querySelector('figcaption');
                            const src = video ? video.getAttribute('src') : (iframe ? iframe.getAttribute('src') : '');
                            const caption = figcaption ? figcaption.innerHTML : '';
                            addDocBlock('video', { src: src, caption: caption });
                        } else if (tag === 'div' && node.classList.contains('doc-callout')) {
                            let calloutStyle = 'info';
                            if (node.classList.contains('callout-warning')) calloutStyle = 'warning';
                            else if (node.classList.contains('callout-success')) calloutStyle = 'success';
                            else if (node.classList.contains('callout-danger')) calloutStyle = 'danger';
                            const iconEl = node.querySelector('.callout-icon');
                            const contentEl = node.querySelector('.callout-content');
                            const icon = iconEl ? iconEl.innerText.trim() : 'ⓘ';
                            const text = contentEl ? contentEl.innerHTML : '';
                            addDocBlock('callout', { style: calloutStyle, icon: mapToMonochromeIcon(icon), text: text });
                        } else if (tag === 'img') {
                            addDocBlock('image', { src: node.getAttribute('src'), caption: node.getAttribute('alt') || '' });
                        } else if (tag === 'video') {
                            addDocBlock('video', { src: node.getAttribute('src'), caption: '' });
                        } else if (tag === 'h2') {
                            addDocBlock('h2', content);
                        } else if (tag === 'h3') {
                            addDocBlock('h3', content);
                        } else if (tag === 'blockquote') {
                            addDocBlock('blockquote', content);
                        } else if (tag === 'p') {
                            addDocBlock('p', content);
                        } else if (tag === 'ul') {
                            addDocBlock('ul', content);
                        } else if (tag === 'ol') {
                            addDocBlock('ol', content);
                        } else if (tag === 'pre') {
                            addDocBlock('pre', content);
                        } else if (tag === 'table') {
                            addDocBlock('table', node.outerHTML);
                        } else {
                            // Any other element, treat as paragraph block containing the outer HTML

                            addDocBlock('p', node.outerHTML);
                        }
                    } else if (node.nodeType === Node.TEXT_NODE && node.textContent.trim()) {
                        addDocBlock('p', node.textContent.trim());
                    }
                });
            }

            document.getElementById('submateriFormTitle').innerText = 'Ubah Sub Materi';
            document.getElementById('submateriFormSubtitle').innerText = 'Perbarui data sub materi belajar ini';
            document.getElementById('submateriCancelBtn').style.display = 'block';
            document.getElementById('submateriFormTitle').scrollIntoView({ behavior: 'smooth' });
            // Update sidebar outline dynamically

            updateDocSidebarOutline();
        }

        function resetSubmateriForm() {
            document.getElementById('submateriForm').action = '{{ route("admin.submateri.store") }}';
            document.getElementById('submateriMethodInput').value = 'POST';
            document.getElementById('submateriForm').reset();
            const container = document.getElementById('docBlocksContainer');
            if (container) {
                container.innerHTML = ''; // Reset container empty!
            }

            document.getElementById('submateriFormTitle').innerText = 'Tambah Sub Materi';
            document.getElementById('submateriFormSubtitle').innerText = 'Masukkan data untuk menambahkan sub materi belajar baru';
            document.getElementById('submateriCancelBtn').style.display = 'none';
            // Reset sidebar outline

            updateDocSidebarOutline();
        }

        // Sidebar Dynamic Outline & Drag-and-Drop Functions

        let dragSourceEl = null;
        let dragCounter = 0;
        let outlineDebounceTimeout = null;
        function updateDocSidebarOutlineDebounced() {
            clearTimeout(outlineDebounceTimeout);
            outlineDebounceTimeout = setTimeout(updateDocSidebarOutline, 300);
        }

        function updateDocSidebarOutline() {
            const container = document.getElementById('docSidebarOutline');
            if (!container) return;
            const blocks = document.querySelectorAll('#docBlocksContainer .doc-block');
            if (blocks.length === 0) {
                container.innerHTML = `
                    <div style="text-align: center; color: rgba(255,255,255,0.15); font-size: 11px; padding: 40px 10px;">
                        Belum ada struktur.<br>Tambahkan lewat toolbar di atas.
                    </div>
                `;
                return;
            }

            let outlineHtml = '';
            blocks.forEach((block, index) => {
                let blockId = block.getAttribute('id');
                if (!blockId) {
                    blockId = `doc-block-${Date.now()}-${index}`;
                    block.setAttribute('id', blockId);
                }

                const type = block.getAttribute('data-type');
                const contentEl = block.querySelector('.doc-block-content');
                let rawContent = contentEl ? contentEl.innerText.trim() : '';
                let badge = 'PR';
                let itemClass = 'outline-p';
                let label = 'Paragraf';
                if (type === 'h2') {
                    badge = 'H2';
                    itemClass = 'outline-h2';
                    label = 'Judul';
                } else if (type === 'h3') {
                    badge = 'H3';
                    itemClass = 'outline-h3';
                    label = 'Subjudul';
                } else if (type === 'blockquote') {
                    badge = 'KP';
                    itemClass = 'outline-blockquote';
                    label = 'Kutipan';
                } else if (type === 'ul') {
                    badge = 'PL';
                    itemClass = 'outline-p';
                    label = 'Point List Bab';
                } else if (type === 'ol') {
                    badge = 'PL';
                    itemClass = 'outline-p';
                    label = 'Point List number';
                } else if (type === 'pre') {
                    badge = 'CD';
                    itemClass = 'outline-pre';
                    label = 'Kode Block';
                } else if (type === 'table') {
                    badge = 'TB';
                    itemClass = 'outline-table';
                    label = 'Tabel';
                    rawContent = 'Tabel Data';
                } else if (type === 'image') {
                    badge = 'IM';
                    itemClass = 'outline-image';
                    label = 'Gambar';
                    const capEl = block.querySelector('.media-caption-editor');
                    const previewImg = block.querySelector('.media-preview-element');
                    const captionText = capEl ? capEl.innerText.trim() : '';
                    if (captionText) {
                        rawContent = `Gambar: ${captionText}`;
                    } else if (previewImg && previewImg.src && !previewImg.src.endsWith('/')) {
                        const filename = previewImg.src.substring(previewImg.src.lastIndexOf('/') + 1);
                        rawContent = `Gambar: ${filename}`;
                    } else {
                        rawContent = 'Gambar (Belum diunggah)';
                    }
                } else if (type === 'video') {
                    badge = 'VD';
                    itemClass = 'outline-video';
                    label = 'Video';
                    const capEl = block.querySelector('.media-caption-editor');
                    const videoTag = block.querySelector('.media-preview-video-tag');
                    const iframeTag = block.querySelector('.media-preview-iframe');
                    const captionText = capEl ? capEl.innerText.trim() : '';
                    if (captionText) {
                        rawContent = `Video: ${captionText}`;
                    } else if (videoTag && videoTag.src && !videoTag.src.endsWith('/')) {
                        const filename = videoTag.src.substring(videoTag.src.lastIndexOf('/') + 1);
                        rawContent = `Video: ${filename}`;
                    } else if (iframeTag && iframeTag.src) {
                        rawContent = 'Video: YouTube Embed';
                    } else {
                        rawContent = 'Video (Belum diunggah)';
                    }
                } else if (type === 'callout') {
                    badge = 'CO';
                    itemClass = 'outline-callout';
                    label = 'Info Box';
                    const styleSelect = block.querySelector('.callout-style-select');
                    const editableEl = block.querySelector('.callout-editable-content');
                    const styleName = styleSelect ? styleSelect.options[styleSelect.selectedIndex].text : 'Info';
                    const textVal = editableEl ? editableEl.innerText.trim() : '';
                    rawContent = `Info Box (${styleName})${textVal ? ': ' + textVal : ''}`;
                }

                const previewText = rawContent || `[${label} Kosong]`;
                outlineHtml += `
                    <div class="doc-sidebar-outline-item ${itemClass}" 
                         data-block-id="${blockId}" 
                         data-index="${index}">
                        <div class="doc-sidebar-drag-handle" title="Tarik untuk memindahkan">⋮⋮</div>
                        <div class="doc-sidebar-outline-badge">${badge}</div>
                        <div class="doc-sidebar-outline-label" title="${previewText}" onclick="scrollToAndFocusBlock('${blockId}')">${previewText}</div>
                        <button type="button" class="doc-sidebar-outline-remove" onclick="event.stopPropagation(); removeBlockById('${blockId}')" title="Hapus Blok">&times;</button>
                    </div>
                `;
            });
            container.innerHTML = outlineHtml;
            initDragAndDrop();
        }

        function scrollToAndFocusBlock(blockId) {
            const block = document.getElementById(blockId);
            if (block) {
                block.scrollIntoView({ behavior: 'smooth', block: 'center' });
                // Highlight block momentarily for premium visual feedback

                block.style.transition = 'background 0.3s ease';
                block.style.background = 'rgba(139, 92, 246, 0.08)';
                setTimeout(() => {
                    block.style.background = '';
                }, 1000);
                const editable = block.querySelector('.doc-block-content');
                if (editable) {
                    editable.focus();
                }
            }
        }

        function removeBlockById(blockId) {
            const block = document.getElementById(blockId);
            if (block) {
                block.remove();
                updateDocSidebarOutline();
            }
        }

        function moveBlockRow(button, direction) {
            const row = button.closest('.doc-block');
            if (!row) return;
            const container = document.getElementById('docBlocksContainer');
            if (!container) return;
            if (direction === 'up') {
                const prev = row.previousElementSibling;
                if (prev && prev.classList.contains('doc-block')) {
                    container.insertBefore(row, prev);
                }
            } else if (direction === 'down') {
                const next = row.nextElementSibling;
                if (next && next.classList.contains('doc-block')) {
                    container.insertBefore(row, next.nextElementSibling);
                }
            }

            updateDocSidebarOutline();
        }

        let _dragInitialized = false;
        function initDragAndDrop() {
            if (_dragInitialized) return;
            _dragInitialized = true;
            const container = document.getElementById('docSidebarOutline');
            if (!container) { _dragInitialized = false; return; }

            let dragState = null;
            container.addEventListener('mousedown', function (e) {
                // Only start drag from the drag handle or the item itself (not button/remove)

                if (e.target.closest('.doc-sidebar-outline-remove')) return;
                if (e.button !== 0) return; // left click only
                const item = e.target.closest('.doc-sidebar-outline-item');
                if (!item) return;
                const startX = e.clientX;
                const startY = e.clientY;
                const blockId = item.getAttribute('data-block-id');
                const labelEl = item.querySelector('.doc-sidebar-outline-label');
                const badgeEl = item.querySelector('.doc-sidebar-outline-badge');
                const labelText = labelEl ? labelEl.textContent : '';
                const badgeText = badgeEl ? badgeEl.textContent : '';
                dragState = {
                    item: item,
                    blockId: blockId,
                    startX: startX,
                    startY: startY,
                    ghost: null,
                    indicator: null,
                    started: false,
                    labelText: labelText,
                    badgeText: badgeText
                };
                e.preventDefault();
            });
            document.addEventListener('mousemove', function (e) {
                if (!dragState) return;
                const dx = Math.abs(e.clientX - dragState.startX);
                const dy = Math.abs(e.clientY - dragState.startY);
                // Start drag after 5px movement (deadzone to allow clicks)

                if (!dragState.started) {
                    if (dx < 5 && dy < 5) return;
                    dragState.started = true;
                    dragState.item.classList.add('is-dragging');
                    // Create ghost element

                    const ghost = document.createElement('div');
                    ghost.className = 'doc-sidebar-outline-ghost';
                    ghost.innerHTML = `<span style="font-size:9px;font-weight:800;opacity:0.7">${dragState.badgeText}</span> ${dragState.labelText}`;
                    document.body.appendChild(ghost);
                    dragState.ghost = ghost;
                    // Create drop indicator

                    const indicator = document.createElement('div');
                    indicator.className = 'doc-sidebar-drop-indicator';
                    dragState.indicator = indicator;
                }

                // Move ghost

                if (dragState.ghost) {
                    dragState.ghost.style.left = (e.clientX + 14) + 'px';
                    dragState.ghost.style.top = (e.clientY - 14) + 'px';
                }

                // Find closest item to insert indicator

                const items = Array.from(container.querySelectorAll('.doc-sidebar-outline-item:not(.is-dragging)'));
                let closestItem = null;
                let insertBefore = true;
                let minDist = Infinity;
                items.forEach(function (itm) {
                    const rect = itm.getBoundingClientRect();
                    const midY = rect.top + rect.height / 2;
                    const dist = Math.abs(e.clientY - midY);
                    if (dist < minDist) {
                        minDist = dist;
                        closestItem = itm;
                        insertBefore = e.clientY < midY;
                    }
                });
                // Remove existing indicator

                if (dragState.indicator && dragState.indicator.parentNode) {
                    dragState.indicator.parentNode.removeChild(dragState.indicator);
                }

                // Insert indicator at drop position

                if (closestItem && dragState.indicator) {
                    if (insertBefore) {
                        container.insertBefore(dragState.indicator, closestItem);
                    } else {
                        container.insertBefore(dragState.indicator, closestItem.nextSibling);
                    }

                    dragState.insertBeforeItem = insertBefore ? closestItem : (closestItem.nextElementSibling && closestItem.nextElementSibling !== dragState.indicator ? closestItem.nextElementSibling : null);
                    dragState.closestItem = closestItem;
                    dragState.insertBeforeFlag = insertBefore;
                }
            });
            document.addEventListener('mouseup', function (e) {
                if (!dragState) return;
                if (dragState.started) {
                    // Perform the actual DOM swap in docBlocksContainer

                    const sourceBlockId = dragState.blockId;
                    const sourceBlock = document.getElementById(sourceBlockId);
                    const blocksContainer = document.getElementById('docBlocksContainer');
                    if (sourceBlock && blocksContainer && dragState.closestItem) {
                        const targetBlockId = dragState.closestItem.getAttribute('data-block-id');
                        const targetBlock = document.getElementById(targetBlockId);
                        if (targetBlock && sourceBlock !== targetBlock) {
                            if (dragState.insertBeforeFlag) {
                                blocksContainer.insertBefore(sourceBlock, targetBlock);
                            } else {
                                blocksContainer.insertBefore(sourceBlock, targetBlock.nextElementSibling);
                            }
                        }
                    }

                    // Cleanup

                    dragState.item.classList.remove('is-dragging');
                    if (dragState.ghost && dragState.ghost.parentNode) {
                        dragState.ghost.parentNode.removeChild(dragState.ghost);
                    }

                    if (dragState.indicator && dragState.indicator.parentNode) {
                        dragState.indicator.parentNode.removeChild(dragState.indicator);
                    }

                    dragState = null;
                    updateDocSidebarOutline();
                } else {
                    // Was a click, not a drag — trigger scrollToAndFocusBlock

                    const clickedLabel = e.target.closest('.doc-sidebar-outline-label');
                    const clickedItem = e.target.closest('.doc-sidebar-outline-item');
                    if (clickedItem && !e.target.closest('.doc-sidebar-outline-remove')) {
                        scrollToAndFocusBlock(dragState.blockId);
                    }

                    dragState = null;
                }
            });
        }

        /* Image, Video and Callout Helper Functions */

        function isVideoEmbed(url) {
            if (!url) return false;
            return url.includes('youtube.com') || url.includes('youtu.be') || url.includes('vimeo.com');
        }

        function getEmbedUrl(url) {
            if (!url) return '';
            if (url.includes('youtube.com') || url.includes('youtu.be')) {
                let videoId = '';
                if (url.includes('youtu.be/')) {
                    videoId = url.split('youtu.be/')[1].split(/[?#]/)[0];
                } else if (url.includes('embed/')) {
                    videoId = url.split('embed/')[1].split(/[?#]/)[0];
                } else if (url.includes('v=')) {
                    videoId = url.split('v=')[1].split('&')[0];
                }

                return `https://www.youtube.com/embed/${videoId}`;
            } else if (url.includes('vimeo.com')) {
                let videoId = url.split('vimeo.com/')[1].split(/[?#]/)[0];
                return `https://player.vimeo.com/video/${videoId}`;
            }

            return url;
        }

        function triggerBlockFileInput(btn) {
            const container = btn.closest('.media-upload-area');
            const fileInput = container.querySelector('.media-file-input-raw');
            if (fileInput) {
                fileInput.click();
            }
        }

        function uploadMediaFile(file, onSuccess, onError) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('_token', '{{ csrf_token() }}');
            fetch('{{ route("admin.media.upload") }}', {
                method: 'POST',
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        onSuccess(data.url);
                    } else {
                        onError(data.error || 'Terjadi kesalahan saat mengunggah');
                    }
                })
                .catch(err => {
                    onError('Gagal mengunggah berkas: ' + err.message);
                });
        }

        function handleBlockImageUpload(input) {
            if (!input.files || input.files.length === 0) return;
            const file = input.files[0];
            const container = input.closest('.media-editor-container');
            const uploadArea = container.querySelector('.media-upload-area');
            const previewArea = container.querySelector('.media-preview-area');
            const previewImg = container.querySelector('.media-preview-element');
            const uploadBtn = uploadArea.querySelector('.btn-media-upload');
            const origText = uploadBtn.innerText;
            uploadBtn.innerText = 'Mengunggah...';
            uploadBtn.disabled = true;
            uploadMediaFile(file, function (url) {
                uploadBtn.innerText = origText;
                uploadBtn.disabled = false;
                previewImg.src = url;
                uploadArea.style.display = 'none';
                previewArea.style.display = 'block';
                updateDocSidebarOutlineDebounced();
            }, function (error) {
                uploadBtn.innerText = origText;
                uploadBtn.disabled = false;
                alert(error);
            });
        }

        function handleBlockVideoUpload(input) {
            if (!input.files || input.files.length === 0) return;
            const file = input.files[0];
            const container = input.closest('.media-editor-container');
            const uploadArea = container.querySelector('.media-upload-area');
            const previewArea = container.querySelector('.media-preview-area');
            const previewContainer = container.querySelector('.media-video-container-element');
            const uploadBtn = uploadArea.querySelector('.btn-media-upload');
            const origText = uploadBtn.innerText;
            uploadBtn.innerText = 'Mengunggah...';
            uploadBtn.disabled = true;
            uploadMediaFile(file, function (url) {
                uploadBtn.innerText = origText;
                uploadBtn.disabled = false;
                previewContainer.innerHTML = `<video class="media-preview-video-tag" src="${url}" controls></video>`;
                uploadArea.style.display = 'none';
                previewArea.style.display = 'block';
                updateDocSidebarOutlineDebounced();
            }, function (error) {
                uploadBtn.innerText = origText;
                uploadBtn.disabled = false;
                alert(error);
            });
        }

        function handleBlockImageUrlInput(btn) {
            const container = btn.closest('.media-editor-container');
            const urlInput = container.querySelector('.media-url-input-raw');
            const uploadArea = container.querySelector('.media-upload-area');
            const previewArea = container.querySelector('.media-preview-area');
            const previewImg = container.querySelector('.media-preview-element');
            const url = urlInput.value.trim();
            if (!url) return;
            previewImg.src = url;
            uploadArea.style.display = 'none';
            previewArea.style.display = 'block';
            updateDocSidebarOutlineDebounced();
        }

        function handleBlockVideoUrlInput(btn) {
            const container = btn.closest('.media-editor-container');
            const urlInput = container.querySelector('.media-url-input-raw');
            const uploadArea = container.querySelector('.media-upload-area');
            const previewArea = container.querySelector('.media-preview-area');
            const previewContainer = container.querySelector('.media-video-container-element');
            const url = urlInput.value.trim();
            if (!url) return;
            const isEmbed = isVideoEmbed(url);
            if (isEmbed) {
                previewContainer.innerHTML = `<iframe class="media-preview-iframe" src="${getEmbedUrl(url)}" frameborder="0" allowfullscreen></iframe>`;
            } else {
                previewContainer.innerHTML = `<video class="media-preview-video-tag" src="${url}" controls></video>`;
            }

            uploadArea.style.display = 'none';
            previewArea.style.display = 'block';
            updateDocSidebarOutlineDebounced();
        }

        function resetMediaBlock(btn) {
            const container = btn.closest('.media-editor-container');
            const uploadArea = container.querySelector('.media-upload-area');
            const previewArea = container.querySelector('.media-preview-area');
            const previewContainer = container.querySelector('.media-video-container-element');
            const previewImg = container.querySelector('.media-preview-element');
            const urlInput = container.querySelector('.media-url-input-raw');
            const fileInput = container.querySelector('.media-file-input-raw');
            if (previewImg) previewImg.src = '';
            if (previewContainer) previewContainer.innerHTML = '';
            if (urlInput) urlInput.value = '';
            if (fileInput) fileInput.value = '';
            previewArea.style.display = 'none';
            uploadArea.style.display = 'flex';
            updateDocSidebarOutlineDebounced();
        }

        function mapToMonochromeIcon(icon, defaultVal = 'ⓘ') {
            const trimmed = (icon || '').trim();
            switch (trimmed) {
                case '💡':
                case 'ℹ️':
                case 'ⓘ':
                case 'info':
                    return 'ⓘ';
                case '⚠️':
                case '⚠':
                case 'warning':
                    return '⚠';
                case '✅':
                case '✔️':
                case '✓':
                case 'success':
                    return '✓';
                case '🚨':
                case '🔥':
                case '✖':
                case 'danger':
                    return '✖';
                default:
                    return trimmed || defaultVal;
            }
        }

        function handleCalloutStyleChange(select) {
            const container = select.closest('.callout-editor-container');
            const body = container.querySelector('.callout-editor-body');
            const iconInput = container.querySelector('.callout-icon-input');
            // Remove existing style classes

            body.className = 'callout-editor-body';
            body.classList.add('callout-' + select.value);
            // Change icon automatically if it matches standard defaults

            const currentIcon = iconInput.value.trim();
            const standardDefaults = ['💡', '⚠️', '✅', '🚨', 'ℹ️', '✔️', '🔥', 'ⓘ', '⚠', '✓', '✖'];
            if (standardDefaults.includes(currentIcon) || currentIcon === '') {
                let defaultIcon = 'ⓘ';
                if (select.value === 'warning') defaultIcon = '⚠';
                else if (select.value === 'success') defaultIcon = '✓';
                else if (select.value === 'danger') defaultIcon = '✖';
                iconInput.value = defaultIcon;
            }

            updateDocSidebarOutlineDebounced();
        }

        function handleCalloutIconChange(input) {
            updateDocSidebarOutlineDebounced();
        }

        /* Document editor functions */

        function formatDoc(command, value = null) {
            document.execCommand(command, false, value);
        }

        function setQuizEditMode(id, lessonId, question, correctAnswer, explanation, optA, optB, optC, optD) {
            document.getElementById('quizForm').action = '/admin/quizzes/' + id;
            document.getElementById('quizMethodInput').value = 'PUT';
            document.getElementById('qzLessonId').value = lessonId;
            document.getElementById('qzQuestion').value = question;
            document.getElementById('qzCorrect').value = correctAnswer;
            document.getElementById('qzExpl').value = explanation;
            document.getElementById('qzOptA').value = optA;
            document.getElementById('qzOptB').value = optB;
            document.getElementById('qzOptC').value = optC;
            document.getElementById('qzOptD').value = optD;
            document.getElementById('quizFormTitle').innerText = 'Ubah Soal Kuis';
            document.getElementById('quizFormSubtitle').innerText = 'Ubah pengaturan kuis pilihan ganda yang dipilih';
            document.getElementById('quizCancelBtn').style.display = 'block';
            const formCard = document.getElementById('quizFormTitle');
            if (formCard) {
                formCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        function resetQuizForm() {
            document.getElementById('quizForm').action = '{{ route("admin.quizzes.store") }}';
            document.getElementById('quizMethodInput').value = 'POST';
            document.getElementById('quizForm').reset();
            document.getElementById('quizFormTitle').innerText = 'Tambah Soal Baru';
            document.getElementById('quizFormSubtitle').innerText = 'Masukkan data untuk menambahkan kuis pilihan ganda baru';
            document.getElementById('quizCancelBtn').style.display = 'none';
        }

        function openEditExp(id, name, exp) {
            document.getElementById('expModalDesc').textContent = `Ubah akumulasi EXP untuk pengguna "${name}".`;
            document.getElementById('expInput').value = exp;
            document.getElementById('editExpForm').action = '/admin/users/' + id + '/exp';
            openModal('editExpModal');
        }

        function switchDbMode(mode) {
            const container = document.getElementById('db-grid-container');
            const buttons = document.querySelectorAll('.db-mode-btn');
            buttons.forEach(btn => {
                if (parseInt(btn.getAttribute('data-mode')) === mode) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
            container.classList.remove('mode-1', 'mode-2', 'mode-3');
            container.classList.add('mode-' + mode);
            if (mode === 3) {
                setTimeout(() => {
                    drawDbRelations();
                    makeDbBoxesDraggable();
                }, 150);
            }
        }

        function drawDbRelations() {
            const svg = document.getElementById('db-relations-svg');
            if (!svg) return;
            const activeTable = window.activeDbTable;
            svg.innerHTML = `
                <defs>
                    <marker id="arrow" viewBox="0 0 10 10" refX="6" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
                        <path d="M 0 1.5 L 8 5 L 0 8.5 z" fill="rgba(255, 255, 255, 0.35)" />
                    </marker>
                    <marker id="dot" viewBox="0 0 10 10" refX="5" refY="5" markerWidth="6" markerHeight="6">
                        <circle cx="5" cy="5" r="3.5" fill="rgba(255, 255, 255, 0.6)" />
                    </marker>
                    <marker id="arrow-active" viewBox="0 0 10 10" refX="6" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
                        <path d="M 0 1.5 L 8 5 L 0 8.5 z" fill="var(--accent-color)" />
                    </marker>
                    <marker id="dot-active" viewBox="0 0 10 10" refX="5" refY="5" markerWidth="6" markerHeight="6">
                        <circle cx="5" cy="5" r="3.5" fill="var(--accent-color)" />
                    </marker>
                    <marker id="arrow-dim" viewBox="0 0 10 10" refX="6" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
                        <path d="M 0 1.5 L 8 5 L 0 8.5 z" fill="rgba(255, 255, 255, 0.1)" />
                    </marker>
                    <marker id="dot-dim" viewBox="0 0 10 10" refX="5" refY="5" markerWidth="6" markerHeight="6">
                        <circle cx="5" cy="5" r="3.5" fill="rgba(255, 255, 255, 0.15)" />
                    </marker>
                </defs>
            `;
            const canvas = document.querySelector('.db-roadmap-canvas');
            if (!canvas) return;
            const canvasRect = canvas.getBoundingClientRect();
            const foreignKeys = document.querySelectorAll('.db-table-box-row[data-ref]');
            foreignKeys.forEach(fkNode => {
                const refTargetId = fkNode.getAttribute('data-ref');
                const [targetTableName, targetColName] = refTargetId.split('.');
                const targetNode = document.getElementById(`node-${targetTableName}-${targetColName}`);
                if (fkNode && targetNode) {
                    const sourceBox = fkNode.closest('.db-table-box');
                    const targetBox = targetNode.closest('.db-table-box');
                    if (!sourceBox || !targetBox) return;
                    const fkRect = fkNode.getBoundingClientRect();
                    const targetRect = targetNode.getBoundingClientRect();
                    const sourceBoxRect = sourceBox.getBoundingClientRect();
                    const targetBoxRect = targetBox.getBoundingClientRect();
                    const y1 = fkRect.top + (fkRect.height / 2) - canvasRect.top;
                    const y2 = targetRect.top + (targetRect.height / 2) - canvasRect.top;
                    const sourceLeft = sourceBoxRect.left - canvasRect.left;
                    const sourceRight = sourceBoxRect.right - canvasRect.left;
                    const targetLeft = targetBoxRect.left - canvasRect.left;
                    const targetRight = targetBoxRect.right - canvasRect.left;
                    const tableName = sourceBox.id.replace('db-box-', '');
                    let x1, x2, controlX1, controlX2;
                    // Case 1: Source table is to the left of Target table

                    if (sourceRight < targetLeft - 20) {
                        x1 = sourceRight;
                        x2 = targetLeft;
                        const dx = (x2 - x1) * 0.5;
                        controlX1 = x1 + dx;
                        controlX2 = x2 - dx;
                    }

                    // Case 2: Source table is to the right of Target table

                    else if (targetRight < sourceLeft - 20) {
                        x1 = sourceLeft;
                        x2 = targetRight;
                        const dx = (x1 - x2) * 0.5;
                        controlX1 = x1 - dx;
                        controlX2 = x2 + dx;
                    }

                    // Case 3: Vertically aligned (same column)

                    else {
                        if (sourceLeft < canvasRect.width / 2) {
                            x1 = sourceLeft;
                            x2 = targetLeft;
                            controlX1 = x1 - 50;
                            controlX2 = x2 - 50;
                        } else {
                            x1 = sourceRight;
                            x2 = targetRight;
                            controlX1 = x1 + 50;
                            controlX2 = x2 + 50;
                        }
                    }

                    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                    path.setAttribute('d', `M ${x1} ${y1} C ${controlX1} ${y1}, ${controlX2} ${y2}, ${x2} ${y2}`);
                    path.setAttribute('fill', 'none');
                    let isRelated = false;
                    if (activeTable) {
                        if (tableName === activeTable || targetTableName === activeTable) {
                            isRelated = true;
                        }
                    }

                    if (activeTable) {
                        if (isRelated) {
                            path.setAttribute('stroke', 'var(--accent-color)');
                            path.setAttribute('stroke-width', '2.5');
                            path.setAttribute('marker-start', 'url(#dot-active)');
                            path.setAttribute('marker-end', 'url(#arrow-active)');
                            path.style.opacity = '0.9';
                        } else {
                            path.setAttribute('stroke', 'rgba(255, 255, 255, 0.08)');
                            path.setAttribute('stroke-width', '1.5');
                            path.setAttribute('marker-start', 'url(#dot-dim)');
                            path.setAttribute('marker-end', 'url(#arrow-dim)');
                            path.style.opacity = '0.2';
                        }
                    } else {
                        path.setAttribute('stroke', 'rgba(255, 255, 255, 0.25)');
                        path.setAttribute('stroke-width', '2');
                        path.setAttribute('marker-start', 'url(#dot)');
                        path.setAttribute('marker-end', 'url(#arrow)');
                        path.style.opacity = '0.65';
                    }

                    path.addEventListener('mouseenter', () => {
                        if (!activeTable) {
                            path.setAttribute('stroke', 'rgba(255, 255, 255, 0.85)');
                            path.setAttribute('stroke-width', '3');
                            path.style.opacity = '1';
                        }
                    });
                    path.addEventListener('mouseleave', () => {
                        if (!activeTable) {
                            path.setAttribute('stroke', 'rgba(255, 255, 255, 0.25)');
                            path.setAttribute('stroke-width', '2');
                            path.style.opacity = '0.65';
                        }
                    });
                    svg.appendChild(path);
                }
            });
        }

        function makeDbBoxesDraggable() {
            const boxes = document.querySelectorAll('.db-table-box');
            const canvas = document.querySelector('.db-roadmap-canvas');
            if (!canvas) return;
            // Bind click listener to canvas for deselecting (once)

            if (!canvas.dataset.hasClickListener) {
                canvas.addEventListener('click', (e) => {
                    if (e.target === canvas || e.target.id === 'db-relations-svg') {
                        window.activeDbTable = null;
                        document.querySelectorAll('.db-table-box').forEach(b => b.classList.remove('active'));
                        drawDbRelations();
                    }
                });
                canvas.dataset.hasClickListener = 'true';
            }

            boxes.forEach(box => {
                const header = box.querySelector('.db-table-box-header');
                if (!header) return;
                header.style.cursor = 'grab';
                // Prevent click behavior on dragging

                let isDragging = false;
                // Click selection event

                if (!box.dataset.hasClickListener) {
                    box.addEventListener('click', (e) => {
                        // Ignore row actions or dragging

                        if (e.target.closest('.db-table-box-row')) return;
                        e.stopPropagation();
                        const tableName = box.id.replace('db-box-', '');
                        if (window.activeDbTable === tableName) {
                            window.activeDbTable = null;
                            box.classList.remove('active');
                        } else {
                            document.querySelectorAll('.db-table-box').forEach(b => b.classList.remove('active'));
                            window.activeDbTable = tableName;
                            box.classList.add('active');
                        }

                        drawDbRelations();
                    });
                    box.dataset.hasClickListener = 'true';
                }

                header.addEventListener('mousedown', (e) => {
                    e.preventDefault();
                    header.style.cursor = 'grabbing';
                    box.style.zIndex = '1000';
                    isDragging = false;
                    const startX = e.clientX;
                    const startY = e.clientY;
                    const rect = box.getBoundingClientRect();
                    const canvasRect = canvas.getBoundingClientRect();
                    const initialLeft = rect.left - canvasRect.left;
                    const initialTop = rect.top - canvasRect.top;
                    box.style.left = `${initialLeft}px`;
                    box.style.top = `${initialTop}px`;
                    const onMouseMove = (moveEvent) => {
                        const deltaX = moveEvent.clientX - startX;
                        const deltaY = moveEvent.clientY - startY;
                        if (Math.abs(deltaX) > 2 || Math.abs(deltaY) > 2) {
                            isDragging = true;
                        }

                        let newLeft = initialLeft + deltaX;
                        let newTop = initialTop + deltaY;
                        const maxLeft = canvasRect.width - rect.width;
                        const maxTop = canvasRect.height - rect.height;
                        newLeft = Math.max(0, Math.min(newLeft, maxLeft));
                        newTop = Math.max(0, Math.min(newTop, maxTop));
                        box.style.left = `${newLeft}px`;
                        box.style.top = `${newTop}px`;
                        drawDbRelations();
                    };
                    const onMouseUp = () => {
                        header.style.cursor = 'grab';
                        box.style.zIndex = window.activeDbTable === box.id.replace('db-box-', '') ? '50' : '2';
                        document.removeEventListener('mousemove', onMouseMove);
                        document.removeEventListener('mouseup', onMouseUp);
                        if (isDragging) {
                            // Briefly disable clicks to prevent triggering selection toggle

                            box.style.pointerEvents = 'none';
                            setTimeout(() => {
                                box.style.pointerEvents = 'auto';
                            }, 50);
                        }
                    };
                    document.addEventListener('mousemove', onMouseMove);
                    document.addEventListener('mouseup', onMouseUp);
                });
            });
        }

        window.addEventListener('resize', () => {
            const container = document.getElementById('db-grid-container');
            if (container && container.classList.contains('mode-3')) {
                drawDbRelations();
            }
        });
        // Trigger load connection check if default active is tab database

        document.addEventListener('DOMContentLoaded', () => {
            const params = new URLSearchParams(window.location.search);
            if (params.get('tab') === 'database') {
                setTimeout(() => {
                    switchDbMode(1);
                }, 200);
            }

            // Bind submit event for Submateri form to copy block elements to hidden field

            const submateriForm = document.getElementById('submateriForm');
            if (submateriForm) {
                submateriForm.addEventListener('submit', function (e) {
                    const descInput = document.getElementById('submateriInputDesc');
                    if (descInput) {
                        let html = '';
                        const blocks = document.querySelectorAll('#docBlocksContainer .doc-block');
                        blocks.forEach(block => {
                            const type = block.getAttribute('data-type');
                            const contentEl = block.querySelector('.doc-block-content');
                            let content = contentEl ? contentEl.innerHTML.trim() : '';
                            // Remove empty tags/line breaks that browser leaves

                            if (content === '<br>' || content === '<p><br></p>') {
                                content = '';
                            }

                            if (type === 'image') {
                                const previewImg = block.querySelector('.media-preview-element');
                                const captionEditor = block.querySelector('.media-caption-editor');
                                const imgUrl = previewImg ? previewImg.getAttribute('src') : '';
                                const captionText = captionEditor ? captionEditor.innerHTML.trim() : '';
                                if (imgUrl) {
                                    html += `<figure class="doc-media-image"><img src="${imgUrl}" alt="${captionText.replace(/"/g, '&quot;')}">${captionText ? `<figcaption>${captionText}</figcaption>` : ''}</figure>`;
                                }
                            } else if (type === 'video') {
                                const videoTag = block.querySelector('.media-preview-video-tag');
                                const iframeTag = block.querySelector('.media-preview-iframe');
                                const captionEditor = block.querySelector('.media-caption-editor');
                                const captionText = captionEditor ? captionEditor.innerHTML.trim() : '';
                                let videoUrl = '';
                                let isEmbed = false;
                                if (videoTag) {
                                    videoUrl = videoTag.getAttribute('src');
                                } else if (iframeTag) {
                                    videoUrl = iframeTag.getAttribute('src');
                                    isEmbed = true;
                                }

                                if (videoUrl) {
                                    if (isEmbed) {
                                        html += `<figure class="doc-media-video"><iframe src="${videoUrl}" frameborder="0" allowfullscreen></iframe>${captionText ? `<figcaption>${captionText}</figcaption>` : ''}</figure>`;
                                    } else {
                                        html += `<figure class="doc-media-video"><video controls src="${videoUrl}"></video>${captionText ? `<figcaption>${captionText}</figcaption>` : ''}</figure>`;
                                    }
                                }
                            } else if (type === 'callout') {
                                const styleSelect = block.querySelector('.callout-style-select');
                                const iconInput = block.querySelector('.callout-icon-input');
                                const contentEditor = block.querySelector('.callout-editable-content');
                                const calloutStyle = styleSelect ? styleSelect.value : 'info';
                                const calloutIcon = iconInput ? iconInput.value.trim() : 'ⓘ';
                                const calloutText = contentEditor ? contentEditor.innerHTML.trim() : '';
                                if (calloutText) {
                                    html += `<div class="doc-callout callout-${calloutStyle}"><span class="callout-icon">${calloutIcon}</span><div class="callout-content">${calloutText}</div></div>`;
                                }
                            } else if (content) {
                                if (type === 'p') {
                                    html += `<p>${content}</p>`;
                                } else if (type === 'h2') {
                                    html += `<h2>${content}</h2>`;
                                } else if (type === 'h3') {
                                    html += `<h3>${content}</h3>`;
                                } else if (type === 'blockquote') {
                                    html += `<blockquote>${content}</blockquote>`;
                                } else if (type === 'ul') {
                                    html += `<ul>${content}</ul>`;
                                } else if (type === 'ol') {
                                    html += `<ol>${content}</ol>`;
                                } else if (type === 'pre') {
                                    html += `<pre>${content}</pre>`;
                                } else if (type === 'table') {
                                    if (content.startsWith('<table')) {
                                        html += content;
                                    } else {
                                        html += `<table style="width:100%; border-collapse:collapse; margin:16px 0;">${content}</table>`;
                                    }
                                }
                            }
                        });
                        descInput.value = html;
                    }
                });
            }

            // Bind input listener to docBlocksContainer to update sidebar dynamically on typing

            const docBlocksContainer = document.getElementById('docBlocksContainer');
            if (docBlocksContainer) {
                docBlocksContainer.addEventListener('input', function (e) {
                    if (e.target.classList.contains('doc-block-content')) {
                        updateDocSidebarOutlineDebounced();
                    }
                });
            }

            // Initialize sidebar outline on load

            updateDocSidebarOutline();
        });
    </script>
</body>
</html>