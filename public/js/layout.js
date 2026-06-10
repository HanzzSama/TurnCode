if (!window.layoutJsInitialized) {
    window.layoutJsInitialized = true;

    // Early console clutter protection and parameter mismatch safety patch
    (function safeGuardGlobalScope() {
        // Safe check for getComputedStyle to prevent TypeError exceptions
        const originalGetComputedStyle = window.getComputedStyle;
        window.getComputedStyle = function (element, pseudoElt) {
            if (!element || !(element instanceof Element)) {
                return {
                    getPropertyValue: () => '',
                    [Symbol.iterator]: function* () {}
                };
            }
            return originalGetComputedStyle.call(this, element, pseudoElt);
        };

        // Silence external errors and preload warnings in console logs
        const originalConsoleError = console.error;
        console.error = function (...args) {
            const msg = args.map(arg => (arg && arg.message) || String(arg)).join(' ');
            if (msg.includes("SplitText called before fonts loaded") || 
                msg.includes("parameter 1 is not of type") ||
                msg.includes("getComputedStyle")) {
                return;
            }
            originalConsoleError.apply(console, args);
        };

        const originalConsoleWarn = console.warn;
        console.warn = function (...args) {
            const msg = args.map(arg => String(arg)).join(' ');
            if (msg.includes("SplitText called before fonts loaded") || 
                msg.includes("preloaded using link preload but not used")) {
                return;
            }
            originalConsoleWarn.apply(console, args);
        };
    })();

    // 1. Hide navbar on scroll down, show on scroll up
    let lastScrollY = window.scrollY;
    let ticking = false;

    window.addEventListener('scroll', () => {
        if (!ticking) {
            requestAnimationFrame(() => {
                const navbar = document.querySelector('.fixed-navbar');
                if (navbar) {
                    const currentScrollY = window.scrollY;
                    if (currentScrollY > lastScrollY && currentScrollY > 80) {
                        // Scrolling down — hide navbar
                        navbar.classList.add('nav-hidden');
                    } else {
                        // Scrolling up — show navbar
                        navbar.classList.remove('nav-hidden');
                    }
                }
                lastScrollY = window.scrollY;
                ticking = false;
            });
            ticking = true;
        }
    }, { passive: true });

    // 2. Global Event Delegation for Page Nav Toggle
    let pageNavTimeoutId = null;
    
    document.addEventListener('click', (e) => {
        const pageNavBtn = e.target.closest('#pageNavBtn');
        const pageNav = document.getElementById('pageNav');
        const pageNavItems = document.querySelector('.page-nav-items') || document.getElementById('pageNavItems');

        if (pageNavBtn && pageNav) {
            // Clicked the toggle button
            const open = pageNav.classList.toggle('open');
            pageNavBtn.setAttribute('aria-expanded', open);
            
            if (pageNavItems) {
                if (open) {
                    clearTimeout(pageNavTimeoutId);
                    pageNavItems.style.display = 'flex';
                } else {
                    pageNavTimeoutId = setTimeout(() => {
                        pageNavItems.style.display = 'none';
                    }, 1000);
                }
            }
        } else if (pageNav && pageNav.classList.contains('open') && !pageNav.contains(e.target)) {
            // Clicked outside while open
            pageNav.classList.remove('open');
            const navBtn = document.getElementById('pageNavBtn');
            if (navBtn) navBtn.setAttribute('aria-expanded', false);
            
            if (pageNavItems) {
                pageNavTimeoutId = setTimeout(() => {
                    pageNavItems.style.display = 'none';
                }, 1000);
            }
        }
    });

    // 3. EXP System (Database Backend)
    function formatExp(exp, format) {
        if (format === 'k') {
            return Math.floor(exp / 1000);
        }
        return exp.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function updateExpDisplay(exp) {
        const displays = document.querySelectorAll('.user-exp-display');
        displays.forEach(el => {
            const format = el.getAttribute('data-format');
            el.textContent = formatExp(exp, format);
        });
    }

    function updateTierDisplay(tier, tierColor, level) {
        const displays = document.querySelectorAll('.user-tier-display');
        displays.forEach(el => {
            el.textContent = tier;
        });

        if (typeof window.syncTierProgressionUI === 'function') {
            window.syncTierProgressionUI(tier);
        }

        if (level) {
            const levelDisplays = document.querySelectorAll('.user-level-display');
            levelDisplays.forEach(el => {
                el.textContent = level;
            });
            
            const levelBadges = document.querySelectorAll('.navbar-role span, .navbar-user-info span');
            levelBadges.forEach(el => {
                if (el.textContent.includes('LV.')) {
                    el.textContent = 'LV. ' + level;
                }
            });
        }

        if (tierColor) {
            // 1. Update dashboard tier card color via CSS variable
            const newTierCards = document.querySelectorAll('.new-tier-card');
            newTierCards.forEach(el => {
                el.style.setProperty('--tier-color', tierColor);
            });

            // 2. Update navbar avatar border
            const navbarAvatars = document.querySelectorAll('.navbar-avatar');
            navbarAvatars.forEach(el => {
                el.style.borderColor = `rgba(${tierColor}, 0.4)`;
            });

            // 3. Update level badge backgrounds and colors
            const levelBadgesColor = document.querySelectorAll('.navbar-role span, .navbar-user-info span');
            levelBadgesColor.forEach(el => {
                if (el.textContent.includes('LV.')) {
                    el.style.background = `rgba(${tierColor}, 0.15)`;
                    el.style.color = `rgb(${tierColor})`;
                    el.style.borderColor = `rgba(${tierColor}, 0.3)`;
                }
            });

            // 4. Update profile page avatar wrapper
            const largeAvatars = document.querySelectorAll('.large-avatar-wrapper');
            largeAvatars.forEach(el => {
                el.style.background = `linear-gradient(135deg, rgba(${tierColor}, 0.5) 0%, rgba(${tierColor}, 0.5) 100%)`;
            });

            // 5. Update profile page tier badge and side panel account badge
            const tierBadges = document.querySelectorAll('.avatar-tier-badge, .account-badge');
            tierBadges.forEach(el => {
                el.style.background = `rgba(${tierColor}, 1)`;
            });
        }
    }

    function updateNextTierExpDisplay(nextExp) {
        const displays = document.querySelectorAll('.user-next-tier-display');
        displays.forEach(el => {
            const format = el.getAttribute('data-format');
            if (format === 'number') {
                el.textContent = nextExp.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            } else {
                el.textContent = nextExp;
            }
        });
    }

    function updateExpBarDisplay(percentage) {
        const bars = document.querySelectorAll('.user-exp-bar');
        bars.forEach(el => {
            el.style.width = percentage + '%';
            el.textContent = percentage < 25 ? `${percentage}%` : `${percentage}% EXP`;
        });
    }

    // Inject Tier-Up Popup Styles (once)
    (function injectTierUpStyles() {
        if (document.getElementById('tier-up-popup-styles')) return;
        const styleEl = document.createElement('style');
        styleEl.id = 'tier-up-popup-styles';
        styleEl.textContent = `
            @keyframes tierUpOverlayIn { from { opacity: 0; } to { opacity: 1; } }
            @keyframes tierUpOverlayOut { from { opacity: 1; } to { opacity: 0; } }
            @keyframes tierUpCardIn { 
                0% { opacity: 0; transform: translate(-50%, -50%) scale(0.8) rotateX(10deg); }
                70% { opacity: 1; transform: translate(-50%, -50%) scale(1.05) rotateX(-2deg); }
                100% { opacity: 1; transform: translate(-50%, -50%) scale(1) rotateX(0deg); }
            }
            @keyframes tierUpCardOut { 
                from { opacity: 1; transform: translate(-50%, -50%) scale(1); }
                to { opacity: 0; transform: translate(-50%, -50%) scale(0.85) translateY(20px); }
            }
            @keyframes cardBreakthroughPop {
                0% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
                40% { transform: translate(-50%, -50%) scale(1.05); opacity: 1; }
                100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
            }
            @keyframes glowPulse { 0%, 100% { opacity: 0.4; } 50% { opacity: 0.7; } }
            @keyframes flashStrike { 0% { opacity: 0; } 15% { opacity: 1; } 100% { opacity: 0; } }
            @keyframes tierUpParticle {
                0% { opacity: 1; transform: translate(0, 0) scale(1); }
                100% { opacity: 0; transform: translate(var(--tx), var(--ty)) scale(0); }
            }

            .tier-up-overlay {
                position: fixed; inset: 0; z-index: 99999;
                background: rgba(0, 0, 0, 0.88);
                backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px);
                animation: tierUpOverlayIn 0.5s ease forwards;
            }
            .tier-up-overlay.closing { animation: tierUpOverlayOut 0.4s ease forwards; }

            .tier-up-card {
                position: absolute; top: 50%; left: 50%;
                transform: translate(-50%, -50%);
                width: 500px; max-width: 90vw;
                padding: 2.5rem 3.5rem;
                border-radius: 140px;
                background-color: rgb(var(--active-tier-color));
                box-shadow: 0 15px 50px -10px rgba(var(--active-tier-color), 0.7), inset 0 2px 15px rgba(255,255,255,0.2);
                animation: tierUpCardIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
                opacity: 0; overflow: hidden;
                display: flex; flex-direction: column; justify-content: center; align-items: flex-end;
                transition: background-color 1.2s ease, box-shadow 1.2s ease;
            }
            .tier-up-card.phase-breakthrough {
                opacity: 1;
                animation: cardBreakthroughPop 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            }
            .tier-up-overlay.closing .tier-up-card { animation: tierUpCardOut 0.35s ease forwards; }

            .tier-up-bg-image {
                position: absolute; inset: 0;
                background-image: url('/images/feather_gold.png');
                background-size: cover; background-position: center left;
                mix-blend-mode: overlay; opacity: 0.5;
                animation: glowPulse 5s ease-in-out infinite;
            }
            .tier-up-bg-gradient {
                position: absolute; inset: 0;
                background: linear-gradient(to right, rgba(var(--active-tier-color), 0.1) 0%, rgba(var(--active-tier-color), 0.6) 45%, rgba(var(--active-tier-color), 1) 90%);
                transition: background 1.2s ease;
            }
            
            .tier-up-flash {
                position: absolute; inset: 0; background: #fff; opacity: 0; pointer-events: none; z-index: 10;
            }
            .tier-up-flash.active { animation: flashStrike 0.8s ease-out forwards; }

            .tier-up-particles {
                position: absolute; inset: 0; pointer-events: none; z-index: 10;
            }
            .tier-up-particle {
                position: absolute; border-radius: 50%;
                background: rgb(var(--active-tier-color));
                box-shadow: 0 0 10px rgb(var(--active-tier-color));
            }

            .tier-up-content {
                position: relative; z-index: 2; width: 100%; display: flex; flex-direction: column; align-items: flex-end;
            }

            .tier-badge-row { display: flex; align-items: center; gap: 12px; margin-bottom: 4px; position: relative; height: 30px; justify-content: flex-end; width: 100%; }
            
            .tier-lvl {
                background: rgba(0, 0, 0, 0.22); color: #fff;
                font-size: 0.85rem; font-weight: 800; letter-spacing: 0.5px;
                padding: 4px 14px; border-radius: 20px;
                box-shadow: inset 0 1px 3px rgba(255, 255, 255, 0.05);
                white-space: nowrap;
            }
            .tier-lbl { color: rgba(255, 255, 255, 0.9); font-size: 0.85rem; font-weight: 800; letter-spacing: 2.5px; }
            
            .tier-title-container { position: relative; height: 50px; display: flex; align-items: center; justify-content: flex-end; width: 100%; }
            .tier-title {
                font-size: 2.5rem; font-weight: 900; line-height: 1.1; color: #ffffff;
                text-shadow: 0 4px 15px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.2);
                letter-spacing: -0.5px; white-space: nowrap;
            }

            .prepare-only { opacity: 1; pointer-events: auto; transform: translateY(0); transition: opacity 0.4s ease, transform 0.4s ease; }
            .phase-breakthrough .prepare-only { opacity: 0; pointer-events: none; transform: translateY(-15px); }
            
            .breakthrough-only {
                opacity: 0; pointer-events: none; transform: translateY(15px);
                transition: opacity 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.2s, transform 0.6s cubic-bezier(0.16, 1, 0.3, 1) 0.2s;
            }
            .phase-breakthrough .breakthrough-only { opacity: 1; pointer-events: auto; transform: translateY(0); }

            .tier-dismiss {
                margin-top: 20px; font-size: 0.75rem; font-weight: 800; color: rgba(255, 255, 255, 0.6);
                letter-spacing: 1.5px; cursor: pointer; transition: color 0.3s ease; text-transform: uppercase;
            }
            .tier-dismiss:hover { color: rgba(255, 255, 255, 1); text-shadow: 0 0 10px rgba(255,255,255,0.5); }

            @media (max-width: 576px) {
                .tier-up-card { padding: 2.5rem 2.5rem; border-radius: 80px; width: 90vw; }
                .tier-title { font-size: 2rem; }
            }
        `;
        document.head.appendChild(styleEl);
    })();

    // Show Tier-Up Popup Animation
    function showTierUpPopup(oldTier, newTier, tierColor, level, oldTierColor) {
        // Remove any existing popup
        const existing = document.querySelector('.tier-up-overlay');
        if (existing) existing.remove();

        // Fallbacks for colors
        const newColor = tierColor || '168, 162, 158';
        const oldColor = oldTierColor || '168, 162, 158';
        const oldLvl = Math.max(1, level - 1);

        // Create overlay
        const overlay = document.createElement('div');
        overlay.className = 'tier-up-overlay';

        // Set variables for transition
        overlay.style.setProperty('--old-tier-color', oldColor);
        overlay.style.setProperty('--new-tier-color', newColor);
        overlay.style.setProperty('--active-tier-color', 'var(--old-tier-color)');

        overlay.innerHTML = `
            <div class="tier-up-card phase-prepare">
                <div class="tier-up-flash"></div>
                <div class="tier-up-particles"></div>
                
                <div class="tier-up-bg-image"></div>
                <div class="tier-up-bg-gradient"></div>

                <div class="tier-up-content">
                    
                    <div class="tier-badge-row">
                        <div style="position: relative; width: 60px; height: 100%; display: flex; align-items: center; justify-content: flex-end;">
                            <span class="tier-lvl prepare-only" style="position: absolute;">LV. ${oldLvl}</span>
                            <span class="tier-lvl breakthrough-only" style="position: absolute;">LV. ${level}</span>
                        </div>
                        <span class="tier-lbl">TIER</span>
                    </div>

                    <div class="tier-title-container">
                        <div class="tier-title prepare-only" style="position: absolute;">${oldTier}</div>
                        <div class="tier-title breakthrough-only" style="position: absolute;">${newTier}</div>
                    </div>

                    <div class="tier-dismiss breakthrough-only">KETUK UNTUK LANJUT</div>
                </div>
            </div>
        `;

        document.body.appendChild(overlay);

        const card = overlay.querySelector('.tier-up-card');
        const flash = overlay.querySelector('.tier-up-flash');

        // Close handler
        function closeTierPopup() {
            overlay.classList.add('closing');
            setTimeout(() => {
                overlay.remove();
            }, 450);
        }

        // Only allow clicking close in breakthrough phase
        overlay.addEventListener('click', (e) => {
            if (card.classList.contains('phase-breakthrough')) {
                closeTierPopup();
            }
        });

        // Function to create burst of particles
        function createParticleBlast() {
            const particlesContainer = card.querySelector('.tier-up-particles');
            if (!particlesContainer) return;
            
            particlesContainer.innerHTML = '';
            const count = 45;
            
            for (let i = 0; i < count; i++) {
                const particle = document.createElement('div');
                particle.className = 'tier-up-particle';
                
                const angle = Math.random() * Math.PI * 2;
                const speed = 100 + Math.random() * 250;
                const tx = Math.cos(angle) * speed;
                const ty = Math.sin(angle) * speed;
                
                const size = 3 + Math.random() * 6;
                const delay = Math.random() * 0.15;
                const duration = 0.8 + Math.random() * 1.2;
                
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = '50%';
                particle.style.top = '50%';
                particle.style.setProperty('--tx', `${tx}px`);
                particle.style.setProperty('--ty', `${ty}px`);
                particle.style.animation = `tierUpParticle ${duration}s cubic-bezier(0.16, 1, 0.3, 1) ${delay}s forwards`;
                
                particlesContainer.appendChild(particle);
            }
        }

        // Trigger Phase 2: Breakthrough! (at 2.0s)
        setTimeout(() => {
            if (!document.body.contains(overlay)) return;

            // 1. Trigger Flash Strike
            flash.classList.add('active');

            // 2. Switch classes
            card.classList.remove('phase-prepare');
            card.classList.add('phase-breakthrough');
            overlay.style.setProperty('--active-tier-color', 'var(--new-tier-color)');

            // 3. Spawns particle burst
            createParticleBlast();

        }, 2000);

        // Auto-dismiss 8 seconds after opening (gives 6s to read in breakthrough mode)
        setTimeout(() => {
            if (document.body.contains(overlay)) {
                closeTierPopup();
            }
        }, 8000);
    }

    function initExpSystem() {
        setInterval(() => {
            const csrfTokenMeta = document.querySelector('meta[name="csrf-token"]');
            if (!csrfTokenMeta) return;
            const csrfToken = csrfTokenMeta.getAttribute('content');

            fetch('/api/user/add-exp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data && data.success) {
                    updateExpDisplay(data.exp);
                    if (data.tier) {
                        updateTierDisplay(data.tier, data.tier_color, data.level);
                    }
                    if (data.next_tier_exp !== undefined) {
                        updateNextTierExpDisplay(data.next_tier_exp);
                    }
                    if (data.exp_percentage !== undefined) {
                        updateExpBarDisplay(data.exp_percentage);
                    }
                    // Show tier-up popup animation on tier change
                    if (data.tier_changed) {
                        showTierUpPopup(
                            data.old_tier,
                            data.new_tier,
                            data.tier_color || '168, 162, 158',
                            data.level || '',
                            data.old_tier_color || '168, 162, 158'
                        );
                    }
                }
            })
            .catch(error => console.error('Error updating EXP:', error));
        }, 60000);
    }

    // 4. Web Push Notification API
    function initNotifications() {
        if (!('Notification' in window)) {
            console.log('This browser does not support desktop notification');
            return;
        }

        if (Notification.permission !== 'granted' && Notification.permission !== 'denied') {
            Notification.requestPermission();
        }

        setInterval(() => {
            if (Notification.permission !== 'granted') return;

            let sinceId = localStorage.getItem('lastNotificationId') || 0;

            fetch(`/api/notifications/unread?since_id=${sinceId}`, {
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data && data.success && data.notifications.length > 0) {
                    let maxId = sinceId;
                    data.notifications.forEach(notif => {
                        new Notification(notif.title, {
                            body: notif.description,
                            icon: '/favicon.ico' // adjust icon if needed
                        });
                        if (notif.id > maxId) {
                            maxId = notif.id;
                        }
                    });
                    localStorage.setItem('lastNotificationId', maxId);
                }
            })
            .catch(error => console.error('Error fetching notifications:', error));
        }, 15000); // Check every 15 seconds
    }

    // Expose tier-up popup globally for testing
    window.showTierUpPopup = showTierUpPopup;

    // 5. Dynamic Lenis Smooth Scrolling Load & Init (adapted from antigravity.google)
    function initSmoothScrolling() {
        if (window.lenisInitialized) return;
        window.lenisInitialized = true;

        // Inject Lenis CSS directly
        const styleEl = document.createElement('style');
        styleEl.id = 'lenis-smooth-styles';
        styleEl.textContent = `
            html.lenis, html.lenis body {
                height: auto;
            }
            .lenis.lenis-smooth {
                scroll-behavior: auto !important;
            }
            .lenis.lenis-smooth [data-lenis-prevent] {
                overscroll-behavior: contain;
            }
            .lenis.lenis-stopped {
                overflow: hidden;
            }
            .lenis.lenis-scrolling iframe {
                pointer-events: none;
            }
        `;
        document.head.appendChild(styleEl);

        // Dynamically load Lenis JS from CDN
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/@darkroom.engineering/lenis@1.0.42/dist/lenis.min.js';
        script.onload = () => {
            const lenis = new Lenis({
                duration: 1.2,
                easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)), // beautiful exponential out easing from studio freight / antigravity.google
                direction: 'vertical',
                gestureDirection: 'vertical',
                smooth: true,
                mouseMultiplier: 1.0,
                smoothTouch: false,
                infinite: false,
            });

            window.lenis = lenis;

            function raf(time) {
                lenis.raf(time);
                requestAnimationFrame(raf);
            }
            requestAnimationFrame(raf);
            
            document.documentElement.style.scrollBehavior = 'auto';
            document.body.style.scrollBehavior = 'auto';
        };
        document.head.appendChild(script);
    }

    initNotifications();
    initExpSystem();
    initSmoothScrolling();
}
