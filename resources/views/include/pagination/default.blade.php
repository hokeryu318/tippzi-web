@if ($paginator->hasPages())
    <div class="pagination pagination-center">
        <ul>
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="disabled"><a rel="prev">Previous</a></li>
            @else
                <li class="prev"><a href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a></li>
            @endif

            {{-- Pagination Elements --}}
            @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                <li class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }}">
                    <a href="{{ $paginator->url($i) }}">{{ $i }}</a>
                </li>
            @endfor
            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="next"><a href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a></li>
            @else
                <li class="disabled"><a>Next</a></li>
            @endif
        </ul>
    </div>
@endif