<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $course->title }} - TurnCode</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    @include('layouts.transition-head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/course.css') }}">
</head>

<body class="course-page">

    <nav class="top-nav">
        <a href="{{ route('dashboard') }}" class="btn-back">
            <i class='bx bx-arrow-back'></i> Kembali ke Dashboard
        </a>
        <button class="navbar-menu-btn" id="navMenuBtn" style="position: relative;">
            <i class='bx bx-grid-alt'></i>
            @php
                $unreadNotificationsCount = isset($notifications) ? $notifications->whereNull('read_at')->count() : 0;
            @endphp
            <span class="nav-unread-dot"
                style="position: absolute; top: 12px; right: 12px; width: 8px; height: 8px; background: #ef4444; border-radius: 50%; box-shadow: 0 0 8px #ef4444; transition: opacity 0.3s ease, transform 0.3s ease; {{ $unreadNotificationsCount > 0 ? '' : 'display: none; opacity: 0; transform: scale(0);' }}"></span>
        </button>
    </nav>
    @include('partials.menu-panel')

    <div class="main-layout">
        <!-- LEFT COLUMN -->
        <div class="left-col">

            <!-- HERO -->
            <div class="course-hero-card">
                <div class="hero-img-container">
                    <img src="{{ asset('images/course-hero.png') }}" alt="{{ $course->title }}">
                </div>
                <div class="course-hero-content">
                    <h1 class="course-hero-title">{{ $course->title }}</h1>
                    <p class="course-hero-desc">{{ $course->description }}</p>
                </div>
            </div>

            <!-- SUBMATERI TABS -->
            @if($course->submateris->count() > 1)
                <div class="submateri-tabs">
                    @foreach($course->submateris as $sub)
                        <a href="{{ route('courses.show', [$course->id, 'submateri_id' => $sub->id]) }}"
                            class="submateri-tab {{ $sub->id == $activeSubmateriId ? 'active' : '' }}">
                            <span class="submateri-tab-icon">{{ $sub->icon ?: '📚' }}</span>
                            <span>{{ $sub->title }}</span>
                        </a>
                    @endforeach
                </div>
            @endif

            <!-- SUBMATERI SECTIONS -->
            @foreach($course->submateris->where('id', $activeSubmateriId) as $submateri)
                <div class="submateri-section">
                    <!-- Submateri Banner Header -->
                    <div class="submateri-header">
                        <div class="submateri-icon-box">
                            {{ $submateri->icon ?: '📚' }}
                        </div>
                        <div class="submateri-header-content" style="flex: 1;">
                            <span>Submateri</span>
                            <h2>{{ $submateri->title }}</h2>
                            @if($submateri->description)
                                <div class="submateri-doc-content">
                                    {!! $submateri->description !!}
                                </div>
                            @endif
                        </div>
                        @if($isSubmateriCompleted)
                        <div class="submateri-header-action">
                            <a href="{{ route('certificates.generate', $submateri->id) }}" class="btn-certificate" target="_blank">
                                <i class='bx bx-trophy' style="font-size: 1.2rem;"></i> Unduh Sertifikat
                            </a>
                        </div>
                        @endif
                    </div>

                    <!-- Timeline for this Submateri's Chapters -->
                    <div class="timeline-container">
                        <div class="timeline-line"></div>
                        @php $canAccess = true; @endphp

                        @foreach($submateri->chapters as $chapter)
                            <div class="chapter-group">
                                <div class="timeline-node"></div>

                                <!-- Chapter Card -->
                                <div class="chapter-card">
                                    <div class="chapter-icon"></div>
                                    <div class="chapter-text">
                                        <h3>Bab {{ $chapter->order }}</h3>
                                        <p>{{ $chapter->title }}</p>
                                    </div>
                                </div>

                                <!-- Lessons -->
                                <div class="lesson-list">
                                    @foreach($chapter->lessons as $lesson)
                                        @php 
                                            $isCompleted = in_array($lesson->id, $completedLessons); 
                                            $isLocked = !$canAccess;
                                            
                                            // Lock subsequent lessons if this one is not completed
                                            if (!$isCompleted) {
                                                $canAccess = false;
                                            }
                                        @endphp
                                        <a href="{{ $isLocked ? '#' : route('lessons.show', $lesson->id) }}"
                                            class="lesson-card {{ $isCompleted ? 'completed' : '' }} {{ $isLocked ? 'locked' : '' }}">
                                            <div class="lesson-dot"></div>
                                            <span class="lesson-title">{{ $lesson->title }}</span>
                                            @if($isLocked)
                                                <i class='bx bxs-lock-alt' style="margin-left: auto; font-size: 0.9rem; color: rgba(255,255,255,0.3);"></i>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

        </div>

        <!-- RIGHT COLUMN -->
        <div class="right-col">
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
                if (isset($schedules)) {
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
                        }
                    }
                }

                // Sort schedules by start_time
                usort($todaySchedules, function ($a, $b) {
                    return strcmp($a->start_time, $b->start_time);
                });

                $currentTime = date('H:i');
                $classifiedTodaySchedules = [];
                $nextScheduleFound = false;

                foreach ($todaySchedules as $sch) {
                    $start = date('H:i', strtotime($sch->start_time));
                    $end = date('H:i', strtotime($sch->end_time));

                    $status = 'nanti';
                    $statusLabel = 'Nanti';
                    $statusDotColor = '#3b82f6'; // blue

                    if ($currentTime >= $start && $currentTime <= $end) {
                        $status = 'saat_ini';
                        $statusLabel = 'Saat ini';
                        $statusDotColor = '#38b2ac'; // green
                    } elseif ($currentTime > $end) {
                        $status = 'selesai';
                        $statusLabel = 'Selesai';
                        $statusDotColor = '#a855f7'; // purple
                    } else {
                        if (!$nextScheduleFound) {
                            $status = 'selanjutnya';
                            $statusLabel = 'Selanjutnya';
                            $statusDotColor = '#e5e7eb'; // white
                            $nextScheduleFound = true;
                        } else {
                            $status = 'nanti';
                            $statusLabel = 'Nanti';
                            $statusDotColor = '#3b82f6'; // blue
                        }
                    }

                    $config = $sch->routine_config;
                    $schColor = $config['color'] ?? null;
                    if ($schColor) {
                        if ($schColor === 'green') {
                            $statusDotColor = '#38b2ac';
                        } elseif ($schColor === 'purple') {
                            $statusDotColor = '#a855f7';
                        } elseif ($schColor === 'blue') {
                            $statusDotColor = '#3b82f6';
                        } elseif ($schColor === 'white') {
                            $statusDotColor = '#e5e7eb';
                        }
                    }

                    $carbonStart = Carbon\Carbon::parse($start);
                    $carbonEnd = Carbon\Carbon::parse($end);
                    if ($carbonEnd->lt($carbonStart)) {
                        $carbonEnd->addDay();
                    }
                    $diffMinutes = $carbonStart->diffInMinutes($carbonEnd);
                    $hoursPart = floor($diffMinutes / 60);
                    $minsPart = $diffMinutes % 60;

                    $durationStr = '';
                    if ($hoursPart > 0) {
                        $durationStr .= $hoursPart . ' jam ';
                    }
                    if ($minsPart > 0 || $hoursPart == 0) {
                        $durationStr .= $minsPart . ' menit';
                    }
                    $durationStr = trim($durationStr);

                    $classifiedTodaySchedules[] = [
                        'model' => $sch,
                        'status' => $status,
                        'status_label' => $statusLabel,
                        'status_dot' => $statusDotColor,
                        'duration_str' => $durationStr,
                        'start' => $start,
                        'end' => $end,
                    ];
                }

                $activeSchedule = null;
                $upcomingSchedule = null;

                foreach ($classifiedTodaySchedules as $cs) {
                    if ($cs['status'] === 'saat_ini') {
                        $activeSchedule = $cs;
                        break;
                    }
                }
                if (!$activeSchedule) {
                    foreach ($classifiedTodaySchedules as $cs) {
                        if ($cs['status'] === 'selanjutnya') {
                            $upcomingSchedule = $cs;
                            break;
                        }
                    }
                }

                $displaySchedule = $activeSchedule ?: $upcomingSchedule;
                $targetTimeStr = '';
                $displayDurationStr = '';
                $displayStatusLabel = '';

                if ($displaySchedule) {
                    $displayDurationStr = $displaySchedule['duration_str'];
                    if ($activeSchedule) {
                        $displayStatusLabel = 'saat ini';
                        $targetTimeStr = $displaySchedule['end'];
                    } else {
                        $displayStatusLabel = 'selanjutnya';
                        $targetTimeStr = $displaySchedule['start'];
                    }
                }
            @endphp

            <div>
                <div class="right-section-title">Mengingatkan jadwal mu hari ini</div>

                @if($displaySchedule)
                    <div class="schedule-card">
                        <div class="schedule-badges">
                            <div class="badge">
                                <div class="badge-dot"
                                    style="background: {{ $activeSchedule ? '#38b2ac' : '#3b82f6' }}; box-shadow: 0 0 8px {{ $activeSchedule ? '#38b2ac' : '#3b82f6' }}80;">
                                </div>
                                {{ $displayStatusLabel }}
                            </div>
                            <div class="badge">
                                {{ $displayDurationStr }}
                            </div>
                        </div>

                        <div class="countdown-display">
                            <div class="countdown-time" id="countdown" data-target="{{ $targetTimeStr }}">--:--:--</div>
                            <div class="countdown-label">waktu<br>{{ $activeSchedule ? 'tersisa' : 'menuju' }}</div>
                        </div>

                        <div class="schedule-course-title">{{ $displaySchedule['model']->title }}
                            ({{ $displaySchedule['model']->course }})</div>
                    </div>
                @else
                    <div class="schedule-card"
                        style="align-items: center; justify-content: center; text-align: center; padding: 2rem; gap: 0.5rem;">
                        <div style="font-size: 1.8rem; opacity: 0.7;">🎉</div>
                        <div class="schedule-course-title"
                            style="color: var(--text-main); font-size: 0.95rem; font-weight: 700;">Tidak ada jadwal tersisa
                        </div>
                        <div style="font-size: 0.78rem; color: var(--text-muted); line-height: 1.4;">Semua sesi belajar hari
                            ini selesai atau belum terjadwal.</div>
                    </div>
                @endif
            </div>

            <div>
                <div class="right-section-title">Detail Jadwal Hari Ini</div>
                <div class="schedule-list" id="scheduleList"
                    style="display: flex; flex-direction: column; gap: 0.5rem;">
                    @forelse ($classifiedTodaySchedules as $cs)
                        @php
                            $sch = $cs['model'];
                            $status = $cs['status'];
                            $statusLabel = $cs['status_label'];
                            $statusDot = $cs['status_dot'];
                        @endphp
                        <div class="schedule-item">
                            <div class="schedule-item-left"
                                style="display: flex; align-items: center; gap: 1rem; color: var(--text-main); font-weight: 600; min-width: 0; flex: 1; margin-right: 1rem;">
                                <div class="status-dot"
                                    style="width: 10px; height: 10px; border-radius: 50%; background: {{ $statusDot }}; box-shadow: 0 0 8px {{ $statusDot }}80; flex-shrink: 0;">
                                </div>
                                <span
                                    style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block;">{{ $sch->title }}</span>
                            </div>
                            @if ($status === 'saat_ini')
                                <div class="schedule-status-text"
                                    style="font-size: 0.7rem; color: #38b2ac; font-weight: 500; flex-shrink: 0;">
                                    Sedang berlangsung...</div>
                            @elseif ($status === 'selesai')
                                <div class="schedule-status-text"
                                    style="font-size: 0.7rem; color: #a855f7; font-weight: 500; flex-shrink: 0;">
                                    Selesai</div>
                            @elseif ($status === 'selanjutnya')
                                <div class="schedule-status-text"
                                    style="font-size: 0.7rem; color: #e5e7eb; font-weight: 500; flex-shrink: 0;">
                                    Berikutnya ({{ $cs['start'] }})</div>
                            @else
                                <div class="schedule-status-text"
                                    style="font-size: 0.7rem; color: #3b82f6; font-weight: 500; flex-shrink: 0;">
                                    Nanti ({{ $cs['start'] }})</div>
                            @endif
                        </div>
                    @empty
                        <div
                            style="display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 2rem 1rem; background: rgba(255,255,255,0.02); border-radius: 20px; border: 1px dashed rgba(255,255,255,0.08); color: #9ca3af; min-height: 140px; width: 100%;">
                            <div style="font-size: 1.8rem; margin-bottom: 0.5rem; opacity: 0.8;">✨</div>
                            <div style="font-weight: 600; color: #fff; margin-bottom: 0.25rem; font-size: 0.9rem;">Hari ini
                                Bebas Belajar!</div>
                            <div style="font-size: 0.78rem; color: #8b8591;">Kamu bebas membaca sub-materi apapun atau <a
                                    href="{{ route('jadwal') }}"
                                    style="color: #38b2ac; text-decoration: none; font-weight: 600; transition: color 0.2s;"
                                    onmouseover="this.style.color='#4fd1c5'" onmouseout="this.style.color='#38b2ac'">kelola
                                    jadwalmu</a>.</div>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <script>
        (function () {
            function initCountdown() {
                const el = document.getElementById('countdown');
                if (!el) return;

                const targetStr = el.getAttribute('data-target');
                if (!targetStr) {
                    el.textContent = '0:00:00';
                    return;
                }

                const parts = targetStr.split(':');
                if (parts.length < 2) return;

                const targetHours = parseInt(parts[0], 10);
                const targetMinutes = parseInt(parts[1], 10);

                function tick() {
                    const now = new Date();
                    const targetTime = new Date();
                    targetTime.setHours(targetHours, targetMinutes, 0, 0);

                    let diffMs = targetTime.getTime() - now.getTime();
                    if (diffMs <= 0) {
                        el.textContent = '0:00:00';
                        return;
                    }

                    const totalSecs = Math.floor(diffMs / 1000);
                    const h = Math.floor(totalSecs / 3600);
                    const m = Math.floor((totalSecs % 3600) / 60);
                    const s = totalSecs % 60;

                    el.textContent = h + ':' + String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
                }

                tick();
                // Clear any existing interval to prevent memory leaks from page navigation
                if (window.courseCountdownInterval) {
                    clearInterval(window.courseCountdownInterval);
                }
                window.courseCountdownInterval = setInterval(tick, 1000);
            }

            function initCalloutMonochrome() {
                document.querySelectorAll('.doc-callout').forEach(function(callout) {
                    const iconEl = callout.querySelector('.callout-icon');
                    if (iconEl) {
                        const iconText = iconEl.innerText.trim();
                        if (iconText === '💡' || iconText === 'ℹ️' || iconText === 'ⓘ') {
                            iconEl.innerText = 'ⓘ';
                        } else if (iconText === '⚠️' || iconText === '⚠') {
                            iconEl.innerText = '⚠';
                        } else if (iconText === '✅' || iconText === '✔️' || iconText === '✓') {
                            iconEl.innerText = '✓';
                        } else if (iconText === '🚨' || iconText === '🔥' || iconText === '✖' || iconText === 'danger') {
                            iconEl.innerText = '✖';
                        }
                    }
                });
            }

            // Run immediately
            initCountdown();
            initCalloutMonochrome();

            // Run on turbo load if using Turbolinks/Livewire
            document.addEventListener('turbo:load', function() {
                initCountdown();
                initCalloutMonochrome();
            });
            document.addEventListener('livewire:navigated', function() {
                initCountdown();
                initCalloutMonochrome();
            });
        })();
    </script>
    <script src="{{ asset('js/panel.js') }}"></script>
</body>

</html>