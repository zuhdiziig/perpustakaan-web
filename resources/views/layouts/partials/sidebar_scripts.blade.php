<script>
    // Immediate check if user previously navigated internally to stabilize sidebar chrome
    try {
        if (sessionStorage.getItem('bn_navigated')) {
            document.documentElement.classList.add('bn-navigated');
        }
    } catch (e) {}

    // Global Mobile Sidebar Controls
    (function () {
        let lastSidebarToggleTime = 0;

        function toggleSidebarMobile(e) {
            if (e) {
                if (typeof e.preventDefault === 'function') e.preventDefault();
                if (typeof e.stopPropagation === 'function') e.stopPropagation();
                if (typeof e.stopImmediatePropagation === 'function') e.stopImmediatePropagation();
            }

            // Anti-bounce / double-trigger guard
            const now = Date.now();
            if (now - lastSidebarToggleTime < 250) {
                return;
            }
            lastSidebarToggleTime = now;

            const sidebar = document.getElementById('sidebarPanel');
            const backdrop = document.getElementById('sidebarBackdrop');
            const toggleBtn = document.getElementById('sidebarToggle');

            if (!sidebar) return;

            const willOpen = !sidebar.classList.contains('mobile-open');

            if (willOpen) {
                sidebar.classList.add('mobile-open');
                if (backdrop) backdrop.classList.add('show');
                if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'true');
                document.body.style.overflow = 'hidden';
            } else {
                sidebar.classList.remove('mobile-open');
                if (backdrop) backdrop.classList.remove('show');
                if (toggleBtn) toggleBtn.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = '';
            }
        }

        function closeSidebarMobile(e) {
            if (e) {
                if (typeof e.stopPropagation === 'function') e.stopPropagation();
                if (typeof e.stopImmediatePropagation === 'function') e.stopImmediatePropagation();
            }
            const sidebar = document.getElementById('sidebarPanel');
            const backdrop = document.getElementById('sidebarBackdrop');
            const toggleBtn = document.getElementById('sidebarToggle');

            if (sidebar) {
                sidebar.classList.remove('mobile-open');
            }
            if (backdrop) {
                backdrop.classList.remove('show');
            }
            if (toggleBtn) {
                toggleBtn.setAttribute('aria-expanded', 'false');
            }
            document.body.style.overflow = '';
        }

        function openSidebarMobile(e) {
            if (e) {
                if (typeof e.stopPropagation === 'function') e.stopPropagation();
                if (typeof e.stopImmediatePropagation === 'function') e.stopImmediatePropagation();
            }
            const sidebar = document.getElementById('sidebarPanel');
            const backdrop = document.getElementById('sidebarBackdrop');
            const toggleBtn = document.getElementById('sidebarToggle');

            if (sidebar) {
                sidebar.classList.add('mobile-open');
            }
            if (backdrop) {
                backdrop.classList.add('show');
            }
            if (toggleBtn) {
                toggleBtn.setAttribute('aria-expanded', 'true');
            }
            document.body.style.overflow = 'hidden';
        }

        window.toggleSidebarMobile = toggleSidebarMobile;
        window.closeSidebarMobile = closeSidebarMobile;
        window.openSidebarMobile = openSidebarMobile;

        function initSidebarMobileToggle() {
            const toggleBtn = document.getElementById('sidebarToggle');
            const backdrop = document.getElementById('sidebarBackdrop');
            const closeBtn = document.querySelector('.sidebar-close-mobile-btn');

            if (toggleBtn) {
                toggleBtn.onclick = toggleSidebarMobile;
                toggleBtn.setAttribute('aria-controls', 'sidebarPanel');
                if (!toggleBtn.hasAttribute('aria-expanded')) {
                    toggleBtn.setAttribute('aria-expanded', 'false');
                }
            }

            if (backdrop) {
                backdrop.onclick = closeSidebarMobile;
            }

            if (closeBtn) {
                closeBtn.onclick = closeSidebarMobile;
            }

            // Close when clicking any link inside sidebar
            document.querySelectorAll('#sidebarPanel .sidebar-link, #sidebarPanel a').forEach(function (link) {
                link.addEventListener('click', function () {
                    closeSidebarMobile();
                });
            });

            // Close on Escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' || e.key === 'Esc') {
                    closeSidebarMobile();
                }
            });

            // Auto close if resized to desktop breakpoint (> 992px)
            window.addEventListener('resize', function () {
                if (window.innerWidth > 992) {
                    closeSidebarMobile();
                }
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initSidebarMobileToggle);
        } else {
            initSidebarMobileToggle();
        }
    })();

    document.addEventListener('DOMContentLoaded', function () {

        // User dropdown in sidebar
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userDropdown = document.getElementById('userDropdown');

        if (userMenuBtn && userDropdown) {
            userMenuBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                const isOpen = userDropdown.classList.contains('show');
                userDropdown.classList.toggle('show');
                userMenuBtn.setAttribute('aria-expanded', !isOpen);
            });

            document.addEventListener('click', function (e) {
                if (!userDropdown.contains(e.target) && !userMenuBtn.contains(e.target)) {
                    userDropdown.classList.remove('show');
                    userMenuBtn.setAttribute('aria-expanded', 'false');
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' || e.key === 'Esc') {
                    userDropdown.classList.remove('show');
                    userMenuBtn.setAttribute('aria-expanded', 'false');
                }
            });
        }

        // Complete top progress indicator on page load
        (function() {
            let progress = document.getElementById('pageProgressIndicator');
            if (!progress) {
                progress = document.createElement('div');
                progress.id = 'pageProgressIndicator';
                document.body.appendChild(progress);
            }
            progress.style.width = '100%';
            progress.style.opacity = '1';
            setTimeout(function () {
                progress.style.opacity = '0';
                setTimeout(function () {
                    progress.style.width = '0%';
                }, 200);
            }, 140);
        })();

        // Smooth Page Transition on Sidebar & Navigation Menu Clicks
        let isNavigating = false;

        function handleNavigationClick(e) {
            // Respect special clicks (new tab, background tab, modified click)
            if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) {
                return;
            }

            const targetUrl = this.getAttribute('href');
            if (!targetUrl || targetUrl.startsWith('#') || targetUrl.startsWith('javascript:') || this.target === '_blank' || this.hasAttribute('download')) {
                return;
            }

            // Skip if clicking current URL or anchor
            const currentUrl = window.location.href;
            const currentPath = window.location.pathname + window.location.search;
            if (targetUrl === currentUrl || targetUrl === window.location.pathname || targetUrl === currentPath) {
                return;
            }

            // Prevent multi-triggering while transition is running
            if (isNavigating) {
                e.preventDefault();
                return;
            }

            // Respect prefers-reduced-motion
            if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                return;
            }

            // Intercept navigation to play smooth exit transition
            e.preventDefault();
            isNavigating = true;

            // Remember that internal navigation has occurred to keep sidebar stable
            try {
                sessionStorage.setItem('bn_navigated', 'true');
            } catch (err) {}

            // Visual active highlight on clicked link
            if (this.classList.contains('sidebar-link')) {
                document.querySelectorAll('.sidebar-link').forEach(function (l) {
                    l.classList.remove('navigating-active');
                });
                this.classList.add('navigating-active');
            }

            // Progress bar
            let progress = document.getElementById('pageProgressIndicator');
            if (!progress) {
                progress = document.createElement('div');
                progress.id = 'pageProgressIndicator';
                document.body.appendChild(progress);
            }
            progress.style.opacity = '1';
            progress.style.width = '70%';

            // Smooth exit transition on main content area
            const mainContent = document.querySelector('.content-area') || document.querySelector('.catalog-container') || document.querySelector('.about-page');
            if (mainContent) {
                mainContent.classList.add('page-transitioning-out');
            }

            // After exit transition completes (140ms), navigate to target page
            setTimeout(function () {
                if (progress) {
                    progress.style.width = '92%';
                }
                window.location.href = targetUrl;
            }, 140);

            // Failsafe reset if navigation is interrupted/cancelled
            setTimeout(function () {
                isNavigating = false;
                if (mainContent) {
                    mainContent.classList.remove('page-transitioning-out');
                }
            }, 4000);
        }

        // Event delegation for all sidebar, topbar, and navigation links
        document.addEventListener('click', function (e) {
            const link = e.target.closest('a.sidebar-link, a.topbar-nav-link, a.topbar-brand, a.topbar-icon-btn, a.topbar-user-badge, a.user-dropdown-item:not(.logout)');
            if (link) {
                handleNavigationClick.call(link, e);
            }
        });

        // BFCache (Back/Forward Cache) Restore Handler
        window.addEventListener('pageshow', function (event) {
            isNavigating = false;
            const progress = document.getElementById('pageProgressIndicator');
            if (progress) {
                progress.style.width = '100%';
                setTimeout(function () {
                    progress.style.opacity = '0';
                    progress.style.width = '0%';
                }, 140);
            }

            document.querySelectorAll('.sidebar-link').forEach(function (l) {
                l.classList.remove('navigating-active');
            });

            const mainContent = document.querySelector('.content-area') || document.querySelector('.catalog-container') || document.querySelector('.about-page');
            if (mainContent) {
                mainContent.classList.remove('page-transitioning-out');
                mainContent.style.opacity = '';
                mainContent.style.transform = '';
            }
        });
    });
</script>
