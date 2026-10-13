@auth
    @if (auth()->user()->role === 'member')
        <div id="member-feedback-toasts" class="member-feedback-toasts" aria-live="polite" aria-atomic="false"></div>

        <style>
            .member-feedback-toasts {
                position: fixed;
                top: 1.25rem;
                right: 1.25rem;
                z-index: 1200;
                display: grid;
                gap: .75rem;
                width: min(25rem, calc(100vw - 2rem));
                pointer-events: none;
            }

            .member-feedback-toast {
                display: flex;
                align-items: flex-start;
                gap: .75rem;
                padding: .95rem 1rem;
                border: 1px solid;
                border-radius: 1rem;
                background: #fff;
                box-shadow: 0 14px 38px rgb(15 23 42 / 16%);
                color: #1f2937;
                pointer-events: auto;
                animation: member-toast-enter .24s ease-out both;
            }

            .member-feedback-toast[data-status="success"] { border-color: #a7f3d0; }
            .member-feedback-toast[data-status="error"] { border-color: #fecaca; }
            .member-feedback-toast[data-status="info"] { border-color: #bfdbfe; }

            .member-feedback-toast-icon {
                display: grid;
                flex: 0 0 2rem;
                width: 2rem;
                height: 2rem;
                place-items: center;
                border-radius: 999px;
            }

            .member-feedback-toast[data-status="success"] .member-feedback-toast-icon { background: #dcfce7; color: #15803d; }
            .member-feedback-toast[data-status="error"] .member-feedback-toast-icon { background: #fee2e2; color: #b91c1c; }
            .member-feedback-toast[data-status="info"] .member-feedback-toast-icon { background: #dbeafe; color: #1d4ed8; }

            .member-feedback-toast-message {
                flex: 1;
                padding-top: .3rem;
                font-size: .9rem;
                line-height: 1.45;
                overflow-wrap: anywhere;
            }

            .member-feedback-toast-close {
                display: grid;
                flex: 0 0 1.75rem;
                width: 1.75rem;
                height: 1.75rem;
                place-items: center;
                border: 0;
                border-radius: .5rem;
                background: transparent;
                color: #64748b;
                cursor: pointer;
            }

            .member-feedback-toast-close:hover { background: #f1f5f9; color: #0f172a; }
            .member-feedback-toast.is-leaving { animation: member-toast-leave .18s ease-in both; }

            @keyframes member-toast-enter {
                from { opacity: 0; transform: translate3d(1rem, -.25rem, 0); }
                to { opacity: 1; transform: translate3d(0, 0, 0); }
            }

            @keyframes member-toast-leave {
                to { opacity: 0; transform: translate3d(1rem, -.25rem, 0); }
            }

            @media (max-width: 640px) {
                .member-feedback-toasts { top: .75rem; right: .75rem; left: .75rem; width: auto; }
            }

            @media (prefers-reduced-motion: reduce) {
                .member-feedback-toast, .member-feedback-toast.is-leaving { animation-duration: .01ms; }
            }
        </style>

        <script>
            (() => {
                const notifications = {{ Illuminate\Support\Js::from([
                    ['status' => 'success', 'message' => session('success')],
                    ['status' => 'error', 'message' => session('error') ?? $errors->first()],
                    ['status' => 'info', 'message' => session('info')],
                ]) }};
                const stack = document.getElementById('member-feedback-toasts');

                if (!stack || stack.dataset.initialized === 'true') {
                    return;
                }

                stack.dataset.initialized = 'true';
                const seen = new Set();
                const icons = {
                    success: 'M20 6 9 17l-5-5',
                    error: 'M18 6 6 18M6 6l12 12',
                    info: 'M12 16v-4m0-4h.01M22 12a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z',
                };

                const dismiss = (toast) => {
                    toast.classList.add('is-leaving');
                    toast.addEventListener('animationend', () => toast.remove(), { once: true });
                    window.setTimeout(() => toast.remove(), 250);
                };

                notifications.forEach((notification) => {
                    if (typeof notification.message !== 'string' || notification.message.trim() === '') {
                        return;
                    }

                    const key = `${notification.status}:${notification.message}`;

                    if (seen.has(key)) {
                        return;
                    }

                    seen.add(key);
                    const status = ['success', 'error', 'info'].includes(notification.status) ? notification.status : 'info';
                    const toast = document.createElement('div');
                    toast.className = 'member-feedback-toast';
                    toast.dataset.status = status;
                    toast.setAttribute('role', status === 'error' ? 'alert' : 'status');

                    const icon = document.createElement('span');
                    icon.className = 'member-feedback-toast-icon';
                    icon.setAttribute('aria-hidden', 'true');
                    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
                    svg.setAttribute('viewBox', '0 0 24 24');
                    svg.setAttribute('width', '18');
                    svg.setAttribute('height', '18');
                    svg.setAttribute('fill', 'none');
                    svg.setAttribute('stroke', 'currentColor');
                    svg.setAttribute('stroke-width', '2');
                    svg.setAttribute('stroke-linecap', 'round');
                    svg.setAttribute('stroke-linejoin', 'round');
                    const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
                    path.setAttribute('d', icons[status]);
                    svg.append(path);
                    icon.append(svg);

                    const message = document.createElement('div');
                    message.className = 'member-feedback-toast-message';
                    message.textContent = notification.message;

                    const close = document.createElement('button');
                    close.className = 'member-feedback-toast-close';
                    close.type = 'button';
                    close.setAttribute('aria-label', 'Tutup notifikasi');
                    close.textContent = '×';
                    close.addEventListener('click', () => dismiss(toast));

                    toast.append(icon, message, close);
                    stack.append(toast);

                    if (status !== 'error') {
                        window.setTimeout(() => dismiss(toast), 6000);
                    }
                });
            })();
        </script>
    @endif
@endauth
