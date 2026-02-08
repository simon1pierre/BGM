@extends('layouts.audiences.app')
@section('contents')
<main class="grow bg-slate-50">
    <section class="pt-20 pb-10 bg-gradient-to-b from-blue-950 to-slate-900 text-white">
        <div class="container mx-auto px-6">
            <div class="max-w-3xl">
                <span class="inline-block py-1 px-3 rounded-full bg-blue-500/20 border border-blue-300/30 text-blue-100 text-xs font-medium tracking-widest uppercase mb-4">
                    Book Library
                </span>
                <h1 class="text-3xl md:text-5xl font-serif font-bold mb-4">Read, Study, and Grow</h1>
                <p class="text-blue-100/90 text-lg">Download PDFs, read online, and share with your community.</p>
            </div>
        </div>
    </section>

    <section class="py-10">
        <div class="container mx-auto px-6">
            <div class="flex flex-col lg:flex-row lg:items-center gap-4 pb-4">
                <form method="GET" action="{{ route('books.index') }}" class="w-full lg:max-w-md">
                    <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-full px-4 py-2 shadow-sm">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"></path>
                        </svg>
                        <input
                            type="text"
                            name="q"
                            value="{{ $search ?? '' }}"
                            placeholder="Search books, authors, series..."
                            class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
                        >
                        @if (!empty($activeCategory))
                            <input type="hidden" name="category" value="{{ $activeCategory }}">
                        @endif
                    </div>
                </form>
                <div class="flex items-center gap-3 overflow-x-auto">
                @php
                    $allActive = empty($activeCategory);
                @endphp
                <a href="{{ route('books.index') }}"
                   class="whitespace-nowrap px-4 py-2 rounded-full border text-sm font-medium inline-flex items-center gap-2 {{ $allActive ? 'bg-blue-900 text-white border-blue-900' : 'bg-white text-slate-700 border-slate-200' }}">
                    All
                    <span class="text-[11px] px-2 py-0.5 rounded-full {{ $allActive ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700' }}">{{ $allCount ?? 0 }}</span>
                </a>
                @foreach ($categories as $category)
                    <a href="{{ route('books.index', ['category' => $category->id]) }}"
                       class="whitespace-nowrap px-4 py-2 rounded-full border text-sm font-medium inline-flex items-center gap-2 {{ (string) $activeCategory === (string) $category->id ? 'bg-blue-900 text-white border-blue-900' : 'bg-white text-slate-700 border-slate-200' }}">
                        {{ $category->name }}
                        <span class="text-[11px] px-2 py-0.5 rounded-full {{ (string) $activeCategory === (string) $category->id ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700' }}">{{ $category->documents_count ?? 0 }}</span>
                    </a>
                @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-6">
                @forelse ($books as $book)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow border border-slate-100 flex flex-col">
                        <div class="relative aspect-[3/2] overflow-hidden bg-slate-100">
                            @if ($book->cover_image)
                                <img src="{{ asset('storage/'.$book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                            @else
                                <img src="{{ asset('landingpage/download-book.webp') }}" alt="Downloadable Books" class="w-full h-full object-cover">
                            @endif
                            <div class="absolute bottom-3 left-3 text-white text-sm font-medium drop-shadow">
                                {{ $book->category?->name ?? 'Book' }}
                            </div>
                            @if ($book->featured)
                                <span class="absolute top-3 left-3 bg-amber-500 text-white text-xs font-semibold px-3 py-1 rounded-full shadow">Featured</span>
                            @endif
                        </div>
                        <div class="p-6 flex-1 flex flex-col">
                            <h3 class="text-xl font-serif font-bold text-blue-950 mb-2">{{ $book->title }}</h3>
                            <p class="text-slate-600 text-sm mb-4">{{ \Illuminate\Support\Str::limit($book->description, 140) }}</p>
                            <div class="flex items-center gap-4 text-xs text-slate-500 mb-4">
                                <button
                                    type="button"
                                    class="inline-flex items-center gap-1 text-slate-600 hover:text-rose-600 transition-colors"
                                    data-like-button
                                    data-book-id="{{ $book->id }}"
                                    onclick="toggleBookLike(this)"
                                >
                                    <svg viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true">
                                        <path fill="currentColor" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6 3.99 4 6.5 4c1.74 0 3.41 0.81 4.5 2.09C12.09 4.81 13.76 4 15.5 4 18.01 4 20 6 20 8.5c0 3.78-3.4 6.86-8.55 11.54z"/>
                                    </svg>
                                    <span>Like</span>
                                    <span data-like-count>{{ $book->likes_count ?? 0 }}</span>
                                </button>
                                <a href="{{ route('books.show', $book) }}" class="text-slate-600 hover:text-blue-700 transition-colors">
                                    Comments ({{ $book->comments_count ?? 0 }})
                                </a>
                            </div>
                            <div class="mt-auto flex items-center justify-between">
                                <span class="text-xs text-slate-500">{{ $book->published_at?->toDateString() ?? $book->created_at?->toDateString() }}</span>
                                <a href="{{ route('books.show', $book) }}" class="text-blue-700 font-medium text-sm hover:text-blue-900">Read Online</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center text-slate-500">No books found for this category.</div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $books->links() }}
            </div>
        </div>
    </section>
</main>
<script>
    function csrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function collectClientMetrics() {
        const w = window.screen ? window.screen.width : null;
        const h = window.screen ? window.screen.height : null;
        const tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
        const lang = navigator.language || '';
        const platform = navigator.platform || '';
        const width = window.innerWidth || 0;
        let deviceType = 'desktop';
        if (width < 768) {
            deviceType = 'mobile';
        } else if (width < 1024) {
            deviceType = 'tablet';
        }

        return {
            screen_width: w,
            screen_height: h,
            timezone: tz,
            language: lang,
            platform: platform,
            device_type: deviceType
        };
    }

    function toggleBookLike(button) {
        const bookId = button.getAttribute('data-book-id');
        if (!bookId) return;
        const payload = collectClientMetrics();

        fetch(`/books/${bookId}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken()
            },
            body: JSON.stringify(payload)
        })
        .then((res) => res.json())
        .then((data) => {
            if (!data) return;
            const countEl = button.querySelector('[data-like-count]');
            if (countEl && typeof data.likes_count !== 'undefined') {
                countEl.textContent = data.likes_count;
            }
            button.classList.toggle('text-rose-600', data.liked);
            button.classList.toggle('text-slate-600', !data.liked);
        })
        .catch(() => {});
    }
</script>
@endsection
