@if ($paginator->hasPages())
    <nav class="flex flex-col md:flex-row md:items-center md:justify-between gap-4" aria-label="Pagination">
        <div class="text-sm text-slate-500">
            Showing
            <span class="font-semibold text-slate-700">{{ $paginator->firstItem() ?? 0 }}</span>
            to
            <span class="font-semibold text-slate-700">{{ $paginator->lastItem() ?? 0 }}</span>
            of
            <span class="font-semibold text-slate-700">{{ $paginator->total() }}</span>
            results
        </div>
        <div class="flex items-center gap-2">
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-200 text-slate-400 bg-slate-50" aria-disabled="true">
                    <span class="text-lg">&laquo;</span>
                    Previous
                </span>
            @else
                <a class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                    <span class="text-lg">&laquo;</span>
                    Previous
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100" href="{{ $paginator->nextPageUrl() }}" rel="next">
                    Next
                    <span class="text-lg">&raquo;</span>
                </a>
            @else
                <span class="inline-flex items-center gap-2 px-3 py-2 rounded-lg border border-slate-200 text-slate-400 bg-slate-50" aria-disabled="true">
                    Next
                    <span class="text-lg">&raquo;</span>
                </span>
            @endif
        </div>
    </nav>
@endif







