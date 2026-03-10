@if ($paginator->hasPages())
  <nav class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3" role="navigation" aria-label="Pagination">
    <p class="text-xs text-slate-500 order-2 sm:order-1">
      Showing <span class="font-semibold text-slate-700">{{ $paginator->firstItem() }}</span>
      to <span class="font-semibold text-slate-700">{{ $paginator->lastItem() }}</span>
      of <span class="font-semibold text-slate-700">{{ $paginator->total() }}</span> results
    </p>

    <div class="flex items-center gap-2 order-1 sm:order-2">
      @if ($paginator->onFirstPage())
        <span class="px-3 py-1.5 text-sm rounded-full border border-slate-200 text-slate-400 cursor-not-allowed">Previous</span>
      @else
        <a class="px-3 py-1.5 text-sm rounded-full border border-slate-200 text-slate-700 hover:bg-white hover:text-blue-900 transition-colors" href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a>
      @endif

      <div class="hidden sm:flex items-center gap-1">
        @foreach ($elements as $element)
          @if (is_string($element))
            <span class="px-2 text-slate-400">…</span>
          @endif

          @if (is_array($element))
            @foreach ($element as $page => $url)
              @if ($page == $paginator->currentPage())
                <span class="w-8 h-8 rounded-full bg-blue-900 text-white text-sm inline-flex items-center justify-center">{{ $page }}</span>
              @else
                <a class="w-8 h-8 rounded-full text-sm inline-flex items-center justify-center text-slate-600 hover:bg-blue-50 hover:text-blue-900 transition-colors" href="{{ $url }}">{{ $page }}</a>
              @endif
            @endforeach
          @endif
        @endforeach
      </div>

      @if ($paginator->hasMorePages())
        <a class="px-3 py-1.5 text-sm rounded-full border border-slate-200 text-slate-700 hover:bg-white hover:text-blue-900 transition-colors" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>
      @else
        <span class="px-3 py-1.5 text-sm rounded-full border border-slate-200 text-slate-400 cursor-not-allowed">Next</span>
      @endif
    </div>
  </nav>
@endif
