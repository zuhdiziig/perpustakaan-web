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
            background: #0f766e !important;
            color: #ffffff !important;
            border-color: #0f766e !important;
            font-weight: 800 !important;
            box-shadow: 0 3px 8px rgba(15, 118, 110, 0.28) !important;
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
            user-select: none !important;
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
