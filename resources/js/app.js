/**
 * BookNest - frontend animations (smooth scroll reveal + optional count-up).
 *
 * - Content stays visible if JS fails or IntersectionObserver is unavailable.
 * - Elements are tagged with `.reveal-on-scroll` only when JS is running,
 *   and the tag is removed after the reveal so hover styles are unaffected.
 * - Respects prefers-reduced-motion via CSS dampening without aborting transitions.
 * - Count-up only runs on elements explicitly marked with `data-countup`.
 */
(function () {
    if (!('IntersectionObserver' in window)) {
        return;
    }

    var REVEAL_MS = 850;

    var AUTO_SELECTORS = [
        // Dashboard Stats & Cards
        '.stats-grid-4 > .stat-box',
        '.stats-grid-5 > .stat-box',
        '.stats-grid-3 > .stat-box',
        '.stats-grid > .stat-box',
        '.management-grid > .manage-card',
        '.stats-summary > .stat-card-member',
        '.dashboard-header',
        '.welcome-banner',
        '.monthly-report-card',
        '.section-container',
        // Catalog & Books
        '.filter-card',
        '.categories-grid > .cat-btn',
        '.categories-grid > .cat-card',
        '.book-grid > .book-card',
        '.books-grid > .book-card',
        '.related-books-grid > .book-card',
        '.related-grid > .related-card',
        '.detail-container',
        '.cover-card',
        '.location-card',
        '.spec-card',
        '.about-card',
        // Tentang Kami
        '.about-hero',
        '.about-values .about-value-card',
        '.about-info-grid .about-info-card',
        '.about-membership',
        // Sirkulasi, Peminjaman, Pengembalian & Riwayat
        '.card-status-berhasil',
        '.card-rincian',
        '.card-buku-kembali',
        '.riwayat-card',
        '.table-card',
        '.table-responsive-card',
        // Profile
        '.profile-grid',
        '.summary-card',
        '.privacy-card',
        '.profile-card-section',
        // Panels & Reusable tags
        '.card-panel',
        '.card-custom',
        '.form-card',
        '.panel-card',
        '.report-card',
        '.main-card',
        '.detail-card',
        '.tiket-card',
        '.qr-card-center',
        '.batch-card',
        '.nota-card',
        '.scroll-reveal',
        '.reveal-on-scroll'
    ].join(', ');

    function formatNumber(num) {
        return Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function animateCountUp(element) {
        if (element.dataset.counted === 'true') {
            return;
        }
        element.dataset.counted = 'true';

        var target = parseInt(element.dataset.countup || element.textContent.replace(/\D/g, ''), 10);
        if (isNaN(target) || target <= 0) {
            return;
        }

        var duration = 2400;
        var start = performance.now();

        function step(now) {
            var progress = Math.min((now - start) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3);
            element.textContent = formatNumber(eased * target);
            if (progress < 1) {
                requestAnimationFrame(step);
            }
        }

        requestAnimationFrame(step);
    }

    function finishReveal(element) {
        window.setTimeout(function () {
            element.classList.remove('reveal-on-scroll', 'is-revealed');
            element.style.transitionDelay = '';
        }, REVEAL_MS + 400);
    }

    function init() {
        var elements = Array.prototype.slice.call(document.querySelectorAll(AUTO_SELECTORS));

        if (!elements.length) {
            return;
        }

        elements.forEach(function (element) {
            var siblings = Array.prototype.slice.call(element.parentElement.children);
            var siblingIndex = siblings.indexOf(element);
            element.style.transitionDelay = Math.min(Math.max(siblingIndex, 0) % 8, 6) * 60 + 'ms';
            element.classList.add('reveal-on-scroll');
        });

        // Hidden state only applies once this class exists.
        document.documentElement.classList.add('js-reveal-active');

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }
                var element = entry.target;
                observer.unobserve(element);
                element.classList.add('is-revealed');
                finishReveal(element);
            });
        }, { rootMargin: '0px 0px -20px 0px', threshold: 0.06 });

        elements.forEach(function (element) {
            observer.observe(element);
        });

        // Safety fallback: ensure visible elements are revealed
        window.setTimeout(function () {
            var threshold = window.innerHeight * 0.95;
            elements.forEach(function (el) {
                if (el.classList.contains('reveal-on-scroll') && !el.classList.contains('is-revealed')) {
                    var rect = el.getBoundingClientRect();
                    if (rect.top <= threshold && rect.bottom >= 0) {
                        el.classList.add('is-revealed');
                        finishReveal(el);
                    }
                }
            });
        }, 700);

        var counterObserver = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    counterObserver.unobserve(entry.target);
                    animateCountUp(entry.target);
                }
            });
        }, { threshold: 0.25 });

        document.querySelectorAll('[data-countup]').forEach(function (counter) {
            counterObserver.observe(counter);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
