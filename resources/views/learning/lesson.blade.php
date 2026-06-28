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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/ace.js"></script>
</head>
<body class="lesson-page">

    @include('partials.menu-panel')

    <!-- EDITORIAL LAYOUT SHELL -->
    <div class="editorial-shell">
        
        <!-- LEFT PANEL: Sticky Title & Meta Info -->
        <aside class="editorial-left">
            <div class="left-watermark" id="leftWatermark">TURNCODE</div>
            <div class="left-glow-accent" id="leftGlowAccent"></div>
            <div class="left-top">
                <a href="{{ route('courses.show', [$course->id, 'submateri_id' => $lesson->chapter->submateri->id]) }}" class="editorial-back">
                    <i class='bx bx-left-arrow-alt'></i> Kembali
                </a>
                <span class="left-brand">TurnCode</span>
            </div>
            
            <div class="left-middle">
                <span class="left-chapter-num">BAB {{ $lesson->chapter->order }}</span>
                <h1 class="left-title">
                    @php
                        // Split title to render stacked artistically
                        $words = explode(' ', $lesson->title);
                    @endphp
                    @foreach($words as $word)
                        <span>{{ $word }}</span>
                    @endforeach
                </h1>
                <div class="left-meta">
                    <span>{{ $course->title }}</span>
                    <span class="meta-dot"></span>
                    <span>{{ $lesson->chapter->submateri->title }}</span>
                </div>
            </div>

            <!-- Parallax Scroll Indicator -->
            <div class="scroll-indicator" id="scrollIndicator">
                <div class="scroll-track">
                    <div class="scroll-track-fill" id="scrollTrackFill"></div>
                </div>
                <div class="scroll-dot" id="scrollDot">
                    <span class="scroll-percent" id="scrollPercent">0%</span>
                </div>
                <div class="scroll-labels">
                    <span class="scroll-label-start">Mulai</span>
                    <span class="scroll-label-end">Selesai</span>
                </div>
            </div>

            <div class="left-bottom">
                <div class="author-info-new">
                    <img src="https://i.pravatar.cc/100?img=12" alt="HanzzSama" class="author-avatar-new">
                    <div class="author-text-new">
                        <span class="author-name-new">HanzzSama</span>
                        <span class="author-role-new">Penulis & Developer</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- RIGHT PANEL: Scrollable Reading Area -->
        <main class="editorial-right">
            
            <!-- Controls Floating Top-Right -->
            <div class="editorial-controls">
                <button class="control-btn" id="toggleLessonNav" title="Daftar Materi">
                    <i class='bx bx-list-ul'></i>
                </button>
                <button class="control-btn" id="navMenuBtn" style="position: relative;" title="Menu Utama">
                    <i class='bx bx-grid-alt'></i>
                    @php
                        $unreadNotificationsCount = isset($notifications) ? $notifications->whereNull('read_at')->count() : 0;
                    @endphp
                    <span class="nav-unread-dot"
                        style="position: absolute; top: 8px; right: 8px; width: 8px; height: 8px; background: #ef4444; border-radius: 50%; box-shadow: 0 0 8px #ef4444; transition: opacity 0.3s ease, transform 0.3s ease; {{ $unreadNotificationsCount > 0 ? '' : 'display: none; opacity: 0; transform: scale(0);' }}"></span>
                </button>
            </div>

            <!-- Scrollable container -->
            <div class="right-scroll-container">
                <!-- Massive Background Number for Parallax -->
                <div class="parallax-index" id="parallaxIndex">
                    0{{ $lesson->chapter->order }}.{{ $lesson->order }}
                </div>

                <div class="right-content">
                    <div class="right-header">
                        <div class="chapter-badge">BAB {{ $lesson->chapter->order }} : {{ $lesson->chapter->title }}</div>
                        @if($lesson->chapter->description)
                            <p class="chapter-sub-desc">
                                {{ $lesson->chapter->description }}
                            </p>
                        @endif
                    </div>

                    <!-- Conditional Thumbnail Image with Parallax wrapper -->
                    @if(!empty($lesson->thumbnail) || !empty($lesson->image) || !empty($lesson->image_url))
                        <div class="parallax-hero-wrapper">
                            @php
                                $imgSrc = !empty($lesson->thumbnail) ? asset($lesson->thumbnail) : (!empty($lesson->image) ? asset($lesson->image) : $lesson->image_url);
                            @endphp
                            <img src="{{ $imgSrc }}" alt="{{ $lesson->title }}" class="parallax-hero-img" id="parallaxHero">
                        </div>
                    @endif

                    <!-- Prose Content -->
                    <div class="prose">
                        {!! $lesson->content !!}
                    </div>

                    <!-- Quiz / Complete Section -->
                    <div class="quiz-section-new">
                        <div class="next-lesson-new">
                            @php
                                $hasNextLesson = isset($nextLesson) && $nextLesson;
                            @endphp

                            @if(in_array($lesson->id, $completedLessons))
                                @if($hasNextLesson)
                                    <a href="{{ route('lessons.show', $nextLesson->id) }}" class="btn-editorial-next">
                                        Lanjut ke materi berikutnya <i class='bx bx-right-arrow-alt'></i>
                                    </a>
                                @else
                                    @php
                                        $isQuizPassed = in_array($lesson->chapter->submateri->id, auth()->user()->achievements['passed_submateri_quizzes'] ?? []);
                                    @endphp
                                    @if($isQuizPassed)
                                        <a href="{{ route('courses.show', [$course->id, 'submateri_id' => $lesson->chapter->submateri->id]) }}" class="btn-editorial-next">
                                            Kembali ke Halaman Kelas <i class='bx bx-home-alt'></i>
                                        </a>
                                    @else
                                        <a href="{{ route('submateris.quiz.show', $lesson->chapter->submateri->id) }}" class="btn-editorial-next">
                                            Mulai Uji Pemahaman <i class='bx bx-brain'></i>
                                        </a>
                                    @endif
                                @endif
                            @else
                                @if($hasNextLesson)
                                    <form action="{{ route('lessons.complete', $lesson->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-editorial-next" style="width: 100%;">
                                            Tandai selesai & Lanjut <i class='bx bx-right-arrow-alt'></i>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('lessons.complete', $lesson->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-editorial-next" style="width: 100%;">
                                            Selesai & Mulai Uji Pemahaman <i class='bx bx-check-double'></i>
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Forum Diskusi & Tanya Jawab -->
                    <div class="discussion-section">
                        <div class="discussion-header">
                            <span class="forum-eyebrow">Diskusi</span>
                            <div class="forum-heading-row">
                                <h3 class="forum-title">Ruang Tanya Jawab</h3>
                                <span class="forum-count-badge" id="forumCountBadge">{{ $discussions->count() }}</span>
                            </div>
                            <p class="forum-subtitle">Tanyakan hal yang membingungkan atau diskusikan topik materi ini bersama pelajar lain.</p>
                        </div>

                        <!-- Main Thread Input Form -->
                        <div class="discussion-input-wrapper">
                            <img class="forum-user-avatar" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=e2583e&color=fff&bold=true" alt="{{ auth()->user()->name }}">
                            <form id="newDiscussionForm" class="discussion-form" data-lesson-id="{{ $lesson->id }}">
                                @csrf
                                <div class="input-row-wrapper" style="display: flex; align-items: flex-end; gap: 0.75rem; width: 100%;">
                                    <div class="textarea-container">
                                        <textarea id="discussionContent" placeholder="Tulis pertanyaan atau pendapatmu..." rows="1" required></textarea>
                                        <div class="textarea-focus-glow"></div>
                                    </div>
                                    <button type="submit" class="btn-forum-send" id="btnSendDiscussion" title="Kirim">
                                        <i class='bx bx-send'></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Thread List Container -->
                        <div class="discussion-list" id="discussionList">
                            @forelse($discussions as $disc)
                                <div class="discussion-node" data-id="{{ $disc->id }}">
                                    <div class="discussion-card">
                                        <img class="forum-user-avatar" src="https://ui-avatars.com/api/?name={{ urlencode($disc->user->name) }}&background=121212&color=f5f4f0&bold=true" alt="{{ $disc->user->name }}">
                                        <div class="discussion-card-body">
                                            <div class="discussion-card-meta">
                                                <span class="user-name">{{ $disc->user->name }}</span>
                                                @if($disc->user->email === 'admin@turncode.com' || $disc->user_id === 1)
                                                    <span class="badge-role">Staff</span>
                                                @endif
                                                <span class="meta-separator">•</span>
                                                <span class="timestamp">{{ $disc->created_at->diffForHumans() }}</span>
                                            </div>
                                            <div class="discussion-text-content">
                                                {!! nl2br(e($disc->content)) !!}
                                            </div>
                                            <div class="discussion-actions">
                                                <button class="action-btn-like {{ $disc->isLikedBy(auth()->user()) ? 'active' : '' }}" onclick="toggleDiscussionLike({{ $disc->id }}, this)">
                                                    <i class='bx bxs-heart heart-icon'></i>
                                                    <span class="like-count">{{ $disc->likes_count }}</span>
                                                </button>
                                                <button class="action-btn-reply" onclick="toggleReplyForm({{ $disc->id }})">
                                                    <i class='bx bx-comment-detail'></i> Balas
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reply Form (Hidden initially) -->
                                    <div class="reply-form-wrapper" id="replyFormWrapper-{{ $disc->id }}" style="display: none;">
                                        <img class="forum-user-avatar-sm" src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=e2583e&color=fff&bold=true" alt="{{ auth()->user()->name }}">
                                        <form onsubmit="submitReply(event, {{ $disc->id }}, {{ $lesson->id }})" class="reply-form">
                                            @csrf
                                            <div class="textarea-container-sm">
                                                <textarea id="replyContent-{{ $disc->id }}" placeholder="Balas tanggapan..." rows="1" required></textarea>
                                                <div class="textarea-focus-glow"></div>
                                            </div>
                                            <div class="form-actions-sm">
                                                <button type="button" class="btn-forum-cancel-sm" onclick="toggleReplyForm({{ $disc->id }})">Batal</button>
                                                <button type="submit" class="btn-forum-submit-sm">Kirim</button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Child Replies List -->
                                    <div class="replies-container" id="repliesContainer-{{ $disc->id }}">
                                        @if($disc->replies->count() > 0)
                                            <div class="replies-toggle-btn" onclick="toggleRepliesCollapse({{ $disc->id }}, this)">
                                                <i class='bx bx-chevron-down toggle-icon'></i> Lihat {{ $disc->replies->count() }} Balasan
                                            </div>
                                            <div class="replies-list-wrapper" id="repliesListWrapper-{{ $disc->id }}" style="display: none;">
                                                @foreach($disc->replies as $reply)
                                                    <div class="reply-card" data-id="{{ $reply->id }}">
                                                        <img class="forum-user-avatar-sm" src="https://ui-avatars.com/api/?name={{ urlencode($reply->user->name) }}&background=555555&color=fff&bold=true" alt="{{ $reply->user->name }}">
                                                        <div class="discussion-card-body">
                                                            <div class="discussion-card-meta">
                                                                <span class="user-name-sm">{{ $reply->user->name }}</span>
                                                                @if($reply->user->email === 'admin@turncode.com' || $reply->user_id === 1)
                                                                    <span class="badge-role">Staff</span>
                                                                @endif
                                                                <span class="meta-separator">•</span>
                                                                <span class="timestamp-sm">{{ $reply->created_at->diffForHumans() }}</span>
                                                            </div>
                                                            <div class="discussion-text-content-sm">
                                                                {!! nl2br(e($reply->content)) !!}
                                                            </div>
                                                            <div class="discussion-actions-sm">
                                                                <button class="action-btn-like-sm {{ $reply->isLikedBy(auth()->user()) ? 'active' : '' }}" onclick="toggleDiscussionLike({{ $reply->id }}, this)">
                                                                    <i class='bx bxs-heart heart-icon'></i>
                                                                    <span class="like-count">{{ $reply->likes_count }}</span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="forum-empty-state" id="forumEmptyState">
                                    <i class='bx bx-chat-smile empty-icon'></i>
                                    <p class="empty-title">Belum ada diskusi</p>
                                    <p class="empty-desc">Jadilah yang pertama untuk memulai diskusi atau bertanya di materi ini!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- NAVIGATION DRAWER (Slide-out Lesson List) -->
        <div class="nav-drawer-overlay" id="navDrawerOverlay"></div>
        <div class="nav-drawer" id="navDrawer">
            <div class="drawer-header">
                <h3>Daftar Materi</h3>
                <button class="close-drawer" id="closeDrawerBtn"><i class='bx bx-x'></i></button>
            </div>
            
            <div class="drawer-meta-section">
                <span class="drawer-submateri">{{ $lesson->chapter->submateri->title }}</span>
                <div class="drawer-chapter">BAB {{ $lesson->chapter->order }} : {{ $lesson->chapter->title }}</div>
            </div>

            @php
                $chapterLessons = $lesson->chapter->lessons;
                $totalChapterLessons = $chapterLessons->count();
                $completedChapterLessons = $chapterLessons->filter(function($lsn) use ($completedLessons) {
                    return in_array($lsn->id, $completedLessons);
                })->count();
                $progressPercent = $totalChapterLessons > 0 ? round(($completedChapterLessons / $totalChapterLessons) * 100) : 0;
            @endphp

            <!-- Dynamic Progress Stats Widget -->
            <div class="drawer-progress-container">
                <div class="drawer-progress-text">
                    <span class="progress-title">Progres Bab</span>
                    <span class="progress-ratio">{{ $completedChapterLessons }}/{{ $totalChapterLessons }} Selesai</span>
                </div>
                <div class="drawer-progress-bar-wrapper">
                    <div class="drawer-progress-bar-fill" style="width: {{ $progressPercent }}%;"></div>
                </div>
            </div>

            <!-- Material Search Filter Input -->
            <div class="drawer-search-wrapper">
                <i class='bx bx-search search-icon'></i>
                <input type="text" id="drawerSearchInput" placeholder="Cari materi..." autocomplete="off">
                <button type="button" id="clearDrawerSearch" class="clear-search-btn" style="display: none;">
                    <i class='bx bx-x'></i>
                </button>
            </div>
            
            <div class="drawer-list">
                @php 
                    $canAccess = true; 
                    $isLastChapter = $lesson->chapter->order === $lesson->chapter->submateri->chapters->max('order');
                @endphp
                @foreach($lesson->chapter->lessons as $index => $lsn)
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
                        <div class="drawer-item locked coming-soon" style="--item-index: {{ $index }};">
                            <div class="item-status-icon soon">
                                <i class='bx bx-time-five'></i>
                            </div>
                            <span class="title">{{ $lsn->title }}</span>
                            <span class="badge-soon">Soon</span>
                        </div>
                    @else
                        <a href="{{ $isLocked ? '#' : route('lessons.show', $lsn->id) }}"
                           class="drawer-item {{ $isCurrent ? 'active' : '' }} {{ $isCompleted ? 'completed' : '' }} {{ $isLocked ? 'locked' : '' }}"
                           style="--item-index: {{ $index }};"
                           data-title="{{ strtolower($lsn->title) }}">
                            <div class="item-status-icon">
                                @if($isCurrent)
                                    <span class="active-pulse"></span>
                                @elseif($isCompleted)
                                    <i class='bx bx-check-circle completed-check'></i>
                                @elseif($isLocked)
                                    <i class='bx bx-lock-alt locked-padlock'></i>
                                @else
                                    <span class="bullet"></span>
                                @endif
                            </div>
                            <span class="title">{{ $lsn->title }}</span>
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
                        $quizIndex = count($lesson->chapter->lessons);
                    @endphp
                    @if($isQuizLocked)
                        <div class="drawer-item locked quiz-item" style="--item-index: {{ $quizIndex }};">
                            <div class="item-status-icon">
                                <i class='bx bx-lock-alt locked-padlock'></i>
                            </div>
                            <span class="title">Uji Pemahaman</span>
                        </div>
                    @else
                        <a href="{{ route('submateris.quiz.show', $lesson->chapter->submateri->id) }}"
                           class="drawer-item quiz {{ $isQuizPassed ? 'completed' : '' }}"
                           style="--item-index: {{ $quizIndex }};"
                           data-title="uji pemahaman">
                            <div class="item-status-icon">
                                @if($isQuizPassed)
                                    <i class='bx bx-check-circle completed-check'></i>
                                @else
                                    <i class='bx bx-brain'></i>
                                @endif
                            </div>
                            <span class="title">Uji Pemahaman</span>
                        </a>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Lenis Smooth Scroll CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@studio-freight/lenis@1.0.42/dist/lenis.min.js"></script>
    <script src="{{ asset('js/panel.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Lenis smooth scroll on the right scroll container
            let lenis;
            if (typeof Lenis !== 'undefined') {
                const scrollContainer = document.querySelector('.right-scroll-container');
                const contentContainer = document.querySelector('.right-content');
                if (scrollContainer && contentContainer) {
                    lenis = new Lenis({
                        wrapper: scrollContainer,
                        content: contentContainer,
                        lerp: 0.12,
                        smoothWheel: true,
                        smoothTouch: false
                    });

                    function raf(time) {
                        lenis.raf(time);
                        requestAnimationFrame(raf);
                    }
                    requestAnimationFrame(raf);
                    
                    // Expose globally or store it for potential custom routing scroll resets
                    window.lenis = lenis;
                }
            }

            // Mouse-following glow highlight on left sidebar panel
            const sidebar = document.querySelector('.editorial-left');
            const glow = document.getElementById('leftGlowAccent');
            if (sidebar && glow) {
                sidebar.addEventListener('mousemove', (e) => {
                    const rect = sidebar.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    glow.style.left = `${x}px`;
                    glow.style.top = `${y}px`;
                });
            }

            // Dynamic Scroll Checkpoint Timeline
            const rightScrollContainer = document.querySelector('.right-scroll-container');
            const timelineTrack = document.querySelector('.scroll-track');
            const timelineCheckpoints = [];

            if (timelineTrack && rightScrollContainer) {
                const sections = [
                    { selector: '.right-header', label: 'Pengantar' },
                    { selector: '.vc-player', label: 'Video Pembelajaran' },
                    { selector: '.prose h2, .prose h3', label: 'Materi Utama' },
                    { selector: '.quiz-section-new', label: 'Selesai' }
                ];

                sections.forEach(sec => {
                    const el = document.querySelector(sec.selector);
                    if (el) {
                        const cp = document.createElement('div');
                        cp.className = 'scroll-checkpoint';
                        
                        const cpTooltip = document.createElement('span');
                        cpTooltip.className = 'checkpoint-tooltip';
                        cpTooltip.textContent = sec.label;
                        cp.appendChild(cpTooltip);

                        cp.addEventListener('click', () => {
                            if (window.lenis) {
                                window.lenis.scrollTo(el, { offset: -40 });
                            } else {
                                el.scrollIntoView({ behavior: 'smooth' });
                            }
                        });

                        timelineTrack.appendChild(cp);
                        timelineCheckpoints.push({ el, dot: cp, pct: 0 });
                    }
                });

                const updateCPPositions = () => {
                    const scrollHeight = rightScrollContainer.scrollHeight - rightScrollContainer.clientHeight;
                    if (scrollHeight <= 0) return;

                    // Calculate raw percentages based on absolute offsets
                    const rawPcts = [];
                    timelineCheckpoints.forEach(cp => {
                        const rect = cp.el.getBoundingClientRect();
                        const containerRect = rightScrollContainer.getBoundingClientRect();
                        const elementScrollTop = (rightScrollContainer.scrollTop + rect.top) - containerRect.top;
                        const rawPct = Math.min(Math.max(elementScrollTop / scrollHeight, 0), 1);
                        cp.rawPct = rawPct;
                        rawPcts.push(rawPct);
                    });

                    // Determine bounds for normalization
                    const minPct = Math.min(...rawPcts);
                    const maxPct = Math.max(...rawPcts);
                    const range = maxPct - minPct;

                    // Normalize so the first checkpoint is exactly at 0% and the last is at 100%
                    timelineCheckpoints.forEach(cp => {
                        let normalizedPct = cp.rawPct;
                        if (range > 0) {
                            normalizedPct = (cp.rawPct - minPct) / range;
                        }
                        cp.dot.style.top = `${normalizedPct * 100}%`;
                        cp.pct = normalizedPct;
                    });
                };

                // Store update function on window for scroll listener access
                window.updateCPPositions = updateCPPositions;
                
                setTimeout(updateCPPositions, 600);
                window.addEventListener('load', updateCPPositions);
                window.addEventListener('resize', updateCPPositions);
            }

            // Lesson navigation drawer toggle logic
            const toggleBtn = document.getElementById('toggleLessonNav');
            const closeBtn = document.getElementById('closeDrawerBtn');
            const drawer = document.getElementById('navDrawer');
            const overlay = document.getElementById('navDrawerOverlay');
            const searchInput = document.getElementById('drawerSearchInput');
            const clearSearchBtn = document.getElementById('clearDrawerSearch');
            const drawerItems = document.querySelectorAll('.drawer-item');

            const openDrawer = () => {
                drawer.classList.add('open');
                overlay.classList.add('active');
                
                // Trigger staggered entrance animation
                const drawerList = drawer.querySelector('.drawer-list');
                if (drawerList) {
                    drawerList.classList.add('animating');
                }
                
                // Focus search input after drawer slides in
                setTimeout(() => {
                    if (searchInput) searchInput.focus();
                }, 300);
            };

            const closeDrawer = () => {
                drawer.classList.remove('open');
                overlay.classList.remove('active');
                
                // Reset staggered entrance states
                const drawerList = drawer.querySelector('.drawer-list');
                if (drawerList) {
                    drawerList.classList.remove('animating');
                }
                
                // Clear search on close
                if (searchInput) {
                    searchInput.value = '';
                    filterDrawerItems('');
                }
            };

            const filterDrawerItems = (query) => {
                const cleanQuery = query.trim().toLowerCase();
                
                if (clearSearchBtn) {
                    clearSearchBtn.style.display = cleanQuery.length > 0 ? 'flex' : 'none';
                }

                drawerItems.forEach(item => {
                    const title = item.getAttribute('data-title') || '';
                    if (title.includes(cleanQuery)) {
                        item.style.display = '';
                        item.style.opacity = '';
                        item.style.transform = '';
                    } else {
                        item.style.display = 'none';
                    }
                });
            };

            if (toggleBtn && drawer && overlay) {
                toggleBtn.addEventListener('click', openDrawer);
                closeBtn.addEventListener('click', closeDrawer);
                overlay.addEventListener('click', closeDrawer);
            }

            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    // Temporarily disable entrance animations during filter typing to keep it clean
                    const drawerList = drawer.querySelector('.drawer-list');
                    if (drawerList) {
                        drawerList.classList.remove('animating');
                    }
                    filterDrawerItems(e.target.value);
                });
            }

            if (clearSearchBtn && searchInput) {
                clearSearchBtn.addEventListener('click', () => {
                    searchInput.value = '';
                    filterDrawerItems('');
                    searchInput.focus();
                });
            }

            // Parallax scroll effect + Scroll Indicator
            const rightPanel = document.querySelector('.right-scroll-container');
            const parallaxIndex = document.getElementById('parallaxIndex');
            const parallaxHero = document.getElementById('parallaxHero');
            const scrollTrackFill = document.getElementById('scrollTrackFill');
            const scrollDot = document.getElementById('scrollDot');
            const scrollPercent = document.getElementById('scrollPercent');
            const scrollIndicator = document.getElementById('scrollIndicator');
            
            // Left panel parallax elements
            const leftWatermark = document.getElementById('leftWatermark');
            const leftTitleSpans = document.querySelectorAll('.left-title span');
            const leftChapterNum = document.querySelector('.left-chapter-num');
            const leftMeta = document.querySelector('.left-meta');

            let scrollTimeout;

            if (rightPanel) {
                rightPanel.addEventListener('scroll', function() {
                    const scrollTop = rightPanel.scrollTop;
                    const scrollHeight = rightPanel.scrollHeight - rightPanel.clientHeight;
                    const progress = scrollHeight > 0 ? Math.min(scrollTop / scrollHeight, 1) : 0;

                    // Move index indicator slower (parallax ratio 0.3)
                    if (parallaxIndex) {
                        parallaxIndex.style.transform = `translateY(${scrollTop * 0.3}px)`;
                    }

                    // Move hero image (parallax ratio 0.1)
                    if (parallaxHero) {
                        parallaxHero.style.transform = `translateY(${scrollTop * 0.1}px)`;
                    }

                    // Left panel watermark vertical slide and rotate
                    if (leftWatermark) {
                        leftWatermark.style.transform = `rotate(-90deg) translateY(${-scrollTop * 0.1}px)`;
                    }

                    // Left panel chapter num remains stable or drifts slightly
                    if (leftChapterNum) {
                        leftChapterNum.style.transform = `translateY(${-scrollTop * 0.01}px)`;
                    }

                    // Left panel staggered title words slide left up to -100% staggered from bottom to top
                    const leftTitle = document.querySelector('.left-title');
                    const N = leftTitleSpans.length;
                    leftTitleSpans.forEach((span, idx) => {
                        const revIdx = N - 1 - idx; // Bottom-most is 0, top-most is N-1
                        const startProgress = revIdx * 0.15;
                        const endProgress = startProgress + 0.45;
                        
                        let p = 0;
                        if (progress > startProgress) {
                            p = (progress - startProgress) / (endProgress - startProgress);
                            p = Math.min(Math.max(p, 0), 1);
                        }
                        
                        const translateX = p * -100;
                        const translateY = p * -60; // slightly upward
                        const scale = 1 - (p * 0.35); // scale down to 0.65 (more noticeable)
                        span.style.transform = `translateX(${translateX}%) translateY(${translateY}px) scale(${scale})`;
                        span.style.opacity = `${Math.max(1 - (p * 1.5), 0)}`; // faster opacity fade
                    });

                    // Left panel meta info slides up to exactly below left-chapter
                    if (leftMeta && leftTitle) {
                        const titleHeight = leftTitle.offsetHeight;
                        const leftMiddle = document.querySelector('.left-middle');
                        const gap = leftMiddle ? (parseFloat(getComputedStyle(leftMiddle).gap) || 20) : 20;
                        const maxTranslateY = -(titleHeight + gap);
                        
                        // Sync with overall slide-out completion (0.75 progress)
                        const metaProgress = Math.min(progress / 0.75, 1);
                        const translateY = metaProgress * maxTranslateY;
                        leftMeta.style.transform = `translateY(${translateY}px)`;
                    }

                    // Update scroll indicator on left panel
                    if (scrollTrackFill) {
                        scrollTrackFill.style.height = `${progress * 100}%`;
                    }
                    if (scrollDot) {
                        scrollDot.style.top = `calc(${progress * 100}% - ${progress * 19}px)`;
                        
                        // Fade out indicator dot at the very top and very bottom to prevent overlapping with the checkpoints
                        if (progress <= 0.005 || progress >= 0.995) {
                            scrollDot.style.opacity = '0';
                            scrollDot.style.pointerEvents = 'none';
                        } else {
                            scrollDot.style.opacity = '1';
                            scrollDot.style.pointerEvents = 'auto';
                        }
                    }
                    if (scrollPercent) {
                        scrollPercent.textContent = `${Math.round(progress * 100)}%`;
                    }

                    // Update active timeline checkpoints
                    if (typeof timelineCheckpoints !== 'undefined' && timelineCheckpoints.length > 0) {
                        let activeCp = null;
                        
                        // Find the current active section based on proximity to the viewport top
                        timelineCheckpoints.forEach(cp => {
                            const rect = cp.el.getBoundingClientRect();
                            const containerRect = rightPanel.getBoundingClientRect();
                            const offsetTop = rect.top - containerRect.top;
                            
                            // If section top has crossed 120px threshold from container top, consider it active candidate
                            if (offsetTop <= 120) {
                                activeCp = cp;
                            }
                        });
                        
                        // Fallback: if we haven't scrolled past the first section, make the first active
                        if (!activeCp) {
                            activeCp = timelineCheckpoints[0];
                        }

                        timelineCheckpoints.forEach(cp => {
                            // Passed state: section was scrolled past and is not the current active section
                            const isPassed = (progress >= cp.pct - 0.02) && (cp !== activeCp);
                            
                            if (isPassed) {
                                cp.dot.classList.add('passed');
                            } else {
                                cp.dot.classList.remove('passed');
                            }

                            if (cp === activeCp) {
                                cp.dot.classList.add('active');
                                cp.dot.classList.remove('passed');
                            } else {
                                cp.dot.classList.remove('active');
                            }
                        });
                    }

                    // Show indicator as active while scrolling
                    if (scrollIndicator) {
                        scrollIndicator.classList.add('active');
                        clearTimeout(scrollTimeout);
                        scrollTimeout = setTimeout(() => {
                            scrollIndicator.classList.remove('active');
                        }, 1500);
                    }
                });
            }

            // Configure Ace base path
            ace.config.set("basePath", "https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/");

            // Track all active editors and their theme selectors for synchronization
            const activeEditors = [];

            // Dynamic Live Editor Generation
            const preElements = document.querySelectorAll('.prose pre');
            preElements.forEach((pre, index) => {
                // Get clean code from pre element
                const originalCode = pre.innerText || pre.textContent;
                const editorDivId = `code-editor-${index}`;
                
                // Create parent wrapper element
                const editorWrapper = document.createElement('div');
                editorWrapper.className = 'live-editor-wrapper';

                // Create theme selection and playground element exactly above the container
                const themeOuterWrapper = document.createElement('div');
                themeOuterWrapper.className = 'live-editor-theme-outer-wrapper';
                themeOuterWrapper.innerHTML = `
                    <div class="live-editor-theme-select-wrapper" title="Ubah tema pewarnaan sintaksis">
                        <i class='bx bx-palette'></i>
                        <select class="live-editor-theme-select">
                            <option value="tomorrow_night">Tomorrow Night (Dark)</option>
                            <option value="monokai">Monokai (Dark)</option>
                            <option value="dracula">Dracula (Dark)</option>
                            <option value="twilight">Twilight (Dark)</option>
                            <option value="github">GitHub (Light)</option>
                            <option value="chrome">Chrome (Light)</option>
                            <option value="xcode">Xcode (Light)</option>
                        </select>
                        <i class='bx bx-chevron-down select-arrow-icon'></i>
                    </div>
                    <button class="live-editor-playground-btn" type="button" title="Buka di Layar Penuh">
                        <i class='bx bx-expand'></i> Playground
                    </button>
                `;
                
                // Create editor element
                const editorContainer = document.createElement('div');
                editorContainer.className = 'live-editor-container';
                editorContainer.innerHTML = `
                    <div class="live-editor-header">
                        <div class="live-editor-header-left">
                            <div class="window-dots">
                                <span class="dot red"></span>
                                <span class="dot yellow"></span>
                                <span class="dot green"></span>
                            </div>
                            <span class="live-editor-badge">LIVE CODE</span>
                            <span class="live-editor-desc">Ubah kode secara real-time atau klik Run</span>
                        </div>
                        <div class="live-editor-header-right">
                            <button class="live-editor-reset-btn" type="button">
                                <i class='bx bx-refresh'></i> Reset
                            </button>
                            <button class="live-editor-run-btn" type="button">
                                <i class='bx bx-play'></i> Run
                            </button>
                        </div>
                    </div>
                    <div class="live-editor-body">
                        <div class="main-code">
                            <div class="code-editor-div" id="${editorDivId}"></div>
                        </div>
                        <div class="main-output">
                            <iframe class="output-iframe" sandbox="allow-scripts"></iframe>
                        </div>
                    </div>
                `;
                
                // Assemble the layout
                editorWrapper.appendChild(themeOuterWrapper);
                editorWrapper.appendChild(editorContainer);
                
                // Insert wrapper below the pre element
                pre.insertAdjacentElement('afterend', editorWrapper);
                
                const iframe = editorContainer.querySelector('.output-iframe');
                const resetBtn = editorContainer.querySelector('.live-editor-reset-btn');
                const runBtn = editorContainer.querySelector('.live-editor-run-btn');
                const themeSelect = themeOuterWrapper.querySelector('.live-editor-theme-select');
                const playgroundBtn = themeOuterWrapper.querySelector('.live-editor-playground-btn');
                
                // Detect language mode dynamically
                let mode = "ace/mode/html";
                const trimmed = originalCode.trim().toLowerCase();
                if (trimmed.startsWith("\x3c?php") || trimmed.includes("echo ") || trimmed.includes("\x3c?=")) {
                    mode = "ace/mode/php";
                } else if (trimmed.includes("body {") || (trimmed.includes("color:") && !trimmed.includes("<")) || trimmed.includes("selector {")) {
                    mode = "ace/mode/css";
                } else if (trimmed.includes("function ") || trimmed.includes("const ") || trimmed.includes("let ") || trimmed.includes("var ")) {
                    mode = "ace/mode/javascript";
                }
                
                // Get persisted theme from localStorage
                const savedTheme = localStorage.getItem('turncode_editor_theme') || 'tomorrow_night';
                themeSelect.value = savedTheme;
                
                // Initialize Ace Editor
                const editor = ace.edit(editorDivId);
                editor.setOptions({
                    theme: `ace/theme/${savedTheme}`,
                    mode: mode,
                    fontSize: "14px",
                    fontFamily: "'Fira Code', Monaco, Consolas, monospace",
                    lineHeight: 24,
                    showPrintMargin: false,
                    useWorker: false,
                    tabSize: 4,
                    wrap: true
                });
                
                // Register to active editors list
                activeEditors.push({ editor, selectElement: themeSelect });
                
                // Listen to theme dropdown changes
                themeSelect.addEventListener('change', function() {
                    const selectedTheme = this.value;
                    localStorage.setItem('turncode_editor_theme', selectedTheme);
                    
                    // Sync the theme for all editors on the page
                    activeEditors.forEach(item => {
                        item.editor.setTheme(`ace/theme/${selectedTheme}`);
                        item.selectElement.value = selectedTheme;
                    });
                });
                
                // Listen to Playground button click (Fullscreen mode)
                playgroundBtn.addEventListener('click', function() {
                    const isNowFullscreen = !editorWrapper.classList.contains('fullscreen');
                    
                    if (isNowFullscreen) {
                        // ENTERING FULLSCREEN
                        editorWrapper.classList.add('fullscreen');
                        document.body.classList.add('editor-playground-fullscreen-active');
                        playgroundBtn.innerHTML = `<i class='bx bx-shrink'></i> Keluar`;
                        document.body.style.overflow = "hidden";
                        editor.resize();
                    } else {
                        // EXITING FULLSCREEN with smooth fade-out animation
                        editorWrapper.classList.add('fullscreen-exiting');
                        
                        setTimeout(() => {
                            editorWrapper.classList.remove('fullscreen');
                            editorWrapper.classList.remove('fullscreen-exiting');
                            document.body.classList.remove('editor-playground-fullscreen-active');
                            playgroundBtn.innerHTML = `<i class='bx bx-expand'></i> Playground`;
                            document.body.style.overflow = "";
                            editor.resize();
                        }, 400); // Matches the 0.4s animation duration
                    }
                });
                
                // Populate initial code
                editor.setValue(originalCode.trim(), -1);
                
                function updateOutput() {
                    let code = editor.getValue();
                    const baseStyle = `
                        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
                        <style>
                            body {
                                font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                                color: #121212;
                                background-color: #ffffff;
                                line-height: 1.6;
                                padding: 1.5rem;
                                margin: 0;
                            }
                            h1, h2, h3, h4, h5, h6 {
                                color: #0c0c0c;
                                font-weight: 800;
                                margin-bottom: 1rem;
                                margin-top: 0;
                            }
                            p {
                                margin-bottom: 1rem;
                            }
                        </style>
                    `;
                    if (code.includes('</head>')) {
                        code = code.replace('</head>', `${baseStyle}</head>`);
                    } else {
                        code = baseStyle + code;
                    }
                    iframe.srcdoc = code;
                }
                
                // Listen to changes in editor (real-time rendering)
                editor.session.on('change', updateOutput);
                
                // Listen to run button click
                runBtn.addEventListener('click', updateOutput);
                
                // Listen to reset button
                resetBtn.addEventListener('click', () => {
                    editor.setValue(originalCode.trim(), -1);
                    updateOutput();
                });
                
                // Initialize preview
                updateOutput();
            });

            // ── Custom Video Player ──────────────────────────────────────
            (function initCustomVideoPlayers() {
                const SPEEDS = [0.5, 0.75, 1, 1.25, 1.5, 2];

                function fmtTime(sec) {
                    if (isNaN(sec)) return '0:00';
                    const m = Math.floor(sec / 60);
                    const s = Math.floor(sec % 60);
                    return `${m}:${s.toString().padStart(2, '0')}`;
                }

                function parseTime(timeStr) {
                    if (!timeStr) return null;
                    timeStr = timeStr.trim();
                    if (/^\d+$/.test(timeStr)) {
                        return parseInt(timeStr, 10);
                    }
                    const parts = timeStr.split(':').map(Number);
                    if (parts.some(isNaN)) return null;
                    if (parts.length === 2) {
                        return parts[0] * 60 + parts[1];
                    } else if (parts.length === 3) {
                        return parts[0] * 3600 + parts[1] * 60 + parts[2];
                    }
                    return null;
                }

                function getStartTimeFromUrl() {
                    const urlParams = new URLSearchParams(window.location.search);
                    let timeStr = urlParams.get('t') || urlParams.get('time');

                    if (!timeStr && window.location.hash) {
                        const hash = window.location.hash.substring(1);
                        const hashParams = new URLSearchParams(hash);
                        timeStr = hashParams.get('t') || hashParams.get('time') || hash;
                    }
                    return parseTime(timeStr);
                }

                function buildPlayer(figure) {
                    const video = figure.querySelector('video');
                    if (!video) return;

                    // Remove native controls
                    video.removeAttribute('controls');
                    video.classList.add('vc-managed');

                    // Get caption if any
                    const figcaption = figure.querySelector('figcaption');
                    const captionText = figcaption ? figcaption.textContent.trim() : '';

                    // Create player shell (parent of wrapper + controls)
                    const player = document.createElement('div');
                    player.className = 'vc-player';

                    // Create dynamic ambient backlight glow element
                    const backlight = document.createElement('div');
                    backlight.className = 'vc-backlight';
                    player.appendChild(backlight);

                    // Create video-only wrapper
                    const wrapper = document.createElement('div');
                    wrapper.className = 'vc-wrapper';

                    // Move video into wrapper, wrapper into player
                    wrapper.appendChild(video);
                    player.appendChild(wrapper);

                    // Build controls HTML
                    const controls = document.createElement('div');
                    controls.className = 'vc-controls';
                    controls.innerHTML = `
                        <button class="vc-play-btn" title="Play / Pause">
                            <svg viewBox="0 0 24 24">
                                <polygon class="vc-icon-play" points="5,3 19,12 5,21" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <div class="vc-progress-track">
                            <div class="vc-progress-filled">
                                <span class="vc-time-inside">0:00</span>
                                <div class="vc-progress-thumb"></div>
                            </div>
                            <div class="vc-time-tooltip">0:00</div>
                        </div>
                        <div class="vc-time vc-total">0:00</div>
                        <button class="vc-fullscreen-btn" title="Fullscreen">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/>
                            </svg>
                        </button>
                    `;
                    // Controls go OUTSIDE wrapper, as sibling inside player
                    player.appendChild(controls);

                    // Optional caption (also outside wrapper)
                    if (captionText) {
                        const cap = document.createElement('div');
                        cap.className = 'vc-caption';
                        cap.textContent = captionText;
                        player.appendChild(cap);
                    }

                    // Insert player where figure was, then remove figure
                    figure.parentNode.insertBefore(player, figure);
                    figure.parentNode.removeChild(figure);

                    // ── Refs ──
                    const playBtn        = controls.querySelector('.vc-play-btn');
                    const progressTrack  = controls.querySelector('.vc-progress-track');
                    const progressFilled = controls.querySelector('.vc-progress-filled');
                    const tooltip        = controls.querySelector('.vc-time-tooltip');
                    const timeInsideEl   = controls.querySelector('.vc-time-inside');
                    const totalEl        = controls.querySelector('.vc-total');
                    const fsBtn          = controls.querySelector('.vc-fullscreen-btn');

                    const PAUSE_ICON = `<line x1="6" y1="4" x2="6" y2="20"/><line x1="18" y1="4" x2="18" y2="20"/>`;
                    const PLAY_POLYGON = `<polygon class="vc-icon-play" points="5,3 19,12 5,21" stroke-linejoin="round"/>`;

                    let dragging = false;

                    // ── Helpers ──
                    function setProgress(pct) {
                        progressFilled.style.width = `${Math.max(0, Math.min(100, pct * 100))}%`;
                    }

                    function syncUI() {
                        if (dragging || seeking) return;
                        const dur = video.duration || 0;
                        const cur = video.currentTime || 0;
                        setProgress(dur ? cur / dur : 0);
                        timeInsideEl.textContent = fmtTime(cur);
                        totalEl.textContent      = fmtTime(dur);
                    }

                    function setPaused(paused) {
                        playBtn.querySelector('svg').innerHTML = paused ? PLAY_POLYGON : PAUSE_ICON;
                    }

                    let pendingSeekPct = null;
                    let pendingSeekTime = null;
                    let lastSeekedTime = null;
                    let seeking = false;
                    let wasPlaying = false;

                    // Initialize duration immediately if already available
                    if (video.duration) {
                        totalEl.textContent = fmtTime(video.duration);
                    }

                    function applyPendingSeek() {
                        const dur = video.duration || 0;
                        if (dur > 0) {
                            if (pendingSeekTime !== null) {
                                video.currentTime = Math.max(0, Math.min(dur, pendingSeekTime));
                                pendingSeekTime = null;
                                pendingSeekPct = null;
                            } else if (pendingSeekPct !== null) {
                                video.currentTime = pendingSeekPct * dur;
                                pendingSeekPct = null;
                            }
                        }
                    }

                    // ── Video events ──
                    video.addEventListener('loadedmetadata', () => {
                        totalEl.textContent = fmtTime(video.duration);
                        applyPendingSeek();
                    });

                    video.addEventListener('durationchange', () => {
                        if (video.duration) {
                            totalEl.textContent = fmtTime(video.duration);
                            applyPendingSeek();
                        }
                    });

                    video.addEventListener('timeupdate', syncUI);

                    video.addEventListener('seeking', () => {
                        seeking = true;
                    });

                    video.addEventListener('seeked', () => {
                        seeking = false;
                        syncUI();
                        lastSeekedTime = null;
                    });

                    // ── Auto-Hide Controls & Backlight Integration ──
                    let controlsTimeout;
                    function showControls() {
                        player.classList.remove('controls-hidden');
                        clearTimeout(controlsTimeout);
                        if (!video.paused && !dragging) {
                            controlsTimeout = setTimeout(() => {
                                player.classList.add('controls-hidden');
                            }, 2500);
                        }
                    }

                    player.addEventListener('mousemove', showControls);
                    player.addEventListener('mouseleave', () => {
                        if (!video.paused && !dragging) {
                            controlsTimeout = setTimeout(() => {
                                player.classList.add('controls-hidden');
                            }, 800);
                        }
                    });

                    video.addEventListener('play',  () => {
                        setPaused(false);
                        player.classList.add('is-playing');
                        showControls();
                        if (lastSeekedTime !== null) {
                            video.currentTime = lastSeekedTime;
                            lastSeekedTime = null;
                        }
                    });
                    video.addEventListener('pause', () => {
                        setPaused(true);
                        player.classList.remove('is-playing');
                        player.classList.remove('controls-hidden');
                        clearTimeout(controlsTimeout);
                    });
                    video.addEventListener('ended', () => {
                        video.currentTime = 0;
                        setPaused(true);
                        player.classList.remove('is-playing');
                        player.classList.remove('controls-hidden');
                        clearTimeout(controlsTimeout);
                    });

                    // ── Play / Pause ──
                    function togglePlay() {
                        if (video.paused) { video.play(); } else { video.pause(); }
                    }
                    playBtn.addEventListener('click', togglePlay);
                    video.addEventListener('click', togglePlay);

                    // ── Progress seek ──
                    function seekFromEvent(e) {
                        let clientX = 0;
                        if (e.touches && e.touches.length > 0) {
                            clientX = e.touches[0].clientX;
                        } else if (e.changedTouches && e.changedTouches.length > 0) {
                            clientX = e.changedTouches[0].clientX;
                        } else if (e.clientX !== undefined && e.clientX !== null) {
                            clientX = e.clientX;
                        }

                        const rect = progressTrack.getBoundingClientRect();
                        const pct  = Math.max(0, Math.min(1, (clientX - rect.left) / rect.width));
                        const dur  = video.duration || 0;

                        if (dur > 0) {
                            const targetTime = pct * dur;
                            video.currentTime = targetTime;
                            lastSeekedTime = targetTime;

                            // Instantly update progress bar width & text visually
                            progressFilled.style.width = `${pct * 100}%`;
                            timeInsideEl.textContent = fmtTime(targetTime);

                            // tooltip
                            tooltip.textContent = fmtTime(targetTime);
                            tooltip.style.left  = `${pct * 100}%`;
                        } else {
                            // Duration not loaded yet. Save percentage to seek after loading
                            pendingSeekPct = pct;

                            // Provide instant visual feedback that scrubbing happened
                            progressFilled.style.width = `${pct * 100}%`;
                            timeInsideEl.textContent = "Loading...";
                        }
                    }

                    function startDrag(e) {
                        dragging = true;
                        wasPlaying = !video.paused;
                        video.pause(); // Pause video while dragging (YouTube style)
                        progressTrack.classList.add('dragging');
                        seekFromEvent(e);
                    }

                    function moveDrag(e) {
                        if (!dragging) return;
                        seekFromEvent(e);
                    }

                    function endDrag() {
                        if (dragging) {
                            dragging = false;
                            progressTrack.classList.remove('dragging');
                            // Resume play if it was playing before drag started (YouTube style)
                            if (wasPlaying) {
                                video.play();
                            }
                        }
                    }

                    // Mouse scrubbing events
                    progressTrack.addEventListener('mousedown', startDrag);
                    document.addEventListener('mousemove', moveDrag);
                    document.addEventListener('mouseup', endDrag);

                    // Mobile touch scrubbing events
                    progressTrack.addEventListener('touchstart', (e) => {
                        e.preventDefault();
                        startDrag(e);
                    }, { passive: false });
                    document.addEventListener('touchmove', (e) => {
                        if (dragging) {
                            e.preventDefault();
                            moveDrag(e);
                        }
                    }, { passive: false });
                    document.addEventListener('touchend', endDrag);

                    progressTrack.addEventListener('mousemove', (e) => {
                        if (dragging) return;
                        const rect = progressTrack.getBoundingClientRect();
                        const pct  = Math.max(0, Math.min(1, (e.clientX - rect.left) / rect.width));
                        tooltip.textContent = fmtTime(pct * (video.duration || 0));
                        tooltip.style.left  = `${pct * 100}%`;
                    });

                    // Check URL for initial progress start time
                    const initialTime = getStartTimeFromUrl();
                    if (initialTime !== null) {
                        pendingSeekTime = initialTime;
                        if (video.readyState > 0) {
                            applyPendingSeek();
                        } else {
                            video.load();
                        }
                    }

                    // Listen to URL hash change for custom seeking (e.g. #t=2:23 or #2:23)
                    const onHashChange = () => {
                        const urlStartTime = getStartTimeFromUrl();
                        if (urlStartTime !== null) {
                            const dur = video.duration || 0;
                            if (dur > 0) {
                                video.currentTime = Math.max(0, Math.min(dur, urlStartTime));
                            } else {
                                pendingSeekTime = urlStartTime;
                                video.load();
                            }
                        }
                    };
                    window.addEventListener('hashchange', onHashChange);


                    // ── Fullscreen ──
                    fsBtn.addEventListener('click', () => {
                        if (document.fullscreenElement) {
                            document.exitFullscreen();
                        } else {
                            wrapper.requestFullscreen && wrapper.requestFullscreen();
                        }
                    });
                }

                // Initialize all doc-media-video figures with a <video> child
                document.querySelectorAll('.prose figure.doc-media-video').forEach(fig => {
                    if (fig.querySelector('video')) buildPlayer(fig);
                });
            })();

            // ── Scroll-Driven Prose Reveal Animations ──
            (function initProseReveals() {
                const scrollContainer = document.querySelector('.right-scroll-container');
                if (!scrollContainer) return;

                // Select all revealable elements inside .prose
                const revealTargets = document.querySelectorAll(
                    '.prose p, .prose h2, .prose h3, .prose h4, .prose figure, ' +
                    '.prose blockquote, .prose ul, .prose ol, .prose table, ' +
                    '.prose .live-editor-wrapper, .prose pre'
                );

                if (!revealTargets.length) return;

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('revealed');
                            observer.unobserve(entry.target); // only animate once
                        }
                    });
                }, {
                    root: scrollContainer,
                    rootMargin: '0px 0px -60px 0px', // trigger slightly before fully in view
                    threshold: 0.12
                });

                revealTargets.forEach(el => observer.observe(el));
            })();

            // ── Copy Code Buttons for all Ace Editors ──
            (function initCopyCodeButtons() {
                // Wait a tick for editors to be fully rendered
                setTimeout(() => {
                    document.querySelectorAll('.main-code').forEach(mainCode => {
                        // Skip if already has a copy button
                        if (mainCode.querySelector('.copy-code-btn')) return;

                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'copy-code-btn';
                        btn.title = 'Salin kode';
                        btn.innerHTML = `<i class='bx bx-copy'></i> Copy`;

                        btn.addEventListener('click', () => {
                            // Get code from the Ace editor instance
                            const editorDiv = mainCode.querySelector('.code-editor-div');
                            if (!editorDiv) return;
                            const aceEditor = ace.edit(editorDiv);
                            const code = aceEditor.getValue();

                            navigator.clipboard.writeText(code).then(() => {
                                btn.classList.add('copied');
                                btn.innerHTML = `<i class='bx bx-check'></i> Copied!`;

                                setTimeout(() => {
                                    btn.classList.remove('copied');
                                    btn.innerHTML = `<i class='bx bx-copy'></i> Copy`;
                                }, 2000);
                            }).catch(() => {
                                // Fallback for older browsers
                                const textarea = document.createElement('textarea');
                                textarea.value = code;
                                textarea.style.position = 'fixed';
                                textarea.style.opacity = '0';
                                document.body.appendChild(textarea);
                                textarea.select();
                                document.execCommand('copy');
                                document.body.removeChild(textarea);

                                btn.classList.add('copied');
                                btn.innerHTML = `<i class='bx bx-check'></i> Copied!`;
                                setTimeout(() => {
                                    btn.classList.remove('copied');
                                    btn.innerHTML = `<i class='bx bx-copy'></i> Copy`;
                                }, 2000);
                            });
                        });

                        mainCode.appendChild(btn);
                    });
                }, 300);
            })();

            // ── Discussion Forum JS Logic ──
            (function initDiscussionForum() {
                const currentUser = {
                    name: @json(auth()->user()->name),
                    email: @json(auth()->user()->email)
                };
                const csrfToken = '{{ csrf_token() }}';
                const lessonId = {{ $lesson->id }};

                const mainTextarea = document.getElementById('discussionContent');
                const newDiscussionForm = document.getElementById('newDiscussionForm');
                const discussionList = document.getElementById('discussionList');
                const forumCountBadge = document.getElementById('forumCountBadge');
                const forumEmptyState = document.getElementById('forumEmptyState');

                if (!mainTextarea) return;

                // Auto grow textarea utility
                const setupAutoGrow = (el) => {
                    el.addEventListener('input', function() {
                        this.style.height = 'auto';
                        this.style.height = this.scrollHeight + 'px';
                    });
                };

                setupAutoGrow(mainTextarea);

                // Post main discussion thread
                newDiscussionForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const content = mainTextarea.value.trim();

                    if (!content) return;

                    fetch(`/lessons/${lessonId}/discussions`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ content: content })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Clear form
                            mainTextarea.value = '';
                            mainTextarea.style.height = 'auto';

                            // Remove empty state if present
                            if (forumEmptyState) {
                                forumEmptyState.remove();
                            }

                            // Prepend new comment HTML
                            const nodeHtml = createDiscussionNodeHtml(data.discussion);
                            discussionList.insertAdjacentHTML('afterbegin', nodeHtml);

                            // Setup auto grow for new reply textarea inside prepended node
                            const newReplyTextarea = document.getElementById(`replyContent-${data.discussion.id}`);
                            if (newReplyTextarea) setupAutoGrow(newReplyTextarea);

                            // Update count badge
                            const currentCount = parseInt(forumCountBadge.textContent) || 0;
                            forumCountBadge.textContent = currentCount + 1;
                        }
                    });
                });

                // Toggle Reply Form display
                window.toggleReplyForm = function(id) {
                    const wrapper = document.getElementById(`replyFormWrapper-${id}`);
                    if (!wrapper) return;
                    if (wrapper.style.display === 'none') {
                        wrapper.style.display = 'flex';
                        const textarea = document.getElementById(`replyContent-${id}`);
                        if (textarea) {
                            textarea.focus();
                            setupAutoGrow(textarea);
                        }
                    } else {
                        wrapper.style.display = 'none';
                    }
                };

                // Toggle Replies list visibility (collapse/expand)
                window.toggleRepliesCollapse = function(id, btn) {
                    const listWrapper = document.getElementById(`repliesListWrapper-${id}`);
                    if (!listWrapper) return;
                    
                    btn.classList.toggle('active');
                    if (listWrapper.style.display === 'none') {
                        listWrapper.style.display = 'flex';
                    } else {
                        listWrapper.style.display = 'none';
                    }
                };

                // Submit inline reply
                window.submitReply = function(e, parentId, lessonId) {
                    e.preventDefault();
                    const textarea = document.getElementById(`replyContent-${parentId}`);
                    const content = textarea.value.trim();

                    if (!content) return;

                    fetch(`/lessons/${lessonId}/discussions`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ content: content, parent_id: parentId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Clear and hide reply form
                            textarea.value = '';
                            textarea.style.height = 'auto';
                            document.getElementById(`replyFormWrapper-${parentId}`).style.display = 'none';

                            const repliesContainer = document.getElementById(`repliesContainer-${parentId}`);
                            let listWrapper = document.getElementById(`repliesListWrapper-${parentId}`);
                            
                            // If replies wrapper doesn't exist or is empty, build it
                            if (!listWrapper) {
                                // First add toggle button
                                const toggleHtml = `<div class="replies-toggle-btn active" onclick="toggleRepliesCollapse(${parentId}, this)"><i class='bx bx-chevron-down toggle-icon'></i> Lihat 1 Balasan</div>`;
                                listWrapper = document.createElement('div');
                                listWrapper.className = 'replies-list-wrapper';
                                listWrapper.id = `repliesListWrapper-${parentId}`;
                                listWrapper.style.display = 'flex';
                                repliesContainer.appendChild(listWrapper);
                                repliesContainer.insertAdjacentHTML('afterbegin', toggleHtml);
                            } else {
                                // If list wrapper is hidden, expand it
                                const toggleBtn = repliesContainer.querySelector('.replies-toggle-btn');
                                if (toggleBtn) {
                                    toggleBtn.classList.add('active');
                                    // Update count in toggle button
                                    const replyCards = listWrapper.querySelectorAll('.reply-card');
                                    const newCount = replyCards.length + 1;
                                    toggleBtn.innerHTML = `<i class='bx bx-chevron-down toggle-icon'></i> Lihat ${newCount} Balasan`;
                                }
                                listWrapper.style.display = 'flex';
                            }

                            // Append new reply HTML
                            const replyHtml = createReplyCardHtml(data.discussion);
                            listWrapper.insertAdjacentHTML('beforeend', replyHtml);
                        }
                    });
                };

                // Toggle Like / Upvote AJAX
                window.toggleDiscussionLike = function(id, btn) {
                    fetch(`/discussions/${id}/like`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const countSpan = btn.querySelector('.like-count');
                            if (countSpan) {
                                countSpan.textContent = data.likes_count;
                            }
                            if (data.liked) {
                                btn.classList.add('active');
                            } else {
                                btn.classList.remove('active');
                            }
                        }
                    });
                };

                // HTML generators for AJAX updates
                function createDiscussionNodeHtml(disc) {
                    return `
                    <div class="discussion-node" data-id="${disc.id}">
                        <div class="discussion-card">
                            <img class="forum-user-avatar" src="https://ui-avatars.com/api/?name=${disc.user_initials}&background=121212&color=f5f4f0&bold=true" alt="${disc.user_name}">
                            <div class="discussion-card-body">
                                <div class="discussion-card-meta">
                                    <span class="user-name">${disc.user_name}</span>
                                    <span class="meta-separator">&bull;</span>
                                    <span class="timestamp">${disc.created_at_human}</span>
                                </div>
                                <div class="discussion-text-content">
                                    ${disc.content}
                                </div>
                                <div class="discussion-actions">
                                    <button class="action-btn-like" onclick="toggleDiscussionLike(${disc.id}, this)">
                                        <i class='bx bxs-heart heart-icon'></i>
                                        <span class="like-count">0</span>
                                    </button>
                                    <button class="action-btn-reply" onclick="toggleReplyForm(${disc.id})">
                                        <i class='bx bx-comment-detail'></i> Balas
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Reply Form (Hidden initially) -->
                        <div class="reply-form-wrapper" id="replyFormWrapper-${disc.id}" style="display: none;">
                            <img class="forum-user-avatar-sm" src="https://ui-avatars.com/api/?name=${encodeURIComponent(currentUser.name)}&background=e2583e&color=fff&bold=true" alt="${currentUser.name}">
                            <form onsubmit="submitReply(event, ${disc.id}, ${lessonId})" class="reply-form">
                                <div class="textarea-container-sm">
                                    <textarea id="replyContent-${disc.id}" placeholder="Balas tanggapan..." rows="1" required></textarea>
                                    <div class="textarea-focus-glow"></div>
                                </div>
                                <div class="form-actions-sm">
                                    <button type="button" class="btn-forum-cancel-sm" onclick="toggleReplyForm(${disc.id})">Batal</button>
                                    <button type="submit" class="btn-forum-submit-sm">Kirim</button>
                                </div>
                            </form>
                        </div>

                        <!-- Child Replies List -->
                        <div class="replies-container" id="repliesContainer-${disc.id}">
                            <div class="replies-list-wrapper" id="repliesListWrapper-${disc.id}" style="display: none;"></div>
                        </div>
                    </div>
                    `;
                }

                function createReplyCardHtml(reply) {
                    return `
                    <div class="reply-card" data-id="${reply.id}">
                        <img class="forum-user-avatar-sm" src="https://ui-avatars.com/api/?name=${reply.user_initials}&background=555555&color=fff&bold=true" alt="${reply.user_name}">
                        <div class="discussion-card-body">
                            <div class="discussion-card-meta">
                                <span class="user-name-sm">${reply.user_name}</span>
                                <span class="meta-separator">&bull;</span>
                                <span class="timestamp-sm">${reply.created_at_human}</span>
                            </div>
                            <div class="discussion-text-content-sm">
                                ${reply.content}
                            </div>
                            <div class="discussion-actions-sm">
                                <button class="action-btn-like-sm" onclick="toggleDiscussionLike(${reply.id}, this)">
                                    <i class='bx bxs-heart heart-icon'></i>
                                    <span class="like-count">0</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    `;
                }
            })();

        });
    </script>
</body>
</html>


