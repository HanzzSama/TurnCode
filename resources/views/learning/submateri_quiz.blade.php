<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Uji Pemahaman: {{ $submateri->title }} - TurnCode</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    @include('layouts.transition-head')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
    <link rel="stylesheet" href="{{ asset('css/quiz-page.css') }}">
</head>
<body class="quiz-page-body">

    <!-- Quiz Container -->
    <div class="quiz-container" id="quizApp">

        <!-- Progress Section -->
        <div class="quiz-progress-section">
            <div class="quiz-progress-bar">
                <div class="quiz-progress-fill" id="progressFill"></div>
            </div>
            <div class="quiz-progress-counter" id="progressCounter">1/{{ count($quizzes) }}</div>
        </div>

        <!-- Workspace Layout -->
        <div class="quiz-workspace">
            <div class="quiz-main-content">
                <!-- Question Area -->
                <div class="quiz-question-area">
                    @foreach($quizzes as $index => $quiz)
                        <div class="quiz-slide" data-slide="{{ $index }}" data-quiz-id="{{ $quiz->id }}" style="display: {{ $index === 0 ? 'block' : 'none' }};">
                            
                            <!-- Question Header -->
                            <div class="quiz-question-header">
                                <span class="quiz-soal-label">Soal {{ $index + 1 }} - {{ $submateri->title }}</span>
                            </div>

                            <!-- Question Media Assets -->
                            @if($quiz->image_url)
                                <div class="quiz-media-container" style="margin-bottom: 20px; display: flex; justify-content: center;">
                                    <img src="{{ $quiz->image_url }}" alt="Media Soal" style="max-width: 100%; max-height: 280px; border-radius: 12px; border: 1px solid var(--border-color); object-fit: contain;">
                                </div>
                            @endif

                            @if($quiz->video_url)
                                <div class="quiz-media-container" style="margin-bottom: 20px; display: flex; justify-content: center; width: 100%;">
                                    <video src="{{ $quiz->video_url }}" controls style="max-width: 100%; max-height: 280px; border-radius: 12px; border: 1px solid var(--border-color);"></video>
                                </div>
                            @endif

                            <!-- Question Code Block -->
                            @if($quiz->code_block)
                                <div class="quiz-code-block-container" style="margin-bottom: 20px; border-radius: 12px; overflow: hidden; border: 1px solid var(--border-color); background: #1e1e24; padding: 16px;">
                                    <pre style="margin: 0; font-family: 'Fira Code', 'Courier New', Courier, monospace; font-size: 13px; color: #f8f8f2; overflow-x: auto; white-space: pre-wrap; word-break: break-all;"><code>{{ $quiz->code_block }}</code></pre>
                                </div>
                            @endif

                            <!-- Question Text -->
                            <div class="quiz-question-text">
                                {{ $quiz->question }}
                            </div>

                            <!-- Options Render -->
                            @if(($quiz->type ?? 'text') === 'puzzle')
                                @php
                                    $options = [];
                                    if (is_array($quiz->options)) {
                                        $options = $quiz->options;
                                    } elseif (is_string($quiz->options)) {
                                        $options = json_decode($quiz->options, true) ?: [];
                                    }
                                    
                                    $correctOrder = json_decode($quiz->correct_answer, true) ?: [];
                                    $puzzleItems = $isQuizPassed ? $correctOrder : $options;
                                @endphp
                                <div class="quiz-puzzle-container" data-quiz-id="{{ $quiz->id }}" style="margin-top: 20px; display: flex; flex-direction: column; gap: 8px;">
                                    @foreach($puzzleItems as $opt)
                                        <div class="quiz-puzzle-item" draggable="{{ $isQuizPassed ? 'false' : 'true' }}" data-value="{{ $opt }}" style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; background: rgba(31, 29, 34, 0.45); border: 1px solid var(--border-color); border-radius: 10px; cursor: {{ $isQuizPassed ? 'default' : 'grab' }}; user-select: none; transition: border-color 0.2s, background-color 0.2s;">
                                            <div style="display: flex; align-items: center; gap: 12px;">
                                                @if(!$isQuizPassed)
                                                    <i class='bx bx-menu' style="color: var(--text-muted); font-size: 18px; cursor: grab;"></i>
                                                @endif
                                                <code style="font-family: 'Fira Code', 'Courier New', Courier, monospace; font-size: 13px; color: #ffffff;">{{ $opt }}</code>
                                            </div>
                                            @if(!$isQuizPassed)
                                                <div class="puzzle-item-controls" style="display: flex; flex-direction: column; gap: 4px;">
                                                    <button type="button" class="puzzle-arrow-btn arrow-up" style="background: transparent; border: none; color: var(--text-muted); cursor: pointer; padding: 2px;" onclick="movePuzzleItem(this, -1)">
                                                        <i class='bx bx-chevron-up' style="font-size: 18px;"></i>
                                                    </button>
                                                    <button type="button" class="puzzle-arrow-btn arrow-down" style="background: transparent; border: none; color: var(--text-muted); cursor: pointer; padding: 2px;" onclick="movePuzzleItem(this, 1)">
                                                        <i class='bx bx-chevron-down' style="font-size: 18px;"></i>
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @elseif(($quiz->type ?? 'text') === 'code_writing')
                                <div class="quiz-code-writing-container" data-quiz-id="{{ $quiz->id }}" style="margin-top: 20px;">
                                    <textarea class="code-writing-input" 
                                              data-quiz-id="{{ $quiz->id }}"
                                              placeholder="Ketik kode jawabanmu di sini..." 
                                              @if($isQuizPassed) disabled @endif
                                              style="width: 100%; height: 200px; padding: 16px; font-family: 'Fira Code', 'Courier New', Courier, monospace; font-size: 14px; color: #f8f8f2; background: #1e1e24; border: 1px solid var(--border-color); border-radius: 12px; resize: vertical; line-height: 1.5; outline: none; transition: border-color 0.2s;">{{ $isQuizPassed ? $quiz->correct_answer : '' }}</textarea>
                                </div>
                            @else
                                <div class="quiz-options-list">
                                    @php
                                        $options = [];
                                        if (is_array($quiz->options)) {
                                            $options = $quiz->options;
                                        } elseif (is_string($quiz->options)) {
                                            $options = json_decode($quiz->options, true) ?: [];
                                        }
                                        $letters = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
                                        $isCorrectOpt = false;
                                    @endphp
                                    @foreach($options as $idx => $opt)
                                        @php
                                            $isCorrectOpt = str_replace("\r\n", "\n", trim($opt)) === str_replace("\r\n", "\n", trim($quiz->correct_answer));
                                        @endphp
                                        <label class="quiz-option-row {{ $isQuizPassed && $isCorrectOpt ? 'correct-locked' : '' }}" data-quiz-id="{{ $quiz->id }}" data-value="{{ $opt }}">
                                            <div class="quiz-option-letter">{{ $letters[$idx] ?? chr(65 + $idx) }}</div>
                                            <div class="quiz-option-text" style="{{ ($quiz->type ?? 'text') === 'code' ? 'font-family: monospace; font-size: 13px;' : '' }}">{{ $opt }}</div>
                                            <input type="radio" name="answer_{{ $quiz->id }}" value="{{ $opt }}" class="quiz-option-radio"
                                                @if($isQuizPassed && $isCorrectOpt) checked disabled @endif
                                                @if($isQuizPassed) disabled @endif>
                                        </label>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Explanation (shown after answering) -->
                            <div class="quiz-explanation" id="explanation-{{ $quiz->id }}" style="display: {{ $isQuizPassed ? 'block' : 'none' }};">
                                <div class="quiz-explanation-label"><i class='bx bx-info-circle'></i> Penjelasan</div>
                                <div class="quiz-explanation-text">{{ $quiz->explanation }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Navigation Buttons -->
                <div class="quiz-nav-buttons">
                    <button class="quiz-btn quiz-btn-back" id="btnBack" disabled>
                        <i class='bx bx-chevron-left'></i> Kembali
                    </button>
                    <button class="quiz-btn quiz-btn-next" id="btnNext">
                        Selanjutnya <i class='bx bx-chevron-right'></i>
                    </button>
                    <button class="quiz-btn quiz-btn-submit" id="btnSubmit" style="display: none;">
                        Selesai <i class='bx bx-check'></i>
                    </button>
                    <button type="button" class="quiz-btn quiz-btn-submit" id="btnShowResults" style="display: none;">
                        Lihat Hasil <i class='bx bx-bar-chart-alt-2'></i>
                    </button>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="quiz-sidebar">
                @if(!$isQuizPassed)
                <!-- Timer Card -->
                <div class="quiz-sidebar-card quiz-timer-card" id="timerCard">
                    <div class="quiz-sidebar-card-header">
                        <i class='bx bx-time-five'></i>
                        <span>Sisa Waktu</span>
                    </div>
                    <div class="quiz-timer-display" id="quizTimer">--:--</div>
                </div>
                @endif

                <!-- Question Navigation Card -->
                <div class="quiz-sidebar-card quiz-panel-card">
                    <div class="quiz-sidebar-card-header">
                        <i class='bx bx-grid-alt'></i>
                        <span>Navigasi Soal</span>
                    </div>
                    <div class="quiz-panel-grid">
                        @foreach($quizzes as $index => $quiz)
                            <button type="button" class="quiz-panel-num {{ $index === 0 ? 'active' : '' }} {{ $isQuizPassed ? 'correct' : '' }}" data-slide-index="{{ $index }}" id="panelNum-{{ $index }}">
                                {{ $index + 1 }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Feedback Overlay -->
        <div class="quiz-feedback-overlay" id="feedbackOverlay" style="display: none;">
            <div class="quiz-feedback-content">
                <div class="quiz-feedback-hero">
                    <div class="quiz-feedback-title">Hasil penilaian</div>
                    <div class="quiz-feedback-percentage" id="feedbackPercentage">0%</div>
                    
                    <!-- Stats Pills -->
                    <div class="quiz-feedback-pills">
                        <span class="quiz-pill">total soal <span id="pillTotal">0</span></span>
                        <span class="quiz-pill">benar <span id="pillCorrect">0</span></span>
                        <span class="quiz-pill">salah <span id="pillIncorrect">0</span></span>
                    </div>

                    <!-- Description Subtext -->
                    <div class="quiz-feedback-desc" id="feedbackDesc">
                        beberapa jawaban masih ada yang salah periksa penjelasan dibawah dan coba lagi untuk mendapat nilai sempurna
                    </div>

                    <!-- Action Buttons -->
                    <div class="quiz-feedback-actions">
                        <a href="{{ route('courses.show', [$course->id, 'submateri_id' => $submateri->id]) }}" class="quiz-btn-outline" id="btnFinish">
                            Keluar
                        </a>
                        <button type="button" class="quiz-btn-outline" id="btnReview">
                            check soal
                        </button>
                        <button type="button" class="quiz-btn-outline" id="btnRetry">
                            Coba Lagi
                        </button>
                    </div>
                </div>

                <!-- Wrong Answers List (Inline Breakdown) -->
                <div class="quiz-wrong-answers-container" id="wrongAnswersContainer" style="display: none;">
                    <!-- Filled by JS -->
                </div>
            </div>
        </div>

        @if($isQuizPassed)
        <!-- Already passed overlay info -->
        <div class="quiz-passed-banner" id="passedBanner">
            <i class='bx bx-check-circle'></i>
            <span>Anda telah lulus Uji Pemahaman ini. Menampilkan pembahasan.</span>
            <a href="{{ route('courses.show', [$course->id, 'submateri_id' => $submateri->id]) }}" class="quiz-btn quiz-btn-back-class">Kembali ke Kelas</a>
        </div>
        @endif
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const totalSlides = {{ count($quizzes) }};
        let isAlreadyPassed = {{ $isQuizPassed ? 'true' : 'false' }};
        let currentSlide = 0;
        let answers = {};
        let isReviewMode = false;
        let timerInterval = null;

        let timeRemaining = totalSlides * 120; // 2 minutes per question
        const savedTime = localStorage.getItem('quiz_time_{{ $submateri->id }}');
        if (savedTime && !isAlreadyPassed) {
            timeRemaining = parseInt(savedTime, 10);
            if (timeRemaining <= 0) {
                timeRemaining = totalSlides * 120;
            }
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

        function normalizeString(str) {
            if (typeof str !== 'string') return '';
            return str.trim().replace(/\r\n/g, '\n');
        }

        // Load saved answers from localStorage
        const savedAnswers = localStorage.getItem('quiz_answers_{{ $submateri->id }}');
        if (savedAnswers && !isAlreadyPassed) {
            try {
                answers = JSON.parse(savedAnswers);
            } catch (e) {
                console.error("Error parsing saved answers", e);
            }
        }

        const progressFill = document.getElementById('progressFill');
        const progressCounter = document.getElementById('progressCounter');
        const btnBack = document.getElementById('btnBack');
        const btnNext = document.getElementById('btnNext');
        const btnSubmit = document.getElementById('btnSubmit');
        const feedbackOverlay = document.getElementById('feedbackOverlay');
        const slides = document.querySelectorAll('.quiz-slide');
        const timerCard = document.getElementById('timerCard');
        const quizTimer = document.getElementById('quizTimer');

        // Apply saved answers to UI
        if (Object.keys(answers).length > 0) {
            Object.keys(answers).forEach(quizId => {
                const value = answers[quizId];
                
                // MCQ option restoration
                const rows = document.querySelectorAll(`.quiz-option-row[data-quiz-id="${quizId}"]`);
                rows.forEach(row => {
                    if (row.dataset.value === value) {
                        row.classList.add('selected');
                        const radio = row.querySelector('.quiz-option-radio');
                        if (radio) radio.checked = true;

                        const slide = row.closest('.quiz-slide');
                        if (slide) {
                            const slideIndex = slide.dataset.slide;
                            const panelBtn = document.getElementById('panelNum-' + slideIndex);
                            if (panelBtn) {
                                panelBtn.classList.add('answered');
                            }
                        }
                    }
                });

                // Puzzle option restoration
                if (Array.isArray(value)) {
                    const puzzleContainer = document.querySelector(`.quiz-puzzle-container[data-quiz-id="${quizId}"]`);
                    if (puzzleContainer) {
                        const items = Array.from(puzzleContainer.querySelectorAll('.quiz-puzzle-item'));
                        puzzleContainer.innerHTML = '';
                        value.forEach(val => {
                            const item = items.find(i => i.dataset.value === val);
                            if (item) {
                                puzzleContainer.appendChild(item);
                            }
                        });

                        const slide = puzzleContainer.closest('.quiz-slide');
                        if (slide) {
                            const slideIndex = slide.dataset.slide;
                            const panelBtn = document.getElementById('panelNum-' + slideIndex);
                            if (panelBtn) {
                                panelBtn.classList.add('answered');
                            }
                        }
                    }
                }

                // Code writing option restoration
                const codeWritingContainer = document.querySelector(`.quiz-code-writing-container[data-quiz-id="${quizId}"]`);
                if (codeWritingContainer) {
                    const textarea = codeWritingContainer.querySelector('.code-writing-input');
                    if (textarea && !isAlreadyPassed) {
                        textarea.value = value;
                        const slide = codeWritingContainer.closest('.quiz-slide');
                        if (slide) {
                            const slideIndex = slide.dataset.slide;
                            const panelBtn = document.getElementById('panelNum-' + slideIndex);
                            if (panelBtn) {
                                panelBtn.classList.add('answered');
                            }
                        }
                    }
                }
            });
        }

        // Initialize unsaved puzzle answers with their default scrambled order
        document.querySelectorAll('.quiz-puzzle-container').forEach(container => {
            const quizId = container.dataset.quizId;
            if (!answers[quizId] && !isAlreadyPassed) {
                const items = Array.from(container.querySelectorAll('.quiz-puzzle-item'));
                const initialOrder = items.map(item => item.dataset.value);
                answers[quizId] = initialOrder;
            }
        });

        // Check if there are saved quiz results
        const savedResults = localStorage.getItem('quiz_results_{{ $submateri->id }}');
        let hasPendingResults = false;
        if (savedResults && !isAlreadyPassed) {
            try {
                const resultsData = JSON.parse(savedResults);
                showQuizResults(resultsData, false);
                hasPendingResults = true;
            } catch (e) {
                console.error("Error parsing saved results", e);
            }
        }

        // Initialize timer
        if (!isAlreadyPassed && !savedResults) {
            startTimer();
        }

        function startTimer() {
            if (timerInterval) clearInterval(timerInterval);
            updateTimerDisplay();

            timerInterval = setInterval(() => {
                timeRemaining--;
                updateTimerDisplay();

                if (!isAlreadyPassed) {
                    localStorage.setItem('quiz_time_{{ $submateri->id }}', timeRemaining);
                }

                if (timeRemaining <= 60 && timerCard) {
                    timerCard.classList.add('warning');
                }

                if (timeRemaining <= 0) {
                    clearInterval(timerInterval);
                    autoSubmitQuiz();
                }
            }, 1000);
        }

        function updateTimerDisplay() {
            if (!quizTimer) return;
            const minutes = Math.floor(timeRemaining / 60);
            const seconds = timeRemaining % 60;
            quizTimer.textContent = 
                String(minutes).padStart(2, '0') + ':' + 
                String(seconds).padStart(2, '0');
        }

        function updateProgress() {
            const pct = ((currentSlide + 1) / totalSlides) * 100;
            progressFill.style.width = pct + '%';
            progressCounter.textContent = (currentSlide + 1) + '/' + totalSlides;
        }

        function showSlide(index) {
            slides.forEach((s, i) => {
                s.style.display = i === index ? 'block' : 'none';
            });
            currentSlide = index;
            updateProgress();

            // Back button
            btnBack.disabled = currentSlide === 0;

            // Next/Submit button
            if (currentSlide === totalSlides - 1) {
                btnNext.style.display = 'none';
                if (!isAlreadyPassed && !isReviewMode) {
                    btnSubmit.style.display = 'inline-flex';
                } else {
                    btnSubmit.style.display = 'none';
                }
            } else {
                btnNext.style.display = 'inline-flex';
                btnSubmit.style.display = 'none';
            }

            // Show Results button (if review mode, already passed, or there are pending results)
            const btnShowResults = document.getElementById('btnShowResults');
            if (btnShowResults) {
                if (isAlreadyPassed || isReviewMode || hasPendingResults) {
                    btnShowResults.style.display = 'inline-flex';
                } else {
                    btnShowResults.style.display = 'none';
                }
            }

            // Sync panel active state
            document.querySelectorAll('.quiz-panel-num').forEach((btn, idx) => {
                if (idx === index) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }

        // Panel click navigation
        document.querySelectorAll('.quiz-panel-num').forEach(btn => {
            btn.addEventListener('click', function() {
                const idx = parseInt(this.dataset.slideIndex);
                showSlide(idx);
            });
        });

        // Option click
        document.querySelectorAll('.quiz-option-row').forEach(row => {
            row.addEventListener('click', function() {
                if (isAlreadyPassed || isReviewMode) return;

                const quizId = this.dataset.quizId;
                const value = this.dataset.value;
                const radio = this.querySelector('.quiz-option-radio');

                // Deselect siblings
                this.closest('.quiz-options-list').querySelectorAll('.quiz-option-row').forEach(r => {
                    r.classList.remove('selected');
                    r.querySelector('.quiz-option-radio').checked = false;
                });

                // Select this
                this.classList.add('selected');
                radio.checked = true;
                answers[quizId] = value;

                // Save to localStorage
                localStorage.setItem('quiz_answers_{{ $submateri->id }}', JSON.stringify(answers));

                // Mark as answered in sidebar panel
                const activePanelNum = document.getElementById('panelNum-' + currentSlide);
                if (activePanelNum) {
                    activePanelNum.classList.add('answered');
                }
            });
        });

        // Code writing input
        document.querySelectorAll('.code-writing-input').forEach(input => {
            input.addEventListener('input', function() {
                if (isAlreadyPassed || isReviewMode) return;
                
                const quizId = this.dataset.quizId;
                const value = this.value;
                answers[quizId] = value;
                
                localStorage.setItem('quiz_answers_{{ $submateri->id }}', JSON.stringify(answers));
                
                const slide = this.closest('.quiz-slide');
                if (slide) {
                    const slideIndex = slide.dataset.slide;
                    const panelBtn = document.getElementById('panelNum-' + slideIndex);
                    if (panelBtn) {
                        if (value.trim().length > 0) {
                            panelBtn.classList.add('answered');
                        } else {
                            panelBtn.classList.remove('answered');
                        }
                    }
                }
            });
        });

        btnBack.addEventListener('click', () => {
            if (currentSlide > 0) showSlide(currentSlide - 1);
        });

        btnNext.addEventListener('click', () => {
            if (currentSlide < totalSlides - 1) showSlide(currentSlide + 1);
        });

        function autoSubmitQuiz() {
            if (timerInterval) clearInterval(timerInterval);
            // Submit whatever answers are filled
            submitQuizData(true);
        }

        function showQuizResults(data, isAuto = false) {
            if (timerInterval) clearInterval(timerInterval);
            if (timerCard) timerCard.style.display = 'none';

            try {
                // Style correct/incorrect
                if (data && data.explanations) {
                    Object.keys(data.explanations).forEach(quizId => {
                        const info = data.explanations[quizId];
                        if (!info) return;
                        
                        const container = document.querySelector(`.quiz-slide[data-quiz-id="${quizId}"]`);
                        if (!container) return;

                        const explanationEl = document.getElementById(`explanation-${quizId}`);
                        if (explanationEl) explanationEl.style.display = 'block';

                        let isCorrect = info.correct;
                        const slideIndex = container.dataset.slide;
                        if (slideIndex !== undefined) {
                            const panelBtn = document.getElementById('panelNum-' + slideIndex);
                            if (panelBtn) {
                                panelBtn.classList.remove('active', 'answered');
                                if (isCorrect) {
                                    panelBtn.classList.add('correct');
                                } else {
                                    panelBtn.classList.add('incorrect');
                                }
                            }
                        }

                        // Lock MCQ options
                        container.querySelectorAll('.quiz-option-row').forEach(row => {
                            const radio = row.querySelector('.quiz-option-radio');
                            if (radio) {
                                radio.disabled = true;
                            }
                            row.style.pointerEvents = 'none';

                            if (info && radio) {
                                const radioVal = normalizeString(radio.value);
                                const correctVal = normalizeString(info.correct_answer);
                                if (radioVal === correctVal) {
                                    row.classList.add('correct-locked');
                                } else if (radio.checked && !info.correct) {
                                    row.classList.add('incorrect-locked');
                                }
                            }
                        });

                        // Lock puzzle items
                        const puzzleContainer = container.querySelector('.quiz-puzzle-container');
                        if (puzzleContainer) {
                            puzzleContainer.querySelectorAll('.quiz-puzzle-item').forEach(item => {
                                item.setAttribute('draggable', 'false');
                                item.style.cursor = 'default';
                            });
                            puzzleContainer.querySelectorAll('.puzzle-arrow-btn').forEach(btn => {
                                btn.style.display = 'none';
                            });
                            puzzleContainer.querySelectorAll('.bx-menu').forEach(icon => {
                                icon.style.display = 'none';
                            });
                        }

                        // Lock code writing
                        const codeWritingContainer = container.querySelector('.quiz-code-writing-container');
                        if (codeWritingContainer) {
                            const textarea = codeWritingContainer.querySelector('.code-writing-input');
                            if (textarea) {
                                textarea.disabled = true;
                                const userVal = normalizeString(answers[quizId]);
                                const correctVal = normalizeString(info.correct_answer);
                                if (userVal === correctVal) {
                                    textarea.style.borderColor = 'var(--color-accent-green, #10b981)';
                                } else {
                                    textarea.style.borderColor = 'var(--color-accent-red, #ef4444)';
                                }
                            }
                        }
                    });
                }

                // Show feedback overlay
                const feedbackDesc = document.getElementById('feedbackDesc');
                const feedbackPercentage = document.getElementById('feedbackPercentage');
                const wrongAnswersContainer = document.getElementById('wrongAnswersContainer');
                const pillTotal = document.getElementById('pillTotal');
                const pillCorrect = document.getElementById('pillCorrect');
                const pillIncorrect = document.getElementById('pillIncorrect');

                const percentage = data && data.total_questions 
                    ? Math.round((data.correct_count / data.total_questions) * 100)
                    : 0;
                
                if (feedbackPercentage) feedbackPercentage.textContent = percentage + '%';

                if (data) {
                    if (pillTotal) pillTotal.textContent = data.total_questions || 0;
                    if (pillCorrect) pillCorrect.textContent = data.correct_count || 0;
                    if (pillIncorrect) pillIncorrect.textContent = (data.total_questions || 0) - (data.correct_count || 0);
                }

                if (data && data.passed) {
                    if (feedbackDesc) feedbackDesc.textContent = 'Luar biasa! Semua jawaban benar. Anda telah lulus Uji Pemahaman untuk submateri ini.';
                    localStorage.removeItem('quiz_answers_{{ $submateri->id }}');
                    localStorage.removeItem('quiz_time_{{ $submateri->id }}');
                    localStorage.removeItem('quiz_results_{{ $submateri->id }}');
                    if (wrongAnswersContainer) {
                        wrongAnswersContainer.style.display = 'none';
                        wrongAnswersContainer.innerHTML = '';
                    }
                } else {
                    if (feedbackDesc) {
                        feedbackDesc.textContent = isAuto 
                            ? 'Waktu pengerjaan telah habis. Beberapa jawaban masih salah atau kosong. Periksa penjelasan dibawah dan coba lagi untuk mendapat nilai sempurna.'
                            : 'beberapa jawaban masih ada yang salah periksa penjelasan dibawah dan coba lagi untuk mendapat nilai sempurna';
                    }

                    // Save failed results to localStorage so they persist on refresh
                    if (data) {
                        localStorage.setItem('quiz_results_{{ $submateri->id }}', JSON.stringify(data));
                    }

                    // Populate wrong answers list with inline question cards matching mockup options styling
                    let wrongListHtml = '';
                    slides.forEach((slide, idx) => {
                        const quizId = slide.dataset.quizId;
                        const info = (data && data.explanations) ? data.explanations[quizId] : null;
                        if (info && !info.correct) {
                            const qTextEl = slide.querySelector('.quiz-question-text');
                            const qText = qTextEl ? qTextEl.textContent.trim() : '';
                            const explanation = info.explanation || '';
                            
                            let optionsHtml = '';
                            const puzzleContainer = slide.querySelector('.quiz-puzzle-container');
                            
                            if (puzzleContainer) {
                                // Puzzle review formatting
                                let correctArray = [];
                                try {
                                    correctArray = JSON.parse(info.correct_answer) || [];
                                } catch(e) {
                                    correctArray = [];
                                }
                                if (!Array.isArray(correctArray)) correctArray = [];
                                
                                const userArray = answers[quizId] || [];
                                if (!Array.isArray(userArray)) userArray = [];
                                
                                let userItemsHtml = '';
                                userArray.forEach((line, lineIdx) => {
                                    const isCorrectPosition = correctArray[lineIdx] && line && normalizeString(correctArray[lineIdx]) === normalizeString(line);
                                    const borderStyle = isCorrectPosition 
                                        ? 'border: 1px solid var(--color-accent-green, #10b981); background: rgba(16, 185, 129, 0.05);' 
                                        : 'border: 1px solid var(--color-accent-red, #ef4444); background: rgba(239, 68, 68, 0.05);';
                                    userItemsHtml += `
                                        <div style="padding: 8px 12px; margin-bottom: 6px; border-radius: 8px; font-family: monospace; font-size: 12px; display: flex; align-items: center; gap: 8px; ${borderStyle}">
                                            <span style="font-weight: bold; opacity: 0.6; color: #ffffff;">#${lineIdx + 1}</span>
                                            <code style="color: #ffffff; white-space: pre-wrap; word-break: break-all;">${escapeHtml(line)}</code>
                                        </div>
                                    `;
                                });
                                
                                let correctItemsHtml = '';
                                correctArray.forEach((line, lineIdx) => {
                                    correctItemsHtml += `
                                        <div style="padding: 8px 12px; margin-bottom: 6px; border-radius: 8px; font-family: monospace; font-size: 12px; display: flex; align-items: center; gap: 8px; border: 1px solid var(--color-accent-green, #10b981); background: rgba(16, 185, 129, 0.05);">
                                            <span style="font-weight: bold; opacity: 0.6; color: #ffffff;">#${lineIdx + 1}</span>
                                            <code style="color: #ffffff; white-space: pre-wrap; word-break: break-all;">${escapeHtml(line)}</code>
                                        </div>
                                    `;
                                });
                                
                                optionsHtml = `
                                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 12px; width: 100%;">
                                        <div>
                                            <div style="font-size: 12px; font-weight: 600; color: #ef4444; margin-bottom: 8px; display: flex; align-items: center; gap: 4px;"><i class='bx bx-x-circle'></i> Susunan Anda:</div>
                                            ${userItemsHtml}
                                        </div>
                                        <div>
                                            <div style="font-size: 12px; font-weight: 600; color: #10b981; margin-bottom: 8px; display: flex; align-items: center; gap: 4px;"><i class='bx bx-check-circle'></i> Susunan Benar:</div>
                                            ${correctItemsHtml}
                                        </div>
                                    </div>
                                `;
                            } else if (slide.querySelector('.quiz-code-writing-container')) {
                                // Code writing review formatting
                                const userVal = answers[quizId] || '';
                                const correctVal = info.correct_answer || '';
                                
                                optionsHtml = `
                                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 12px; width: 100%;">
                                        <div>
                                            <div style="font-size: 12px; font-weight: 600; color: #ef4444; margin-bottom: 8px; display: flex; align-items: center; gap: 4px;"><i class='bx bx-x-circle'></i> Jawaban Anda:</div>
                                            <pre style="padding: 12px; margin: 0; border-radius: 8px; font-family: monospace; font-size: 12px; background: rgba(239, 68, 68, 0.05); border: 1px solid var(--color-accent-red, #ef4444); color: #fff; white-space: pre-wrap; word-break: break-all;"><code>${escapeHtml(userVal) || '<i>Kosong</i>'}</code></pre>
                                        </div>
                                        <div>
                                            <div style="font-size: 12px; font-weight: 600; color: #10b981; margin-bottom: 8px; display: flex; align-items: center; gap: 4px;"><i class='bx bx-check-circle'></i> Jawaban Benar:</div>
                                            <pre style="padding: 12px; margin: 0; border-radius: 8px; font-family: monospace; font-size: 12px; background: rgba(16, 185, 129, 0.05); border: 1px solid var(--color-accent-green, #10b981); color: #fff; white-space: pre-wrap; word-break: break-all;"><code>${escapeHtml(correctVal)}</code></pre>
                                        </div>
                                    </div>
                                `;
                            } else {
                                // MCQ options formatting
                                const optionRows = slide.querySelectorAll('.quiz-option-row');
                                optionRows.forEach(row => {
                                    const letterEl = row.querySelector('.quiz-option-letter');
                                    const letter = letterEl ? letterEl.textContent.trim() : '';
                                    const textEl = row.querySelector('.quiz-option-text');
                                    const text = textEl ? textEl.textContent.trim() : '';
                                    const optVal = row.dataset.value || '';

                                    let optionClass = '';
                                    const optValNormalized = normalizeString(optVal);
                                    const correctVal = normalizeString(info.correct_answer);
                                    const userVal = normalizeString(answers[quizId]);
                                    if (optValNormalized === correctVal) {
                                        optionClass = 'correct-locked';
                                    } else if (optValNormalized === userVal && !info.correct) {
                                        optionClass = 'incorrect-locked';
                                    }

                                    optionsHtml += `
                                        <div class="quiz-option-row ${optionClass}" style="pointer-events: none;">
                                            <div class="quiz-option-letter">${letter}</div>
                                            <div class="quiz-option-text">${escapeHtml(text)}</div>
                                        </div>
                                    `;
                                });
                                optionsHtml = `<div class="quiz-options-list">${optionsHtml}</div>`;
                            }

                            wrongListHtml += `
                                <div class="quiz-wrong-item">
                                    <div class="quiz-wrong-header">Soal ${idx + 1} - {{ $submateri->title }}</div>
                                    <div class="quiz-wrong-question">${escapeHtml(qText)}</div>
                                    ${optionsHtml}
                                    <div class="quiz-wrong-explanation">
                                        <div class="quiz-wrong-explanation-label">Penjelasan:</div>
                                        <div class="quiz-wrong-explanation-text">${escapeHtml(explanation)}</div>
                                    </div>
                                </div>
                            `;
                        }
                    });

                    if (wrongAnswersContainer) {
                        wrongAnswersContainer.innerHTML = wrongListHtml;
                        wrongAnswersContainer.style.display = 'block';
                    }
                }
            } catch (err) {
                console.error("Error in showQuizResults:", err);
            }

            if (feedbackOverlay) {
                feedbackOverlay.style.display = 'block';
            }
        }

        function submitQuizData(isAuto = false) {
            if (btnSubmit) {
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = isAuto 
                    ? '<i class="bx bx-loader-alt bx-spin"></i> Menyimpan...'
                    : '<i class="bx bx-loader-alt bx-spin"></i> Mengecek...';
            }

            fetch('{{ route("submateris.quiz.submit", $submateri->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ answers: answers })
            })
            .then(r => r.json())
            .then(data => {
                showQuizResults(data, isAuto);
            })
            .catch(err => {
                console.error(err);
                if (btnSubmit) {
                    btnSubmit.disabled = false;
                    btnSubmit.innerHTML = 'Selesai <i class="bx bx-check"></i>';
                }
            });
        }

        // Submit Button Click
        if (btnSubmit) {
            btnSubmit.addEventListener('click', function() {
                // Check all answered
                if (Object.keys(answers).length < totalSlides) {
                    // Find unanswered
                    let unanswered = null;
                    slides.forEach((s, i) => {
                        const qId = s.dataset.quizId;
                        if (!answers[qId] && unanswered === null) unanswered = i;
                    });
                    if (unanswered !== null) {
                        showSlide(unanswered);
                        // Flash the options or puzzle container
                        const opts = slides[unanswered].querySelector('.quiz-options-list') || slides[unanswered].querySelector('.quiz-puzzle-container');
                        if (opts) {
                            opts.classList.add('shake');
                            setTimeout(() => opts.classList.remove('shake'), 600);
                        }
                    }
                    return;
                }
                submitQuizData(false);
            });
        }

        // Review button
        document.getElementById('btnReview')?.addEventListener('click', () => {
            feedbackOverlay.style.display = 'none';
            isReviewMode = true;
            showSlide(0);
            // Hide submit, show next only
            btnSubmit.style.display = 'none';
            btnNext.style.display = 'inline-flex';
        });

        // Show Results button click listener
        document.getElementById('btnShowResults')?.addEventListener('click', () => {
            if (isAlreadyPassed && !isReviewMode) {
                // Construct mock results data since they already passed
                const explanations = {};
                slides.forEach(slide => {
                    const quizId = slide.dataset.quizId;
                    const textEl = slide.querySelector('.quiz-explanation-text');
                    explanations[quizId] = {
                        correct: true,
                        explanation: textEl ? textEl.textContent.trim() : '',
                        correct_answer: ''
                    };
                });
                showQuizResults({
                    passed: true,
                    correct_count: totalSlides,
                    total_questions: totalSlides,
                    explanations: explanations
                });
            } else {
                // In review mode, show the saved results
                const savedResults = localStorage.getItem('quiz_results_{{ $submateri->id }}');
                if (savedResults) {
                    try {
                        showQuizResults(JSON.parse(savedResults));
                    } catch (e) {
                        console.error(e);
                    }
                } else {
                    showQuizResults({
                        passed: true,
                        correct_count: totalSlides,
                        total_questions: totalSlides,
                        explanations: {}
                    });
                }
            }
        });

        // HTML5 drag and drop logic for puzzle questions
        let dragSourceEl = null;

        window.handleDragStart = function(e) {
            if (isAlreadyPassed || isReviewMode) {
                e.preventDefault();
                return;
            }
            this.style.opacity = '0.4';
            dragSourceEl = this;
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/plain', this.dataset.value);
        };

        window.handleDragOver = function(e) {
            if (e.preventDefault) {
                e.preventDefault();
            }
            e.dataTransfer.dropEffect = 'move';
            return false;
        };

        window.handleDragEnter = function(e) {
            this.classList.add('over');
        };

        window.handleDragLeave = function(e) {
            this.classList.remove('over');
        };

        window.handleDrop = function(e) {
            e.stopPropagation();
            e.preventDefault();
            this.classList.remove('over');
            if (dragSourceEl !== this) {
                const container = this.closest('.quiz-puzzle-container');
                const children = Array.from(container.children);
                const sourceIdx = children.indexOf(dragSourceEl);
                const destIdx = children.indexOf(this);
                if (sourceIdx < destIdx) {
                    container.insertBefore(dragSourceEl, this.nextSibling);
                } else {
                    container.insertBefore(dragSourceEl, this);
                }
                
                updatePuzzleAnswer(container);
            }
            return false;
        };

        window.handleDragEnd = function(e) {
            this.style.opacity = '1';
            document.querySelectorAll('.quiz-puzzle-item').forEach(item => {
                item.style.opacity = '1';
                item.classList.remove('over');
            });
        };

        window.movePuzzleItem = function(btn, direction) {
            if (isAlreadyPassed || isReviewMode) return;
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
        };

        function updatePuzzleAnswer(container) {
            if (!container) return;
            const quizId = container.dataset.quizId;
            const items = Array.from(container.querySelectorAll('.quiz-puzzle-item'));
            const orderedValues = items.map(item => item.dataset.value);
            answers[quizId] = orderedValues;
            
            localStorage.setItem('quiz_answers_{{ $submateri->id }}', JSON.stringify(answers));
            
            const slide = container.closest('.quiz-slide');
            if (slide) {
                const slideIndex = slide.dataset.slide;
                const panelBtn = document.getElementById('panelNum-' + slideIndex);
                if (panelBtn) {
                    panelBtn.classList.add('answered');
                }
            }
        }

        function initPuzzleDragListeners() {
            document.querySelectorAll('.quiz-puzzle-item').forEach(item => {
                item.addEventListener('dragstart', handleDragStart, false);
                item.addEventListener('dragover', handleDragOver, false);
                item.addEventListener('dragenter', handleDragEnter, false);
                item.addEventListener('dragleave', handleDragLeave, false);
                item.addEventListener('drop', handleDrop, false);
                item.addEventListener('dragend', handleDragEnd, false);
            });
        }

        // Retry button
        document.getElementById('btnRetry')?.addEventListener('click', () => {
            feedbackOverlay.style.display = 'none';
            const passedBanner = document.getElementById('passedBanner');
            if (passedBanner) passedBanner.style.display = 'none';
            answers = {};
            localStorage.removeItem('quiz_answers_{{ $submateri->id }}');
            localStorage.removeItem('quiz_time_{{ $submateri->id }}');
            localStorage.removeItem('quiz_results_{{ $submateri->id }}');
            isReviewMode = false;
            isAlreadyPassed = false;
            hasPendingResults = false;
            timeRemaining = totalSlides * 120; // reset timer

            // Explicitly hide the "Lihat Hasil" button on retry
            const btnShowResultsRetry = document.getElementById('btnShowResults');
            if (btnShowResultsRetry) btnShowResultsRetry.style.display = 'none';

            if (timerCard) {
                timerCard.style.display = 'block';
                timerCard.classList.remove('warning');
            }

            if (wrongAnswersContainer) {
                wrongAnswersContainer.style.display = 'none';
                wrongAnswersContainer.innerHTML = '';
            }

            // Reset all option rows
            document.querySelectorAll('.quiz-option-row').forEach(row => {
                row.classList.remove('selected', 'correct-locked', 'incorrect-locked');
                row.style.pointerEvents = '';
                const radio = row.querySelector('.quiz-option-radio');
                radio.checked = false;
                radio.disabled = false;
            });

            // Reset all puzzle containers
            document.querySelectorAll('.quiz-puzzle-container').forEach(container => {
                const quizId = container.dataset.quizId;
                container.querySelectorAll('.quiz-puzzle-item').forEach(item => {
                    item.setAttribute('draggable', 'true');
                    item.style.cursor = 'grab';
                });
                container.querySelectorAll('.puzzle-item-controls').forEach(ctrl => {
                    ctrl.style.display = 'flex';
                });
                container.querySelectorAll('.bx-menu').forEach(icon => {
                    icon.style.display = 'block';
                });
                
                const items = Array.from(container.querySelectorAll('.quiz-puzzle-item'));
                const currentOrder = items.map(item => item.dataset.value);
                answers[quizId] = currentOrder;
            });

            // Reset code writing inputs
            document.querySelectorAll('.code-writing-input').forEach(input => {
                input.value = '';
                input.disabled = false;
                input.style.borderColor = 'var(--border-color)';
            });

            // Hide explanations
            document.querySelectorAll('.quiz-explanation').forEach(el => {
                el.style.display = 'none';
            });

            // Reset panel buttons styles
            document.querySelectorAll('.quiz-panel-num').forEach((btn, idx) => {
                btn.className = 'quiz-panel-num';
                if (idx === 0) btn.classList.add('active');
            });

            showSlide(0);
            if (btnSubmit) {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = 'Selesai <i class="bx bx-check"></i>';
            }

            startTimer();
        });

        // Init
        initPuzzleDragListeners();
        showSlide(0);
    });
    </script>

    <script src="{{ asset('js/panel.js') }}"></script>
</body>
</html>
