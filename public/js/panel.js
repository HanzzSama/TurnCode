function initPanelJs() {
    if (window.panelJsInitialized) return;
    window.panelJsInitialized = true;

    // Helper to lock body scroll when overlay is active
    function updateBodyScroll() {
        const panel = document.getElementById('menuPanel');
        const modal = document.getElementById('friendHubModal');
        const isMenuOpen = panel && panel.classList.contains('open');
        const isFriendHubOpen = modal && modal.classList.contains('show');
        
        if (isMenuOpen || isFriendHubOpen) {
            if (document.body) document.body.classList.add('no-scroll');
        } else {
            if (document.body) document.body.classList.remove('no-scroll');
        }
    }

    // Set initial state
    updateBodyScroll();

    // Listen to class changes on menuPanel
    const targetMenuPanel = document.getElementById('menuPanel');
    if (targetMenuPanel) {
        const menuObserver = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'class') {
                    updateBodyScroll();
                }
            });
        });
        menuObserver.observe(targetMenuPanel, { attributes: true });
    }

    // Listen to class changes on friendHubModal
    const targetFriendHubModal = document.getElementById('friendHubModal');
    if (targetFriendHubModal) {
        const friendObserver = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'class') {
                    updateBodyScroll();
                }
            });
        });
        friendObserver.observe(targetFriendHubModal, { attributes: true });
    }

    // YouTube Player State (declared early to prevent TDZ ReferenceError)
    let ytPlayer = null;
    let isYtReady = false;
    let currentYtVideoId = null;
    let pendingYtSeekTime = null;
    let ytProgressInterval = null;
    let ytVisualizerAnimationId = null;

    const menuPanel = document.getElementById('menuPanel');
    if (menuPanel) {
        menuPanel.addEventListener('click', function (e) {
            // Tutup panel jika klik terjadi di luar area panel-notif, panel-right-col, dan bug-report-wrapper
            if (!e.target.closest('.panel-notif') && !e.target.closest('.panel-right-col') && !e.target.closest('.bug-report-wrapper')) {
                menuPanel.classList.remove('open');
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') menuPanel.classList.remove('open');
        });
    }
    // Volume slider
    const volumeInput = document.getElementById('volumeInput');
    const volumeFill = document.getElementById('volumeFill');

    function updateVolume(val) {
        // Map 0-100 to 10%-100% width of the container
        const pct = 10 + (val / 100) * 90;
        if (volumeFill) {
            volumeFill.style.width = pct + '%';

            // Animasi Icon Volume Rendah - Tinggi
            const svg = volumeFill.querySelector('svg');
            if (svg) {
                if (val == 0) {
                    svg.innerHTML = '<polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" /><line x1="23" y1="9" x2="17" y2="15" /><line x1="17" y1="9" x2="23" y2="15" />';
                } else if (val < 50) {
                    svg.innerHTML = '<polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" /><path d="M15.54 8.46a5 5 0 0 1 0 7.07" />';
                } else {
                    svg.innerHTML = '<polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" /><path d="M19.07 4.93a10 10 0 0 1 0 14.14" /><path d="M15.54 8.46a5 5 0 0 1 0 7.07" />';
                }
            }
        }

        // Terhubung langsung ke volume lagu (0.0 hingga 1.0)
        const audioPlayer = document.getElementById('audioPlayer');
        if (audioPlayer) {
            audioPlayer.volume = val / 100;
        }

        // Terhubung ke volume YouTube (0 hingga 100)
        if (typeof ytPlayer !== 'undefined' && ytPlayer && typeof isYtReady !== 'undefined' && isYtReady) {
            ytPlayer.setVolume(val);
        }

        // Simpan ke localStorage
        localStorage.setItem('audioPlayerVolume', val);
    }

    if (volumeInput) {
        // Restore dari localStorage saat loading
        const savedVolume = localStorage.getItem('audioPlayerVolume');
        if (savedVolume !== null) {
            volumeInput.value = savedVolume;
        }
        updateVolume(volumeInput.value);

        volumeInput.addEventListener('input', function () {
            updateVolume(this.value);
        });
    }

    // Grid Carousel Pagination Dots
    const panelGrid = document.querySelector('.panel-grid');
    const panelDots = document.querySelectorAll('.panel-dot');

    if (panelGrid && panelDots.length > 0) {
        // Restore last saved slide index on load
        const savedIndex = localStorage.getItem('lastPanelSlideIndex');
        if (savedIndex !== null) {
            const targetIndex = parseInt(savedIndex, 10);
            setTimeout(() => {
                const width = panelGrid.offsetWidth;
                if (width > 0) {
                    panelGrid.scrollLeft = targetIndex * width;
                    panelDots.forEach((dot, idx) => {
                        if (idx === targetIndex) {
                            dot.classList.add('active');
                        } else {
                            dot.classList.remove('active');
                        }
                    });
                }
            }, 100);
        }

        panelGrid.addEventListener('scroll', () => {
            // Kalkulasi index page (0 atau 1) berdasarkan seberapa jauh scroll terjadi
            const scrollLeft = panelGrid.scrollLeft;
            const width = panelGrid.offsetWidth;
            if (width > 0) {
                const activeIndex = Math.round(scrollLeft / width);
                localStorage.setItem('lastPanelSlideIndex', activeIndex);

                panelDots.forEach((dot, index) => {
                    if (index === activeIndex) {
                        dot.classList.add('active');
                    } else {
                        dot.classList.remove('active');
                    }
                });
            }
        });

        // Klik indikator dot untuk pindah halaman
        panelDots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                const width = panelGrid.offsetWidth;
                if (width > 0) {
                    localStorage.setItem('lastPanelSlideIndex', index);
                    panelGrid.scrollTo({
                        left: index * width,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    // Toggle Grid Buttons and Panels
    const gridBtns = document.querySelectorAll('.panel-grid-btn');
    const btnNotif = document.getElementById('btnNotif');
    const panelNotif = document.getElementById('panelNotif');
    const btnMusic = document.getElementById('btnMusic');
    const panelMusic = document.getElementById('panelMusic');

    // Helper functions to show/hide sub-panels
    function showPanel(panelId) {
        const allPanels = ['panelMusic', 'panelFriend', 'panelAccount', 'panelSetting', 'panelAbout'];
        allPanels.forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            if (id === panelId) {
                el.style.display = 'flex';
                // Reset animasi dengan cara force reflow
                el.classList.remove('panel-show');
                void el.offsetWidth;
                el.classList.add('panel-show');
            } else {
                el.style.display = 'none';
                el.classList.remove('panel-show');
            }
        });
    }

    function hideAllPanels() {
        ['panelMusic', 'panelFriend', 'panelAccount', 'panelSetting', 'panelAbout'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.style.display = 'none';
                el.classList.remove('panel-show');
            }
        });
    }

    // Restore last active menu panel on page load
    const lastActiveMenuPanel = localStorage.getItem('lastActiveMenuPanel');
    if (lastActiveMenuPanel) {
        const activeBtn = document.getElementById(lastActiveMenuPanel);
        if (activeBtn) {
            activeBtn.classList.add('active');
            if (lastActiveMenuPanel === 'btnMusic') {
                showPanel('panelMusic');
            } else if (lastActiveMenuPanel === 'btnFriend') {
                showPanel('panelFriend');
            } else if (lastActiveMenuPanel === 'btnAccount') {
                showPanel('panelAccount');
            } else if (lastActiveMenuPanel === 'btnSetting') {
                showPanel('panelSetting');
            } else if (lastActiveMenuPanel === 'btnAbout') {
                showPanel('panelAbout');
            } else if (lastActiveMenuPanel === 'btnBug') {
                const bugReportWrapper = document.getElementById('bugReportWrapper');
                const panelContentMain = document.getElementById('panelContentMain');
                if (bugReportWrapper && panelContentMain) {
                    panelContentMain.style.display = 'none';
                    bugReportWrapper.classList.remove('show', 'step-2');
                    void bugReportWrapper.offsetWidth;
                    bugReportWrapper.classList.add('show');
                }
            }
        }
    } else {
        hideAllPanels();
    }

    // Restore last active notifications panel on page load
    const lastNotifActive = localStorage.getItem('lastNotifActive');
    if (lastNotifActive && btnNotif && panelNotif) {
        btnNotif.classList.add('active');
        panelNotif.style.display = 'flex';
        panelNotif.classList.remove('panel-hide');
        void panelNotif.offsetWidth;
        panelNotif.classList.add('panel-show');
    }

    if (gridBtns.length > 0) {
        gridBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                if (this === btnNotif && panelNotif) {
                    // Khusus untuk tombol Notifikasi (independen)
                    this.classList.toggle('active');
                    if (this.classList.contains('active')) {
                        localStorage.setItem('lastNotifActive', 'true');
                        // Animasi masuk
                        panelNotif.style.display = 'flex';
                        panelNotif.classList.remove('panel-hide');
                        void panelNotif.offsetWidth; // force reflow
                        panelNotif.classList.add('panel-show');
                    } else {
                        localStorage.removeItem('lastNotifActive');
                        // Animasi keluar: tunggu selesai lalu sembunyikan
                        panelNotif.classList.remove('panel-show');
                        panelNotif.classList.add('panel-hide');
                        panelNotif.addEventListener('animationend', function handler() {
                            panelNotif.style.display = 'none';
                            panelNotif.classList.remove('panel-hide');
                            panelNotif.removeEventListener('animationend', handler);
                        });
                    }
                } else {
                    // Untuk tombol selain Notifikasi
                    // Hapus active dari tombol lain (kecuali Notifikasi)
                    gridBtns.forEach(otherBtn => {
                        if (otherBtn !== btnNotif && otherBtn !== this) {
                            otherBtn.classList.remove('active');
                        }
                    });
                    // Toggle tombol yang sedang diklik
                    const isNowActive = this.classList.toggle('active');
                    if (isNowActive) {
                        localStorage.setItem('lastActiveMenuPanel', this.id);
                    } else {
                        localStorage.removeItem('lastActiveMenuPanel');
                    }

                    // Kontrol panel music
                    if (btnMusic && document.getElementById('panelMusic')) {
                        if (btnMusic.classList.contains('active')) showPanel('panelMusic');
                    }

                    // Kontrol panel friend
                    const btnFriend = document.getElementById('btnFriend');
                    if (btnFriend && document.getElementById('panelFriend')) {
                        if (btnFriend.classList.contains('active')) showPanel('panelFriend');
                    }

                    // Kontrol panel account
                    const btnAccount = document.getElementById('btnAccount');
                    if (btnAccount && document.getElementById('panelAccount')) {
                        if (btnAccount.classList.contains('active')) showPanel('panelAccount');
                    }

                    // Kontrol panel setting
                    const btnSetting = document.getElementById('btnSetting');
                    if (btnSetting && document.getElementById('panelSetting')) {
                        if (btnSetting.classList.contains('active')) showPanel('panelSetting');
                    }

                    // Kontrol panel about
                    const btnAbout = document.getElementById('btnAbout');
                    if (btnAbout && document.getElementById('panelAbout')) {
                        if (btnAbout.classList.contains('active')) showPanel('panelAbout');
                    }

                    // Kontrol panel bug
                    const btnBug = document.getElementById('btnBug');
                    const bugReportWrapper = document.getElementById('bugReportWrapper');
                    const panelContentMain = document.getElementById('panelContentMain');
                    if (btnBug && bugReportWrapper && panelContentMain) {
                        if (btnBug.classList.contains('active')) {
                            panelContentMain.style.display = 'none';
                            bugReportWrapper.classList.remove('show', 'step-2');
                            void bugReportWrapper.offsetWidth; // force reflow
                            bugReportWrapper.classList.add('show');
                        }
                    }

                    // Jika tidak ada panel yang aktif, sembunyikan semua panel biasa
                    const anyActive = ['btnMusic', 'btnFriend', 'btnAccount', 'btnSetting', 'btnAbout', 'btnBug']
                        .some(id => document.getElementById(id)?.classList.contains('active'));
                    if (!anyActive) {
                        hideAllPanels();
                        if (bugReportWrapper && panelContentMain) {
                            bugReportWrapper.classList.remove('show');
                            panelContentMain.style.display = 'flex';
                        }
                    }
                }
            });
        });
    }

    // Standalone Bug Report Logic
    const bugReportWrapper = document.getElementById('bugReportWrapper');
    const panelContentMain = document.getElementById('panelContentMain');
    const btnBugClose = document.getElementById('btnBugClose');
    const btnBugNext = document.getElementById('btnBugNext');
    const btnBugPrev = document.getElementById('btnBugPrev');
    const btnBugSubmit = document.getElementById('btnBugSubmit');
    const btnBug = document.getElementById('btnBug');

    if (bugReportWrapper) {
        // Close button
        if (btnBugClose) {
            btnBugClose.addEventListener('click', (e) => {
                e.preventDefault();
                bugReportWrapper.classList.remove('show');
                // Restore main content
                if (panelContentMain) {
                    panelContentMain.style.display = 'flex';
                    void panelContentMain.offsetWidth;
                }
                // Deactivate grid button
                if (btnBug) {
                    btnBug.classList.remove('active');
                    localStorage.removeItem('lastActiveMenuPanel');
                }
                // Reset to slide 1
                setTimeout(() => bugReportWrapper.classList.remove('step-2'), 400);
            });
        }
        // Next Slide
        if (btnBugNext) {
            btnBugNext.addEventListener('click', (e) => {
                e.preventDefault();
                bugReportWrapper.classList.add('step-2');
            });
        }
        // Previous Slide
        if (btnBugPrev) {
            btnBugPrev.addEventListener('click', (e) => {
                e.preventDefault();
                bugReportWrapper.classList.remove('step-2');
            });
        }
        // Submit Button
        if (btnBugSubmit) {
            btnBugSubmit.addEventListener('click', (e) => {
                e.preventDefault();
                btnBugSubmit.textContent = 'Mengirim...';
                setTimeout(() => {
                    btnBugSubmit.textContent = 'Kirim Laporan';
                    if (btnBugClose) btnBugClose.click(); // close modal
                }, 1000);
            });
        }
    }

    // Audio Player Logic
    const audioPlayer = document.getElementById('audioPlayer');
    const musicUpload = document.getElementById('musicUpload');
    const musicPlayBtn = document.getElementById('musicPlayBtn');
    const iconPlay = document.getElementById('iconPlay');
    const iconPause = document.getElementById('iconPause');
    const musicBars = document.querySelectorAll('.music-bar');
    const musicProgressBar = document.getElementById('musicProgressBar');
    const musicProgressFill = document.getElementById('musicProgressFill');

    // YouTube Integration Elements
    const musicYtToggleBtn = document.getElementById('musicYtToggleBtn');
    const musicYtInputContainer = document.getElementById('musicYtInputContainer');
    const musicYtInput = document.getElementById('musicYtInput');
    const musicYtSubmit = document.getElementById('musicYtSubmit');

    let audioCtx, analyser, dataArray, source;
    let animationId;
    let activeSource = localStorage.getItem('musicActiveSource') || 'local'; // 'local' or 'youtube'



    // Assign data-seed to music bars for the simulated visualizer
    musicBars.forEach((bar, index) => {
        bar.setAttribute('data-seed', (index * 1.5).toString());
    });

    // Load YouTube API dynamically
    if (!window.YT) {
        const tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        const firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    }

    const previousAPIReady = window.onYouTubeIframeAPIReady;
    window.onYouTubeIframeAPIReady = function() {
        if (previousAPIReady) previousAPIReady();
        initYoutubePlayer();
    };

    // If window.YT is already loaded
    if (window.YT && window.YT.Player) {
        initYoutubePlayer();
    }

    function initYoutubePlayer() {
        if (ytPlayer) return;
        ytPlayer = new YT.Player('youtubePlayer', {
            height: '1',
            width: '1',
            videoId: '',
            playerVars: {
                'playsinline': 1,
                'controls': 0,
                'disablekb': 1,
                'fs': 0,
                'rel': 0
            },
            events: {
                'onReady': onPlayerReady,
                'onStateChange': onPlayerStateChange
            }
        });
    }

    function onPlayerReady(event) {
        isYtReady = true;

        // Set initial volume from slider
        const savedVolume = localStorage.getItem('audioPlayerVolume');
        if (savedVolume !== null && ytPlayer) {
            ytPlayer.setVolume(parseInt(savedVolume));
        }

        if (activeSource === 'youtube') {
            const savedUrl = localStorage.getItem('musicActiveYtUrl');
            if (savedUrl) {
                const videoId = getYouTubeId(savedUrl);
                if (videoId) {
                    ytPlayer.cueVideoById(videoId);
                    const savedTime = localStorage.getItem('musicProgressYt');
                    if (savedTime && parseFloat(savedTime) > 0) {
                        pendingYtSeekTime = parseFloat(savedTime);
                    }
                }
            }
        }
    }

    function onPlayerStateChange(event) {
        // YT.PlayerState.PLAYING is 1, PAUSED is 2, ENDED is 0
        if (event.data === 1) {
            iconPlay.style.display = 'none';
            iconPause.style.display = 'block';
            
            if (pendingYtSeekTime !== null) {
                ytPlayer.seekTo(pendingYtSeekTime, true);
                pendingYtSeekTime = null;
            }
            
            animateYtVisualizer();
            if (ytProgressInterval) clearInterval(ytProgressInterval);
            ytProgressInterval = setInterval(updateYtProgress, 500);
        } else {
            if (event.data === 2 || event.data === 0) {
                iconPlay.style.display = 'block';
                iconPause.style.display = 'none';
                resetVisualizer();
                if (ytProgressInterval) clearInterval(ytProgressInterval);
            }
            if (event.data === 0) {
                musicProgressFill.style.width = '0%';
                localStorage.setItem('musicProgressYt', 0);
            }
        }
    }

    function updateYtProgress() {
        if (!ytPlayer || !isYtReady || activeSource !== 'youtube') return;
        const duration = ytPlayer.getDuration();
        const currentTime = ytPlayer.getCurrentTime();
        if (duration > 0) {
            const pct = (currentTime / duration) * 100;
            musicProgressFill.style.width = pct + '%';
            localStorage.setItem('musicProgressYt', currentTime);
        }
    }

    function getYouTubeId(url) {
        const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
        const match = url.match(regExp);
        return (match && match[2].length === 11) ? match[2] : null;
    }

    function playYoutubeLink() {
        const url = musicYtInput.value.trim();
        if (!url) return;
        const videoId = getYouTubeId(url);
        if (videoId) {
            // Hentikan audio lokal jika sedang berjalan
            if (!audioPlayer.paused) {
                audioPlayer.pause();
                resetVisualizer();
            }

            activeSource = 'youtube';
            localStorage.setItem('musicActiveSource', 'youtube');
            localStorage.setItem('musicActiveYtUrl', url);
            localStorage.setItem('musicProgressYt', 0);

            if (isYtReady && ytPlayer) {
                ytPlayer.loadVideoById(videoId);
            } else {
                currentYtVideoId = videoId;
                initYoutubePlayer();
            }
            musicYtInputContainer.classList.remove('active');
            musicYtToggleBtn.classList.remove('active');
            if (panelMusic) panelMusic.classList.remove('minimized');
            musicYtInput.value = '';
        } else {
            alert('URL YouTube tidak valid. Harap masukkan tautan yang benar.');
        }
    }

    if (musicYtToggleBtn && musicYtInputContainer) {
        musicYtToggleBtn.addEventListener('click', function() {
            const isActive = musicYtInputContainer.classList.toggle('active');
            musicYtToggleBtn.classList.toggle('active', isActive);
            if (panelMusic) panelMusic.classList.toggle('minimized', isActive);
            if (isActive) {
                musicYtInput.focus();
            }
        });
    }

    if (musicYtSubmit) {
        musicYtSubmit.addEventListener('click', playYoutubeLink);
    }
    if (musicYtInput) {
        musicYtInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                playYoutubeLink();
            }
        });
    }

    function initAudio() {
        if (!audioCtx) {
            audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            analyser = audioCtx.createAnalyser();
            source = audioCtx.createMediaElementSource(audioPlayer);
            source.connect(analyser);
            analyser.connect(audioCtx.destination);

            analyser.fftSize = 512;
            analyser.maxDecibels = -45;
            analyser.minDecibels = -100;
            analyser.smoothingTimeConstant = 0.65;

            const bufferLength = analyser.frequencyBinCount;
            dataArray = new Uint8Array(bufferLength);
        }
        if (audioCtx.state === 'suspended') {
            audioCtx.resume();
        }
    }

    function animateVisualizer() {
        if (audioPlayer.paused || activeSource !== 'local') return;
        animationId = requestAnimationFrame(animateVisualizer);
        if (analyser && dataArray) {
            analyser.getByteFrequencyData(dataArray);

            const bins = [40, 15, 6, 2, 6, 15, 40];

            musicBars.forEach((bar, index) => {
                const freqIndex = bins[index];
                const data = dataArray[freqIndex] || 0;

                const normalized = data / 255;
                const curve = Math.pow(normalized, 1.8);

                const base = 8;
                const max = parseInt(bar.style.getPropertyValue('--max')) || 20;

                const height = base + curve * (max - base);
                bar.style.height = height + 'px';
            });
        }
    }

    function animateYtVisualizer() {
        if (activeSource !== 'youtube' || !ytPlayer || ytPlayer.getPlayerState() !== 1) {
            return;
        }
        ytVisualizerAnimationId = requestAnimationFrame(animateYtVisualizer);
        
        musicBars.forEach((bar) => {
            const max = parseInt(bar.style.getPropertyValue('--max')) || 20;
            const base = 8;
            const time = Date.now() * 0.005;
            const seed = parseFloat(bar.getAttribute('data-seed') || '0');
            const noise = Math.sin(time + seed) * 0.5 + 0.5;
            const height = base + noise * (max - base);
            bar.style.height = height + 'px';
        });
    }

    function resetVisualizer() {
        cancelAnimationFrame(animationId);
        cancelAnimationFrame(ytVisualizerAnimationId);
        musicBars.forEach(bar => {
            bar.style.height = '20px';
        });
    }

    // Setup IndexedDB untuk menyimpan file lagu secara persisten
    const dbName = "TurnCodeMusicDB";
    let db;

    const request = indexedDB.open(dbName, 1);
    request.onupgradeneeded = (e) => {
        db = e.target.result;
        if (!db.objectStoreNames.contains("music")) {
            db.createObjectStore("music");
        }
    };

    request.onsuccess = (e) => {
        db = e.target.result;
        loadSavedMusic();
    };

    function saveMusicToDB(file) {
        if (!db) return;
        const tx = db.transaction("music", "readwrite");
        tx.objectStore("music").put(file, "savedAudio");
    }

    function loadSavedMusic() {
        if (!db || !audioPlayer) return;

        if (activeSource === 'youtube') {
            const savedUrl = localStorage.getItem('musicActiveYtUrl');
            if (savedUrl) {
                const videoId = getYouTubeId(savedUrl);
                if (videoId) {
                    currentYtVideoId = videoId;
                    initYoutubePlayer();
                    
                    const savedTime = localStorage.getItem('musicProgressYt');
                    if (savedTime && parseFloat(savedTime) > 0) {
                        pendingYtSeekTime = parseFloat(savedTime);
                    }
                }
            }
            return;
        }

        const tx = db.transaction("music", "readonly");
        const store = tx.objectStore("music");
        const req = store.get("savedAudio");

        req.onsuccess = (e) => {
            const file = e.target.result;
            if (file) {
                const url = URL.createObjectURL(file);
                audioPlayer.src = url;
                // Kembalikan progress terakhir
                const savedTime = localStorage.getItem('musicProgress');
                if (savedTime) {
                    audioPlayer.addEventListener('loadedmetadata', function () {
                        audioPlayer.currentTime = parseFloat(savedTime);
                        const pct = (audioPlayer.currentTime / audioPlayer.duration) * 100;
                        musicProgressFill.style.width = pct + '%';
                    }, { once: true });
                }
            }
        };
    }

    if (musicUpload && audioPlayer) {
        musicUpload.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                // Pause YouTube if playing
                if (ytPlayer && isYtReady) {
                    ytPlayer.pauseVideo();
                }
                activeSource = 'local';
                localStorage.setItem('musicActiveSource', 'local');

                saveMusicToDB(file); // Simpan lagu ke database lokal
                localStorage.setItem('musicProgress', 0); // Reset progress

                const url = URL.createObjectURL(file);
                audioPlayer.src = url;
                initAudio();
                audioPlayer.play();
                iconPlay.style.display = 'none';
                iconPause.style.display = 'block';
                animateVisualizer();
            }
        });

        musicPlayBtn.addEventListener('click', function () {
            if (activeSource === 'local') {
                if (!audioPlayer.src || audioPlayer.src === "") return;
                initAudio();

                if (audioPlayer.paused) {
                    audioPlayer.play();
                    iconPlay.style.display = 'none';
                    iconPause.style.display = 'block';
                    animateVisualizer();
                } else {
                    audioPlayer.pause();
                    iconPlay.style.display = 'block';
                    iconPause.style.display = 'none';
                    resetVisualizer();
                }
            } else if (activeSource === 'youtube') {
                if (!ytPlayer || !isYtReady) return;
                const state = ytPlayer.getPlayerState();
                if (state === 1) { // playing
                    ytPlayer.pauseVideo();
                } else {
                    ytPlayer.playVideo();
                }
            }
        });

        let isDraggingProgress = false;

        audioPlayer.addEventListener('timeupdate', function () {
            if (activeSource !== 'local') return;
            // Jangan update dari pemutar otomatis jika user sedang menggeser bar secara manual
            if (audioPlayer.duration && !isDraggingProgress) {
                const pct = (audioPlayer.currentTime / audioPlayer.duration) * 100;
                musicProgressFill.style.width = pct + '%';
                localStorage.setItem('musicProgress', audioPlayer.currentTime); // Simpan progress
            }
        });

        audioPlayer.addEventListener('ended', function () {
            if (activeSource !== 'local') return;
            iconPlay.style.display = 'block';
            iconPause.style.display = 'none';
            resetVisualizer();
            musicProgressFill.style.width = '0%';
        });

        // Fungsi untuk menggeser lagu sesuai posisi kursor/jari
        function updateProgress(e) {
            if (activeSource === 'local') {
                if (!audioPlayer.src || !audioPlayer.duration) return;
                const rect = musicProgressBar.getBoundingClientRect();
                let clickX = e.clientX - rect.left;
                clickX = Math.max(0, Math.min(clickX, rect.width));
                const pct = clickX / rect.width;

                audioPlayer.currentTime = pct * audioPlayer.duration;
                musicProgressFill.style.width = (pct * 100) + '%';
                localStorage.setItem('musicProgress', audioPlayer.currentTime);
            } else if (activeSource === 'youtube') {
                if (!ytPlayer || !isYtReady) return;
                const duration = ytPlayer.getDuration();
                if (!duration) return;
                const rect = musicProgressBar.getBoundingClientRect();
                let clickX = e.clientX - rect.left;
                clickX = Math.max(0, Math.min(clickX, rect.width));
                const pct = clickX / rect.width;

                const seekToSeconds = pct * duration;
                ytPlayer.seekTo(seekToSeconds, true);
                musicProgressFill.style.width = (pct * 100) + '%';
                localStorage.setItem('musicProgressYt', seekToSeconds);
            }
        }

        // Pointer events bekerja baik untuk Mouse maupun Layar Sentuh (Mobile)
        musicProgressBar.addEventListener('pointerdown', function (e) {
            isDraggingProgress = true;
            musicProgressBar.setPointerCapture(e.pointerId);
            updateProgress(e);
            e.preventDefault();
        });

        musicProgressBar.addEventListener('pointermove', function (e) {
            if (isDraggingProgress) {
                updateProgress(e);
            }
        });

        musicProgressBar.addEventListener('pointerup', function (e) {
            isDraggingProgress = false;
            musicProgressBar.releasePointerCapture(e.pointerId);
        });

        musicProgressBar.addEventListener('pointercancel', function (e) {
            isDraggingProgress = false;
            musicProgressBar.releasePointerCapture(e.pointerId);
        });
    }
    // Hero Wallpaper Scroller (Static) + Ad Overlay Cycling
    const heroDots = document.querySelectorAll('.hero-dot');
    const heroCard = document.querySelector('.hero-card');
    const bg1 = document.getElementById('heroBg1');
    const bg2 = document.getElementById('heroBg2');
    const bg3 = document.getElementById('heroBg3'); // Ad overlay layer
    
    if (heroDots.length > 0 && heroCard) {
        let currentBgIndex = 0;
        let activeBgLayer = 1;

        // ===== Wallpaper (Static, User-controlled) =====
        function setHeroBackground(index, isInitial = false) {
            heroDots.forEach(d => d.classList.remove('active'));
            heroDots[index].classList.add('active');
            
            const bgImage = heroDots[index].getAttribute('data-bg');
            if (bgImage) {
                if (bg1 && bg2) {
                    if (isInitial) {
                        bg1.style.backgroundImage = `url('${bgImage}')`;
                        bg1.style.opacity = '1';
                        bg2.style.opacity = '0';
                        activeBgLayer = 1;
                    } else {
                        if (activeBgLayer === 1) {
                            bg2.style.backgroundImage = `url('${bgImage}')`;
                            bg2.style.opacity = '1';
                            bg1.style.opacity = '0';
                            activeBgLayer = 2;
                        } else {
                            bg1.style.backgroundImage = `url('${bgImage}')`;
                            bg1.style.opacity = '1';
                            bg2.style.opacity = '0';
                            activeBgLayer = 1;
                        }
                    }
                } else {
                    heroCard.style.backgroundImage = `url('${bgImage}')`;
                }
            }
            currentBgIndex = index;
            localStorage.setItem('heroBgIndex', index);
        }

        // Initialize wallpaper from localStorage
        const savedIndex = localStorage.getItem('heroBgIndex');
        if (savedIndex !== null && !isNaN(savedIndex) && savedIndex >= 0 && savedIndex < heroDots.length) {
            setHeroBackground(parseInt(savedIndex), true);
        } else {
            setHeroBackground(0, true);
        }

        heroDots.forEach((dot, index) => {
            dot.addEventListener('click', function() {
                setHeroBackground(index);
            });
        });

        // ===== Ad Overlay Cycling =====
        const adImages = window.__heroAdImages || [];
        let currentAdIndex = 0;
        let adTimer = null;
        let isAdVisible = false;

        const heroOverlay = heroCard.querySelector('.hero-overlay');

        function showAd(index) {
            if (!bg3 || adImages.length === 0) return;
            bg3.style.backgroundImage = `url('${adImages[index]}')`;
            bg3.style.opacity = '1';
            isAdVisible = true;
            
            // Fade out wallpaper layers
            if (bg1) bg1.style.opacity = '0';
            if (bg2) bg2.style.opacity = '0';

            // Hide overlay content for clean ad display
            if (heroOverlay) {
                heroOverlay.style.transition = 'opacity 0.8s cubic-bezier(0.25, 1, 0.5, 1)';
                heroOverlay.style.opacity = '0';
                heroOverlay.style.pointerEvents = 'none';
            }
        }

        function hideAd() {
            if (!bg3) return;
            bg3.style.opacity = '0';
            isAdVisible = false;
            
            // Restore wallpaper layers opacity based on active layer
            if (bg1 && bg2) {
                if (activeBgLayer === 1) {
                    bg1.style.opacity = '1';
                    bg2.style.opacity = '0';
                } else {
                    bg2.style.opacity = '1';
                    bg1.style.opacity = '0';
                }
            }

            // Restore overlay content
            if (heroOverlay) {
                heroOverlay.style.opacity = '1';
                heroOverlay.style.pointerEvents = '';
            }
        }

        function startAdCycle() {
            if (adImages.length === 0) return;
            // Show first ad after 10s, then cycle
            adTimer = setInterval(function() {
                if (isAdVisible) {
                    // Currently showing ad -> fade out back to wallpaper
                    hideAd();
                    // Move to next ad for the next show
                    currentAdIndex = (currentAdIndex + 1) % adImages.length;
                } else {
                    // Currently showing wallpaper -> fade in ad
                    showAd(currentAdIndex);
                }
            }, 10000);
        }

        function resetAdCycle() {
            // On manual wallpaper interaction, hide ad and restart cycle
            if (adTimer) clearInterval(adTimer);
            hideAd();
            currentAdIndex = 0;
            startAdCycle();
        }

        // Start ad cycling on load
        startAdCycle();

        // ===== Swipe & Drag Support =====
        let startX = 0;
        let isDraggingHero = false;

        heroCard.addEventListener('touchstart', e => {
            startX = e.touches[0].clientX;
            isDraggingHero = true;
        }, {passive: true});

        heroCard.addEventListener('touchmove', e => {
            if (!isDraggingHero) return;
            const currentX = e.touches[0].clientX;
            const diffX = startX - currentX;

            if (Math.abs(diffX) > 50) {
                if (diffX > 0) {
                    if (currentBgIndex < heroDots.length - 1) setHeroBackground(currentBgIndex + 1);
                } else {
                    if (currentBgIndex > 0) setHeroBackground(currentBgIndex - 1);
                }
                isDraggingHero = false;
                resetAdCycle();
            }
        }, {passive: true});

        heroCard.addEventListener('touchend', () => {
            isDraggingHero = false;
        });

        heroCard.addEventListener('mousedown', e => {
            startX = e.clientX;
            isDraggingHero = true;
        });

        heroCard.addEventListener('mousemove', e => {
            if (!isDraggingHero) return;
            const currentX = e.clientX;
            const diffX = startX - currentX;

            if (Math.abs(diffX) > 50) { 
                if (diffX > 0) {
                    if (currentBgIndex < heroDots.length - 1) setHeroBackground(currentBgIndex + 1);
                } else {
                    if (currentBgIndex > 0) setHeroBackground(currentBgIndex - 1);
                }
                isDraggingHero = false;
                resetAdCycle();
            }
        });

        heroCard.addEventListener('mouseup', () => isDraggingHero = false);
        heroCard.addEventListener('mouseleave', () => isDraggingHero = false);
    }

    // Season Timer Logic
    function updateSeasonTimer() {
        const seasonTimers = document.querySelector('.season-timers');
        if (!seasonTimers) return;

        const seasonEndStr = seasonTimers.getAttribute('data-season-end');
        if (!seasonEndStr) return;

        const now = new Date();
        const seasonEnd = new Date(seasonEndStr);
        const timeDiff = seasonEnd - now;

        // Calculate total days in season for bar width proportions
        const totalDaysInMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0).getDate();

        if (timeDiff > 0) {
            const daysRemaining = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
            const hoursRemaining = Math.floor((timeDiff / (1000 * 60 * 60)) % 24);
            const minutesRemaining = Math.floor((timeDiff / 1000 / 60) % 60);
            const secondsRemaining = Math.floor((timeDiff / 1000) % 60);

            const elDays = document.getElementById('seasonDays');
            const elHours = document.getElementById('seasonHours');
            const elMinutes = document.getElementById('seasonMinutes');
            const elSeconds = document.getElementById('seasonSeconds');

            if (elDays) {
                elDays.textContent = daysRemaining;
                elDays.style.width = daysRemaining === 0 ? '44px' : `calc(44px + (100% - 44px) * ${daysRemaining / totalDaysInMonth})`;
            }
            if (elHours) {
                elHours.textContent = hoursRemaining;
                elHours.style.width = hoursRemaining === 0 ? '44px' : `calc(44px + (100% - 44px) * ${hoursRemaining / 24})`;
            }
            if (elMinutes) {
                elMinutes.textContent = minutesRemaining;
                elMinutes.style.width = minutesRemaining === 0 ? '44px' : `calc(44px + (100% - 44px) * ${minutesRemaining / 60})`;
            }
            if (elSeconds) {
                elSeconds.textContent = secondsRemaining;
                elSeconds.style.width = secondsRemaining === 0 ? '44px' : `calc(44px + (100% - 44px) * ${secondsRemaining / 60})`;
            }
        } else {
            // Season ended! Reload to trigger server-side season transition & achievement distribution
            if (!window._seasonReloading) {
                window._seasonReloading = true;
                window.location.reload();
            }
        }
    }
    updateSeasonTimer();
    setInterval(updateSeasonTimer, 1000);

    // ==================== FRIENDSHIP & LEADERBOARD SYSTEM ====================
    const friendListContainer = document.getElementById('friendListContainer');
    const friendSearchInput = document.getElementById('friendSearchInput');

    function updateFriendshipUIAfterChange(friendId, status, friendsCount) {
        // Update account panel friend count
        const countEl = document.getElementById('accountFriendsCount');
        if (countEl && friendsCount !== undefined) {
            countEl.textContent = friendsCount;
        }

        // Update all occurrences of the toggle button for this friend in DOM
        const allRelatedBtns = document.querySelectorAll(`.toggle-friend-btn[data-id="${friendId}"]`);
        allRelatedBtns.forEach(b => {
            // Reset status classes
            b.classList.remove('friends', 'pending-sent', 'pending-received');
            
            const isLeaderboardBtn = b.classList.contains('lb-action');
            
            if (status === 'friends') {
                b.classList.add('friends');
                if (isLeaderboardBtn) {
                    b.innerHTML = 'Teman';
                } else {
                    b.innerHTML = `
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>`;
                }
            } else if (status === 'pending_sent') {
                b.classList.add('pending-sent');
                if (isLeaderboardBtn) {
                    b.innerHTML = 'Pending';
                } else {
                    b.innerHTML = `
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>`;
                }
            } else if (status === 'pending_received') {
                b.classList.add('pending-received');
                if (isLeaderboardBtn) {
                    b.innerHTML = 'Terima';
                } else {
                    b.innerHTML = `
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>`;
                }
            } else { // status === 'none'
                if (isLeaderboardBtn) {
                    b.innerHTML = '+ Tambah';
                } else {
                    b.innerHTML = `
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="12" y1="5" x2="12" y2="19" />
                            <line x1="5" y1="12" x2="19" y2="12" />
                        </svg>`;
                }
            }
        });

        // Reload friend list if search is empty to reflect current friends / requests
        const searchInput = document.getElementById('friendSearchInput');
        const query = searchInput ? searchInput.value : '';
        if (query.trim() === '') {
            // Immediately remove the request card from sidebar DOM (optimistic UI)
            const sidebarCard = friendListContainer
                ? friendListContainer.querySelector(`.accept-request-btn[data-id="${friendId}"], .decline-request-btn[data-id="${friendId}"]`)
                : null;
            if (sidebarCard) {
                const parentCard = sidebarCard.closest('.friend-card, .pending-request-card');
                if (parentCard) {
                    parentCard.style.transition = 'opacity 0.2s, transform 0.2s';
                    parentCard.style.opacity = '0';
                    parentCard.style.transform = 'translateX(6px)';
                    setTimeout(() => {
                        if (parentCard.parentNode) parentCard.remove();
                    }, 200);
                }
            }
            loadFriendList();
        }

        // Refresh Friend Hub if it is open
        const friendHubModal = document.getElementById('friendHubModal');
        if (friendHubModal && friendHubModal.classList.contains('show')) {
            // Optimistically remove the request card from cache so it disappears immediately
            const prevLen = hubRequestsCache.length;
            hubRequestsCache = hubRequestsCache.filter(u => String(u.id) !== String(friendId));

            if (hubRequestsCache.length !== prevLen) {
                // The person was in the requests list — update badge and re-render requests tab now
                updateHubBadge(hubRequestsCache.length);

                // If they were accepted (now friends), add them to the friends cache immediately
                if (status === 'friends') {
                    const alreadyInFriends = hubFriendsCache.some(u => String(u.id) === String(friendId));
                    if (!alreadyInFriends) {
                        // Get their info from the card that was just removed (find in DOM before it's re-rendered)
                        const card = document.querySelector(`#friendHubTabContent .friend-hub-card`);
                        const name = card ? card.querySelector('.friend-name')?.textContent?.trim() : '';
                        const tier = card ? card.querySelector('.friend-exp span:last-child')?.textContent?.trim() : '';
                        if (name) {
                            hubFriendsCache.push({
                                id: friendId,
                                name,
                                tier: tier || '',
                                exp: 0,
                                avatar: `https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=random`
                            });
                        }
                    }
                    // Re-render friends left list
                    const hubSearch = document.getElementById('friendHubSearchInput');
                    renderHubFriends(hubSearch ? hubSearch.value : '');
                }

                // Re-render the tab immediately (no network wait)
                renderHubActiveTab();
            }

            // Background-refresh for full accuracy after a small delay
            setTimeout(() => {
                if (typeof window.refreshFriendHub === 'function') {
                    window.refreshFriendHub();
                }
            }, 300);
        }
    }
    window.updateFriendshipUIAfterChange = updateFriendshipUIAfterChange;

    function loadFriendList(query = '') {
        if (!friendListContainer) return;

        const endpoint = query.trim() === '' ? '/api/friends' : `/api/friends/search?query=${encodeURIComponent(query)}`;

        friendListContainer.innerHTML = `
            <div style="padding: 2rem 0; text-align: center; color: #615f66;">
                <div style="font-size: 0.85rem;">Memuat teman...</div>
            </div>`;

        fetch(endpoint)
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                friendListContainer.innerHTML = `<div style="padding: 1.5rem; text-align: center; color: #ef4444; font-size: 0.85rem;">Gagal memuat teman</div>`;
                return;
            }

            if (query.trim() === '') {
                const friendsCount = data.friends ? data.friends.length : 0;
                const requestsCount = data.requests ? data.requests.length : 0;

                if (friendsCount === 0 && requestsCount === 0) {
                    friendListContainer.innerHTML = `
                        <div style="padding: 2.5rem 1.25rem; text-align: center; color: #615f66; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                            <svg viewBox="0 0 24 24" width="36" height="36" stroke="rgba(255,255,255,0.08)" stroke-width="1.8" fill="none">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                <circle cx="9" cy="7" r="4" />
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                            </svg>
                            <div style="font-size: 0.85rem; font-weight: 500;">Belum ada teman</div>
                            <div style="font-size: 0.75rem; color: #8e8c94; max-width: 200px;">Gunakan pencarian di atas untuk berteman!</div>
                        </div>`;
                    return;
                }

                let html = '';
                if (requestsCount > 0) {
                    html += `
                        <div class="friend-section-title" style="color: #ea580c; font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin: 0.5rem 0 0.5rem 0.25rem; display: flex; align-items: center; gap: 0.4rem;">
                            <i class='bx bx-user-plus' style='font-size: 1rem;'></i> Permintaan Pertemanan (${requestsCount})
                        </div>`;
                    data.requests.forEach(u => {
                        html += `
                            <div class="friend-card pending-request-card" style="display: flex; align-items: center; gap: 0.85rem; padding: 0.75rem 1rem; background: rgba(234, 88, 12, 0.04); border: 1.5px dashed rgba(234, 88, 12, 0.25); border-radius: 16px; margin-bottom: 0.5rem;">
                                <img src="${u.avatar}" alt="Avatar" class="friend-avatar" style="width: 40px; height: 40px; border-radius: 12px; object-fit: cover;">
                                <div class="friend-info" style="flex: 1; min-width: 0;">
                                    <div class="friend-name" style="color: white; font-weight: 600; font-size: 0.88rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${u.name}</div>
                                    <div class="friend-exp" style="color: #8e8c94; font-size: 0.75rem; display: flex; align-items: center; gap: 0.4rem; margin-top: 0.1rem;">
                                        <span>${u.exp.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")} EXP</span>
                                        <span style="display: inline-block; width: 3px; height: 3px; border-radius: 50%; background: #615f66;"></span>
                                        <span style="color: #8b5cf6; font-weight: 600;">${u.tier}</span>
                                    </div>
                                </div>
                                <div class="friend-actions" style="display: flex; gap: 0.4rem;">
                                    <button class="accept-request-btn" data-id="${u.id}" style="padding: 0.4rem 0.75rem; font-size: 0.75rem; font-weight: 700; border-radius: 20px; border: none; background: #0d9488; color: white; cursor: pointer; display: flex; align-items: center; gap: 0.25rem;">
                                        <i class='bx bx-check' style="font-size: 0.95rem;"></i> Terima
                                    </button>
                                    <button class="decline-request-btn" data-id="${u.id}" style="padding: 0.4rem 0.75rem; font-size: 0.75rem; font-weight: 700; border-radius: 20px; border: 1px solid rgba(255,255,255,0.08); background: rgba(255,255,255,0.03); color: #e11d48; cursor: pointer; display: flex; align-items: center; gap: 0.25rem;">
                                        <i class='bx bx-x' style="font-size: 0.95rem;"></i> Tolak
                                    </button>
                                </div>
                            </div>`;
                    });
                }

                if (friendsCount > 0) {
                    html += `
                        <div class="friend-section-title" style="color: #8e8c94; font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin: 1rem 0 0.5rem 0.25rem; display: flex; align-items: center; gap: 0.4rem;">
                            <i class='bx bx-group' style='font-size: 1rem;'></i> Daftar Teman (${friendsCount})
                        </div>`;
                    data.friends.forEach(u => {
                        html += `
                            <div class="friend-card" style="display: flex; align-items: center; gap: 0.85rem; padding: 0.75rem 1rem; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.04); border-radius: 16px; margin-bottom: 0.5rem; transition: background 0.2s;">
                                <img src="${u.avatar}" alt="Avatar" class="friend-avatar" style="width: 40px; height: 40px; border-radius: 12px; object-fit: cover;">
                                <div class="friend-info" style="flex: 1; min-width: 0;">
                                    <div class="friend-name" style="color: white; font-weight: 600; font-size: 0.88rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${u.name}</div>
                                    <div class="friend-exp" style="color: #8e8c94; font-size: 0.75rem; display: flex; align-items: center; gap: 0.4rem; margin-top: 0.1rem;">
                                        <span>${u.exp.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")} EXP</span>
                                        <span style="display: inline-block; width: 3px; height: 3px; border-radius: 50%; background: #615f66;"></span>
                                        <span style="color: #8b5cf6; font-weight: 600;">${u.tier}</span>
                                    </div>
                                </div>
                                <button class="friend-add-btn toggle-friend-btn friends" data-id="${u.id}" style="width: 32px; height: 32px; border-radius: 50%; border: none; background: #2b272f; color: #34d399; display: flex; align-items: center; justify-content: center; transition: 0.2s; cursor: pointer;">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="20 6 9 17 4 12" />
                                    </svg>
                                </button>
                            </div>`;
                    });
                }

                friendListContainer.innerHTML = html;
            } else {
                if (data.users.length === 0) {
                    friendListContainer.innerHTML = `
                        <div style="padding: 2rem 1.5rem; text-align: center; color: #615f66;">
                            <div style="font-size: 0.85rem; font-weight: 500;">Tidak ditemukan "${query}"</div>
                        </div>`;
                    return;
                }

                let html = '';
                data.users.forEach(u => {
                    const status = u.friendship_status;
                    let actionHtml = '';

                    if (status === 'friends') {
                        actionHtml = `
                            <button class="friend-add-btn toggle-friend-btn friends" data-id="${u.id}" style="width: 32px; height: 32px; border-radius: 50%; border: none; background: #2b272f; color: #34d399; display: flex; align-items: center; justify-content: center; transition: 0.2s; cursor: pointer;" title="Hapus Teman">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                            </button>`;
                    } else if (status === 'pending_sent') {
                        actionHtml = `
                            <button class="friend-add-btn toggle-friend-btn pending-sent" data-id="${u.id}" style="width: 32px; height: 32px; border-radius: 50%; border: 1px solid rgba(249, 115, 22, 0.4); background: rgba(249, 115, 22, 0.1); color: #f97316; display: flex; align-items: center; justify-content: center; transition: 0.2s; cursor: pointer;" title="Batalkan Permintaan">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <polyline points="12 6 12 12 16 14" />
                                </svg>
                            </button>`;
                    } else if (status === 'pending_received') {
                        actionHtml = `
                            <div style="display: flex; gap: 0.3rem;">
                                <button class="accept-request-btn" data-id="${u.id}" style="width: 28px; height: 28px; border-radius: 50%; border: none; background: #0d9488; color: white; display: flex; align-items: center; justify-content: center; transition: 0.2s; cursor: pointer;" title="Terima permintaan">
                                    <i class='bx bx-check' style="font-size: 1.1rem;"></i>
                                </button>
                                <button class="decline-request-btn" data-id="${u.id}" style="width: 28px; height: 28px; border-radius: 50%; border: 1px solid rgba(255,255,255,0.08); background: rgba(255,255,255,0.03); color: #e11d48; display: flex; align-items: center; justify-content: center; transition: 0.2s; cursor: pointer;" title="Tolak permintaan">
                                    <i class='bx bx-x' style="font-size: 1.1rem;"></i>
                                </button>
                            </div>`;
                    } else {
                        actionHtml = `
                            <button class="friend-add-btn toggle-friend-btn" data-id="${u.id}" style="width: 32px; height: 32px; border-radius: 50%; border: 1px solid rgba(255,255,255,0.1); background: transparent; color: white; display: flex; align-items: center; justify-content: center; transition: 0.2s; cursor: pointer;" title="Tambah Teman">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="12" y1="5" x2="12" y2="19" />
                                    <line x1="5" y1="12" x2="19" y2="12" />
                                </svg>
                            </button>`;
                    }

                    html += `
                        <div class="friend-card" style="display: flex; align-items: center; gap: 0.85rem; padding: 0.75rem 1rem; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.04); border-radius: 16px; margin-bottom: 0.5rem; transition: background 0.2s;">
                            <img src="${u.avatar}" alt="Avatar" class="friend-avatar" style="width: 40px; height: 40px; border-radius: 12px; object-fit: cover;">
                            <div class="friend-info" style="flex: 1; min-width: 0;">
                                <div class="friend-name" style="color: white; font-weight: 600; font-size: 0.88rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">${u.name}</div>
                                <div class="friend-exp" style="color: #8e8c94; font-size: 0.75rem; display: flex; align-items: center; gap: 0.4rem; margin-top: 0.1rem;">
                                    <span>${u.exp.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")} EXP</span>
                                    <span style="display: inline-block; width: 3px; height: 3px; border-radius: 50%; background: #615f66;"></span>
                                    <span style="color: #8b5cf6; font-weight: 600;">${u.tier}</span>
                                </div>
                            </div>
                            ${actionHtml}
                        </div>`;
                });
                friendListContainer.innerHTML = html;
            }
        })
        .catch(err => {
            console.error('Error loading friends list:', err);
            friendListContainer.innerHTML = `<div style="padding: 1.5rem; text-align: center; color: #ef4444; font-size: 0.85rem;">Gagal memuat teman</div>`;
        });
    }

    // Expose load function globally
    window.loadFriendList = loadFriendList;

    // Load initial list on open
    const btnFriend = document.getElementById('btnFriend');
    if (btnFriend) {
        btnFriend.addEventListener('click', () => {
            loadFriendList();
        });
    }

    // Restore friends if they were active on page load
    if (btnFriend && btnFriend.classList.contains('active')) {
        loadFriendList();
    }

    // Search bar handler with debounce
    if (friendSearchInput) {
        let debounceTimer;
        friendSearchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const query = this.value;
            debounceTimer = setTimeout(() => {
                loadFriendList(query);
            }, 300);
        });
    }



    // ==================== FRIENDSHIP HUB OVERLAY MODAL ====================
    let hubFriendsCache = [];
    let hubRequestsCache = [];
    let hubActiveTab = 'requests'; // 'requests' or 'find'

    function fetchFriendHubData(onSuccess = null) {
        fetch('/api/friends')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                hubFriendsCache = data.friends || [];
                hubRequestsCache = data.requests || [];
                // Update badge count
                const reqCount = hubRequestsCache.length;
                updateHubBadge(reqCount);
                
                // Render list of friends
                const hubSearch = document.getElementById('friendHubSearchInput');
                const searchQuery = hubSearch ? hubSearch.value : '';
                renderHubFriends(searchQuery);
                
                // Render active tab content
                renderHubActiveTab();

                if (typeof onSuccess === 'function') onSuccess();
            }
        })
        .catch(err => {
            console.error('Error fetching Friendship Hub data:', err);
        });
    }

    function renderHubFriends(query = '') {
        const listContainer = document.getElementById('friendHubList');
        if (!listContainer) return;

        const filtered = hubFriendsCache.filter(u => 
            u.name.toLowerCase().includes(query.toLowerCase())
        );

        if (filtered.length === 0) {
            listContainer.innerHTML = `
                <div class="friend-hub-empty">
                    <i class='bx bx-user-voice'></i>
                    <span>Tidak ada teman ditemukan</span>
                    <p>Gunakan tab Cari Orang Lain di sebelah kanan untuk menambahkan teman baru.</p>
                </div>`;
            return;
        }

        let html = '';
        filtered.forEach(u => {
            html += `
                <div class="friend-hub-card">
                    <img src="${u.avatar}" alt="Avatar" class="friend-avatar">
                    <div class="friend-info">
                        <div class="friend-name">${u.name}</div>
                        <div class="friend-exp">
                            <span>${u.exp.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")} EXP</span>
                            <span style="display: inline-block; width: 3px; height: 3px; border-radius: 50%; background: #615f66;"></span>
                            <span style="color: #8b5cf6; font-weight: 600;">${u.tier}</span>
                        </div>
                    </div>
                    <button class="friend-add-btn toggle-friend-btn friends" data-id="${u.id}" style="width: 36px; height: 36px; border-radius: 50%; border: none; background: #2b272f; color: #34d399; display: flex; align-items: center; justify-content: center; transition: 0.2s; cursor: pointer;" title="Hapus Teman">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12" />
                        </svg>
                    </button>
                </div>`;
        });
        listContainer.innerHTML = html;
    }

    function updateHubBadge(count) {
        const badge = document.getElementById('friendHubRequestBadge');
        if (badge) {
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }

        const friendBtnBadge = document.getElementById('friendBtnBadge');
        if (friendBtnBadge) {
            friendBtnBadge.style.display = count > 0 ? 'block' : 'none';
        }
    }

    function renderHubActiveTab() {
        const tabContent = document.getElementById('friendHubTabContent');
        if (!tabContent) return;

        if (hubActiveTab === 'requests') {
            if (hubRequestsCache.length === 0) {
                tabContent.innerHTML = `
                    <div class="friend-hub-empty">
                        <i class='bx bx-user-check'></i>
                        <span>Tidak ada permintaan</span>
                        <p>Semua permintaan pertemanan sudah ditangani.</p>
                    </div>`;
                return;
            }

            let html = '';
            hubRequestsCache.forEach((u, index) => {
                html += `
                    <div class="friend-hub-card" style="animation-delay: ${index * 0.05}s;">
                        <img src="${u.avatar}" alt="Avatar" class="friend-avatar">
                        <div class="friend-info">
                            <div class="friend-name">${u.name}</div>
                            <div class="friend-exp">
                                <span>${u.exp.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")} EXP</span>
                                <span style="display: inline-block; width: 3px; height: 3px; border-radius: 50%; background: #615f66;"></span>
                                <span style="color: #8b5cf6; font-weight: 600;">${u.tier}</span>
                            </div>
                        </div>
                        <div style="display: flex; gap: 0.4rem;">
                            <button class="accept-request-btn" data-id="${u.id}" style="padding: 0.45rem 0.85rem; font-size: 0.78rem; font-weight: 700; border-radius: 20px; border: none; background: #0d9488; color: white; cursor: pointer; display: flex; align-items: center; gap: 0.25rem; transition: 0.2s;">
                                <i class='bx bx-check' style="font-size: 1rem;"></i> Terima
                            </button>
                            <button class="decline-request-btn" data-id="${u.id}" style="padding: 0.45rem 0.85rem; font-size: 0.78rem; font-weight: 700; border-radius: 20px; border: 1px solid rgba(255,255,255,0.08); background: rgba(255,255,255,0.03); color: #e11d48; cursor: pointer; display: flex; align-items: center; gap: 0.25rem; transition: 0.2s;">
                                <i class='bx bx-x' style="font-size: 1rem;"></i> Tolak
                            </button>
                        </div>
                    </div>`;
            });
            tabContent.innerHTML = html;
        } else if (hubActiveTab === 'find') {
            tabContent.innerHTML = `
                <div class="friend-hub-search" style="margin-top: 0; margin-bottom: 0.75rem;">
                    <i class='bx bx-search'></i>
                    <input type="text" id="findFriendsSearchInput" placeholder="Cari nama atau email...">
                </div>
                <div class="friend-hub-tab-scroll" id="findFriendsResults" style="flex: 1; display: flex; flex-direction: column; gap: 0.6rem; overflow-y: auto;">
                    <div class="friend-hub-empty" style="padding: 2rem 1rem;">
                        <i class='bx bx-search-alt'></i>
                        <span>Cari Teman Baru</span>
                        <p>Ketikkan nama di atas untuk mencari pengguna lain.</p>
                    </div>
                </div>`;

            // Wire up input listener for the global user search
            const findInput = document.getElementById('findFriendsSearchInput');
            if (findInput) {
                // Restore search value if we had one
                if (window.hubGlobalSearchQuery) {
                    findInput.value = window.hubGlobalSearchQuery;
                    performHubGlobalSearch(window.hubGlobalSearchQuery);
                }

                let debounceTimer;
                findInput.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    const query = this.value;
                    window.hubGlobalSearchQuery = query;
                    debounceTimer = setTimeout(() => {
                        performHubGlobalSearch(query);
                    }, 350);
                });
            }
        }
    }

    function performHubGlobalSearch(query) {
        const resultsContainer = document.getElementById('findFriendsResults');
        if (!resultsContainer) return;

        if (query.trim() === '') {
            resultsContainer.innerHTML = `
                <div class="friend-hub-empty" style="padding: 2rem 1rem;">
                    <i class='bx bx-search-alt'></i>
                    <span>Cari Teman Baru</span>
                    <p>Ketikkan nama di atas untuk mencari pengguna lain.</p>
                </div>`;
            return;
        }

        resultsContainer.innerHTML = `
            <div style="padding: 2rem; text-align: center; color: #615f66;">
                <div style="font-size: 0.85rem;">Mencari pengguna...</div>
            </div>`;

        fetch(`/api/friends/search?query=${encodeURIComponent(query)}`)
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                resultsContainer.innerHTML = `<div style="padding: 1.5rem; text-align: center; color: #ef4444; font-size: 0.85rem;">Gagal memuat hasil</div>`;
                return;
            }

            if (data.users.length === 0) {
                resultsContainer.innerHTML = `
                    <div class="friend-hub-empty" style="padding: 2rem 1rem;">
                        <i class='bx bx-block'></i>
                        <span>Pengguna tidak ditemukan</span>
                        <p>Tidak ditemukan pengguna dengan nama "${query}".</p>
                    </div>`;
                return;
            }

            let html = '';
            data.users.forEach(u => {
                const status = u.friendship_status;
                let actionHtml = '';

                if (status === 'friends') {
                    actionHtml = `
                        <button class="friend-add-btn toggle-friend-btn friends" data-id="${u.id}" style="width: 36px; height: 36px; border-radius: 50%; border: none; background: #2b272f; color: #34d399; display: flex; align-items: center; justify-content: center; transition: 0.2s; cursor: pointer;" title="Hapus Teman">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </button>`;
                } else if (status === 'pending_sent') {
                    actionHtml = `
                        <button class="friend-add-btn toggle-friend-btn pending-sent" data-id="${u.id}" style="width: 36px; height: 36px; border-radius: 50%; border: 1px solid rgba(249, 115, 22, 0.4); background: rgba(249, 115, 22, 0.1); color: #f97316; display: flex; align-items: center; justify-content: center; transition: 0.2s; cursor: pointer;" title="Batalkan Permintaan">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                        </button>`;
                } else if (status === 'pending_received') {
                    actionHtml = `
                        <div style="display: flex; gap: 0.3rem;">
                            <button class="accept-request-btn" data-id="${u.id}" style="width: 32px; height: 32px; border-radius: 50%; border: none; background: #0d9488; color: white; display: flex; align-items: center; justify-content: center; transition: 0.2s; cursor: pointer;" title="Terima permintaan">
                                <i class='bx bx-check' style="font-size: 1.25rem;"></i>
                            </button>
                            <button class="decline-request-btn" data-id="${u.id}" style="width: 32px; height: 32px; border-radius: 50%; border: 1px solid rgba(255,255,255,0.08); background: rgba(255,255,255,0.03); color: #e11d48; display: flex; align-items: center; justify-content: center; transition: 0.2s; cursor: pointer;" title="Tolak permintaan">
                                <i class='bx bx-x' style="font-size: 1.25rem;"></i>
                            </button>
                        </div>`;
                } else {
                    actionHtml = `
                        <button class="friend-add-btn toggle-friend-btn" data-id="${u.id}" style="width: 36px; height: 36px; border-radius: 50%; border: 1px solid rgba(255, 255, 255, 0.15); background: transparent; color: white; display: flex; align-items: center; justify-content: center; transition: 0.2s; cursor: pointer;" title="Tambah Teman">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19" />
                                <line x1="5" y1="12" x2="19" y2="12" />
                            </svg>
                        </button>`;
                }

                html += `
                    <div class="friend-hub-card">
                        <img src="${u.avatar}" alt="Avatar" class="friend-avatar">
                        <div class="friend-info">
                            <div class="friend-name">${u.name}</div>
                            <div class="friend-exp">
                                <span>${u.exp.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")} EXP</span>
                                <span style="display: inline-block; width: 3px; height: 3px; border-radius: 50%; background: #615f66;"></span>
                                <span style="color: #8b5cf6; font-weight: 600;">${u.tier}</span>
                            </div>
                        </div>
                        ${actionHtml}
                    </div>`;
            });
            resultsContainer.innerHTML = html;
        })
        .catch(err => {
            console.error('Error searching friends in hub:', err);
            resultsContainer.innerHTML = `<div style="padding: 1.5rem; text-align: center; color: #ef4444; font-size: 0.85rem;">Gagal memuat hasil</div>`;
        });
    }

    // Modal control
    const btnFriendExpand = document.getElementById('btnFriendExpand');
    const friendHubModal = document.getElementById('friendHubModal');
    const btnFriendHubClose = document.getElementById('btnFriendHubClose');

    function openFriendHubModal() {
        if (!friendHubModal) return;
        
        // Close menuPanel first
        const menuPanel = document.getElementById('menuPanel');
        if (menuPanel) {
            menuPanel.classList.remove('open');
        }

        // Show Friend Hub Modal
        friendHubModal.style.display = 'flex';
        // Force reflow
        void friendHubModal.offsetWidth;
        friendHubModal.classList.add('show');

        // Fetch data and render
        fetchFriendHubData();
    }

    function closeFriendHubModal() {
        if (!friendHubModal) return;
        friendHubModal.classList.remove('show');
        
        // Hide after transition
        setTimeout(() => {
            friendHubModal.style.display = 'none';
        }, 400); // matches CSS 0.4s
    }

    if (btnFriendExpand) {
        btnFriendExpand.addEventListener('click', openFriendHubModal);
    }

    if (btnFriendHubClose) {
        btnFriendHubClose.addEventListener('click', closeFriendHubModal);
    }

    // Escape listener
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && friendHubModal && friendHubModal.classList.contains('show')) {
            closeFriendHubModal();
        }
    });

    // Background overlay click close
    if (friendHubModal) {
        friendHubModal.addEventListener('click', function(e) {
            if (e.target === friendHubModal) {
                closeFriendHubModal();
            }
        });
    }

    // Wire up search input for the left panel
    const hubSearchInput = document.getElementById('friendHubSearchInput');
    if (hubSearchInput) {
        hubSearchInput.addEventListener('input', function() {
            renderHubFriends(this.value);
        });
    }

    // Wire up tabs
    const tabRequests = document.getElementById('tabFriendRequests');
    const tabFind = document.getElementById('tabFindFriends');

    if (tabRequests && tabFind) {
        tabRequests.addEventListener('click', () => {
            tabFind.classList.remove('active');
            tabRequests.classList.add('active');
            hubActiveTab = 'requests';
            renderHubActiveTab();
        });

        tabFind.addEventListener('click', () => {
            tabRequests.classList.remove('active');
            tabFind.classList.add('active');
            hubActiveTab = 'find';
            renderHubActiveTab();
        });
    }

    // Expose refresh function globally
    window.refreshFriendHub = function(onSuccess = null) {
        fetchFriendHubData(() => {
            // Also refresh global search results if active tab is "find" and there's a search query
            if (hubActiveTab === 'find' && window.hubGlobalSearchQuery) {
                performHubGlobalSearch(window.hubGlobalSearchQuery);
            }
            if (typeof onSuccess === 'function') onSuccess();
        });
    };

    // Global Event Delegation for toggle-friend-btn, accept-request-btn, decline-request-btn (Leaderboard & Friend Panel)
    if (!window.friendEventsBound) {
        window.friendEventsBound = true;
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.toggle-friend-btn');
            if (btn) {
                const friendId = btn.getAttribute('data-id');
                const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                const token = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

                btn.style.pointerEvents = 'none';
                btn.style.opacity = '0.6';

                fetch('/api/friends/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ friend_id: friendId })
                })
                .then(res => res.json())
                .then(data => {
                    btn.style.pointerEvents = 'auto';
                    btn.style.opacity = '1';

                    if (data.success) {
                        updateFriendshipUIAfterChange(friendId, data.status, data.friends_count);
                    }
                })
                .catch(err => {
                    btn.style.pointerEvents = 'auto';
                    btn.style.opacity = '1';
                    console.error('Error toggling friend:', err);
                });
                return;
            }

            const acceptBtn = e.target.closest('.accept-request-btn');
            if (acceptBtn) {
                const friendId = acceptBtn.getAttribute('data-id');
                const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                const token = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

                acceptBtn.style.pointerEvents = 'none';
                acceptBtn.style.opacity = '0.6';

                fetch('/api/friends/accept', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ friend_id: friendId })
                })
                .then(res => res.json())
                .then(data => {
                    acceptBtn.style.pointerEvents = 'auto';
                    acceptBtn.style.opacity = '1';

                    if (data.success) {
                        updateFriendshipUIAfterChange(friendId, 'friends', data.friends_count);
                    }
                })
                .catch(err => {
                    acceptBtn.style.pointerEvents = 'auto';
                    acceptBtn.style.opacity = '1';
                    console.error('Error accepting friend request:', err);
                });
                return;
            }

            const declineBtn = e.target.closest('.decline-request-btn');
            if (declineBtn) {
                const friendId = declineBtn.getAttribute('data-id');
                const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
                const token = csrfTokenMeta ? csrfTokenMeta.getAttribute('content') : '';

                declineBtn.style.pointerEvents = 'none';
                declineBtn.style.opacity = '0.6';

                fetch('/api/friends/decline', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ friend_id: friendId })
                })
                .then(res => res.json())
                .then(data => {
                    declineBtn.style.pointerEvents = 'auto';
                    declineBtn.style.opacity = '1';

                    if (data.success) {
                        updateFriendshipUIAfterChange(friendId, 'none', data.friends_count);
                    }
                })
                .catch(err => {
                    declineBtn.style.pointerEvents = 'auto';
                    declineBtn.style.opacity = '1';
                    console.error('Error declining friend request:', err);
                });
                return;
            }
        });
    }

    // ==================== AUTOMATIC FRIEND REQUEST POLLER ====================
    if (window.friendRequestPollInterval) {
        clearInterval(window.friendRequestPollInterval);
    }

    // Fetch immediately to ensure accurate badges on load
    fetch('/api/friends')
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            hubRequestsCache = data.requests || [];
            const reqCount = hubRequestsCache.length;
            updateHubBadge(reqCount);
        }
    })
    .catch(err => console.error('Error on initial request check:', err));

    // Poll every 8 seconds for new requests/updates
    window.friendRequestPollInterval = setInterval(() => {
        // Only poll if the user is active/tab is focused
        if (document.hidden) return;

        fetch('/api/friends')
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const newRequests = data.requests || [];
                const newFriends = data.friends || [];
                
                // Update Badge
                updateHubBadge(newRequests.length);

                // Check for updates to requests
                const currentReqIds = hubRequestsCache.map(r => String(r.id)).sort().join(',');
                const newReqIds = newRequests.map(r => String(r.id)).sort().join(',');

                // Check for updates to friends list
                const currentFriendIds = hubFriendsCache.map(f => String(f.id)).sort().join(',');
                const newFriendIds = newFriends.map(f => String(f.id)).sort().join(',');

                if (currentReqIds !== newReqIds || currentFriendIds !== newFriendIds) {
                    // Update cache
                    hubRequestsCache = newRequests;
                    hubFriendsCache = newFriends;

                    // 1. Re-render Friendship Hub left side friends list
                    const hubSearch = document.getElementById('friendHubSearchInput');
                    const searchQuery = hubSearch ? hubSearch.value : '';
                    renderHubFriends(searchQuery);

                    // 2. Re-render active tab content if Friendship Hub modal is open
                    const friendHubModal = document.getElementById('friendHubModal');
                    if (friendHubModal && friendHubModal.classList.contains('show')) {
                        renderHubActiveTab();
                    }

                    // 3. Update sidebar requests/friends if search is empty
                    const searchInput = document.getElementById('friendSearchInput');
                    const query = searchInput ? searchInput.value : '';
                    if (query.trim() === '') {
                        loadFriendList();
                    }
                }
            }
        })
        .catch(err => console.error('Error polling friend requests:', err));
    }, 8000);
}

if (!window.panelTurboBound) {
    window.panelTurboBound = true;

    // Global Event Delegation for navMenuBtn
    document.addEventListener('click', function (e) {
        const btn = e.target.closest('#navMenuBtn');
        if (btn) {
            e.preventDefault();
            e.stopPropagation();
            const panel = document.getElementById('menuPanel');
            if (panel) {
                const isOpen = panel.classList.toggle('open');
                if (isOpen) {
                    const savedIndex = localStorage.getItem('lastPanelSlideIndex');
                    const panelGrid = document.querySelector('.panel-grid');
                    const panelDots = document.querySelectorAll('.panel-dot');
                    if (savedIndex !== null && panelGrid && panelDots.length > 0) {
                        const targetIndex = parseInt(savedIndex, 10);
                        const width = panelGrid.offsetWidth;
                        if (width > 0) {
                            panelGrid.scrollLeft = targetIndex * width;
                            panelDots.forEach((dot, idx) => {
                                if (idx === targetIndex) {
                                    dot.classList.add('active');
                                } else {
                                    dot.classList.remove('active');
                                }
                            });
                        }
                    }
                }
            }
        }
    });

    document.addEventListener('turbo:load', function () {
        if (typeof initPanelJs === 'function') initPanelJs();
    });
    document.addEventListener('turbo:before-visit', function () {
        document.body.classList.remove('no-scroll');
    });
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof initPanelJs === 'function') initPanelJs();
        });
    } else {
        if (typeof initPanelJs === 'function') initPanelJs();
    }
}
