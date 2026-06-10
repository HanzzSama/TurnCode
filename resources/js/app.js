import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
import "@hotwired/turbo";

// --- GLOBAL: Prevent image dragging ---
document.addEventListener('dragstart', (e) => {
    if (e.target.tagName === 'IMG') {
        e.preventDefault();
    }
});

// --- GLOBAL CIRCLE TRANSITION SYSTEM ---
function setupTransitionOverlay() {
    let overlay = document.getElementById('circle-transition-overlay') || document.querySelector('.circle-transition-overlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'circle-transition-overlay';
        overlay.className = 'circle-transition-overlay active';
        overlay.innerHTML = `
            <div class="circle-element"></div>
            <div class="circle-loader-content">
                <img src="/images/loads.gif" alt="Loading" class="circle-loader-gif" />
            </div>
        `;
        document.body.prepend(overlay);
    }

    // Buat Tombol Refresh Melayang secara dinamis jika belum ada di DOM
    let refreshBtn = document.querySelector('.floating-refresh-btn');
    if (!refreshBtn) {
        refreshBtn = document.createElement('button');
        refreshBtn.className = 'floating-refresh-btn';
        refreshBtn.title = 'Refresh Halaman';
        refreshBtn.innerHTML = `
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21.5 2v6h-6M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/>
            </svg>
        `;
        document.body.appendChild(refreshBtn);
    }

    return overlay;
}

function initCircleTransition() {
    const overlay = setupTransitionOverlay();
    const circle = overlay.querySelector('.circle-element');
    if (!overlay || !circle) return;

    // Clear fallback timeout if it exists
    if (window.transitionFallbackTimeout) {
        clearTimeout(window.transitionFallbackTimeout);
    }

    const transitionWrapper = document.querySelector('.page-transition-wrapper');
    if (transitionWrapper) {
        window.requestAnimationFrame(() => {
            transitionWrapper.classList.add('loaded');
        });
    }

    let lastMouseX = window.innerWidth / 2;
    let lastMouseY = window.innerHeight / 2;
    let isTransitioning = false;

    // Selalu catat posisi kursor mouse dalam memori untuk mendeteksi posisi terakhir saat browser direfresh/unload
    window.addEventListener('mousemove', (e) => {
        lastMouseX = e.clientX;
        lastMouseY = e.clientY;
    });

    // Catat ke sessionStorage saat halaman akan ditutup/direfresh agar halaman berikutnya tahu posisi kursor terakhir
    const saveTransitionState = () => {
        sessionStorage.setItem('circle_click_x', lastMouseX.toString());
        sessionStorage.setItem('circle_click_y', lastMouseY.toString());
        sessionStorage.setItem('intentional_transition', 'true');
    };
    window.addEventListener('beforeunload', saveTransitionState);
    window.addEventListener('pagehide', saveTransitionState);

    // Memulai halaman: Buka lubang lingkaran (Dimensi 0 -> 250vmax)
    function initializeEntranceTransition() {
        const lastClickX = sessionStorage.getItem('circle_click_x');
        const lastClickY = sessionStorage.getItem('circle_click_y');
        
        const perfEntries = performance.getEntriesByType('navigation');
        const isReload = (perfEntries.length > 0 && perfEntries[0].type === 'reload') ||
                         (window.performance && window.performance.navigation && window.performance.navigation.type === 1);
        const isIntentional = isReload || sessionStorage.getItem('intentional_transition') === 'true';

        // Matikan transisi sementara untuk set letak awal secara instan
        circle.style.transition = 'none';

        let shouldFollowCursor = false;

        if (isIntentional && lastClickX !== null && lastClickY !== null && !isNaN(parseFloat(lastClickX)) && !isNaN(parseFloat(lastClickY))) {
            circle.style.left = `${lastClickX}px`;
            circle.style.top = `${lastClickY}px`;
            shouldFollowCursor = true;
        } else {
            circle.style.left = `${lastMouseX}px`;
            circle.style.top = `${lastMouseY}px`;
            shouldFollowCursor = true;
        }

        // Selalu bersihkan koordinat sessionStorage agar reload berikutnya/reload manual kembali ke tengah jika gagal menyimpan
        sessionStorage.removeItem('circle_click_x');
        sessionStorage.removeItem('circle_click_y');
        sessionStorage.removeItem('intentional_transition');

        // Pastikan dimensi awal 0 agar tidak berkedip
        circle.style.width = '0';
        circle.style.height = '0';

        // Paksa reflow browser agar posisi dan dimensi awal dirender
        circle.offsetWidth;

        // KRITIS: Hapus style preload (dengan !important) SEBELUM memulai animasi.
        // CSS !important di stylesheet mengalahkan inline style, sehingga circle tidak bisa
        // mengembang jika style preload masih aktif.
        const preloadStyle = document.getElementById('transition-preload-style');
        if (preloadStyle) preloadStyle.remove();

        // Restore visibility now that the black overlay is safely covering the screen
        document.documentElement.style.visibility = '';
        document.documentElement.style.backgroundColor = '';

        // Aktifkan kembali transisi dan mulai animasi pembukaan (lubang membesar) setelah delay kecil agar transisi terasa natural
        setTimeout(() => {
            // Gunakan kurva easeOutQuart (cubic-bezier(0.25, 1, 0.5, 1)) dengan durasi 1.4s untuk pembukaan yang lambat dan sangat halus
            circle.style.transition = 'width 1.4s cubic-bezier(0.25, 1, 0.5, 1), height 1.4s cubic-bezier(0.25, 1, 0.5, 1)';
            circle.style.width = '250vmax';
            circle.style.height = '250vmax';
            
            // FADE OUT LOADER CONTENT: Hapus 'show-loader' agar konten pemutar memudar secara paralel sewaktu lingkaran terbuka
            overlay.classList.remove('show-loader');
        }, 250);

        // Buat lingkaran mengikuti kursor mouse secara real-time jika transisi terarah diaktifkan
        let followCursorEntrance = null;
        if (shouldFollowCursor) {
            followCursorEntrance = function(e) {
                circle.style.left = `${e.clientX}px`;
                circle.style.top = `${e.clientY}px`;
            };
            window.addEventListener('mousemove', followCursorEntrance);
        }

        // Setelah transisi selesai, nonaktifkan overlay agar pointer-events tidak mengganggu
        setTimeout(() => {
            if (shouldFollowCursor && followCursorEntrance) {
                window.removeEventListener('mousemove', followCursorEntrance);
            }
            overlay.classList.remove('active');
        }, 1700); // 250ms delay + 1400ms transisi + 50ms buffer (total 1700ms)
    }

    // Memicu transisi keluar (Dimensi 250vmax -> 0)
    function triggerExitTransition(clientX, clientY, targetUrl) {
        isTransitioning = true;

        let x = clientX;
        let y = clientY;
        if (x === undefined || y === undefined || x === null || y === null || isNaN(parseFloat(x)) || isNaN(parseFloat(y)) || (x === 0 && y === 0)) {
            x = lastMouseX;
            y = lastMouseY;
        }

        // Simpan koordinat awal ke sessionStorage dan tandai sebagai transisi sengaja
        sessionStorage.setItem('circle_click_x', x.toString());
        sessionStorage.setItem('circle_click_y', y.toString());
        sessionStorage.setItem('intentional_transition', 'true');

        // Set posisi pusat lingkaran ke koordinat klik awal
        circle.style.left = `${x}px`;
        circle.style.top = `${y}px`;

        circle.offsetWidth;

        // Snappy transition for exit: start fast and make it feel immediate
        circle.style.transition = 'width 0.6s cubic-bezier(0.16, 1, 0.3, 1), height 0.6s cubic-bezier(0.16, 1, 0.3, 1)';
        overlay.classList.add('active', 'show-loader');
        circle.style.width = '0';
        circle.style.height = '0';

        function followCursor(e) {
            circle.style.left = `${e.clientX}px`;
            circle.style.top = `${e.clientY}px`;
            sessionStorage.setItem('circle_click_x', e.clientX.toString());
            sessionStorage.setItem('circle_click_y', e.clientY.toString());
        }
        window.addEventListener('mousemove', followCursor);

        if (transitionWrapper) {
            transitionWrapper.classList.remove('loaded');
            transitionWrapper.classList.add('fade-out');
        }

        setTimeout(() => {
            window.removeEventListener('mousemove', followCursor);
            if (typeof targetUrl === 'function') {
                targetUrl();
            } else if (targetUrl) {
                window.location.href = targetUrl;
            } else {
                window.location.reload();
            }
        }, 600); // Wait exactly 600ms matching snappy transition duration
    }

    // Daftarkan event listener untuk link internal (Navigasi Halaman) via event delegation
    document.addEventListener('click', (e) => {
        const link = e.target.closest('a');
        if (!link) return;

        const url = link.getAttribute('href');
        if (url &&
            link.origin === window.location.origin &&
            !url.startsWith('#') &&
            !url.startsWith('javascript:') &&
            !url.startsWith('mailto:') &&
            !url.startsWith('tel:') &&
            !link.hash &&
            link.getAttribute('target') !== '_blank' &&
            !link.classList.contains('no-transition')) {

            e.preventDefault();
            e.stopPropagation();
            triggerExitTransition(e.clientX, e.clientY, url);
        }
    });

    // Daftarkan event listener untuk pengiriman form (Form Submission) agar menampilkan loading
    document.addEventListener('submit', (e) => {
        const form = e.target;
        
        if (e.defaultPrevented || form.classList.contains('no-transition') || isTransitioning) {
            return;
        }

        if (form.checkValidity && !form.checkValidity()) {
            return;
        }

        e.preventDefault();
        e.stopPropagation();

        triggerExitTransition(lastMouseX, lastMouseY, () => {
            form.submit();
        });
    });

    // Daftarkan event listener untuk tombol keyboard Refresh (F5, Ctrl+R, Cmd+R)
    window.addEventListener('keydown', (e) => {
        const isF5 = e.key === 'F5' || e.keyCode === 116;
        const isCtrlR = (e.ctrlKey || e.metaKey) && (e.key === 'r' || e.keyCode === 82);

        if (isF5 || isCtrlR) {
            e.preventDefault();
            triggerExitTransition(lastMouseX, lastMouseY);
        }
    });

    // Daftarkan event listener untuk Tombol Refresh UI Melayang
    document.addEventListener('click', (e) => {
        const refreshBtn = e.target.closest('.floating-refresh-btn');
        if (refreshBtn) {
            triggerExitTransition(e.clientX, e.clientY);
        }
    });

    // Handle back button / bfcache restore
    window.addEventListener('pageshow', (event) => {
        const isBackForward = event.persisted || 
            (window.performance && window.performance.navigation && window.performance.navigation.type === 2) ||
            (window.performance && window.performance.getEntriesByType && 
             window.performance.getEntriesByType('navigation')[0] && 
             window.performance.getEntriesByType('navigation')[0].type === 'back_forward');

        if (isBackForward) {
            isTransitioning = false;
            
            // Force reset classes
            if (overlay) {
                overlay.classList.remove('show-loader');
                overlay.classList.add('active');
            }
            if (transitionWrapper) {
                transitionWrapper.classList.remove('fade-out');
                transitionWrapper.classList.add('loaded');
            }
            
            // Re-run entrance animation
            initializeEntranceTransition();
        }
    });

    // Jalankan inisialisasi transisi pembukaan halaman jika disengaja, atau reset secara instan
    const perfEntries = performance.getEntriesByType('navigation');
    const isReload = (perfEntries.length > 0 && perfEntries[0].type === 'reload') ||
                     (window.performance && window.performance.navigation && window.performance.navigation.type === 1);
    const isIntentional = isReload || sessionStorage.getItem('intentional_transition') === 'true';
    if (isIntentional) {
        initializeEntranceTransition();
    } else {
        // Restore visibility immediately since we are not doing a transition
        document.documentElement.style.visibility = '';
        document.documentElement.style.backgroundColor = '';

        // No intentional transition: reset instantly to fully open/inactive
        circle.style.transition = 'none';
        circle.style.width = '250vmax';
        circle.style.height = '250vmax';
        overlay.classList.remove('active');
        overlay.classList.remove('show-loader');
        
        // Clean up sessionStorage
        sessionStorage.removeItem('circle_click_x');
        sessionStorage.removeItem('circle_click_y');
        sessionStorage.removeItem('intentional_transition');
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCircleTransition);
} else {
    initCircleTransition();
}
