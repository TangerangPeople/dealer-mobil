@if ($paginator->hasPages())
    <nav class="mt-6 flex flex-wrap items-center gap-2" aria-label="Navigasi halaman kategori">
        @if ($paginator->onFirstPage())
            <span class="border border-[#bbbbbb] bg-[#f1f1f1] px-3 py-2 text-sm text-[#262626]" aria-disabled="true">Sebelumnya</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="border border-[#262626] bg-white px-3 py-2 text-sm font-semibold text-[#262626] hover:bg-[#262626] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Sebelumnya</a>
        @endif

        @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
            @if ($page === $paginator->currentPage())
                <span class="border border-[#262626] bg-[#262626] px-3 py-2 text-sm font-semibold text-white" aria-current="page">{{ $page }}</span>
            @else
                <a href="{{ $url }}" class="border border-[#262626] bg-white px-3 py-2 text-sm font-semibold text-[#262626] hover:bg-[#262626] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">{{ $page }}</a>
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="border border-[#262626] bg-white px-3 py-2 text-sm font-semibold text-[#262626] hover:bg-[#262626] hover:text-white focus:outline-none focus:ring-2 focus:ring-[#262626] focus:ring-offset-2">Berikutnya</a>
        @else
            <span class="border border-[#bbbbbb] bg-[#f1f1f1] px-3 py-2 text-sm text-[#262626]" aria-disabled="true">Berikutnya</span>
        @endif
    </nav>
@endif
