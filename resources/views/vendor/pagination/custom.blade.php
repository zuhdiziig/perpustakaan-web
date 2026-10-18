@if ($paginator->hasPages())
    <style>
        .custom-pagination-controls,
        .pagination-controls {
            display: inline-flex !important;
            align-items: center !important;
            gap: 8px !important;
            flex-wrap: wrap !important;
            padding: 4px 0 !important;
            margin: 0 !important;
        }

        .custom-page-btn,
        .page-btn {
            min-width: 38px !important;
            height: 38px !important;
            padding: 0 12px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 10px !important;
            border: 1px solid #e2e8f0 !important;
            background: #ffffff !important;
            color: #334155 !important;
            font-size: 14px !important;
            font-weight: 700 !important;
            text-decoration: none !important;
            line-height: 1 !important;
            transition: all 0.15s ease-in-out !important;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04) !important;
            box-sizing: border-box !important;
            user-select: none !important;
        }

        .custom-page-btn:hover:not(.disabled):not(:disabled):not(.active),
        .page-btn:hover:not(.disabled):not(:disabled):not(.active) {
            background: #f8fafc !important;
            border-color: #cbd5e1 !important;
            color: #0f172a !important;
            transform: translateY(-1px) !important;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.06) !important;
        }

        .custom-page-btn.active,
        .page-btn.active {
            background: var(--theme-accent, #0f766e) !important;
            color: #ffffff !important;
            border-color: var(--theme-accent, #0f766e) !important;
            font-weight: 800 !important;
            box-shadow: 0 3px 8px var(--theme-accent-shadow, rgba(15, 118, 110, 0.28)) !important;
            cursor: default !important;
        }

        .custom-page-btn.disabled,
        .custom-page-btn:disabled,
        .page-btn.disabled,
        .page-btn:disabled {
            opacity: 0.55 !important;
            cursor: not-allowed !important;
            color: #94a3b8 !important;
            background: #ffffff !important;
            border-color: #e2e8f0 !important;
            box-shadow: none !important;
        }

        .custom-page-dots,
        .page-dots {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            min-width: 22px !important;
            height: 38px !important;
            padding: 0 4px !important;
            color: #94a3b8 !important;
            font-size: 14px !important;
            font-weight: 800 !important;
            letter-spacing: 1px !important;
        }

        /* Dark Mode Overrides for Pagination */
        [data-theme="dark"] .custom-page-btn,
        [data-theme="dark"] .page-btn {
            background: #1e293b !important;
            border-color: #334155 !important;
            color: #cbd5e1 !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2) !important;
        }

        [data-theme="dark"] .custom-page-btn svg,
        [data-theme="dark"] .page-btn svg {
            color: #cbd5e1 !important;
        }

        [data-theme="dark"] .custom-page-btn:hover:not(.disabled):not(:disabled):not(.active),
        [data-theme="dark"] .page-btn:hover:not(.disabled):not(:disabled):not(.active) {
            background: #334155 !important;
            border-color: #475569 !important;
            color: #f8fafc !important;
        }

        [data-theme="dark"] .custom-page-btn.active,
        [data-theme="dark"] .page-btn.active {
            background: #10b981 !important;
            border-color: #34d399 !important;
            color: #ffffff !important;
            box-shadow: 0 3px 8px rgba(16, 185, 129, 0.4) !important;
        }

        [data-theme="dark"] .custom-page-btn.disabled,
        [data-theme="dark"] .custom-page-btn:disabled,
        [data-theme="dark"] .page-btn.disabled,
        [data-theme="dark"] .page-btn:disabled {
            background: #0f172a !important;
            border-color: #1e293b !important;
            color: #475569 !important;
            opacity: 0.5 !important;
        }

        [data-theme="dark"] .custom-page-btn.disabled svg,
        [data-theme="dark"] .page-btn.disabled svg {
            color: #475569 !important;
        }

        [data-theme="dark"] .custom-page-dots,
        [data-theme="dark"] .page-dots {
            color: #64748b !important;
        }

        /* Soft Blush Overrides for Pagination */
        [data-theme="soft-blush"] .custom-page-btn,
        [data-theme="soft-blush"] .page-btn {
            background: #ffffff !important;
            border-color: #fce7ed !important;
            color: #4a2d37 !important;
        }
        [data-theme="soft-blush"] .custom-page-btn:hover:not(.disabled):not(:disabled):not(.active),
        [data-theme="soft-blush"] .page-btn:hover:not(.disabled):not(:disabled):not(.active) {
            background: #fff5f8 !important;
            border-color: #f4cdd9 !important;
            color: #b85d77 !important;
        }
        [data-theme="soft-blush"] .custom-page-btn.active,
        [data-theme="soft-blush"] .page-btn.active {
            background: #b85d77 !important;
            border-color: #b85d77 !important;
            color: #ffffff !important;
            box-shadow: 0 3px 8px rgba(184, 93, 119, 0.35) !important;
        }

        /* Midnight Slate Overrides for Pagination */
        [data-theme="midnight-slate"] .custom-page-btn,
        [data-theme="midnight-slate"] .page-btn {
            background: #111d33 !important;
            border-color: #1e3156 !important;
            color: #e2e8f0 !important;
        }
        [data-theme="midnight-slate"] .custom-page-btn svg,
        [data-theme="midnight-slate"] .page-btn svg {
            color: #94a3b8 !important;
        }
        [data-theme="midnight-slate"] .custom-page-btn:hover:not(.disabled):not(:disabled):not(.active),
        [data-theme="midnight-slate"] .page-btn:hover:not(.disabled):not(:disabled):not(.active) {
            background: #1e293b !important;
            border-color: #3b82f6 !important;
            color: #60a5fa !important;
        }
        [data-theme="midnight-slate"] .custom-page-btn.active,
        [data-theme="midnight-slate"] .page-btn.active {
            background: #2563eb !important;
            border-color: #3b82f6 !important;
            color: #ffffff !important;
            box-shadow: 0 3px 8px rgba(37, 99, 235, 0.4) !important;
        }
        [data-theme="midnight-slate"] .custom-page-btn.disabled,
        [data-theme="midnight-slate"] .custom-page-btn:disabled,
        [data-theme="midnight-slate"] .page-btn.disabled,
        [data-theme="midnight-slate"] .page-btn:disabled {
            background: #090f1d !important;
            border-color: #17243c !important;
            color: #475569 !important;
        }
        [data-theme="midnight-slate"] .custom-page-dots,
        [data-theme="midnight-slate"] .page-dots {
            color: #64748b !important;
        }

        /* Van Gogh Overrides for Pagination */
        [data-theme="van-gogh"] .custom-page-btn,
        [data-theme="van-gogh"] .page-btn {
            background: #132238 !important;
            border-color: #22446a !important;
            color: #f6f0df !important;
        }
        [data-theme="van-gogh"] .custom-page-btn svg,
        [data-theme="van-gogh"] .page-btn svg {
            color: #9fb3c8 !important;
        }
        [data-theme="van-gogh"] .custom-page-btn:hover:not(.disabled):not(:disabled):not(.active),
        [data-theme="van-gogh"] .page-btn:hover:not(.disabled):not(:disabled):not(.active) {
            background: #1f3c60 !important;
            border-color: #f59e0b !important;
            color: #fcd34d !important;
        }
        [data-theme="van-gogh"] .custom-page-btn:hover:not(.disabled):not(:disabled):not(.active) svg,
        [data-theme="van-gogh"] .page-btn:hover:not(.disabled):not(:disabled):not(.active) svg {
            color: #fcd34d !important;
        }
        [data-theme="van-gogh"] .custom-page-btn.active,
        [data-theme="van-gogh"] .page-btn.active {
            background: #245b8f !important;
            border-color: #f59e0b !important;
            color: #ffffff !important;
            box-shadow: 0 3px 8px rgba(245, 158, 11, 0.4) !important;
        }
        [data-theme="van-gogh"] .custom-page-btn.disabled,
        [data-theme="van-gogh"] .custom-page-btn:disabled,
        [data-theme="van-gogh"] .page-btn.disabled,
        [data-theme="van-gogh"] .page-btn:disabled {
            background: #0d1b2a !important;
            border-color: #1a3454 !important;
            color: #475569 !important;
        }
        [data-theme="van-gogh"] .custom-page-btn.disabled svg,
        [data-theme="van-gogh"] .page-btn.disabled svg {
            color: #475569 !important;
        }
        [data-theme="van-gogh"] .custom-page-dots,
        [data-theme="van-gogh"] .page-dots {
            color: #9fb3c8 !important;
        }
    </style>

    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="custom-pagination-controls pagination-controls">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="custom-page-btn page-btn disabled" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: #94a3b8;">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="custom-page-btn page-btn" aria-label="{{ __('pagination.previous') }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: #334155;">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </a>
        @endif

        {{-- Pagination Elements --}}
        @if (isset($elements))
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="custom-page-dots page-dots" aria-disabled="true">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="custom-page-btn page-btn active" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="custom-page-btn page-btn">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="custom-page-btn page-btn" aria-label="{{ __('pagination.next') }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: #334155;">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </a>
        @else
            <span class="custom-page-btn page-btn disabled" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="color: #94a3b8;">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </span>
        @endif
    </nav>
@endif
