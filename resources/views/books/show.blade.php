@extends('layouts.audiences.app')
@section('contents')
<main class="grow bg-slate-50">
    <section class="pt-16 pb-10 bg-gradient-to-b from-blue-950 to-slate-900 text-white">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl">
                <span class="inline-block py-1 px-3 rounded-full bg-blue-500/20 border border-blue-300/30 text-blue-100 text-xs font-medium tracking-widest uppercase mb-4">
                    {{ __('messages.books.badge') }}
                </span>
                <h1 class="text-3xl md:text-5xl font-serif font-bold mb-3">{{ $book->title }}</h1>
                <p class="text-blue-100/90 text-lg">{{ $book->category?->name ?? __('messages.common.book') }}</p>
            </div>
        </div>
    </section>

    <section class="py-10">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100">
                        <div class="p-3 border-b border-slate-200 bg-slate-50 flex flex-wrap items-center gap-2">
                            <button type="button" id="normalPrevPage" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white">Prev</button>
                            <button type="button" id="normalNextPage" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white">Next</button>
                            <div class="flex items-center gap-2 text-sm text-slate-700">
                                <span>Page</span>
                                <input id="normalPageNumber" type="number" min="1" value="1" class="w-16 px-2 py-1.5 border border-slate-200 rounded-lg text-sm">
                            </div>
                            <div class="ml-auto flex gap-2">
                                <button type="button" id="normalFullscreen" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white">Fullscreen</button>
                            </div>
                        </div>
                        <div class="aspect-[4/3] bg-slate-100">
                            @if ($book->file_path)
                                <iframe
                                    id="normalPdfFrame"
                                    class="w-full h-full"
                                    src="{{ asset('storage/'.$book->file_path) }}#toolbar=1&view=FitH&page=1"
                                    title="{{ $book->title }}"
                                    frameborder="0"
                                ></iframe>
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-500">{{ __('messages.books.no_pdf') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-6 bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                        <div class="flex items-center gap-4 text-sm text-slate-600 mb-4">
                            <button
                                type="button"
                                class="inline-flex items-center gap-2 text-slate-600 hover:text-rose-600 transition-colors"
                                data-like-button
                                data-book-id="{{ $book->id }}"
                                onclick="toggleBookLike(this)"
                            >
                                <svg viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true">
                                    <path fill="currentColor" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6 3.99 4 6.5 4c1.74 0 3.41 0.81 4.5 2.09C12.09 4.81 13.76 4 15.5 4 18.01 4 20 6 20 8.5c0 3.78-3.4 6.86-8.55 11.54z"/>
                                </svg>
                                <span>{{ __('messages.common.like') }}</span>
                                <span data-like-count>{{ $book->likes_count ?? 0 }}</span>
                            </button>
                            <button
                                type="button"
                                class="text-slate-600 hover:text-blue-700 transition-colors"
                                data-comment-toggle
                                onclick="toggleCommentPanel(this)"
                            >
                                {{ __('messages.common.comment') }} (<span data-comment-count>{{ $book->comments_count ?? 0 }}</span>)
                            </button>
                        </div>
                        <div class="hidden space-y-3 border-t border-slate-100 pt-4" data-comment-panel>
                            <div class="space-y-2" data-comment-list>
                                @foreach ($book->comments as $comment)
                                    <div class="text-xs text-slate-600 bg-slate-50 rounded-lg p-3">
                                        <div class="font-semibold text-slate-700">{{ $comment->name ?: __('messages.common.anonymous') }}</div>
                                        <div class="mt-1">{{ $comment->body }}</div>
                                    </div>
                                @endforeach
                            </div>
                            <form data-comment-form data-book-id="{{ $book->id }}" onsubmit="return submitBookComment(this)">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                    <input type="text" name="name" placeholder="{{ __('messages.common.name_optional') }}" class="rounded-lg border border-slate-200 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    <input type="email" name="email" placeholder="{{ __('messages.common.email_optional') }}" class="rounded-lg border border-slate-200 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400">
                                </div>
                                <textarea name="body" rows="3" placeholder="{{ __('messages.common.write_comment') }}" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400" required></textarea>
                                <button type="submit" class="mt-2 inline-flex items-center justify-center px-4 py-2 text-xs font-semibold text-white bg-blue-900 rounded-lg hover:bg-blue-800 transition-colors">
                                    {{ __('messages.common.post_comment') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                        <h2 class="text-xl font-serif font-bold text-blue-950 mb-3">{{ __('messages.books.about_book') }}</h2>
                        <p class="text-slate-600 text-sm leading-relaxed">{{ $book->description }}</p>
                        <div class="mt-4 text-xs text-slate-500">
                            {{ __('messages.common.published') }}: {{ $book->published_at?->toDateString() ?? $book->created_at?->toDateString() }}
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-3">
                        <a href="{{ route('books.reader', $book) }}" class="w-full inline-flex items-center justify-center px-4 py-3 text-sm font-semibold text-slate-700 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                            Advanced Reader
                        </a>
                        <a href="{{ asset('storage/'.$book->file_path) }}" target="_blank" rel="noopener" class="w-full inline-flex items-center justify-center px-4 py-3 text-sm font-semibold text-slate-700 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                            Browser Reader
                        </a>
                        <a href="https://docs.google.com/viewer?url={{ urlencode(asset('storage/'.$book->file_path)) }}" target="_blank" rel="noopener" class="w-full inline-flex items-center justify-center px-4 py-3 text-sm font-semibold text-slate-700 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                            Compatibility Reader
                        </a>
                        <a href="{{ route('content.download.document', $book) }}" class="w-full inline-flex items-center justify-center px-4 py-3 text-sm font-semibold text-white bg-blue-900 rounded-lg hover:bg-blue-800 transition-colors">
                            {{ __('messages.home.download_pdf') }}
                        </a>
                        <button type="button" class="w-full inline-flex items-center justify-center px-4 py-3 text-sm font-semibold text-slate-700 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors" onclick="shareBook()">
                            {{ __('messages.common.share') }}
                        </button>
                        <a href="{{ route('books.index') }}" class="w-full inline-flex items-center justify-center px-4 py-3 text-sm font-semibold text-blue-900 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            {{ __('messages.books.browse_library') }}
                        </a>
                    </div>
                    @if (!empty($hasLinkedAudiobooks) && $hasLinkedAudiobooks)
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                            @php
                                $bookPrayerBase = array_filter(
                                    ['q' => request('q')],
                                    static fn ($value) => !is_null($value) && $value !== ''
                                );
                            @endphp
                            <div class="flex items-center justify-between gap-3 mb-3">
                                <h3 class="text-lg font-serif font-bold text-blue-950">Audiobook Versions</h3>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('books.show', ['book' => $book] + $bookPrayerBase) }}" class="px-2.5 py-1 rounded-full text-xs border {{ is_null($prayerFilter ?? null) ? 'bg-slate-900 border-slate-900 text-white' : 'bg-white border-slate-200 text-slate-600' }}">All</a>
                                    <a href="{{ route('books.show', ['book' => $book] + $bookPrayerBase + ['prayer' => '1']) }}" class="px-2.5 py-1 rounded-full text-xs border {{ ($prayerFilter ?? null) === true ? 'bg-slate-900 border-slate-900 text-white' : 'bg-white border-slate-200 text-slate-600' }}">Prayer</a>
                                    <a href="{{ route('books.show', ['book' => $book] + $bookPrayerBase + ['prayer' => '0']) }}" class="px-2.5 py-1 rounded-full text-xs border {{ ($prayerFilter ?? null) === false ? 'bg-slate-900 border-slate-900 text-white' : 'bg-white border-slate-200 text-slate-600' }}">Non-Prayer</a>
                                </div>
                            </div>
                            @if ($linkedAudiobooks->count())
                                <div class="space-y-4">
                                    @foreach ($linkedAudiobooks as $ab)
                                        <div class="border border-slate-100 rounded-xl p-3">
                                            <div class="flex items-center justify-between gap-2 mb-2">
                                                <div class="text-sm font-semibold text-slate-800">{{ $ab->title }}</div>
                                                @if ($ab->is_prayer_audio)
                                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] bg-emerald-100 text-emerald-700">Prayer</span>
                                                @endif
                                            </div>
                                            <audio controls class="w-full">
                                                <source src="{{ asset('storage/'.$ab->audio_file) }}" type="audio/mpeg">
                                            </audio>
                                            <a href="{{ route('audiobooks.show', $ab) }}" class="inline-flex mt-2 text-xs text-blue-700 hover:text-blue-900">Open audiobook page</a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-xs text-slate-500">No audiobooks match this prayer filter.</div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            @if (!empty($relatedBooks) && $relatedBooks->count())
                <div class="mt-12">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-serif font-bold text-blue-950">{{ __('messages.common.you_may_also_like') }}</h3>
                        <a href="{{ route('books.index') }}" class="text-sm text-blue-700 hover:text-blue-900">{{ __('messages.common.browse_all') }}</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ($relatedBooks as $item)
                            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100">
                                <div class="relative aspect-[3/2] overflow-hidden bg-slate-100">
                                    @if ($item->cover_image)
                                        <img src="{{ asset('storage/'.$item->cover_image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-500">{{ __('messages.books.no_cover') }}</div>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <div class="font-serif text-blue-950 font-semibold text-sm">{{ $item->title }}</div>
                                    <div class="text-xs text-slate-500 mt-1">{{ $item->category?->name ?? __('messages.common.book') }}</div>
                                    <a href="{{ route('books.show', $item) }}" class="inline-flex text-sm text-blue-700 hover:text-blue-900 mt-3">{{ __('messages.common.read') }}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
</main>
<script>
    function csrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function notify(message, type = 'info') {
        if (window.appToast) {
            window.appToast(message, type);
        }
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

    function trackBook(event, extra) {
        const payload = Object.assign(
            { event, page_url: window.location.href },
            collectClientMetrics(),
            extra || {}
        );

        fetch(`/books/{{ $book->id }}/track`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken()
            },
            body: JSON.stringify(payload)
        });
    }

    function shareBook() {
        const shareData = {
            title: '{{ $book->title }}',
            text: '{{ $book->title }}',
            url: window.location.href
        };

        if (navigator.share) {
            navigator.share(shareData)
                .then(() => {
                    trackBook('share', { share_channel: 'native' });
                    notify('Shared successfully.', 'success');
                })
                .catch(() => {});
        } else {
            navigator.clipboard.writeText(shareData.url).then(() => {
                trackBook('share', { share_channel: 'copy' });
                notify(@json(__('messages.common.link_copied')), 'success');
            }).catch(() => {
                notify('Unable to copy link right now.', 'error');
            });
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        trackBook('view');
    });

    function toggleCommentPanel(button) {
        const card = button.closest('.bg-white');
        const panel = card ? card.querySelector('[data-comment-panel]') : null;
        if (panel) {
            panel.classList.toggle('hidden');
        }
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
            notify(data.liked ? 'Added to liked items.' : 'Removed from liked items.', 'success');
        })
        .catch(() => {
            notify('Request failed. Please try again.', 'error');
        });
    }

    function submitBookComment(form) {
        const bookId = form.getAttribute('data-book-id');
        if (!bookId) return false;
        const formData = new FormData(form);
        const payload = Object.assign({
            name: formData.get('name'),
            email: formData.get('email'),
            body: formData.get('body')
        }, collectClientMetrics());

        fetch(`/books/${bookId}/comment`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken()
            },
            body: JSON.stringify(payload)
        })
        .then((res) => res.json())
        .then((data) => {
            if (!data || !data.comment) return;
            const list = form.closest('[data-comment-panel]').querySelector('[data-comment-list]');
            if (list) {
                const item = document.createElement('div');
                item.className = 'text-xs text-slate-600 bg-slate-50 rounded-lg p-3';
                item.innerHTML = `<div class="font-semibold text-slate-700">${escapeHtml(data.comment.name)}</div><div class="mt-1">${escapeHtml(data.comment.body)}</div>`;
                list.prepend(item);
            }
            const countEl = form.closest('[data-comment-panel]').parentElement.querySelector('[data-comment-count]');
            if (countEl && typeof data.comments_count !== 'undefined') {
                countEl.textContent = data.comments_count;
            }
            form.reset();
            notify('Comment submitted successfully.', 'success');
        })
        .catch(() => {
            notify('Unable to post comment. Please try again.', 'error');
        });

        return false;
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const frame = document.getElementById('normalPdfFrame');
        const pageInput = document.getElementById('normalPageNumber');
        const container = frame ? frame.parentElement : null;
        let page = 1;

        function updateFramePage() {
            if (!frame) return;
            const base = @json(asset('storage/'.$book->file_path));
            frame.src = `${base}#toolbar=1&view=FitH&page=${page}`;
            if (pageInput) {
                pageInput.value = page;
            }
            trackBook('read', { watch_seconds: page * 5 });
        }

        document.getElementById('normalPrevPage')?.addEventListener('click', () => {
            page = Math.max(1, page - 1);
            updateFramePage();
        });

        document.getElementById('normalNextPage')?.addEventListener('click', () => {
            page += 1;
            updateFramePage();
        });

        pageInput?.addEventListener('change', () => {
            const value = parseInt(pageInput.value, 10);
            if (!Number.isFinite(value) || value < 1) {
                pageInput.value = page;
                return;
            }
            page = value;
            updateFramePage();
        });

        document.getElementById('normalFullscreen')?.addEventListener('click', () => {
            if (!container) return;
            if (!document.fullscreenElement) {
                container.requestFullscreen?.();
            } else {
                document.exitFullscreen?.();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'ArrowRight') {
                document.getElementById('normalNextPage')?.click();
            } else if (event.key === 'ArrowLeft') {
                document.getElementById('normalPrevPage')?.click();
            } else if (event.key.toLowerCase() === 'f') {
                document.getElementById('normalFullscreen')?.click();
            }
        });
    });
</script>
@endsection
