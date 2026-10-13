<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Sidebar Mobile Toggle
        const toggleBtn = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebarPanel');
        const backdrop = document.getElementById('sidebarBackdrop');

        if (toggleBtn && sidebar && backdrop) {
            toggleBtn.addEventListener('click', function () {
                sidebar.classList.toggle('mobile-open');
                backdrop.classList.toggle('show');
            });

            backdrop.addEventListener('click', function () {
                sidebar.classList.remove('mobile-open');
                backdrop.classList.remove('show');
            });
        }

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
        }

        // Smooth Page Exit Transition on Navigation
        document.querySelectorAll('a.topbar-nav-link, a.sidebar-link, a.topbar-brand, a.topbar-icon-btn, a.topbar-user-badge').forEach(function (link) {
            link.addEventListener('click', function (e) {
                const targetUrl = this.getAttribute('href');
                if (!targetUrl || targetUrl.startsWith('#') || targetUrl.startsWith('javascript:') || this.target === '_blank') return;
                if (targetUrl === window.location.href) return;

                // Show top progress indicator bar
                let progress = document.getElementById('pageProgressIndicator');
                if (!progress) {
                    progress = document.createElement('div');
                    progress.id = 'pageProgressIndicator';
                    document.body.appendChild(progress);
                }
                progress.style.width = '75%';
                progress.style.opacity = '1';

                const mainContent = document.querySelector('.content-area') || document.querySelector('.page-wrapper') || document.querySelector('.catalog-container') || document.querySelector('.about-page');
                if (mainContent) {
                    mainContent.style.transition = 'opacity 0.18s ease-out, transform 0.18s ease-out';
                    mainContent.style.opacity = '0.45';
                    mainContent.style.transform = 'translateY(-4px)';
                }
            });
        });
    });
</script>
