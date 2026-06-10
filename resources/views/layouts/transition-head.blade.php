<script>
    (function () {
        const perfEntries = performance.getEntriesByType('navigation');
        const isReload = (perfEntries.length > 0 && perfEntries[0].type === 'reload') ||
            (window.performance && window.performance.navigation && window.performance.navigation.type === 1);
        const isIntentional = isReload || sessionStorage.getItem('intentional_transition') === 'true';
        if (isIntentional) {
            // 1. Prevent white flash immediately by setting background to black and hiding document content
            document.documentElement.style.backgroundColor = '#050405';
            document.documentElement.style.visibility = 'hidden';

            // 2. Safety fallback timeout to restore visibility if script loading fails
            const fallbackTimeout = setTimeout(function () {
                document.documentElement.style.visibility = '';
                document.documentElement.style.backgroundColor = '';
                // Remove the preload style so the overlay can function normally
                const ps = document.getElementById('transition-preload-style');
                if (ps) ps.remove();
                const preloader = document.getElementById('circle-transition-overlay');
                if (preloader) {
                    preloader.classList.remove('active', 'show-loader');
                }
            }, 1800);
            window.transitionFallbackTimeout = fallbackTimeout;

            const clickX = sessionStorage.getItem('circle_click_x') || '50%';
            const clickY = sessionStorage.getItem('circle_click_y') || '50%';
            const leftVal = clickX === '50%' ? '50%' : (clickX + 'px');
            const topVal = clickY === '50%' ? '50%' : (clickY + 'px');

            // 3. Inject critical transition styles so they are applied on first paint.
            // IMPORTANT: Do NOT use !important on width/height — app.js uses inline styles to
            // expand the circle, and stylesheet !important would override those inline styles,
            // permanently blocking the opening animation and causing a stuck loading screen.
            const style = document.createElement('style');
            style.id = 'transition-preload-style';
            style.innerHTML = `
                .circle-transition-overlay {
                    position: fixed;
                    inset: 0;
                    z-index: 100000;
                    pointer-events: all !important;
                    overflow: hidden;
                    opacity: 1 !important;
                    display: block !important;
                }
                .circle-element {
                    position: absolute;
                    width: 0;
                    height: 0;
                    border-radius: 50%;
                    background: transparent;
                    box-shadow: 0 0 0 250vmax #050405;
                    transform: translate(-50%, -50%);
                    left: ${leftVal};
                    top: ${topVal};
                    transition: none !important;
                }
                .circle-loader-content {
                    position: absolute;
                    left: 50%;
                    top: 50%;
                    transform: translate(-50%, -50%);
                    z-index: 100001;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    gap: 1.5rem;
                    opacity: 1 !important;
                }
                .circle-loader-gif {
                    width: 30em;
                    height: auto;
                    object-fit: cover;
                    border-radius: 15em;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
                }
            `;
            document.head.appendChild(style);

            // 4. Observe body insertion to prepend overlay immediately before any content renders
            function injectOverlay(body) {
                if (document.getElementById('circle-transition-overlay')) return;
                const overlay = document.createElement('div');
                overlay.id = 'circle-transition-overlay';
                overlay.className = 'circle-transition-overlay active show-loader';
                overlay.innerHTML = `
                    <div class="circle-element"></div>
                    <div class="circle-loader-content">
                        <img src="/images/loads.gif" alt="Loading" class="circle-loader-gif" />
                    </div>
                `;
                body.insertBefore(overlay, body.firstChild);
            }

            if (document.body) {
                injectOverlay(document.body);
            } else {
                const observer = new MutationObserver(function (mutations, obs) {
                    if (document.body) {
                        obs.disconnect();
                        injectOverlay(document.body);
                    }
                });
                observer.observe(document.documentElement, { childList: true, subtree: true });
            }
        }
    })();
</script>