<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $lesson->title }} - TurnCode</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    @include('layouts.transition-head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/lesson.css') }}">
</head>
<body class="lesson-page">

    <!-- TOP NAVBAR -->
    <nav class="lesson-topbar">
        <div class="topbar-left">
            <a href="{{ route('courses.show', [$course->id, 'submateri_id' => $lesson->chapter->submateri->id]) }}" class="topbar-pill-btn">Kembali</a>
            <span class="topbar-pill-label">{{ $course->title }}</span>
        </div>
        <div class="topbar-right">
            <span class="topbar-pill-badge">{{ $lesson->chapter->submateri->title }}</span>
            <span class="topbar-pill-badge">BAB {{ $lesson->chapter->order }}</span>
            <span class="topbar-pill-badge main-badge">{{ $lesson->chapter->title }}</span>
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

    <!-- MAIN SHELL -->
    <div class="lesson-shell">

        <!-- SIDEBAR -->
        <aside class="lesson-sidebar">
            <div class="sidebar-header">
                <div class="sidebar-label">Materi</div>
                <div class="sidebar-course-name">{{ $course->title }}</div>
            </div>
            
            <div class="sidebar-divider"></div>
            
            <div class="sidebar-submateri-title">{{ $lesson->chapter->submateri->title }}</div>
            <div class="sidebar-chapter-title">BAB {{ $lesson->chapter->order }} : {{ $lesson->chapter->title }}</div>
            
            <div class="nav-list">
                @php 
                    $canAccess = true; 
                    $isLastChapter = $lesson->chapter->order === $lesson->chapter->submateri->chapters->max('order');
                @endphp
                @foreach($lesson->chapter->lessons as $lsn)
                    @php 
                        $isLessonComingSoon = $lsn->status === 'coming_soon';
                        $isCompleted = in_array($lsn->id, $completedLessons); 
                        $isCurrent = $lsn->id == $lesson->id;
                        $isLocked = !$canAccess || $isLessonComingSoon;
                        
                        if (!$isCompleted && !$isLessonComingSoon) {
                            $canAccess = false;
                        }
                    @endphp
                    @if($isLessonComingSoon)
                        <div class="nav-lesson locked coming-soon" style="opacity: 0.65; cursor: not-allowed; display: flex; align-items: center;">
                            <span class="nav-bullet" style="background: #eab308;"></span>
                            <span class="nav-lesson-title" style="color: #eab308;">{{ $lsn->title }}</span>
                            <span class="badge-coming-soon" style="font-size: 0.6rem; background: #eab308; color: #000; padding: 1px 4px; border-radius: 4px; margin-left: auto; font-weight: bold;">Soon</span>
                            <i class='bx bxs-lock-alt' style="margin-left: 6px; font-size: 0.9rem; color: #eab308;"></i>
                        </div>
                    @else
                        <a href="{{ $isLocked ? '#' : route('lessons.show', $lsn->id) }}"
                           class="nav-lesson {{ $isCurrent ? 'active' : '' }} {{ $isCompleted ? 'completed' : '' }} {{ $isLocked ? 'locked' : '' }}">
                            <span class="nav-bullet"></span>
                            <span class="nav-lesson-icon-hidden">{{ $isCompleted ? '✓' : ($isCurrent ? '▶' : '○') }}</span>
                            <span class="nav-lesson-title">{{ $lsn->title }}</span>
                            @if($isLocked)
                                <i class='bx bxs-lock-alt' style="margin-left: auto; font-size: 0.9rem; color: rgba(255,255,255,0.35);"></i>
                            @endif
                        </a>
                    @endif
                @endforeach

                @if($isLastChapter)
                    @php
                        $submateriLessons = collect();
                        foreach ($lesson->chapter->submateri->chapters as $chap) {
                            if ($chap->status === 'coming_soon') continue;
                            foreach ($chap->lessons as $lsn) {
                                if ($lsn->status !== 'coming_soon') {
                                    $submateriLessons->push($lsn->id);
                                }
                            }
                        }
                        $completedCount = 0;
                        foreach ($submateriLessons as $lsnId) {
                            if (in_array($lsnId, $completedLessons)) {
                                $completedCount++;
                            }
                        }
                        $allLessonsCompleted = $submateriLessons->count() > 0 && $completedCount === $submateriLessons->count();
                        $isQuizPassed = in_array($lesson->chapter->submateri->id, auth()->user()->achievements['passed_submateri_quizzes'] ?? []);
                        $isQuizLocked = !$allLessonsCompleted;
                    @endphp
                    @if($isQuizLocked)
                        <div class="nav-lesson locked" style="opacity: 0.5; cursor: not-allowed; display: flex; align-items: center; gap: 1rem; padding: 0.75rem 1.25rem;">
                            <span class="nav-bullet" style="background: rgba(255,255,255,0.2);"></span>
                            <span class="nav-lesson-title" style="color: var(--text-muted);">Uji Pemahaman</span>
                            <i class='bx bxs-lock-alt' style="margin-left: auto; font-size: 0.9rem; color: rgba(255,255,255,0.35);"></i>
                        </div>
                    @else
                        <a href="{{ route('submateris.quiz.show', $lesson->chapter->submateri->id) }}"
                           class="nav-lesson quiz-item {{ $isQuizPassed ? 'completed' : '' }}" style="display: flex; align-items: center; gap: 1rem; padding: 0.75rem 1.25rem;">
                            <span class="nav-bullet" style="background: var(--color-accent-amber);"></span>
                            <span class="nav-lesson-title" style="color: #fff;">Uji Pemahaman</span>
                            @if($isQuizPassed)
                                <i class='bx bx-check' style="margin-left: auto; font-size: 1.1rem; color: var(--accent-green);"></i>
                            @endif
                        </a>
                    @endif
                @endif
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="lesson-main">
            <!-- Watermarks behind the content -->
            <div class="bg-watermark">
                <div class="watermark-bab">BAB {{ $lesson->chapter->order }}</div>
                <div class="watermark-course">{{ $course->title }}</div>
            </div>

            <div class="content-wrapper">
                <!-- Header Grid -->
                <div class="content-header-grid">
                    <div class="header-left">
                        <h1 class="submateri-title">{{ $lesson->chapter->submateri->title }}</h1>
                        <h2 class="chapter-title">{{ $lesson->chapter->title }}</h2>
                    </div>
                    <div class="header-right">
                        <p class="chapter-desc">
                            {{ $lesson->chapter->description ?: 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s...' }}
                        </p>
                    </div>
                </div>

                <!-- Author Section -->
                <div class="author-section">
                    <img src="https://i.pravatar.cc/100?img=12" alt="HanzzSama" class="author-avatar">
                    <div class="author-info">
                        <span class="author-label">Penulis</span>
                        <span class="author-name">HanzzSama</span>
                    </div>
                </div>

                <main class="lesson-read">
                    <div>
                        <!-- Featured Image Hero Card -->
                        <div class="content-hero-card">
                            <img src="{{ asset('images/course-hero.png') }}" alt="{{ $lesson->title }}">
                        </div>

                        <!-- Prose Content -->
                        <div class="prose">
                            {!! $lesson->content !!}
                        </div>

                        <div class="quiz-section">
                            <div class="lesson-divider"></div>
                            <div class="next-lesson" style="display:block;">
                                @php
                                    $hasNextLesson = isset($nextLesson) && $nextLesson;
                                @endphp

                                @if(in_array($lesson->id, $completedLessons))
                                    {{-- Already completed --}}
                                    @if($hasNextLesson)
                                        <a href="{{ route('lessons.show', $nextLesson->id) }}" class="btn-next">Lanjut ke materi berikutnya</a>
                                    @else
                                        @php
                                            $isQuizPassed = in_array($lesson->chapter->submateri->id, auth()->user()->achievements['passed_submateri_quizzes'] ?? []);
                                        @endphp
                                        @if($isQuizPassed)
                                            <a href="{{ route('courses.show', [$course->id, 'submateri_id' => $lesson->chapter->submateri->id]) }}" class="btn-next">Kembali ke Halaman Kelas</a>
                                        @else
                                            <a href="{{ route('submateris.quiz.show', $lesson->chapter->submateri->id) }}" class="btn-next">Mulai Uji Pemahaman</a>
                                        @endif
                                    @endif
                                @else
                                    {{-- Not yet completed --}}
                                    @if($hasNextLesson)
                                        <form action="{{ route('lessons.complete', $lesson->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-next" style="width: 100%;">Tandai selesai & Lanjut</button>
                                        </form>
                                    @else
                                        <form action="{{ route('lessons.complete', $lesson->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn-next" style="width: 100%;">Selesai & Mulai Uji Pemahaman</button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </div>
    <script src="{{ asset('js/panel.js') }}"></script>
</body>
</html>

