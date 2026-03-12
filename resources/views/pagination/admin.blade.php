@if ($paginator->hasPages())
<nav class="flex items-center justify-between mt-6">

    {{-- LEFT: showing results --}}
    <div class="text-sm text-gray-600">
        Showing
        <span class="font-semibold text-gray-900">{{ $paginator->firstItem() ?? 0 }}</span>
        to
        <span class="font-semibold text-gray-900">{{ $paginator->lastItem() ?? 0 }}</span>
        of
        <span class="font-semibold text-gray-900">{{ $paginator->total() }}</span>
        results
    </div>

    {{-- RIGHT: pagination --}}
    <div class="flex items-center gap-1">

        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 border rounded-lg cursor-not-allowed">‹</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="px-3 py-2 text-sm bg-white border rounded-lg hover:bg-gray-100">‹</a>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)

            @if (is_string($element))
                <span class="px-3 py-2 text-sm text-gray-400">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)

                    @if ($page == $paginator->currentPage())
                        <span class="px-4 py-2 text-sm font-semibold text-white bg-blue-600 border border-blue-600 rounded-lg">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           class="px-4 py-2 text-sm bg-white border rounded-lg hover:bg-blue-50 hover:text-blue-600">
                            {{ $page }}
                        </a>
                    @endif

                @endforeach
            @endif

        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="px-3 py-2 text-sm bg-white border rounded-lg hover:bg-gray-100">›</a>
        @else
            <span class="px-3 py-2 text-sm text-gray-400 bg-gray-100 border rounded-lg cursor-not-allowed">›</span>
        @endif

    </div>
</nav>
@endif







