<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>History - TurnCode</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    @include('layouts.transition-head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        .history-header {
            margin-bottom: 2.5rem;
        }

        .history-title {
            font-size: 2rem;
            font-weight: 800;
            color: white;
        }

        .history-subtitle {
            font-size: 0.9rem;
            color: #8b8591;
            margin-top: 0.25rem;
        }

        /* Stats Row */
        .hist-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 2.5rem;
        }

        .hist-stat-card {
            background: #1e1c22;
            border-radius: 22px;
            padding: 1.4rem 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.04);
            position: relative;
            overflow: hidden;
        }

        .hist-stat-icon {
            font-size: 1.5rem;
            margin-bottom: 0.75rem;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .hist-stat-card:hover .hist-stat-icon {
            color: #ffffff;
            transform: translateY(-2px);
        }

        .hist-stat-val {
            font-size: 2rem;
            font-weight: 800;
            color: white;
            line-height: 1;
        }

        .hist-stat-label {
            font-size: 0.78rem;
            color: #6b6570;
            font-weight: 500;
            margin-top: 0.4rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .hist-stat-badge {
            position: absolute;
            top: 1.2rem;
            right: 1.2rem;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
            background: rgba(16, 185, 129, 0.12);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.25);
        }

        /* Filter Bar */
        .filter-bar {
            display: flex;
            gap: 0.6rem;
            margin-bottom: 1.75rem;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 0.55rem 1.2rem;
            border-radius: 30px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid rgba(255, 255, 255, 0.07);
            background: #1e1c22;
            color: #8b8591;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .filter-btn:hover {
            background: #2a2830;
            color: white;
        }

        .filter-btn.active {
            background: #3a3440;
            color: white;
            border-color: rgba(255, 255, 255, 0.15);
        }

        .filter-btn i {
            font-size: 0.95rem;
            transition: transform 0.2s ease;
        }

        .filter-btn:hover i {
            transform: scale(1.1);
        }

        /* Timeline */
        .timeline {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        .timeline-group {}

        .timeline-date-label {
            font-size: 0.78rem;
            font-weight: 700;
            color: #6b6570;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.75rem;
            padding-left: 0.25rem;
        }

        .timeline-items {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .history-card {
            background: #1e1c22;
            border-radius: 20px;
            padding: 1.2rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.04);
            transition: background 0.2s, transform 0.2s;
            cursor: pointer;
        }

        .history-card:hover {
            background: #252330;
            transform: translateX(4px);
        }

        .hist-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
            background: rgba(255, 255, 255, 0.04);
            color: #94a3b8;
            border: 1px solid rgba(255, 255, 255, 0.02);
            transition: all 0.2s ease;
        }

        .history-card:hover .hist-icon {
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            border-color: rgba(255, 255, 255, 0.06);
            transform: scale(1.05);
        }

        .hist-info {
            flex: 1;
            min-width: 0;
        }

        .hist-subject {
            font-size: 0.95rem;
            font-weight: 700;
            color: white;
        }

        .hist-topic {
            font-size: 0.8rem;
            color: #6b6570;
            margin-top: 0.2rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .hist-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.4rem;
            flex-shrink: 0;
        }

        .hist-exp {
            font-size: 0.85rem;
            font-weight: 700;
            color: #a78bfa;
        }

        .hist-time {
            font-size: 0.75rem;
            color: #6b6570;
        }

        .hist-score {
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.2rem 0.6rem;
            border-radius: 20px;
        }

        .hist-score.s-good {
            background: rgba(16, 185, 129, 0.12);
            color: #10b981;
        }

        .hist-score.s-ok {
            background: rgba(245, 158, 11, 0.12);
            color: #f59e0b;
        }

        .hist-score.s-done {
            background: rgba(255, 255, 255, 0.06);
            color: #8b8591;
        }

        /* Heatmap */
        .heatmap-section {
            margin-bottom: 2.5rem;
        }

        .heatmap-label {
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
            margin-bottom: 1rem;
        }

        .heatmap-grid {
            display: grid;
            grid-template-columns: repeat(26, 1fr);
            gap: 4px;
        }

        .heatmap-cell {
            aspect-ratio: 1;
            border-radius: 4px;
            background: #1e1c22;
            transition: transform 0.15s;
            cursor: default;
        }

        .heatmap-cell:hover {
            transform: scale(1.3);
        }

        .heatmap-cell.l1 {
            background: #2e2450;
        }

        .heatmap-cell.l2 {
            background: #4a3a7a;
        }

        .heatmap-cell.l3 {
            background: #7c5cbf;
        }

        .heatmap-cell.l4 {
            background: #a78bfa;
        }
    </style>
</head>

<body class="dashboard-page">
    <!-- FIXED NAVBAR -->
    @php
        $navUserTier = auth()->user()->tier ?? 'Initiate';
        $navTierColors = [
            'Initiate' => '168, 162, 158',
            'Explorer' => '34, 197, 94',
            'Operator' => '59, 130, 246',
            'Technician' => '139, 92, 246',
            'Specialist' => '236, 72, 153',
            'Professional' => '239, 68, 68',
            'Senior Professional' => '249, 115, 22',
            'Lead Engineer' => '234, 179, 8',
            'Architect' => '6, 182, 212',
            'Principal' => '15, 118, 110',
            'Strategist' => '225, 29, 72',
            'Visionary' => '218, 165, 32',
        ];
        $navRgbColor = $navTierColors[$navUserTier] ?? '168, 162, 158';
    @endphp
    <nav class="fixed-navbar">
        <div class="navbar-left">
            <div class="navbar-avatar" style="background: url('https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random') center/cover; border: 2.5px solid rgba({{ $navRgbColor }}, 0.4);"></div>
            <div class="navbar-user-info">
                <div class="navbar-username">{{ auth()->user()->name }}</div>
                <div class="navbar-role" style="display: flex; align-items: center; gap: 4px;">
                    <span style="background: rgba({{ $navRgbColor }}, 0.15); color: rgb({{ $navRgbColor }}); font-size: 0.7rem; font-weight: 700; padding: 1px 6px; border-radius: 6px; border: 1px solid rgba({{ $navRgbColor }}, 0.3);">LV. {{ auth()->user()->level }}</span>
                    <span class="user-tier-display">{{ auth()->user()->tier ?? 'Initiate' }}</span>
                </div>
            </div>
        </div>
        <div class="navbar-right">
            <button class="navbar-menu-btn" id="navMenuBtn" style="position: relative;">
                <i class='bx bx-grid-alt'></i>
                @php
                    $unreadNotificationsCount = isset($notifications) ? $notifications->whereNull('read_at')->count() : 0;
                @endphp
                <span class="nav-unread-dot" style="position: absolute; top: 12px; right: 12px; width: 8px; height: 8px; background: #ef4444; border-radius: 50%; box-shadow: 0 0 8px #ef4444; transition: opacity 0.3s ease, transform 0.3s ease; {{ $unreadNotificationsCount > 0 ? '' : 'display: none; opacity: 0; transform: scale(0);' }}"></span>
            </button>
        </div>
    </nav>
    @include('partials.menu-panel')

    <div class="container">

        <!-- Header -->
        <div class="history-header">
            <div class="history-title">History</div>
            <div class="history-subtitle">Rekap aktivitas belajar kamu</div>
        </div>

        <!-- Stats -->
        <div class="hist-stats">
            <div class="hist-stat-card">
                <div class="hist-stat-icon"><i class='bx bx-book-open'></i></div>
                <div class="hist-stat-val">{{ $totalSessions }}</div>
                <div class="hist-stat-label">Sesi Selesai</div>
                <div class="hist-stat-badge">+1 minggu ini</div>
            </div>
            <div class="hist-stat-card">
                <div class="hist-stat-icon"><i class='bx bx-time-five'></i></div>
                <div class="hist-stat-val">{{ $totalHours }}h</div>
                <div class="hist-stat-label">Total Jam Belajar</div>
            </div>
            <div class="hist-stat-card">
                <div class="hist-stat-icon"><i class='bx bx-trophy'></i></div>
                <div class="hist-stat-val"><span class="user-exp-display" data-format="k">{{ $totalExp }}</span>K</div>
                <div class="hist-stat-label">Total EXP</div>
            </div>
            <div class="hist-stat-card">
                <div class="hist-stat-icon"><i class='bx bx-flame'></i></div>
                <div class="hist-stat-val">{{ $streak }}</div>
                <div class="hist-stat-label">Streak Hari</div>
            </div>
        </div>

        <!-- Heatmap -->
        <div class="heatmap-section">
            <div class="section-header">
                <h2 class="section-title">Aktivitas Belajar</h2>
                <div class="section-subtitle">26 minggu terakhir</div>
            </div>
            <div class="heatmap-grid" id="heatmapGrid"></div>
        </div>

        <!-- Filter -->
        <div class="filter-bar">
            <button class="filter-btn active" data-filter="all">Semua</button>
            <button class="filter-btn" data-filter="materi"><i class='bx bx-book-open'></i>Materi</button>
            <button class="filter-btn" data-filter="kuis"><i class='bx bx-edit-alt'></i>Kuis</button>
            <button class="filter-btn" data-filter="latihan"><i class='bx bx-dumbbell'></i>Latihan</button>
            <button class="filter-btn" data-filter="proyek"><i class='bx bx-rocket'></i>Proyek</button>
        </div>

        <!-- Timeline -->
        <div class="timeline">
            <!-- Container ketika hasil filter kosong -->
            <div id="filterEmptyState" class="timeline-group empty-state-group" style="display: none; text-align: center; color: var(--text-muted); padding: 3rem 0;">
                <p>Tidak ada aktivitas yang cocok dengan filter yang dipilih.</p>
            </div>

            @forelse($historyGroups as $dateLabel => $lessons)
            <div class="timeline-group">
                <div class="timeline-date-label">{{ $dateLabel }}</div>
                <div class="timeline-items">
                    @foreach($lessons as $lesson)
                        @php
                            $isQuiz = str_contains(strtolower($lesson->title), 'quiz');
                            $typeClass = $isQuiz ? 'kuis' : 'materi';
                            $typeIconClass = $isQuiz ? 'bx bx-edit-alt' : 'bx bx-book-open';
                            
                            $courseTitle = $lesson->chapter->submateri->course->title ?? 'Course';
                            $chapterTitle = $lesson->chapter->title ?? 'Chapter';
                            
                            $timeStr = \Carbon\Carbon::parse($lesson->pivot->created_at)->format('H:i');
                            
                            // Mocking EXP per lesson since it's not stored in pivot yet
                            $expGained = $isQuiz ? 100 : 50;
                        @endphp
                        <div class="history-card" data-type="{{ $typeClass }}">
                            <div class="hist-icon {{ $typeClass }}"><i class='{{ $typeIconClass }}'></i></div>
                            <div class="hist-info">
                                <div class="hist-subject">{{ $courseTitle }}</div>
                                <div class="hist-topic">{{ $chapterTitle }} - {{ $lesson->title }}</div>
                            </div>
                            <div class="hist-meta">
                                <div class="hist-exp">+{{ $expGained }} EXP</div>
                                <div class="hist-score s-good">Selesai</div>
                                <div class="hist-time">{{ $timeStr }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="timeline-group" style="text-align: center; color: var(--text-muted); padding: 3rem 0;">
                <p>Belum ada history aktivitas belajar.</p>
            </div>
            @endforelse
        </div>

    </div>

    <!-- BOTTOM-LEFT PAGE NAV MENU -->
    <div class="page-nav" id="pageNav">
        <div class="page-nav-items" id="pageNavItems">
            <a href="{{ route('dashboard') }}" class="page-nav-item">Dashboard</a>
            <a href="{{ route('jadwal') }}" class="page-nav-item">Jadwal</a>
            <a href="{{ route('history') }}" class="page-nav-item active">History</a>
            <a href="#" class="page-nav-item" onclick="event.preventDefault(); document.getElementById('logoutFormNav').submit();" style="color: #ef4444; border-color: rgba(239, 68, 68, 0.15); background: rgba(239, 68, 68, 0.03);">
                <i class='bx bx-log-out' style="margin-right: 6px; font-size: 1.15rem; vertical-align: middle;"></i>Log Out
            </a>
        </div>
        <form method="POST" action="{{ route('logout') }}" id="logoutFormNav" style="display: none;">
            @csrf
        </form>
        <button class="page-nav-btn" id="pageNavBtn" aria-label="Menu halaman">
            <span class="page-nav-line"></span>
            <span class="page-nav-line"></span>
            <span class="page-nav-line"></span>
        </button>
    </div>

    <script src="{{ asset('js/layout.js') }}"></script>
    <script>
        (function () {
            const grid = document.getElementById('heatmapGrid');
            const heatmapData = @json($heatmapData);

            heatmapData.forEach(day => {
                const cell = document.createElement('div');
                cell.className = 'heatmap-cell' + (day.level ? ' ' + day.level : '');
                cell.title = `${day.count} sesi pada ${day.date}`;
                grid.appendChild(cell);
            });
        })();

        // Filter buttons
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                
                const filter = btn.getAttribute('data-filter');
                document.querySelectorAll('.history-card').forEach(card => {
                    if (filter === 'all' || card.getAttribute('data-type') === filter) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // Optional: hide empty timeline groups
                let anyGroupVisible = false;
                document.querySelectorAll('.timeline-group:not(.empty-state-group)').forEach(group => {
                    const hasVisibleCards = Array.from(group.querySelectorAll('.history-card')).some(card => card.style.display !== 'none');
                    group.style.display = hasVisibleCards ? 'block' : 'none';
                    if (hasVisibleCards) anyGroupVisible = true;
                });
                
                const emptyState = document.getElementById('filterEmptyState');
                if (emptyState) {
                    // Hanya tampilkan jika kita memiliki data asli namun semua ter-filter
                    const hasData = document.querySelectorAll('.history-card').length > 0;
                    if (hasData) {
                        emptyState.style.display = anyGroupVisible ? 'none' : 'block';
                    }
                }
            });
        });
    </script>
    <script src="{{ asset('js/panel.js') }}"></script>
</body>

</html>
