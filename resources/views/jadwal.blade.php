@php
    $dayMap = [
        'Monday' => 'Sen',
        'Tuesday' => 'Sel',
        'Wednesday' => 'Rab',
        'Thursday' => 'Kam',
        'Friday' => 'Jum',
        'Saturday' => 'Sab',
        'Sunday' => 'Min',
    ];
    $colorMap = [
        'green' => '#38b2ac',
        'purple' => '#a855f7',
        'blue' => '#3b82f6',
        'white' => '#e5e7eb',
    ];
    $todayName = $dayMap[date('l')];
    $weekOfMonth = ceil(date('d') / 7);
    $weekStr = 'Minggu ' . $weekOfMonth;

    $monthMap = [
        'Jan' => 'Jan',
        'Feb' => 'Feb',
        'Mar' => 'Mar',
        'Apr' => 'Apr',
        'May' => 'Mei',
        'Jun' => 'Jun',
        'Jul' => 'Jul',
        'Aug' => 'Ags',
        'Sep' => 'Sep',
        'Oct' => 'Okt',
        'Nov' => 'Nov',
        'Dec' => 'Des'
    ];
    $currentMonthName = $monthMap[date('M')] ?? 'Jan';
    $weekStrShort = 'M' . $weekOfMonth;

    $todaySchedules = [];
    $upcomingSchedules = [];

    foreach ($schedules as $sch) {
        $isActiveToday = false;
        $config = $sch->routine_config;

        if ($sch->routine_type === 'Harian') {
            $days = $config['days'] ?? [];
            if (empty($days) || in_array($todayName, $days)) {
                $isActiveToday = true;
            }
        } elseif ($sch->routine_type === 'Mingguan') {
            $days = $config['days'] ?? [];
            $weeks = $config['weeks'] ?? [];
            if (in_array($todayName, $days) && (in_array($weekStr, $weeks) || in_array('Tiap Minggu', $weeks))) {
                $isActiveToday = true;
            }
        } elseif ($sch->routine_type === 'Bulanan') {
            $months = $config['months'] ?? [];
            $weeks = $config['weeks'] ?? [];
            if (in_array($currentMonthName, $months) && in_array($weekStrShort, $weeks)) {
                $isActiveToday = true;
            }
        } elseif ($sch->routine_type === 'Custom') {
            $customDate = $config['date'] ?? '';
            if ($customDate === date('Y-m-d')) {
                $isActiveToday = true;
            }
        }

        if ($isActiveToday) {
            $todaySchedules[] = $sch;
        } else {
            $upcomingSchedules[] = $sch;
        }
    }

    // Sort schedules: active first, then upcoming, then past (chronologically)
    $currentTime = date('H:i');
    usort($todaySchedules, function ($a, $b) use ($currentTime) {
        $aStart = date('H:i', strtotime($a->start_time));
        $aEnd = date('H:i', strtotime($a->end_time));

        $bStart = date('H:i', strtotime($b->start_time));
        $bEnd = date('H:i', strtotime($b->end_time));

        $getGroup = function ($start, $end) use ($currentTime) {
            if ($currentTime >= $start && $currentTime <= $end)
                return 1; // Active
            if ($currentTime < $start)
                return 2; // Upcoming
            return 3; // Past
        };

        $aGroup = $getGroup($aStart, $aEnd);
        $bGroup = $getGroup($bStart, $bEnd);

        if ($aGroup !== $bGroup) {
            return $aGroup <=> $bGroup;
        }

        return strcmp($aStart, $bStart);
    });
    usort($upcomingSchedules, function ($a, $b) {
        return strcmp($a->start_time, $b->start_time);
    });

    // Calculate total study minutes for today
    $totalMinutes = 0;
    foreach ($todaySchedules as $sch) {
        $start = Carbon\Carbon::parse($sch->start_time);
        $end = Carbon\Carbon::parse($sch->end_time);
        if ($end->lt($start)) {
            $end->addDay();
        }
        $totalMinutes += $start->diffInMinutes($end);
    }
    $totalHours = round($totalMinutes / 60, 1);
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Jadwal - TurnCode</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    @include('layouts.transition-head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal-jadwal.css') }}">
    <style>
        .jadwal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
        }

        .jadwal-title {
            font-size: 2rem;
            font-weight: 800;
            color: white;
        }

        .jadwal-subtitle {
            font-size: 0.9rem;
            color: #8b8591;
            margin-top: 0.25rem;
        }

        .week-strip {
            display: flex;
            gap: 0.6rem;
            margin-bottom: 2.5rem;
        }

        .week-day {
            flex: 1;
            background: #1e1c22;
            border-radius: 20px;
            padding: 1rem 0.5rem;
            text-align: center;
            cursor: pointer;
            transition: background 0.2s, transform 0.2s;
            border: 1px solid rgba(255, 255, 255, 0.04);
            box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
        }

        .week-day:hover {
            background: #2a2830;
            transform: translateY(-2px);
        }

        .week-day.active {
            background: #3a3440;
            border-color: rgba(255, 255, 255, 0.12);
        }

        .week-day-name {
            font-size: 0.7rem;
            color: #6b6570;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .week-day-num {
            font-size: 1.4rem;
            font-weight: 800;
            color: white;
            margin: 0.3rem 0;
        }

        .week-day-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #3f3d46;
            margin: 0 auto;
        }

        .week-day.active .week-day-dot {
            background: #a78bfa;
        }

        .week-day.today .week-day-num {
            color: #a78bfa;
        }

        .sessions-grid {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .session-card {
            background: #1e1c22;
            border-radius: 24px;
            padding: 1.5rem 2rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.04);
            transition: background 0.2s, transform 0.2s;
            cursor: pointer;
            box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
        }

        .session-card:hover {
            background: #252330;
            transform: translateX(4px);
        }

        .session-card.active-now {
            border-color: rgba(167, 139, 250, 0.3);
            background: #231f30;
        }

        .session-time-col {
            min-width: 80px;
        }

        .session-time {
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
        }

        .session-dur {
            font-size: 0.75rem;
            color: #6b6570;
            margin-top: 0.2rem;
        }

        .session-divider {
            width: 2px;
            height: 50px;
            background: rgba(255, 255, 255, 0.07);
            border-radius: 2px;
            flex-shrink: 0;
        }

        .session-active-indicator {
            width: 2px;
            height: 50px;
            background: #a78bfa;
            border-radius: 2px;
            flex-shrink: 0;
        }

        .session-info {
            flex: 1;
        }

        .session-subject {
            font-size: 1rem;
            font-weight: 700;
            color: white;
        }

        .session-topic {
            font-size: 0.82rem;
            color: #8b8591;
            margin-top: 0.25rem;
        }

        .session-tags {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.6rem;
            flex-wrap: wrap;
        }

        .session-tag {
            font-size: 0.7rem;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.06);
            color: #a09eaa;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .session-tag.live {
            background: rgba(167, 139, 250, 0.12);
            color: #a78bfa;
            border-color: rgba(167, 139, 250, 0.25);
        }

        .session-status {
            font-size: 0.8rem;
            color: #6b6570;
            font-weight: 500;
        }

        .session-status.live {
            color: #a78bfa;
        }

        .summary-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 2.5rem;
        }

        .summary-card {
            background: #1e1c22;
            border-radius: 20px;
            padding: 1.25rem 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.04);
            box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
        }

        .summary-val {
            font-size: 2rem;
            font-weight: 800;
            color: white;
        }

        .summary-label {
            font-size: 0.78rem;
            color: #6b6570;
            margin-top: 0.3rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-tambah {
            box-shadow: rgba(0, 0, 0, 0.16) 0px 3px 6px, rgba(0, 0, 0, 0.23) 0px 3px 6px;
        }
    </style>
</head>

<body class="dashboard-page">
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
            <div class="navbar-avatar"
                style="background: url('https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random') center/cover; border: 2.5px solid rgba({{ $navRgbColor }}, 0.4);">
            </div>
            <div class="navbar-user-info">
                <div class="navbar-username">{{ auth()->user()->name }}</div>
                <div class="navbar-role" style="display: flex; align-items: center; gap: 4px;">
                    <span
                        style="background: rgba({{ $navRgbColor }}, 0.15); color: rgb({{ $navRgbColor }}); font-size: 0.7rem; font-weight: 700; padding: 1px 6px; border-radius: 6px; border: 1px solid rgba({{ $navRgbColor }}, 0.3);">LV.
                        {{ auth()->user()->level }}</span>
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
                <span class="nav-unread-dot"
                    style="position: absolute; top: 12px; right: 12px; width: 8px; height: 8px; background: #ef4444; border-radius: 50%; box-shadow: 0 0 8px #ef4444; transition: opacity 0.3s ease, transform 0.3s ease; {{ $unreadNotificationsCount > 0 ? '' : 'display: none; opacity: 0; transform: scale(0);' }}"></span>
            </button>
        </div>
    </nav>
    @include('partials.menu-panel')

    <div class="container">

        <!-- Header -->
        <div class="jadwal-header">
            <div>
                <div class="jadwal-title">Jadwal</div>
                <div class="jadwal-subtitle">Kelola waktu belajarmu dengan efisien</div>
            </div>
            <button class="btn-tambah" id="btnTambahModal">+ Tambah Jadwal</button>
        </div>

        <!-- Summary -->
        <div class="summary-row">
            <div class="summary-card">
                <div class="summary-val" id="todaySessionsCount">{{ count($todaySchedules) }}</div>
                <div class="summary-label">Sesi Hari Ini</div>
            </div>
            <div class="summary-card">
                <div class="summary-val" id="totalStudyHours">{{ $totalHours }}h</div>
                <div class="summary-label">Total Belajar</div>
            </div>
            <div class="summary-card">
                <div class="summary-val">87%</div>
                <div class="summary-label">Kehadiran</div>
            </div>
        </div>

        <!-- Week Strip -->
        <div class="week-strip" id="weekStrip"></div>

        <!-- Sessions Hari Ini -->
        <div class="section-header" style="margin-top: 1.5rem; margin-bottom: 1.25rem;">
            <h2 class="section-title" id="sessionDayTitle">Jadwal Hari Ini</h2>
        </div>
        <div class="sessions-grid" id="todaySessionsGrid" style="margin-bottom: 3rem;">
            @forelse ($todaySchedules as $sch)
                @php
                    $start = Carbon\Carbon::parse($sch->start_time);
                    $end = Carbon\Carbon::parse($sch->end_time);
                    if ($end->lt($start)) {
                        $end->addDay();
                    }
                    $durationMinutes = $start->diffInMinutes($end);
                    $formattedStartTime = $start->format('H:i');

                    // Determine live / past / upcoming status
                    $now = Carbon\Carbon::now();
                    $sessionStart = Carbon\Carbon::today()->setTimeFromTimeString($sch->start_time);
                    $sessionEnd = Carbon\Carbon::today()->setTimeFromTimeString($sch->end_time);
                    if ($sessionEnd->lt($sessionStart)) {
                        $sessionEnd->addDay();
                    }

                    $isLive = $now->between($sessionStart, $sessionEnd);
                    $isPast = $now->gt($sessionEnd);
                    $isUpcoming = $now->lt($sessionStart);

                    // Recurrence Text
                    $recurrenceText = '';
                    $config = $sch->routine_config;
                    if ($sch->routine_type === 'Harian') {
                        $days = $config['days'] ?? [];
                        if (empty($days)) {
                            $recurrenceText = 'Setiap hari';
                        } else {
                            $recurrenceText = 'Hari: ' . implode(', ', $days);
                        }
                    } elseif ($sch->routine_type === 'Mingguan') {
                        $days = $config['days'] ?? [];
                        $weeks = $config['weeks'] ?? [];
                        $recurrenceText = implode(', ', $days) . ' (' . implode(', ', $weeks) . ')';
                    } elseif ($sch->routine_type === 'Bulanan') {
                        $months = $config['months'] ?? [];
                        $weeks = $config['weeks'] ?? [];
                        $recurrenceText = implode(', ', $weeks) . ' di bulan ' . implode(', ', $months);
                    } elseif ($sch->routine_type === 'Custom') {
                        $customDate = $config['date'] ?? '';
                        $recurrenceText = 'Sekali: ' . ($customDate ? Carbon\Carbon::parse($customDate)->format('d M Y') : '-');
                    }

                    // Color configuration
                    $schColor = $config['color'] ?? '';
                    $colorHex = $colorMap[$schColor] ?? '';
                @endphp
                <div class="session-card {{ $isLive ? 'active-now' : '' }}">
                    <div class="session-time-col">
                        <div class="session-time">{{ $formattedStartTime }}</div>
                        <div class="session-dur">{{ $durationMinutes }} mnt</div>
                    </div>
                    @if ($isLive)
                        <div class="session-active-indicator" {!! $colorHex ? 'style="background: ' . $colorHex . '; box-shadow: 0 0 10px ' . $colorHex . ';"' : '' !!}></div>
                    @else
                        <div class="session-divider" {!! $colorHex ? 'style="background: ' . $colorHex . '; box-shadow: 0 0 10px ' . $colorHex . ';"' : '' !!}></div>
                    @endif
                    <div class="session-info">
                        <div class="session-subject">{{ $sch->course }}</div>
                        <div class="session-topic">{{ $sch->title }}@if($sch->description) - {{ $sch->description }}@endif
                        </div>
                        <div class="session-tags">
                            @if ($isLive)
                                <span class="session-tag live">🔴 Live</span>
                            @endif
                            <span class="session-tag">{{ $sch->topic }}</span>
                            <span class="session-tag">{{ $sch->routine_type }}</span>
                            @if($recurrenceText)
                                <span class="session-tag"><i class='bx bx-refresh'
                                        style="vertical-align: middle; margin-right: 2px;"></i> {{ $recurrenceText }}</span>
                            @endif
                        </div>
                    </div>
                    @if ($isLive)
                        <div class="session-status live">Sedang berlangsung</div>
                    @elseif ($isPast)
                        <div class="session-status">Selesai</div>
                    @else
                        <div class="session-status">Akan datang</div>
                    @endif
                    <div class="session-actions">
                        <button type="button" class="btn-action-premium edit-sch-btn" data-id="{{ $sch->id }}"
                            data-schedule="{{ json_encode($sch) }}" title="Edit Jadwal">
                            <i class='bx bx-edit-alt'></i>
                        </button>
                        <button type="button" class="btn-action-premium delete-sch-btn" data-id="{{ $sch->id }}"
                            title="Hapus Jadwal">
                            <i class='bx bx-trash'></i>
                        </button>
                    </div>
                </div>
            @empty
                <div
                    style="background: rgba(255,255,255,0.02); border: 2px dashed rgba(255,255,255,0.05); border-radius: 24px; padding: 3rem; text-align: center;">
                    <i class='bx bx-calendar-x' style="font-size: 3rem; color: #6b6570; margin-bottom: 1rem;"></i>
                    <div style="font-weight: 700; color: white; font-size: 1.1rem; margin-bottom: 0.25rem;">Tidak ada jadwal
                        hari ini</div>
                    <div style="color: #6b6570; font-size: 0.85rem;">Mau bikin sesi belajar baru? klik "+ Tambah Jadwal" di
                        atas!</div>
                </div>
            @endforelse
        </div>

        <!-- Sessions Mendatang -->
        <div class="section-header" style="margin-top: 2rem; margin-bottom: 1.25rem;">
            <h2 class="section-title">Jadwal yang Akan Datang</h2>
        </div>
        <div class="sessions-grid" id="upcomingSessionsGrid">
            @forelse ($upcomingSchedules as $sch)
                @php
                    $start = Carbon\Carbon::parse($sch->start_time);
                    $end = Carbon\Carbon::parse($sch->end_time);
                    if ($end->lt($start)) {
                        $end->addDay();
                    }
                    $durationMinutes = $start->diffInMinutes($end);
                    $formattedStartTime = $start->format('H:i');

                    // Recurrence Text
                    $recurrenceText = '';
                    $config = $sch->routine_config;
                    if ($sch->routine_type === 'Harian') {
                        $days = $config['days'] ?? [];
                        if (empty($days)) {
                            $recurrenceText = 'Setiap hari';
                        } else {
                            $recurrenceText = 'Hari: ' . implode(', ', $days);
                        }
                    } elseif ($sch->routine_type === 'Mingguan') {
                        $days = $config['days'] ?? [];
                        $weeks = $config['weeks'] ?? [];
                        $recurrenceText = implode(', ', $days) . ' (' . implode(', ', $weeks) . ')';
                    } elseif ($sch->routine_type === 'Bulanan') {
                        $months = $config['months'] ?? [];
                        $weeks = $config['weeks'] ?? [];
                        $recurrenceText = implode(', ', $weeks) . ' di bulan ' . implode(', ', $months);
                    } elseif ($sch->routine_type === 'Custom') {
                        $customDate = $config['date'] ?? '';
                        $recurrenceText = 'Sekali: ' . ($customDate ? Carbon\Carbon::parse($customDate)->format('d M Y') : '-');
                    }

                    // Color configuration
                    $schColor = $config['color'] ?? '';
                    $colorHex = $colorMap[$schColor] ?? '';
                @endphp
                <div class="session-card">
                    <div class="session-time-col">
                        <div class="session-time">{{ $formattedStartTime }}</div>
                        <div class="session-dur">{{ $durationMinutes }} mnt</div>
                    </div>
                    <div class="session-divider" {!! $colorHex ? 'style="background: ' . $colorHex . '; box-shadow: 0 0 10px ' . $colorHex . ';"' : '' !!}></div>
                    <div class="session-info">
                        <div class="session-subject">{{ $sch->course }}</div>
                        <div class="session-topic">{{ $sch->title }}@if($sch->description) - {{ $sch->description }}@endif
                        </div>
                        <div class="session-tags">
                            <span class="session-tag">{{ $sch->topic }}</span>
                            <span class="session-tag">{{ $sch->routine_type }}</span>
                            @if($recurrenceText)
                                <span class="session-tag"><i class='bx bx-refresh'
                                        style="vertical-align: middle; margin-right: 2px;"></i> {{ $recurrenceText }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="session-status">Akan datang</div>
                    <div class="session-actions">
                        <button type="button" class="btn-action-premium edit-sch-btn" data-id="{{ $sch->id }}"
                            data-schedule="{{ json_encode($sch) }}" title="Edit Jadwal">
                            <i class='bx bx-edit-alt'></i>
                        </button>
                        <button type="button" class="btn-action-premium delete-sch-btn" data-id="{{ $sch->id }}"
                            title="Hapus Jadwal">
                            <i class='bx bx-trash'></i>
                        </button>
                    </div>
                </div>
            @empty
                <div
                    style="background: rgba(255,255,255,0.02); border: 2px dashed rgba(255,255,255,0.05); border-radius: 24px; padding: 3rem; text-align: center;">
                    <i class='bx bx-calendar' style="font-size: 3rem; color: #6b6570; margin-bottom: 1rem;"></i>
                    <div style="font-weight: 700; color: white; font-size: 1.1rem; margin-bottom: 0.25rem;">Tidak ada jadwal
                        mendatang</div>
                    <div style="color: #6b6570; font-size: 0.85rem;">Semua jadwal belajarmu aktif hari ini!</div>
                </div>
            @endforelse
        </div>

    </div>

    <!-- BOTTOM-LEFT PAGE NAV MENU -->
    <div class="page-nav" id="pageNav">
        <div class="page-nav-items" id="pageNavItems">
            <a href="{{ route('dashboard') }}" class="page-nav-item">Dashboard</a>
            <a href="{{ route('jadwal') }}" class="page-nav-item active">Jadwal</a>
            <a href="{{ route('history') }}" class="page-nav-item">History</a>
            <a href="#" class="page-nav-item"
                onclick="event.preventDefault(); document.getElementById('logoutFormNav').submit();"
                style="color: #ef4444; border-color: rgba(239, 68, 68, 0.15); background: rgba(239, 68, 68, 0.03);">
                <i class='bx bx-log-out' style="margin-right: 6px; font-size: 1.15rem; vertical-align: middle;"></i>Log
                Out
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

    @include('partials.modal-jadwal')
    </div>

    <script src="{{ asset('js/layout.js') }}"></script>
    <script src="{{ asset('js/modal-jadwal.js') }}"></script>
    <script>
        (function () {
            const strip = document.getElementById('weekStrip');
            if (!strip) return;
            strip.innerHTML = '';
            const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
            const today = new Date();
            const startOfWeek = new Date(today);
            startOfWeek.setDate(today.getDate() - today.getDay());
            for (let i = 0; i < 7; i++) {
                const d = new Date(startOfWeek);
                d.setDate(startOfWeek.getDate() + i);
                const isToday = d.toDateString() === today.toDateString();
                const el = document.createElement('div');
                el.className = 'week-day' + (isToday ? ' active today' : '');
                el.innerHTML = `<div class="week-day-name">${days[i]}</div><div class="week-day-num">${d.getDate()}</div><div class="week-day-dot"></div>`;
                strip.appendChild(el);
            }
        })();
    </script>
    <script src="{{ asset('js/panel.js') }}"></script>
</body>

</html>