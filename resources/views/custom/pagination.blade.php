@if ($paginator->hasPages())
<div class="pagination-wrapper">
    <nav aria-label="Page Navigation">
        <ul class="pagination pagination-glassmorphism justify-content-center mb-3">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">
                        <i class="fas fa-chevron-left"></i>
                        <span class="d-none d-sm-inline ms-1">Previous</span>
                    </span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}">
                        <i class="fas fa-chevron-left"></i>
                        <span class="d-none d-sm-inline ms-1">Previous</span>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled">
                        <span class="page-link">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}">
                        <span class="d-none d-sm-inline me-1">Next</span>
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">
                        <span class="d-none d-sm-inline me-1">Next</span>
                        <i class="fas fa-chevron-right"></i>
                    </span>
                </li>
            @endif
        </ul>
    </nav>

    {{-- Results Information --}}
    <div class="pagination-info text-center">
        <small class="pagination-text">
            Showing {{ $paginator->firstItem() ?? 0 }} to {{ $paginator->lastItem() ?? 0 }} of {{ $paginator->total() }} results
        </small>
    </div>
</div>

<style>
.pagination-wrapper {
    padding: 1rem 0;
}

.pagination-glassmorphism {
    --bs-pagination-bg: rgba(255, 255, 255, 0.1);
    --bs-pagination-border-color: rgba(255, 255, 255, 0.2);
    --bs-pagination-hover-bg: rgba(255, 255, 255, 0.2);
    --bs-pagination-hover-border-color: rgba(255, 255, 255, 0.3);
    --bs-pagination-focus-bg: rgba(255, 255, 255, 0.2);
    --bs-pagination-focus-border-color: rgba(255, 255, 255, 0.3);
    --bs-pagination-active-bg: rgba(255, 255, 255, 0.3);
    --bs-pagination-active-border-color: rgba(255, 255, 255, 0.4);
    --bs-pagination-disabled-bg: rgba(255, 255, 255, 0.05);
    --bs-pagination-disabled-border-color: rgba(255, 255, 255, 0.1);
    gap: 0.5rem;
}

.pagination-glassmorphism .page-link {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #333 !important;
    border-radius: 10px;
    padding: 0.5rem 0.75rem;
    backdrop-filter: blur(10px);
    transition: all 0.3s ease;
    text-decoration: none;
    min-width: 40px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    font-weight: 600;
}

.pagination-glassmorphism .page-item:hover .page-link {
    background: rgba(255, 255, 255, 0.3);
    border-color: rgba(255, 255, 255, 0.4);
    color: #222 !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.pagination-glassmorphism .page-item.active .page-link {
    background: rgba(255, 255, 255, 0.4);
    border-color: rgba(255, 255, 255, 0.5);
    color: #667eea !important;
    font-weight: bold;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
}

.pagination-glassmorphism .page-item.disabled .page-link {
    background: rgba(255, 255, 255, 0.05);
    border-color: rgba(255, 255, 255, 0.1);
    color: rgba(0, 0, 0, 0.4) !important;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.pagination-glassmorphism .page-item.disabled:hover .page-link {
    background: rgba(255, 255, 255, 0.05);
    border-color: rgba(255, 255, 255, 0.1);
    color: rgba(0, 0, 0, 0.4) !important;
    transform: none;
    box-shadow: none;
}

.pagination-info {
    margin-top: 1rem;
    padding: 0.5rem 1rem;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    display: inline-block;
}

.pagination-info small {
    color: #333 !important;
    font-weight: 600;
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .pagination-glassmorphism {
        gap: 0.25rem;
        flex-wrap: wrap;
    }
    
    .pagination-glassmorphism .page-link {
        padding: 0.4rem 0.6rem;
        min-width: 35px;
        font-size: 0.9rem;
    }
}
</style>
@endif
