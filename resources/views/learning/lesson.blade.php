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

