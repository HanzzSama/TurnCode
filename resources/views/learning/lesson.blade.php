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
    <style>
        /* ======================================
           PIXEL-PERFECT MOCKUP THEME FOR LESSON
           ====================================== */


        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            transition: .3s all ease-in-out;
            font-family: 'Inter', sans-serif;
        }

        *::-webkit-scrollbar {
            display: none;
        }

        html {
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        body.lesson-page {
            background-color: var(--bg-body);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* ======================================
           LAYOUT: TOP NAVBAR
           ====================================== */
        .lesson-topbar {
            position: fixed;
            top: 2rem;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            height: 80px;
            width: calc(100% - 4rem);
            max-width: 80em;
            background: rgba(18, 17, 20, 0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border-color);
            border-radius: 100px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1rem 0 1.3rem;
        }

        .topbar-left, .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .topbar-pill-btn {
            display: inline-flex;
            align-items: center;
            color: #d4d4d8;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.75rem 1.75rem;
            border-radius: 100px;
            background: var(--bg-card);
            transition: all 0.2s ease;
        }
        .topbar-pill-btn:hover {
            background: #3f3f46;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .topbar-pill-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #ffffff;
            padding: 0.75rem 1.75rem;
            border-radius: 100px;
            background: var(--bg-card);
        }
        
        .topbar-pill-badge {
            font-size: 0.8rem;
            font-weight: 600;
            color: #d4d4d8;
            background: var(--bg-card);
            padding: 0.75rem 1.5rem;
            /* border-radius: 100px; */
        }
        
        .topbar-pill-badge:first-child{
            border-radius: 100px 30px 30px 100px;
        }

        .topbar-pill-badge:nth-child(2){
            border-radius: 7px 7px 7px 7px;
        }
        .topbar-pill-badge.main-badge {
            color: #a1a1aa;
            border-radius: 30px 100px 100px 30px;
        }

        /* ======================================
           LAYOUT: MAIN SHELL
           ====================================== */
        .lesson-shell {
            display: flex;
            height: 100vh;
            padding-top: calc(60px + 4rem);
            position: relative;
            z-index: 1;
            max-width: 80em;
            width: 100%;
            margin: 0 auto;
        }

        /* ======================================
           SIDEBAR
           ====================================== */
        .lesson-sidebar {
            width: 320px;
            flex-shrink: 0;
            background: var(--bg-body);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            padding: 2rem 2rem 2rem 1rem;
        }

        .sidebar-header {
            margin-bottom: 2.25rem;
            padding-left: 1.25rem;
        }

        .sidebar-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--text-muted);
            margin-bottom: 0.5rem;
            letter-spacing: 0.05em;
            text-transform: capitalize;
        }

        .sidebar-course-name {
            font-size: 1.6rem;
            font-weight: 900;
            color: var(--text-main);
            line-height: 1.2;
        }

        .sidebar-divider {
            height: 1px;
            background: var(--border-color);
            margin: 1.5rem 0 1.5rem 1.25rem;
        }

        .sidebar-submateri-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--text-main);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.35rem;
            padding-left: 1.25rem;
        }

        .sidebar-chapter-title {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-muted);
            line-height: 1.4;
            margin-bottom: 1.5rem;
            padding-left: 1.25rem;
        }

        .nav-list {
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .nav-lesson {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1.25rem;
            text-decoration: none;
            color: #a1a1aa;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 100px;
            transition: all 0.2s ease;
        }

        .nav-bullet {
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #3f3f46;
            flex-shrink: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .nav-lesson-icon-hidden {
            display: none;
        }

        .nav-lesson:hover {
            color: #ffffff;
        }

        .nav-lesson:hover .nav-bullet {
            background: #71717a;
        }

        /* Active State */
        .nav-lesson.active {
            color: #eaeaea;
        }

        .nav-lesson.active .nav-bullet {
            background: #a1a1aa;
            width: 32px;
            height: 14px;
            border-radius: 100px;
        }

        /* Quiz State */
        .nav-lesson.quiz-item .nav-bullet {
            background: var(--color-accent-amber);
        }

        .nav-lesson.completed .nav-bullet {
            background: var(--accent-green) !important;
        }

        .nav-lesson.locked {
            opacity: 0.45;
            cursor: not-allowed;
            pointer-events: none;
        }

        .nav-lesson.locked .nav-bullet {
            background: transparent !important;
            border: 1px dashed rgba(255, 255, 255, 0.3);
        }

        /* ======================================
           MAIN CONTENT AREA
           ====================================== */
        .lesson-main {
            flex: 1;
            display: flex;
            overflow-y: auto;
            padding: 2rem 2rem 6rem 2rem;
            position: relative;
        }

        /* Watermarks */
        .bg-watermark {
            position: absolute;
            right: 4rem;
            top: 5.5rem;
            text-align: right;
            pointer-events: none;
            z-index: 0;
            user-select: none;
        }

        .watermark-bab {
            font-size: 2rem;
            font-weight: 900;
            color: rgba(255, 255, 255, 0.02);
            line-height: 1;
            text-transform: uppercase;
        }

        .watermark-course {
            font-size: 5rem;
            font-weight: 900;
            margin-top: 7px;
            color: rgba(255, 255, 255, 0.02);
            line-height: 1.1;
        }

        .content-wrapper {
            max-width: 100%;
            position: relative;
            z-index: 1;
        }

        /* Header Grid */
        .content-header-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 3rem;
            margin-bottom: 2rem;
            align-items: start;
        }

        .submateri-title {
            font-size: 3.5rem;
            font-weight: 900;
            color: #ffffff;
            line-height: 1;
            margin-bottom: 0.5rem;
        }

        .chapter-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #a1a1aa;
            line-height: 1.2;
        }

        .chapter-desc {
            font-size: 0.95rem;
            color: var(--text-muted);
            line-height: 1.6;
        }

        /* Author Section */
        .author-section {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 3rem;
        }

        .author-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #eaeaea;
            object-fit: cover;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .author-info {
            display: flex;
            flex-direction: column;
        }

        .author-label {
            font-size: 0.7rem;
            color: var(--text-muted);
        }

        .author-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: #ffffff;
        }

        /* Featured Image Card */
        .content-hero-card {
            width: 100%;
            aspect-ratio: 16/9;
            border-radius: 32px;
            overflow: hidden;
            margin-bottom: 3rem;
            border: 1px solid var(--border-color);
        }

        .content-hero-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .lesson-read {
            width: 100%;
            display: flex;
            justify-content: center;
            margin-top: 2rem;
            padding-bottom: 2rem;
        }
        .lesson-read > div {
            width: 100%;
            max-width: 760px; /* Optimal width for readability */
        }
        /* Prose Content */
        .prose {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #d4d4d8;
        }

        .prose p {
            margin-bottom: 1.75rem;
        }

        .prose h2, .prose h3 {
            font-size: 1.5rem;
            font-weight: 800;
            color: #ffffff;
            margin: 3.5rem 0 1.25rem;
        }

        .prose pre {
            background: #18171a;
            border: 1px solid var(--border-color);
            color: #e4e4e7;
            padding: 1.5rem;
            border-radius: 16px;
            overflow-x: auto;
            margin-bottom: 2rem;
            font-family: 'Fira Code', 'Courier New', Courier, monospace;
            font-size: 0.9rem;
        }

        .prose code {
            background: rgba(255, 255, 255, 0.05);
            padding: 0.2rem 0.5rem;
            border-radius: 6px;
            font-size: 0.88em;
            color: #e4e4e7;
        }

        .prose pre code {
            background: transparent;
            padding: 0;
        }

        .prose ul, .prose ol {
            padding-left: 2rem;
            margin-bottom: 1.75rem;
        }

        .prose li {
            margin-bottom: 0.5rem;
        }

        /* Quiz & Action Section */
        .quiz-section {
            margin-top: 4rem;
        }

        .quiz-header-row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .quiz-icon {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .quiz-title {
            font-size: 1.35rem;
            font-weight: 800;
            color: #ffffff;
        }

        .quiz-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 2.5rem;
        }

        .question {
            font-size: 1.15rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .options {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
        }

        .option-label {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.1rem 1.5rem;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.95rem;
            color: #ffffff;
            background: rgba(255, 255, 255, 0.02);
        }
        .option-label:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.15);
            transform: translateY(-1px);
        }

        .option-input {
            accent-color: #ffffff;
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .btn-submit {
            margin-top: 2rem;
            background: var(--text-main);
            color: var(--bg-body);
            border: none;
            padding: 1.25rem;
            border-radius: 100px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            transition: all 0.25s ease;
            letter-spacing: 0.02em;
        }
        .btn-submit:hover {
            background: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255, 255, 255, 0.15);
        }
        .btn-submit:disabled {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-muted);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .feedback-alert {
            margin-top: 1.5rem;
            padding: 1.25rem 1.5rem;
            border-radius: 16px;
            font-size: 0.95rem;
            font-weight: 500;
            display: none;
            line-height: 1.6;
        }
        .feedback-alert.success {
            background: rgba(16, 185, 129, 0.1);
            color: #6ee7b7;
            border: 1px solid rgba(16, 185, 129, 0.2);
            display: block;
        }
        .feedback-alert.error {
            background: rgba(239, 68, 68, 0.1);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.2);
            display: block;
        }

        .next-lesson {
            margin-top: 2rem;
            display: none;
        }

        .btn-next {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: var(--text-main);
            color: var(--bg-body);
            border: none;
            padding: 1.25rem;
            border-radius: 100px;
            font-size: 1rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.25s ease;
            width: 100%;
        }
        .btn-next:hover {
            background: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(255, 255, 255, 0.15);
        }
    </style>
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
                @php $canAccess = true; @endphp
                @foreach($lesson->chapter->lessons as $lsn)
                    @php 
                        $isCompleted = in_array($lsn->id, $completedLessons); 
                        $isCurrent = $lsn->id == $lesson->id;
                        $isQuiz = $lsn->quizzes->count() > 0 || str_contains(strtolower($lsn->title), 'quiz');
                        $isLocked = !$canAccess;
                        
                        if (!$isCompleted) {
                            $canAccess = false;
                        }
                    @endphp
                    <a href="{{ $isLocked ? '#' : route('lessons.show', $lsn->id) }}"
                       class="nav-lesson {{ $isCurrent ? 'active' : '' }} {{ $isQuiz ? 'quiz-item' : '' }} {{ $isCompleted ? 'completed' : '' }} {{ $isLocked ? 'locked' : '' }}">
                        <span class="nav-bullet"></span>
                        <!-- Hidden text for backward compatibility with the JS checkmark script -->
                        <span class="nav-lesson-icon-hidden">{{ $isCompleted ? '✓' : ($isCurrent ? '▶' : '○') }}</span>
                        <span class="nav-lesson-title">{{ $lsn->title }}</span>
                        @if($isLocked)
                            <i class='bx bxs-lock-alt' style="margin-left: auto; font-size: 0.9rem; color: rgba(255,255,255,0.3);"></i>
                        @endif
                    </a>
                @endforeach
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

                        @if($lesson->quizzes->count() > 0)
                            @php $quiz = $lesson->quizzes->first(); @endphp
                            <div class="quiz-section" id="quiz-section">
                                <div class="lesson-divider"></div>
                                <div class="quiz-header-row">
                                    <div class="quiz-icon">💡</div>
                                    <div class="quiz-title">Uji Pemahaman</div>
                                </div>

                                <div class="quiz-card">
                                    <form id="quiz-form">
                                        <div class="question">{{ $quiz->question }}</div>
                                        <div class="options">
                                            @php
                                                $options = [];
                                                if (is_array($quiz->options)) {
                                                    $options = $quiz->options;
                                                } elseif (is_string($quiz->options)) {
                                                    $options = json_decode($quiz->options, true) ?: [];
                                                }
                                            @endphp
                                            @foreach($options as $idx => $opt)
                                                <label class="option-label">
                                                    <input type="radio" name="answer" value="{{ $opt }}" class="option-input" required>
                                                    <span>{{ $opt }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <button type="submit" class="btn-submit" id="submit-btn">Jawab Kuis</button>
                                    </form>

                                    <div id="feedback" class="feedback-alert"></div>

                                    @php
                                        // After answering quiz correctly: determine what comes next
                                        $hasNextLesson = isset($nextLesson) && $nextLesson;
                                        $chapterHasQuiz = $lesson->chapter->lessons->contains(function($l) {
                                            return $l->quizzes->count() > 0 || str_contains(strtolower($l->title), 'quiz');
                                        });
                                    @endphp

                                    <div class="next-lesson" id="next-lesson-btn">
                                        @if($hasNextLesson)
                                            <a href="{{ route('lessons.show', $nextLesson->id) }}" class="btn-next">Lanjut ke materi berikutnya</a>
                                        @else
                                            <a href="{{ route('courses.show', [$course->id, 'submateri_id' => $lesson->chapter->submateri->id]) }}" class="btn-next">Selesai</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="quiz-section">
                                <div class="lesson-divider"></div>
                                <div class="next-lesson" style="display:block;">
                                    @php
                                        $hasNextLesson = isset($nextLesson) && $nextLesson;
                                        $nextIsQuiz = $hasNextLesson && ($nextLesson->quizzes->count() > 0 || str_contains(strtolower($nextLesson->title), 'quiz'));

                                        // Check if any lesson in this chapter is a quiz
                                        $chapterHasQuiz = $lesson->chapter->lessons->contains(function($l) {
                                            return $l->quizzes->count() > 0 || str_contains(strtolower($l->title), 'quiz');
                                        });
                                    @endphp

                                    @if(in_array($lesson->id, $completedLessons))
                                        {{-- Already completed --}}
                                        @if($hasNextLesson)
                                            @if($nextIsQuiz)
                                                <a href="{{ route('lessons.show', $nextLesson->id) }}" class="btn-next">Uji Pemahaman</a>
                                            @else
                                                <a href="{{ route('lessons.show', $nextLesson->id) }}" class="btn-next">Selesai</a>
                                            @endif
                                        @else
                                            @if(!$chapterHasQuiz)
                                                <button class="btn-next" style="width: 100%; background: rgba(255,255,255,0.1); color: #8b8591; cursor: not-allowed;" disabled>Coming Soon</button>
                                            @else
                                                <a href="{{ route('courses.show', [$course->id, 'submateri_id' => $lesson->chapter->submateri->id]) }}" class="btn-next">Selesai</a>
                                            @endif
                                        @endif
                                    @else
                                        {{-- Not yet completed --}}
                                        @if($hasNextLesson)
                                            @if($nextIsQuiz)
                                                <form action="{{ route('lessons.complete', $lesson->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn-next" style="width: 100%;">Uji Pemahaman</button>
                                                </form>
                                            @else
                                                <form action="{{ route('lessons.complete', $lesson->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn-next" style="width: 100%;">Tandai selesai & Lanjut</button>
                                                </form>
                                            @endif
                                        @else
                                            @if(!$chapterHasQuiz)
                                                <form action="{{ route('lessons.complete', $lesson->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn-next" style="width: 100%; background: rgba(255,255,255,0.1); color: #8b8591;">Coming Soon</button>
                                                </form>
                                            @else
                                                <form action="{{ route('lessons.complete', $lesson->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn-next" style="width: 100%;">Selesai</button>
                                                </form>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </main>
            </div>
        </main>
    </div>

    @if($lesson->quizzes->count() > 0)
    <script>
        document.getElementById('quiz-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = document.getElementById('submit-btn');
            const feedback = document.getElementById('feedback');
            const nextBtn = document.getElementById('next-lesson-btn');
            const answer = document.querySelector('input[name="answer"]:checked').value;

            submitBtn.disabled = true;
            submitBtn.textContent = 'Mengecek...';

            fetch('{{ route("lessons.quiz.submit", $lesson->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    quiz_id: {{ $quiz->id }},
                    answer: answer
                })
            })
            .then(response => response.json())
            .then(data => {
                feedback.className = 'feedback-alert ' + (data.correct ? 'success' : 'error');
                feedback.innerHTML = `<strong>${data.correct ? '✓ Benar!' : '✗ Kurang Tepat.'}</strong><br>${data.explanation || ''}`;

                if(data.correct) {
                    submitBtn.style.display = 'none';
                    nextBtn.style.display = 'block';
                    const activeIcon = document.querySelector('.nav-lesson.active .nav-lesson-icon-hidden');
                    if(activeIcon) {
                        activeIcon.textContent = '✓';
                        activeIcon.parentElement.classList.add('completed');
                    }
                } else {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Coba Lagi';
                }
            })
            .catch(err => {
                console.error(err);
                submitBtn.disabled = false;
                submitBtn.textContent = 'Jawab Kuis';
            });
        });
    </script>
    @endif
    <script src="{{ asset('js/panel.js') }}"></script>
</body>
</html>

