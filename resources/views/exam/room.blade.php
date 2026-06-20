<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ruang Ujian Akhir - TurnCode</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fira+Code:wght@400;500&display=swap"
        rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        :root {
            --bg-dark: #0f0c13;
            --bg-card: #151317;
            --bg-card-hover: #1e1b24;
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
            --accent-primary: #7c6af7;
            --accent-hover: #6352ce;
            --danger: #ef4444;
            --success: #10b981;
            --gold: #D4AF37;
            --border-color: rgba(255, 255, 255, 0.08);
        }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--bg-dark);
            color: var(--text-main);
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Top Navbar */
        .exam-navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 70px;
            background: rgba(21, 19, 23, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
            z-index: 100;
        }

        .exam-brand {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .exam-brand-logo {
            font-weight: 800;
            font-size: 1.25rem;
            background: linear-gradient(to right, #ffffff, var(--accent-primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .exam-title {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 600;
            padding-left: 1rem;
            border-left: 1px solid var(--border-color);
        }

        .exam-timer {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
            padding: 0.5rem 1.5rem;
            border-radius: 20px;
            font-family: 'Fira Code', monospace;
            font-weight: 700;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.2);
        }

        .btn-finish {
            background: var(--accent-primary);
            color: white;
            border: none;
            padding: 0.6rem 1.5rem;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-finish:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
        }

        /* Sidebar Nav */
        .exam-sidebar {
            width: 280px;
            background: var(--bg-card);
            border-right: 1px solid var(--border-color);
            padding: 90px 1.5rem 1.5rem 1.5rem;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .sidebar-title {
            font-size: 0.9rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .question-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 0.5rem;
        }

        .q-node {
            aspect-ratio: 1;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            border: 1px solid var(--border-color);
            background: rgba(255, 255, 255, 0.02);
            transition: all 0.2s;
        }

        .q-node:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .q-node.active {
            border-color: var(--accent-primary);
            background: rgba(124, 106, 247, 0.15);
            color: #b9affc;
        }

        .q-node.answered {
            background: var(--accent-primary);
            color: white;
            border-color: var(--accent-primary);
        }

        /* Main Content */
        .exam-main {
            flex: 1;
            padding: 100px 3rem 3rem 3rem;
            overflow-y: auto;
            position: relative;
            background-image: radial-gradient(circle at top right, rgba(124, 106, 247, 0.05), transparent 40%);
        }

        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .question-number {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--gold);
        }

        .question-points {
            background: rgba(255, 255, 255, 0.05);
            padding: 0.3rem 0.8rem;
            border-radius: 6px;
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        .question-text {
            font-size: 1.15rem;
            line-height: 1.7;
            margin-bottom: 2.5rem;
        }

        /* Options */
        .options-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .option-item {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .option-item:hover {
            background: var(--bg-card-hover);
            border-color: rgba(255, 255, 255, 0.15);
            transform: translateX(5px);
        }

        .option-label {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: var(--text-muted);
            flex-shrink: 0;
            border: 1px solid var(--border-color);
        }

        .option-text {
            font-size: 1rem;
            line-height: 1.5;
        }

        /* Fake selected state */
        .option-item.selected {
            border-color: var(--accent-primary);
            background: rgba(124, 106, 247, 0.05);
        }

        .option-item.selected .option-label {
            background: var(--accent-primary);
            color: white;
            border-color: var(--accent-primary);
        }

        .exam-controls {
            margin-top: 4rem;
            display: flex;
            justify-content: space-between;
            padding-top: 2rem;
            border-top: 1px solid var(--border-color);
        }

        .btn-nav {
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid var(--border-color);
            background: transparent;
            color: var(--text-main);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
        }

        .btn-nav:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .btn-nav:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }

        /* Enforce linear progression node states */
        .q-node {
            cursor: not-allowed !important;
            pointer-events: none !important;
        }

        /* 15-Second reading countdown card style */
        .reading-countdown-card {
            background: rgba(124, 106, 247, 0.05);
            border: 1px solid rgba(124, 106, 247, 0.25);
            padding: 2.5rem 2rem;
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin: 2rem 0;
            animation: pulseBorder 1.5s infinite alternate;
            gap: 1rem;
        }

        @keyframes pulseBorder {
            from {
                border-color: rgba(124, 106, 247, 0.25);
                box-shadow: 0 0 10px rgba(124, 106, 247, 0.05);
            }

            to {
                border-color: rgba(124, 106, 247, 0.5);
                box-shadow: 0 0 20px rgba(124, 106, 247, 0.15);
            }
        }

        .reading-countdown-display {
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--accent-primary);
            font-family: 'Fira Code', monospace;
            line-height: 1;
        }

        .reading-countdown-text {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-main);
        }

        /* Puzzle question styles */
        .quiz-puzzle-container {
            margin-top: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .quiz-puzzle-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            cursor: grab;
            user-select: none;
            transition: border-color 0.2s, background-color 0.2s;
        }

        .quiz-puzzle-item:hover {
            border-color: rgba(255, 255, 255, 0.15);
            background: var(--bg-card-hover);
        }

        .quiz-puzzle-item.over {
            border: 1px dashed var(--accent-primary) !important;
            background: rgba(124, 106, 247, 0.08) !important;
        }

        .puzzle-arrow-btn {
            background: transparent;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            padding: 2px;
            opacity: 0.4;
            transition: opacity 0.2s, color 0.2s;
        }

        .quiz-puzzle-item:hover .puzzle-arrow-btn {
            opacity: 0.8;
        }

        .puzzle-arrow-btn:hover {
            opacity: 1 !important;
            color: var(--accent-primary) !important;
        }

        /* Code writing styles */
        .quiz-code-writing-container {
            margin-top: 1.5rem;
        }

        .code-writing-input {
            width: 100%;
            height: 220px;
            padding: 1.25rem;
            font-family: 'Fira Code', monospace;
            font-size: 14px;
            color: #f3f4f6;
            background: #151317;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            resize: vertical;
            line-height: 1.5;
            outline: none;
            transition: border-color 0.2s;
        }

        .code-writing-input:focus {
            border-color: var(--accent-primary);
            background: #1b191e;
        }

        /* ═══════════════════════════════════════════════════ */
        /* CUSTOM CONFIRM POPUP (Replacement for JS confirm) */
        /* ═══════════════════════════════════════════════════ */
        .tc-confirm-overlay {
            position: fixed;
            inset: 0;
            z-index: 99999;
            background: rgba(0, 0, 0, 0.65);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .tc-confirm-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .tc-confirm-box {
            background: #110f14;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 28px;
            padding: 3rem 2.5rem;
            width: 92%;
            max-width: 440px;
            text-align: left;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 1.5rem;
            transform: scale(0.8) translateY(20px);
            opacity: 0;
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1),
                opacity 0.25s ease;
            box-shadow: 0 32px 80px rgba(0, 0, 0, 0.6);
        }

        .tc-confirm-overlay.active .tc-confirm-box {
            transform: scale(1) translateY(0);
            opacity: 1;
        }

        .tc-confirm-slide {
            display: none;
            width: 100%;
            flex-direction: column;
            align-items: flex-start;
            gap: 1.5rem;
        }

        .tc-confirm-slide.active {
            display: flex;
            animation: tcFadeIn 0.25s ease-out;
        }

        @keyframes tcFadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .tc-confirm-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: -0.02em;
            line-height: 1.2;
            margin: 0;
        }

        .tc-confirm-desc {
            font-size: 1.15rem;
            color: #ffffff;
            font-weight: 500;
            line-height: 1.5;
            margin: 0;
            width: 100%;
        }

        .tc-confirm-subdesc {
            font-size: 0.8rem;
            color: #6b6570;
            margin-top: -0.8rem;
            width: 100%;
        }

        /* Slide track styling */
        .tc-slide-container {
            width: 100%;
            height: 56px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 50px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 24px;
            overflow: hidden;
            margin-top: 0.5rem;
            user-select: none;
            -webkit-user-select: none;
            transition: border-color 0.25s, background-color 0.25s;
            box-sizing: border-box;
        }

        /* Drag Left State (Tidak) */
        .tc-slide-container.drag-left {
            border-color: rgba(239, 68, 68, 0.3);
            background: rgba(239, 68, 68, 0.05);
        }

        .tc-slide-container.drag-left .tc-slide-handle {
            background: #ef4444 !important;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        .tc-slide-container.drag-left .tc-slide-text-left {
            color: #ef4444 !important;
            opacity: 1 !important;
            transform: scale(1.1);
            transition: all 0.2s;
        }

        /* Drag Right State (Iya) */
        .tc-slide-container.drag-right {
            border-color: rgba(16, 185, 129, 0.3);
            background: rgba(16, 185, 129, 0.05);
        }

        .tc-slide-container.drag-right .tc-slide-handle {
            background: #10b981 !important;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
        }

        .tc-slide-container.drag-right .tc-slide-text-right {
            color: #10b981 !important;
            opacity: 1 !important;
            transform: scale(1.1);
            transition: all 0.2s;
        }

        .tc-slide-text {
            font-size: 0.95rem;
            font-weight: 700;
            color: #a1a1aa;
            pointer-events: none;
            z-index: 1;
            transition: color 0.2s, opacity 0.2s, transform 0.2s;
        }

        .tc-slide-text-left {
            color: #ef4444;
            opacity: 0.8;
        }

        .tc-slide-text-right {
            color: #10b981;
            opacity: 0.8;
        }

        .tc-slide-hint {
            position: absolute;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.25);
            pointer-events: none;
            z-index: 1;
            font-weight: 600;
            transition: opacity 0.2s, color 0.2s;
        }

        .tc-slide-container.drag-left .tc-slide-hint {
            color: #ef4444 !important;
        }

        .tc-slide-container.drag-right .tc-slide-hint {
            color: #10b981 !important;
        }

        .tc-slide-container.drag-left .tc-slide-text-right {
            opacity: 0.15 !important;
        }

        .tc-slide-container.drag-right .tc-slide-text-left {
            opacity: 0.15 !important;
        }

        .tc-slide-handle {
            position: absolute;
            left: 4px;
            top: 4px;
            height: 46px; /* 56px container height - 8px padding/borders - 2px safety */
            width: 90px;
            background: #756885;
            border-radius: 50px;
            cursor: grab;
            z-index: 2;
            box-shadow: 0 4px 12px rgba(117, 104, 133, 0.3);
            transition: left 0.1s ease, background-color 0.25s, box-shadow 0.25s;
            box-sizing: border-box;
        }

        .tc-slide-handle:active {
            cursor: grabbing;
        }

        .tc-confirm-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 2rem;
            margin-top: 0.5rem;
            width: 100%;
        }

        .tc-confirm-btn {
            flex: 1;
            padding: 0.9rem 1.5rem;
            border-radius: 50px;
            /* Pill shaped */
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #756885;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(117, 104, 133, 0.15);
        }

        .tc-confirm-btn:hover {
            background: #877899;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(117, 104, 133, 0.3);
        }

        .tc-confirm-btn-cancel {
            background: #27252b;
            color: #ffffff;
        }

        .tc-confirm-btn-confirm {
            background: var(--accent-primary);
            color: #ffffff;
        }

        .tc-confirm-btn-confirm:hover {
            background: var(--accent-hover);
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <div class="exam-navbar">
        <div class="exam-brand">
            <div class="exam-brand-logo">TurnCode</div>
            <div class="exam-title">Ujian Akhir: {{ $userCourse->title ?? 'Web Development' }}</div>
        </div>

        <div class="exam-timer" id="timer">
            <i class='bx bx-time-five'></i> 00:00:00
        </div>

        <button class="btn-finish" onclick="finishExam()" style="display: none;">Selesai & Kumpulkan</button>
    </div>

    <!-- Sidebar -->
    <div class="exam-sidebar">
        <div class="sidebar-title">Navigasi Soal</div>
        <div class="question-grid" id="question-grid">
            <!-- Populated dynamically -->
        </div>

        <div style="margin-top: 2rem;">
            <div class="sidebar-title">Legenda</div>
            <div
                style="display: flex; flex-direction: column; gap: 0.8rem; font-size: 0.85rem; color: var(--text-muted);">
                <div style="display: flex; align-items: center; gap: 0.8rem;">
                    <div style="width: 16px; height: 16px; background: var(--accent-primary); border-radius: 4px;">
                    </div> Dijawab
                </div>
                <div style="display: flex; align-items: center; gap: 0.8rem;">
                    <div
                        style="width: 16px; height: 16px; background: rgba(124, 106, 247, 0.15); border: 1px solid var(--accent-primary); border-radius: 4px;">
                    </div> Saat ini
                </div>
                <div style="display: flex; align-items: center; gap: 0.8rem;">
                    <div
                        style="width: 16px; height: 16px; background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); border-radius: 4px;">
                    </div> Belum
                </div>
            </div>
        </div>
    </div>

    <!-- Main Question Area -->
    <div class="exam-main">
        <div style="max-width: 800px; margin: 0 auto;" id="quiz-container">
            <!-- Populated dynamically -->
        </div>
    </div>

    <script>
        // Dynamic Question Data
        const quizzes = @json($quizzes);

        // Load saved answers from localStorage
        const savedAnswers = localStorage.getItem('exam_answers_{{ $userCourse->id }}');
        const answers = savedAnswers ? JSON.parse(savedAnswers) : {}; // quiz_id => selected_answer

        // Load saved question index from localStorage
        let currentQuestionIndex = 0;
        const savedQuestionIndex = localStorage.getItem('exam_current_question_{{ $userCourse->id }}');
        if (savedQuestionIndex) {
            const parsedIndex = parseInt(savedQuestionIndex, 10);
            if (parsedIndex >= 0 && parsedIndex < quizzes.length) {
                currentQuestionIndex = parsedIndex;
            }
        }

        // Track which questions have completed their 15-second reading timer
        const savedReadingCompleted = localStorage.getItem('exam_reading_completed_{{ $userCourse->id }}');
        const readingCompleted = savedReadingCompleted ? JSON.parse(savedReadingCompleted) : {};

        let readingTimer = null;
        let readingSecondsLeft = 15;

        // --- Timer persistence ---
        // Store the exam start timestamp so remaining time survives refresh
        const TIMER_KEY = 'exam_timer_start_{{ $userCourse->id }}';
        const EXAM_DURATION = 30 * 60; // 30 minutes in seconds
        let examStartTime = parseInt(localStorage.getItem(TIMER_KEY), 10);
        if (!examStartTime || isNaN(examStartTime)) {
            examStartTime = Math.floor(Date.now() / 1000);
            localStorage.setItem(TIMER_KEY, examStartTime);
        }

        // --- LocalStorage save helpers ---
        function saveAnswers() {
            localStorage.setItem('exam_answers_{{ $userCourse->id }}', JSON.stringify(answers));
        }
        function saveQuestionIndex() {
            localStorage.setItem('exam_current_question_{{ $userCourse->id }}', currentQuestionIndex);
        }
        function saveReadingCompleted() {
            localStorage.setItem('exam_reading_completed_{{ $userCourse->id }}', JSON.stringify(readingCompleted));
        }
        function clearExamStorage() {
            localStorage.removeItem('exam_answers_{{ $userCourse->id }}');
            localStorage.removeItem('exam_current_question_{{ $userCourse->id }}');
            localStorage.removeItem('exam_reading_completed_{{ $userCourse->id }}');
            localStorage.removeItem(TIMER_KEY);
        }

        function escapeHtml(text) {
            if (typeof text !== 'string') return text;
            return text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // Render question nodes in sidebar
        function renderSidebar() {
            const grid = document.getElementById('question-grid');
            grid.innerHTML = '';
            quizzes.forEach((quiz, index) => {
                const node = document.createElement('div');
                node.className = 'q-node';
                node.id = 'panelNum-' + index;
                node.textContent = index + 1;

                if (index === currentQuestionIndex) {
                    node.classList.add('active');
                } else if (answers[quiz.id]) {
                    node.classList.add('answered');
                }

                // Clicks are disabled in sidebar to enforce linear progression
                // No click event listener is added
                grid.appendChild(node);
            });
        }

        // Render current question details
        function renderQuestion() {
            if (quizzes.length === 0) {
                document.getElementById('quiz-container').innerHTML = `
                    <div style="text-align: center; color: var(--text-muted); padding: 3rem;">
                        <i class='bx bx-info-circle' style="font-size: 3rem; color: var(--danger); margin-bottom: 1rem;"></i>
                        <p>Tidak ada soal kuis yang terpasang pada modul ini.</p>
                    </div>`;
                return;
            }

            const quiz = quizzes[currentQuestionIndex];
            const container = document.getElementById('quiz-container');

            // Clear any previous reading timer
            if (readingTimer) {
                clearInterval(readingTimer);
                readingTimer = null;
            }

            // Build media html
            let mediaHtml = '';
            if (quiz.image_url) {
                mediaHtml += `
                    <div class="quiz-media-container" style="margin-bottom: 20px; display: flex; justify-content: center;">
                        <img src="${quiz.image_url}" alt="Media Soal" style="max-width: 100%; max-height: 280px; border-radius: 12px; border: 1px solid var(--border-color); object-fit: contain;">
                    </div>`;
            }
            if (quiz.video_url) {
                mediaHtml += `
                    <div class="quiz-media-container" style="margin-bottom: 20px; display: flex; justify-content: center; width: 100%;">
                        <video src="${quiz.video_url}" controls style="max-width: 100%; max-height: 280px; border-radius: 12px; border: 1px solid var(--border-color);"></video>
                    </div>`;
            }
            if (quiz.code_block) {
                mediaHtml += `
                    <div class="quiz-code-block-container" style="margin-bottom: 20px; border-radius: 12px; overflow: hidden; border: 1px solid var(--border-color); background: #1e1e24; padding: 16px;">
                        <pre style="margin: 0; font-family: 'Fira Code', 'Courier New', Courier, monospace; font-size: 13px; color: #f8f8f2; overflow-x: auto; white-space: pre-wrap; word-break: break-all;"><code>${escapeHtml(quiz.code_block)}</code></pre>
                    </div>`;
            }

            // Build Answers area based on type
            let answersHtml = '';
            const quizType = quiz.type || 'text';

            if (quizType === 'puzzle') {
                let options = [];
                if (Array.isArray(quiz.options)) {
                    options = quiz.options;
                } else {
                    try {
                        options = JSON.parse(quiz.options) || [];
                    } catch (e) {
                        options = [];
                    }
                }

                // If they haven't reordered yet, initialize default scrambled order
                if (!answers[quiz.id]) {
                    answers[quiz.id] = [...options];
                }

                const currentOrder = answers[quiz.id];
                let puzzleLines = '';
                currentOrder.forEach((opt, idx) => {
                    puzzleLines += `
                        <div class="quiz-puzzle-item" draggable="true" data-value="${opt.replace(/"/g, '&quot;')}" ondragstart="handleDragStart(event)" ondragover="handleDragOver(event)" ondragenter="handleDragEnter(event)" ondragleave="handleDragLeave(event)" ondrop="handleDrop(event)" ondragend="handleDragEnd(event)">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <i class='bx bx-menu' style="color: var(--text-muted); font-size: 18px; cursor: grab;"></i>
                                <code style="font-family: 'Fira Code', monospace; font-size: 13px; color: #ffffff;">${escapeHtml(opt)}</code>
                            </div>
                            <div class="puzzle-item-controls" style="display: flex; flex-direction: column; gap: 4px;">
                                <button type="button" class="puzzle-arrow-btn arrow-up" onclick="movePuzzleItem(this, -1)">
                                    <i class='bx bx-chevron-up' style="font-size: 18px;"></i>
                                </button>
                                <button type="button" class="puzzle-arrow-btn arrow-down" onclick="movePuzzleItem(this, 1)">
                                    <i class='bx bx-chevron-down' style="font-size: 18px;"></i>
                                </button>
                            </div>
                        </div>`;
                });

                answersHtml = `<div class="quiz-puzzle-container" id="puzzle-container-${quiz.id}" data-quiz-id="${quiz.id}">${puzzleLines}</div>`;

            } else if (quizType === 'code_writing') {
                const currentVal = answers[quiz.id] || '';
                answersHtml = `
                    <div class="quiz-code-writing-container">
                        <textarea class="code-writing-input" 
                                  placeholder="Ketik kode jawabanmu di sini..." 
                                  oninput="onCodeWritingInput(this, '${quiz.id}')">${escapeHtml(currentVal)}</textarea>
                    </div>`;

            } else {
                // MCQ - text / code
                let options = [];
                if (Array.isArray(quiz.options)) {
                    options = quiz.options;
                } else {
                    try {
                        options = JSON.parse(quiz.options) || [];
                    } catch (e) {
                        options = [];
                    }
                }

                const labels = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
                options.forEach((opt, idx) => {
                    const letter = labels[idx] || '';
                    const isSelected = answers[quiz.id] === opt;
                    const fontStyle = quizType === 'code' ? 'font-family: monospace; font-size: 13px;' : '';
                    answersHtml += `
                        <div class="option-item ${isSelected ? 'selected' : ''}" onclick="selectOption('${opt.replace(/'/g, "\\'")}')">
                            <div class="option-label">${letter}</div>
                            <div class="option-text" style="${fontStyle}">${escapeHtml(opt)}</div>
                        </div>`;
                });

                answersHtml = `<div class="options-list">${answersHtml}</div>`;
            }

            // Define Question Title/Type
            let typeLabel = 'Pilihan Ganda';
            if (quizType === 'puzzle') {
                typeLabel = 'Susun Puzzle Kode';
            } else if (quizType === 'code_writing') {
                typeLabel = 'Tulis Code Sendiri';
            }

            // Render container content
            container.innerHTML = `
                <div class="question-header">
                    <div class="question-number">Soal ${currentQuestionIndex + 1}</div>
                    <div class="question-points">${typeLabel}</div>
                </div>

                <div class="question-text">
                    ${quiz.question}
                </div>

                ${mediaHtml}

                <!-- Countdown reading phase card -->
                <div class="reading-countdown-card" id="reading-countdown">
                    <div class="reading-countdown-display" id="reading-seconds">15</div>
                    <div class="reading-countdown-text">Silakan baca soal terlebih dahulu sebelum menjawab...</div>
                </div>

                <!-- Answer options container -->
                <div id="answers-container" style="display: none;">
                    ${answersHtml}
                </div>

                <!-- Navigation Controls -->
                <div class="exam-controls" id="exam-controls" style="display: none;">
                    <div></div> <!-- Empty spacer, back button is removed -->
                    ${currentQuestionIndex === quizzes.length - 1 ?
                    `<button class="btn-finish" style="padding: 0.8rem 2rem; border-radius: 8px;" onclick="finishExam()">Selesai & Kumpulkan <i class='bx bx-check-shield'></i></button>` :
                    `<button class="btn-nav" id="btn-next" onclick="nextQuestion()">Soal Selanjutnya <i class='bx bx-right-arrow-alt'></i></button>`
                }
                </div>`;

            renderSidebar();

            const readingCard = document.getElementById('reading-countdown');
            const secondsText = document.getElementById('reading-seconds');
            const answersArea = document.getElementById('answers-container');
            const controlsArea = document.getElementById('exam-controls');

            // If reading is already completed for this question, reveal answers immediately
            if (readingCompleted[currentQuestionIndex]) {
                if (readingCard) readingCard.style.display = 'none';
                if (answersArea) answersArea.style.display = 'block';
                if (controlsArea) controlsArea.style.display = 'flex';
            } else {
                // Otherwise start the 15-second timer
                readingSecondsLeft = 15;
                if (secondsText) secondsText.textContent = readingSecondsLeft;

                readingTimer = setInterval(() => {
                    readingSecondsLeft--;
                    if (secondsText) {
                        secondsText.textContent = readingSecondsLeft;
                    }

                    if (readingSecondsLeft <= 0) {
                        clearInterval(readingTimer);
                        readingTimer = null;
                        readingCompleted[currentQuestionIndex] = true;
                        saveReadingCompleted();

                        // Transition to answering phase
                        if (readingCard) readingCard.style.display = 'none';
                        if (answersArea) answersArea.style.display = 'block';
                        if (controlsArea) controlsArea.style.display = 'flex';
                    }
                }, 1000);
            }
        }

        function goToQuestion(index) {
            currentQuestionIndex = index;
            saveQuestionIndex();
            renderQuestion();
        }

        function nextQuestion() {
            if (currentQuestionIndex < quizzes.length - 1) {
                currentQuestionIndex++;
                saveQuestionIndex();
                renderQuestion();
            }
        }

        function selectOption(optValue) {
            const quiz = quizzes[currentQuestionIndex];
            answers[quiz.id] = optValue;
            saveAnswers();
            renderQuestion();
        }

        function onCodeWritingInput(textarea, quizId) {
            answers[quizId] = textarea.value;
            saveAnswers();
            // Mark answered in sidebar panel
            const panelBtn = document.getElementById('panelNum-' + currentQuestionIndex);
            if (panelBtn) {
                if (textarea.value.trim().length > 0) {
                    panelBtn.classList.add('answered');
                } else {
                    panelBtn.classList.remove('answered');
                }
            }
        }

        // HTML5 drag and drop logic for puzzle questions
        let dragSourceEl = null;

        function handleDragStart(e) {
            e.target.style.opacity = '0.4';
            dragSourceEl = e.target;
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', e.target.dataset.value);
        }

        function handleDragOver(e) {
            if (e.preventDefault) {
                e.preventDefault();
            }
            e.dataTransfer.dropEffect = 'move';
            return false;
        }

        function handleDragEnter(e) {
            const item = e.target.closest('.quiz-puzzle-item');
            if (item) item.classList.add('over');
        }

        function handleDragLeave(e) {
            const item = e.target.closest('.quiz-puzzle-item');
            if (item) item.classList.remove('over');
        }

        function handleDrop(e) {
            e.stopPropagation();
            e.preventDefault();
            const item = e.target.closest('.quiz-puzzle-item');
            if (item && dragSourceEl && dragSourceEl !== item) {
                item.classList.remove('over');
                const container = item.closest('.quiz-puzzle-container');
                const children = Array.from(container.children);
                const sourceIdx = children.indexOf(dragSourceEl);
                const destIdx = children.indexOf(item);
                if (sourceIdx < destIdx) {
                    container.insertBefore(dragSourceEl, item.nextSibling);
                } else {
                    container.insertBefore(dragSourceEl, item);
                }
                updatePuzzleAnswer(container);
            }
            return false;
        }

        function handleDragEnd(e) {
            e.target.style.opacity = '1';
            document.querySelectorAll('.quiz-puzzle-item').forEach(item => {
                item.style.opacity = '1';
                item.classList.remove('over');
            });
        }

        function movePuzzleItem(btn, direction) {
            const item = btn.closest('.quiz-puzzle-item');
            const container = item.closest('.quiz-puzzle-container');
            if (!item || !container) return;

            if (direction === -1) {
                const prev = item.previousElementSibling;
                if (prev) {
                    container.insertBefore(item, prev);
                }
            } else if (direction === 1) {
                const next = item.nextElementSibling;
                if (next) {
                    container.insertBefore(item, next.nextSibling);
                }
            }

            updatePuzzleAnswer(container);
        }

        function updatePuzzleAnswer(container) {
            if (!container) return;
            const quizId = container.dataset.quizId;
            const items = Array.from(container.querySelectorAll('.quiz-puzzle-item'));
            const orderedValues = items.map(item => item.dataset.value);
            answers[quizId] = orderedValues;
            saveAnswers();

            const panelBtn = document.getElementById('panelNum-' + currentQuestionIndex);
            if (panelBtn) {
                panelBtn.classList.add('answered');
            }
        }

        // Timer setup – uses persisted start timestamp so remaining time survives refresh
        function getTimeLeft() {
            const elapsed = Math.floor(Date.now() / 1000) - examStartTime;
            return Math.max(0, EXAM_DURATION - elapsed);
        }

        let timeLeft = getTimeLeft();
        // Render initial timer value immediately
        {
            const m = Math.floor(timeLeft / 60).toString().padStart(2, '0');
            const s = (timeLeft % 60).toString().padStart(2, '0');
            document.getElementById('timer').innerHTML = `<i class='bx bx-time-five'></i> ${m}:${s}`;
        }

        const timerInterval = setInterval(() => {
            timeLeft = getTimeLeft();
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                document.getElementById('timer').innerHTML = `<i class='bx bx-time-five'></i> 00:00:00`;
                autoSubmitExam();
            } else {
                const m = Math.floor(timeLeft / 60).toString().padStart(2, '0');
                const s = (timeLeft % 60).toString().padStart(2, '0');
                document.getElementById('timer').innerHTML = `<i class='bx bx-time-five'></i> ${m}:${s}`;
            }
        }, 1000);

        // Custom Confirm Popup Logic (Two-Step Confirm)
        function showCustomConfirm(options = {}) {
            const title = options.title || 'Persetujuan';
            const message = options.message || 'yakin ingin mengakhiri ujian ini';
            const subdesc = options.subdesc || 'Ujian-akhir-fokus=Front-End';
            const confirmText = options.confirmText || 'Akhiri Ujian';
            const cancelText = options.cancelText || 'Batal';

            return new Promise((resolve) => {
                const modal = document.getElementById('tcConfirmModal');

                // Slide 1 elements
                const titleEl1 = document.getElementById('tcConfirmTitle1');
                const descEl1 = document.getElementById('tcConfirmDesc1');
                const subdescEl1 = document.getElementById('tcConfirmSubdesc1');
                const slide1 = document.getElementById('tcConfirmSlide1');
                const container = document.getElementById('tcSlideContainer');
                const handle = document.getElementById('tcSlideHandle');

                // Slide 2 elements
                const titleEl2 = document.getElementById('tcConfirmTitle2');
                const descEl2 = document.getElementById('tcConfirmDesc2');
                const subdescEl2 = document.getElementById('tcConfirmSubdesc2');
                const slide2 = document.getElementById('tcConfirmSlide2');
                const confirmBtn = document.getElementById('tcConfirmConfirmBtn');
                const cancelBtn = document.getElementById('tcConfirmCancelBtn');

                // Populate labels
                titleEl1.textContent = title;
                descEl1.textContent = message;
                if (subdescEl1) subdescEl1.textContent = subdesc;

                titleEl2.textContent = title;
                descEl2.textContent = message;
                if (subdescEl2) subdescEl2.textContent = subdesc;
                confirmBtn.textContent = confirmText;
                cancelBtn.textContent = cancelText;

                // Reset slide state
                slide1.classList.add('active');
                slide2.classList.remove('active');
                container.classList.remove('drag-left', 'drag-right');
                const hintEl = document.getElementById('tcSlideHint');
                if (hintEl) {
                    hintEl.textContent = 'Geser ke Kiri/Kanan';
                }

                modal.classList.add('active');

                // Compute dimensions with fallback
                let containerWidth = container.clientWidth || 374;
                let handleWidth = handle.clientWidth || 90;
                let leftLimit = 4;
                let rightLimit = containerWidth - handleWidth - 4;
                let centerLeft = (containerWidth - handleWidth) / 2;

                // Set initial position to center
                handle.style.left = centerLeft + 'px';
                handle.style.transition = 'none';

                // Recalculate and center after layout settles
                setTimeout(() => {
                    containerWidth = container.clientWidth || 374;
                    handleWidth = handle.clientWidth || 90;
                    rightLimit = containerWidth - handleWidth - 4;
                    centerLeft = (containerWidth - handleWidth) / 2;
                    if (!isDragging) {
                        handle.style.left = centerLeft + 'px';
                    }
                }, 50);

                function updatePositions() {
                    containerWidth = container.clientWidth || 374;
                    handleWidth = handle.clientWidth || 90;
                    rightLimit = containerWidth - handleWidth - 4;
                    centerLeft = (containerWidth - handleWidth) / 2;

                    if (!isDragging) {
                        handle.style.transition = 'none';
                        handle.style.left = centerLeft + 'px';
                    }
                }
                window.addEventListener('resize', updatePositions);

                let isDragging = false;
                let startX = 0;
                let startLeft = centerLeft;

                function onDragStart(e) {
                    // Recalculate dimensions on drag start to ensure correct real-time values
                    containerWidth = container.clientWidth || 374;
                    handleWidth = handle.clientWidth || 90;
                    rightLimit = containerWidth - handleWidth - 4;
                    centerLeft = (containerWidth - handleWidth) / 2;

                    isDragging = true;
                    startX = e.type === 'touchstart' ? e.touches[0].clientX : e.clientX;
                    startLeft = parseFloat(handle.style.left) || centerLeft;
                    handle.style.transition = 'none';

                    document.addEventListener('mousemove', onDragMove);
                    document.addEventListener('mouseup', onDragEnd);
                    document.addEventListener('touchmove', onDragMove, { passive: false });
                    document.addEventListener('touchend', onDragEnd);
                }

                function onDragMove(e) {
                    if (!isDragging) return;
                    if (e.cancelable) e.preventDefault();
                    const currentX = e.type === 'touchmove' ? e.touches[0].clientX : e.clientX;
                    const deltaX = currentX - startX;
                    let newLeft = startLeft + deltaX;

                    if (newLeft < leftLimit) newLeft = leftLimit;
                    if (newLeft > rightLimit) newLeft = rightLimit;

                    handle.style.left = newLeft + 'px';

                    if (newLeft < centerLeft - 5) {
                        container.classList.add('drag-left');
                        container.classList.remove('drag-right');
                        if (hintEl) hintEl.textContent = 'Lepaskan untuk Batal';
                    } else if (newLeft > centerLeft + 5) {
                        container.classList.add('drag-right');
                        container.classList.remove('drag-left');
                        if (hintEl) hintEl.textContent = 'Lepaskan untuk Lanjut';
                    } else {
                        container.classList.remove('drag-left', 'drag-right');
                        if (hintEl) hintEl.textContent = 'Geser ke Kiri/Kanan';
                    }
                }

                function onDragEnd() {
                    if (!isDragging) return;
                    isDragging = false;

                    document.removeEventListener('mousemove', onDragMove);
                    document.removeEventListener('mouseup', onDragEnd);
                    document.removeEventListener('touchmove', onDragMove);
                    document.removeEventListener('touchend', onDragEnd);

                    const currentLeft = parseFloat(handle.style.left) || centerLeft;
                    const travelLeft = centerLeft - leftLimit;
                    const travelRight = rightLimit - centerLeft;

                    if (currentLeft <= leftLimit + 15 || currentLeft <= centerLeft - 0.8 * travelLeft) {
                        // Cancel
                        handle.style.transition = 'left 0.15s ease';
                        handle.style.left = leftLimit + 'px';
                        setTimeout(() => {
                            cleanup(false);
                        }, 150);
                    } else if (currentLeft >= rightLimit - 15 || currentLeft >= centerLeft + 0.8 * travelRight) {
                        // Confirm Slide 1
                        handle.style.transition = 'left 0.15s ease';
                        handle.style.left = rightLimit + 'px';
                        setTimeout(() => {
                            slide1.classList.remove('active');
                            slide2.classList.add('active');
                        }, 200);
                    } else {
                        // Bounce back
                        handle.style.transition = 'left 0.25s cubic-bezier(0.4, 0, 0.2, 1)';
                        handle.style.left = centerLeft + 'px';
                        setTimeout(() => {
                            container.classList.remove('drag-left', 'drag-right');
                            if (hintEl) hintEl.textContent = 'Geser ke Kiri/Kanan';
                        }, 250);
                    }
                }

                const cleanup = (value) => {
                    modal.classList.remove('active');
                    window.removeEventListener('resize', updatePositions);
                    handle.removeEventListener('mousedown', onDragStart);
                    handle.removeEventListener('touchstart', onDragStart);

                    confirmBtn.removeEventListener('click', onConfirmClick);
                    cancelBtn.removeEventListener('click', onCancelClick);
                    modal.removeEventListener('click', onOverlayClick);
                    resolve(value);
                };

                function onConfirmClick() {
                    cleanup(true);
                }

                function onCancelClick() {
                    cleanup(false);
                }

                function onOverlayClick(e) {
                    if (e.target === modal) {
                        cleanup(false);
                    }
                }

                handle.addEventListener('mousedown', onDragStart);
                handle.addEventListener('touchstart', onDragStart);
                confirmBtn.addEventListener('click', onConfirmClick);
                cancelBtn.addEventListener('click', onCancelClick);
                modal.addEventListener('click', onOverlayClick);
            });
        }

        async function finishExam() {
            const confirmed = await showCustomConfirm({
                title: "Persetujuan",
                message: "yakin ingin mengakhiri ujian ini",
                subdesc: "Ujian-akhir-fokus={{ $userCourse->title }}",
                confirmText: "Akhiri Ujian"
            });
            if (confirmed) {
                submitExam();
            }
        }

        function autoSubmitExam() {
            // Auto submit when time runs out
            alert('Waktu ujian Anda telah habis! Ujian akan dikumpulkan otomatis.');
            submitExam();
        }

        function submitExam() {
            clearInterval(timerInterval);
            clearExamStorage();

            // Fetch CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('{{ route("exam.submit") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    answers: answers
                })
            })
                .then(res => res.json())
                .then(data => {
                    showResultModal(data);
                })
                .catch(err => {
                    console.error(err);
                    alert('Terjadi kesalahan saat mengumpulkan ujian. Silakan coba lagi.');
                });
        }

        function showResultModal(data) {
            const modal = document.getElementById('tcResultModal');
            const icon = document.getElementById('result-icon');
            const title = document.getElementById('result-title');
            const desc = document.getElementById('result-desc');
            const score = document.getElementById('result-score');
            const correct = document.getElementById('result-correct');
            const btn = document.getElementById('btn-result-action');

            score.textContent = `${data.score}%`;
            correct.textContent = `${data.correct_count}/${data.total_questions}`;

            if (data.passed) {
                icon.textContent = '🏆';
                title.textContent = 'Selamat, Anda Lulus!';
                desc.textContent = 'Anda telah berhasil menguasai fokus ini dengan sangat baik. Sertifikat Fokus Anda sekarang telah tersedia.';
                btn.textContent = 'Kembali ke Dashboard';
                btn.className = 'tc-confirm-btn tc-confirm-btn-confirm';
                btn.onclick = () => {
                    window.location.href = '{{ route("dashboard") }}';
                };
            } else {
                icon.textContent = '❌';
                title.textContent = 'Belum Lulus';
                desc.textContent = 'Jangan menyerah! Nilai kelulusan minimal adalah 70%. Anda dapat mempelajari kembali materi dan mencoba lagi.';
                btn.textContent = 'Coba Lagi';
                btn.className = 'tc-confirm-btn tc-confirm-btn-cancel';
                btn.onclick = () => {
                    window.location.reload();
                };
            }

            modal.classList.add('active');
        }

        // Initialize quiz view
        renderQuestion();
    </script>

    <!-- CUSTOM CONFIRM MODAL -->
    <div id="tcConfirmModal" class="tc-confirm-overlay">
        <div class="tc-confirm-box">
            <!-- SLIDE 1 PANEL -->
            <div class="tc-confirm-slide active" id="tcConfirmSlide1">
                <div class="tc-confirm-title" id="tcConfirmTitle1">Persetujuan</div>
                <div class="tc-confirm-desc" id="tcConfirmDesc1">yakin ingin mengakhiri ujian ini</div>
                <div class="tc-confirm-subdesc" id="tcConfirmSubdesc1">Ujian-akhir-fokus={{ $userCourse->title }}</div>
                <div class="tc-slide-container" id="tcSlideContainer">
                    <span class="tc-slide-text tc-slide-text-left">Tidak</span>
                    <span class="tc-slide-hint" id="tcSlideHint">Geser ke Kiri/Kanan</span>
                    <div class="tc-slide-handle" id="tcSlideHandle"></div>
                    <span class="tc-slide-text tc-slide-text-right">Iya</span>
                </div>
            </div>

            <!-- SLIDE 2 PANEL -->
            <div class="tc-confirm-slide" id="tcConfirmSlide2">
                <div class="tc-confirm-title" id="tcConfirmTitle2">Persetujuan</div>
                <div class="tc-confirm-desc" id="tcConfirmDesc2">yakin ingin mengakhiri ujian ini</div>
                <div class="tc-confirm-subdesc" id="tcConfirmSubdesc2">Ujian-akhir-fokus={{ $userCourse->title }}</div>
                <div class="tc-confirm-actions">
                    <button type="button" class="tc-confirm-btn tc-confirm-btn-cancel"
                        id="tcConfirmCancelBtn">Batal</button>
                    <button type="button" class="tc-confirm-btn tc-confirm-btn-confirm" id="tcConfirmConfirmBtn">Akhiri
                        Ujian</button>
                </div>
            </div>
        </div>
    </div>

    <!-- RESULT MODAL -->
    <div id="tcResultModal" class="tc-confirm-overlay">
        <div class="tc-confirm-box"
            style="max-width: 500px; text-align: center; align-items: center; padding: 3.5rem 2.5rem;">
            <div id="result-icon" style="font-size: 4.5rem; margin-bottom: 1rem;"></div>
            <div class="tc-confirm-title" id="result-title"
                style="font-size: 1.8rem; margin-bottom: 0.5rem; width: 100%;"></div>
            <div class="tc-confirm-desc" id="result-desc"
                style="font-size: 1.15rem; color: var(--text-muted); margin-bottom: 1.5rem; width: 100%;"></div>

            <div
                style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 1.5rem; width: 100%; box-sizing: border-box; margin-bottom: 2rem; display: flex; justify-content: space-around;">
                <div>
                    <div
                        style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px;">
                        Nilai Anda</div>
                    <div id="result-score" style="font-size: 2.2rem; font-weight: 800; color: var(--gold);">0%</div>
                </div>
                <div style="width: 1px; background: rgba(255,255,255,0.1);"></div>
                <div>
                    <div
                        style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; margin-bottom: 5px;">
                        Jawaban Benar</div>
                    <div id="result-correct" style="font-size: 2.2rem; font-weight: 800; color: var(--success);">0/0
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; width: 100%;">
                <button type="button" class="tc-confirm-btn" id="btn-result-action"
                    style="flex: 1; padding: 1rem 1.5rem;"></button>
            </div>
        </div>
    </div>
</body>

</html>