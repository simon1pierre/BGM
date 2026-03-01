@extends('layouts.audiences.app')
@section('contents')
<style>
    .reader-loading-spinner {
        width: 42px;
        height: 42px;
        margin: 0 auto;
        border-radius: 9999px;
        border: 3px solid #cbd5e1;
        border-top-color: #1d4ed8;
        animation: readerSpin 0.85s linear infinite;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    @keyframes readerSpin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
<main id="bookShowMain" class="grow bg-slate-50 min-h-screen">
    <section class="pt-6 pb-4 bg-gradient-to-b from-blue-950 to-slate-900 text-white">
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

    <section class="py-0">
        <div class="w-full px-0 md:px-3">
            <div class="grid grid-cols-1 gap-8">
                <div>
                    <div id="bookReaderCard" class="bg-white md:rounded-2xl overflow-hidden shadow-sm border border-slate-100">
                        <div id="normalReaderToolbar" class="p-3 border-b border-slate-200 bg-slate-50 flex flex-wrap items-center gap-2 sticky top-0 z-20">
                            <button type="button" id="normalPrevPage" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white">{{ __('messages.common.prev') }}</button>
                            <button type="button" id="normalNextPage" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white">{{ __('messages.common.next') }}</button>
                            <div class="flex items-center gap-2 text-sm text-slate-700">
                                <span>{{ __('messages.common.page') }}</span>
                                <input id="normalPageNumber" type="number" min="1" value="1" class="w-16 px-2 py-1.5 border border-slate-200 rounded-lg text-sm">
                            </div>
                            <div class="ml-auto flex gap-2">
                                <button type="button" id="normalFullscreen" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white">{{ __('messages.common.fullscreen') }}</button>
                            </div>
                        </div>
                        <div id="normalReaderContainer" class="relative bg-slate-100 h-[calc(100vh-4rem)] md:h-[calc(100vh-4.5rem)]">
                            <div id="normalReaderLoading" class="absolute inset-0 z-10 flex items-center justify-center bg-slate-100">
                                <div class="text-center">
                                    <div class="reader-loading-spinner">
                                        <img src="{{ asset('images/logo.png') }}" alt="Ministry Logo" class="w-4 h-4 object-contain rounded-full bg-white p-[1px]">
                                    </div>
                                    <p class="mt-2 text-xs text-slate-600">Loading book pages...</p>
                                </div>
                            </div>
                            @if ($book->file_path)
                                <iframe
                                    id="normalPdfFrame"
                                    class="w-full h-full"
                                    src="{{ asset('storage/'.$book->file_path) }}#toolbar=1&view=FitH&page=1"
                                    title="{{ $book->title }}"
                                    loading="eager"
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
                <div id="readerToolsSection" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="hidden bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                        <h2 class="text-xl font-serif font-bold text-blue-950 mb-3">{{ __('messages.books.about_book') }}</h2>
                        <p class="text-slate-600 text-sm leading-relaxed">{{ $book->description }}</p>
                        <div class="mt-4 text-xs text-slate-500">
                            {{ __('messages.common.published') }}: {{ $book->published_at?->toDateString() ?? $book->created_at?->toDateString() }}
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 space-y-3">
                        @if ($book->file_path)
                            <button type="button" id="openReadModal" class="w-full inline-flex items-center justify-center px-4 py-3 text-sm font-semibold text-white bg-blue-900 rounded-lg hover:bg-blue-800 transition-colors">
                                Read Book (Modal)
                            </button>
                        @endif
                        <button type="button" id="openBookAboutModal" class="w-full inline-flex items-center justify-center px-4 py-3 text-sm font-semibold text-slate-700 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                            {{ __('messages.books.about_book') }}
                        </button>
                        <a href="{{ route('books.reader', $book) }}" class="w-full inline-flex items-center justify-center px-4 py-3 text-sm font-semibold text-slate-700 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                            {{ __('messages.books.advanced_reader') }}
                        </a>
                        <a href="{{ asset('storage/'.$book->file_path) }}" target="_blank" rel="noopener" class="w-full inline-flex items-center justify-center px-4 py-3 text-sm font-semibold text-slate-700 border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                            {{ __('messages.books.browser_reader') }}
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
                                <h3 class="text-lg font-serif font-bold text-blue-950">{{ __('messages.books.audiobook_versions') }}</h3>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('books.show', ['book' => $book] + $bookPrayerBase) }}" class="px-2.5 py-1 rounded-full text-xs border {{ is_null($prayerFilter ?? null) ? 'bg-slate-900 border-slate-900 text-white' : 'bg-white border-slate-200 text-slate-600' }}">{{ __('messages.common.all') }}</a>
                                    <a href="{{ route('books.show', ['book' => $book] + $bookPrayerBase + ['prayer' => '1']) }}" class="px-2.5 py-1 rounded-full text-xs border {{ ($prayerFilter ?? null) === true ? 'bg-slate-900 border-slate-900 text-white' : 'bg-white border-slate-200 text-slate-600' }}">{{ __('messages.common.prayer') }}</a>
                                    <a href="{{ route('books.show', ['book' => $book] + $bookPrayerBase + ['prayer' => '0']) }}" class="px-2.5 py-1 rounded-full text-xs border {{ ($prayerFilter ?? null) === false ? 'bg-slate-900 border-slate-900 text-white' : 'bg-white border-slate-200 text-slate-600' }}">{{ __('messages.common.non_prayer') }}</a>
                                </div>
                            </div>
                            @if ($linkedAudiobooks->count())
                                @php
                                    $bookQueue = [];
                                    $bookQueueByLang = ['rw' => [], 'en' => [], 'fr' => []];
                                    foreach ($linkedAudiobooks as $ab) {
                                        if ($ab->publishedParts->count() > 0) {
                                            foreach ($ab->publishedParts as $part) {
                                                $partLabel = trim((string) $part->title);
                                                $trackLabel = $partLabel !== '' ? $partLabel : trim((string) $ab->title);
                                                $track = [
                                                    'key' => 'part_'.$part->id,
                                                    'label' => $trackLabel,
                                                    'audio' => asset('storage/'.$part->audio_file),
                                                    'download' => route('content.download.audiobook-part', $part),
                                                    'lang' => in_array($part->language, ['rw', 'en', 'fr'], true) ? $part->language : 'rw',
                                                    'source_url' => route('books.show', $book),
                                                    'source_name' => $ab->title,
                                                ];
                                                $bookQueue[] = $track;
                                                $bookQueueByLang[$track['lang']][] = $track;
                                            }
                                        } else {
                                            $playable = $ab->resolvePlayableAudioFile();
                                            if (!empty($playable)) {
                                                $track = [
                                                    'key' => 'ab_'.$ab->id,
                                                    'label' => $ab->title,
                                                    'audio' => asset('storage/'.$playable),
                                                    'download' => asset('storage/'.$playable),
                                                    'lang' => 'rw',
                                                    'source_url' => route('books.show', $book),
                                                    'source_name' => $ab->title,
                                                ];
                                                $bookQueue[] = $track;
                                                $bookQueueByLang['rw'][] = $track;
                                            }
                                        }
                                    }
                                @endphp
                                @if (count($bookQueue) > 0)
                                    <style>
                                        .book-track-row.book-track-active {
                                            background-color: #343743;
                                            color: #ffffff;
                                        }
                                    </style>
                                    <div class="mb-4 rounded-2xl overflow-hidden border border-slate-700 bg-[#1b1c22] text-slate-100 shadow-sm">
                                        <div class="px-3 py-2 border-b border-slate-700 bg-[#22242c]">
                                            <div class="text-[11px] uppercase tracking-wide text-slate-300">{{ __('messages.books.audiobook_while_reading') }}</div>
                                            <div id="bookLinkedNowPlaying" class="text-sm font-semibold mt-1 truncate">{{ $bookQueue[0]['label'] }}</div>
                                        </div>
                                        <div class="p-3 border-b border-slate-700">
                                            <audio id="bookLinkedPlayer" class="hidden" preload="none">
                                                <source id="bookLinkedSource" src="{{ $bookQueue[0]['audio'] }}" type="audio/mpeg">
                                            </audio>
                                            <div class="mb-3 flex flex-wrap gap-2">
                                                <button type="button" class="book-lang-tab px-2.5 py-1 rounded border border-slate-500 text-[11px] bg-[#343743] text-white" data-book-lang="rw">Kinyarwanda ({{ count($bookQueueByLang['rw']) }})</button>
                                                <button type="button" class="book-lang-tab px-2.5 py-1 rounded border border-slate-500 text-[11px]" data-book-lang="en">English ({{ count($bookQueueByLang['en']) }})</button>
                                                <button type="button" class="book-lang-tab px-2.5 py-1 rounded border border-slate-500 text-[11px]" data-book-lang="fr">French ({{ count($bookQueueByLang['fr']) }})</button>
                                            </div>
                                            <div class="flex items-center gap-2 mb-3">
                                                <button type="button" id="bookLinkedPrev" class="px-3 py-2 rounded bg-[#2b2d36] hover:bg-[#373a45] text-sm" aria-label="Previous">Prev</button>
                                                <button type="button" id="bookLinkedPlayPause" class="px-4 py-2 rounded bg-[#ff006e] hover:bg-[#e00062] text-sm font-semibold min-w-[72px]">Play</button>
                                                <button type="button" id="bookLinkedNext" class="px-3 py-2 rounded bg-[#2b2d36] hover:bg-[#373a45] text-sm" aria-label="Next">Next</button>
                                                <div class="flex items-center gap-2 ml-auto">
                                                    <span class="text-xs text-slate-300">Vol</span>
                                                    <input id="bookLinkedVolume" type="range" min="0" max="1" step="0.05" value="1" class="accent-pink-500 w-24">
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between text-xs text-slate-300">
                                                <span id="bookLinkedCurrentTime">00:00</span>
                                                <label class="inline-flex items-center gap-2">
                                                    <input id="bookLinkedAutoNext" type="checkbox" class="rounded border-slate-500 bg-[#2b2d36]" checked>
                                                    Auto next
                                                </label>
                                                <span id="bookLinkedDuration">00:00</span>
                                            </div>
                                        </div>
                                        <div id="bookLinkedQueue" class="max-h-80 overflow-y-auto">
                                            @foreach ($bookQueue as $index => $track)
                                                <button
                                                    type="button"
                                                    class="book-track-row w-full text-left px-3 py-3 border-b border-slate-700/80 hover:bg-[#2a2c35] transition-colors"
                                                    data-book-track
                                                    data-track-index="{{ $index }}"
                                                    data-track-key="{{ $track['key'] }}"
                                                    data-track-title="{{ $track['label'] }}"
                                                    data-track-src="{{ $track['audio'] }}"
                                                    data-track-lang="{{ $track['lang'] }}"
                                                    data-track-source="{{ $track['source_url'] }}"
                                                >
                                                    <div class="flex items-center justify-between gap-3">
                                                        <div class="min-w-0">
                                                            <div class="text-sm font-semibold truncate">{{ $loop->iteration }}. {{ $track['label'] }}</div>
                                                        </div>
                                                        <a href="{{ $track['download'] }}" download class="text-slate-200 hover:text-white" title="{{ __('messages.common.download') }}" data-book-track-download>
                                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                                <path d="M12 3v11m0 0l4-4m-4 4l-4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                        </a>
                                                    </div>
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                <div class="space-y-2 text-xs text-slate-500">
                                    <div>{{ __('messages.books.audiobook_while_reading') }}</div>
                                </div>
                                <script>
                                    (() => {
                                        const player = document.getElementById('bookLinkedPlayer');
                                        const source = document.getElementById('bookLinkedSource');
                                        const nowNode = document.getElementById('bookLinkedNowPlaying');
                                        const autoNext = document.getElementById('bookLinkedAutoNext');
                                        const prevButton = document.getElementById('bookLinkedPrev');
                                        const nextButton = document.getElementById('bookLinkedNext');
                                        const playPauseButton = document.getElementById('bookLinkedPlayPause');
                                        const volumeSlider = document.getElementById('bookLinkedVolume');
                                        const currentTimeNode = document.getElementById('bookLinkedCurrentTime');
                                        const durationNode = document.getElementById('bookLinkedDuration');
                                        const buttons = Array.from(document.querySelectorAll('[data-book-track]'));
                                        const langTabs = Array.from(document.querySelectorAll('[data-book-lang]'));
                                        if (!player || !source || buttons.length === 0) return;

                                        let activeIndex = 0;
                                        let activeLanguage = 'rw';
                                        const formatTime = (seconds) => {
                                            if (!Number.isFinite(seconds)) return '00:00';
                                            const total = Math.max(0, Math.floor(seconds));
                                            const m = String(Math.floor(total / 60)).padStart(2, '0');
                                            const s = String(total % 60).padStart(2, '0');
                                            return `${m}:${s}`;
                                        };

                                        const syncPlayState = () => {
                                            if (!playPauseButton) return;
                                            playPauseButton.textContent = player.paused ? 'Play' : 'Pause';
                                        };

                                        const selectTrack = (index, play = true) => {
                                            if (index < 0 || index >= buttons.length) return;
                                            activeIndex = index;
                                            const button = buttons[index];
                                            source.src = button.getAttribute('data-track-src') || '';
                                            player.load();
                                            if (play) player.play().catch(() => {});
                                            if (nowNode) nowNode.textContent = button.getAttribute('data-track-title') || '';
                                            buttons.forEach((item) => item.classList.remove('book-track-active'));
                                            button.classList.add('book-track-active');
                                            syncPlayState();
                                        };

                                        const visibleButtons = () => buttons.filter((button) => !button.classList.contains('hidden'));

                                        const applyLanguageFilter = (lang) => {
                                            activeLanguage = lang;
                                            langTabs.forEach((tab) => {
                                                const isActive = tab.getAttribute('data-book-lang') === lang;
                                                tab.classList.toggle('bg-[#343743]', isActive);
                                                tab.classList.toggle('text-white', isActive);
                                            });
                                            buttons.forEach((button) => {
                                                const rowLang = button.getAttribute('data-track-lang') || 'rw';
                                                button.classList.toggle('hidden', rowLang !== lang);
                                            });
                                        };

                                        buttons.forEach((button, index) => {
                                            button.addEventListener('click', () => selectTrack(index, true));
                                            button.querySelector('[data-book-track-download]')?.addEventListener('click', (event) => {
                                                event.stopPropagation();
                                            });
                                        });

                                        langTabs.forEach((tab) => {
                                            tab.addEventListener('click', () => {
                                                const lang = tab.getAttribute('data-book-lang') || 'rw';
                                                applyLanguageFilter(lang);
                                                const visible = visibleButtons();
                                                if (visible.length === 0) return;
                                                const nextIndex = Number(visible[0].getAttribute('data-track-index') || 0);
                                                selectTrack(nextIndex, false);
                                            });
                                        });

                                        prevButton?.addEventListener('click', () => {
                                            const visible = visibleButtons();
                                            const pos = visible.findIndex((button) => Number(button.getAttribute('data-track-index')) === activeIndex);
                                            if (pos > 0) {
                                                const nextIndex = Number(visible[pos - 1].getAttribute('data-track-index') || 0);
                                                selectTrack(nextIndex, true);
                                            }
                                        });

                                        nextButton?.addEventListener('click', () => {
                                            const visible = visibleButtons();
                                            const pos = visible.findIndex((button) => Number(button.getAttribute('data-track-index')) === activeIndex);
                                            if (pos >= 0 && pos + 1 < visible.length) {
                                                const nextIndex = Number(visible[pos + 1].getAttribute('data-track-index') || 0);
                                                selectTrack(nextIndex, true);
                                            }
                                        });

                                        playPauseButton?.addEventListener('click', () => {
                                            if (player.paused) {
                                                player.play().catch(() => {});
                                            } else {
                                                player.pause();
                                            }
                                        });

                                        volumeSlider?.addEventListener('input', () => {
                                            player.volume = Number(volumeSlider.value || 1);
                                        });

                                        player.addEventListener('timeupdate', () => {
                                            if (currentTimeNode) currentTimeNode.textContent = formatTime(player.currentTime);
                                            if (durationNode) durationNode.textContent = formatTime(player.duration);
                                        });

                                        player.addEventListener('loadedmetadata', () => {
                                            if (durationNode) durationNode.textContent = formatTime(player.duration);
                                        });

                                        player.addEventListener('play', syncPlayState);
                                        player.addEventListener('pause', syncPlayState);

                                        player.addEventListener('ended', () => {
                                            if (!autoNext || !autoNext.checked) return;
                                            const visible = visibleButtons();
                                            const pos = visible.findIndex((button) => Number(button.getAttribute('data-track-index')) === activeIndex);
                                            if (pos >= 0 && pos + 1 < visible.length) {
                                                const nextIndex = Number(visible[pos + 1].getAttribute('data-track-index') || 0);
                                                selectTrack(nextIndex, true);
                                            }
                                        });

                                        if (!buttons.some((button) => (button.getAttribute('data-track-lang') || 'rw') === activeLanguage)) {
                                            activeLanguage = buttons[0].getAttribute('data-track-lang') || 'rw';
                                        }
                                        applyLanguageFilter(activeLanguage);
                                        const visible = visibleButtons();
                                        if (visible.length > 0) {
                                            const firstIndex = Number(visible[0].getAttribute('data-track-index') || 0);
                                            selectTrack(firstIndex, false);
                                        }
                                        syncPlayState();
                                    })();
                                </script>
                            @else
                                <div class="text-xs text-slate-500">{{ __('messages.books.no_audiobooks_filter') }}</div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            <div id="bookAboutModal" class="hidden fixed inset-0 z-[80] bg-slate-950/70 p-4 md:p-8 overflow-y-auto">
                <div class="mx-auto w-full max-w-3xl bg-white rounded-2xl border border-slate-200 shadow-2xl">
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                        <h2 class="text-xl font-serif font-bold text-blue-950">{{ __('messages.books.about_book') }}</h2>
                        <button type="button" id="closeBookAboutModal" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs text-slate-600 hover:bg-slate-50">Close</button>
                    </div>
                    <div class="p-5">
                        <p class="text-slate-600 text-sm leading-relaxed">{{ $book->description ?: __('messages.books.no_description') }}</p>
                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs text-slate-500">
                            <div><span class="text-slate-700 font-semibold">{{ __('messages.books.author') }}:</span> {{ $book->author ?: '-' }}</div>
                            <div><span class="text-slate-700 font-semibold">{{ __('messages.books.series') }}:</span> {{ $book->series ?: '-' }}</div>
                            <div><span class="text-slate-700 font-semibold">{{ __('messages.common.published') }}:</span> {{ $book->published_at?->toDateString() ?? $book->created_at?->toDateString() }}</div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($book->file_path)
                <div id="bookReadModal" class="hidden fixed inset-0 z-[90] bg-slate-950/80 p-2 md:p-4">
                    <div class="w-full h-full bg-white rounded-xl overflow-hidden shadow-2xl flex flex-col">
                        <div class="px-3 py-2 border-b border-slate-200 bg-slate-50 flex flex-wrap items-center gap-2">
                            <button type="button" id="modalPrevPage" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white">{{ __('messages.common.prev') }}</button>
                            <button type="button" id="modalNextPage" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white">{{ __('messages.common.next') }}</button>
                            <div class="flex items-center gap-2 text-sm text-slate-700">
                                <span>{{ __('messages.common.page') }}</span>
                                <input id="modalPageNumber" type="number" min="1" value="1" class="w-16 px-2 py-1.5 border border-slate-200 rounded-lg text-sm">
                            </div>
                            <div class="ml-auto flex items-center gap-2">
                                <button type="button" id="modalReaderFullscreen" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white">{{ __('messages.common.fullscreen') }}</button>
                                <button type="button" id="closeReadModal" class="px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white">Close</button>
                            </div>
                        </div>
                        <div class="flex-1 grid grid-cols-1 lg:grid-cols-12 min-h-0">
                            <div class="lg:col-span-8 relative bg-slate-100 min-h-0" id="modalReaderViewport">
                                <div id="modalReaderLoading" class="absolute inset-0 z-10 flex items-center justify-center bg-slate-100">
                                    <div class="text-center">
                                        <div class="reader-loading-spinner">
                                            <img src="{{ asset('images/logo.png') }}" alt="Ministry Logo" class="w-4 h-4 object-contain rounded-full bg-white p-[1px]">
                                        </div>
                                        <p class="mt-2 text-xs text-slate-600">Opening reader...</p>
                                    </div>
                                </div>
                                <iframe
                                    id="modalPdfFrame"
                                    class="w-full h-full"
                                    src="about:blank"
                                    data-base-src="{{ asset('storage/'.$book->file_path) }}"
                                    title="{{ $book->title }} - Modal Reader"
                                    frameborder="0"
                                ></iframe>
                            </div>
                            <div class="lg:col-span-4 border-l border-slate-200 bg-white min-h-0 overflow-hidden flex flex-col">
                                <div class="px-4 py-3 border-b border-slate-100">
                                    <h3 class="font-serif text-lg text-slate-900 font-bold">{{ __('messages.books.audiobook_while_reading') }}</h3>
                                </div>
                                @php
                                    $modalBookQueue = [];
                                    foreach ($linkedAudiobooks as $ab) {
                                        if ($ab->publishedParts->count() > 0) {
                                            foreach ($ab->publishedParts as $part) {
                                                $partLabel = trim((string) $part->title);
                                                $trackLabel = $partLabel !== '' ? $partLabel : trim((string) $ab->title);
                                                $modalBookQueue[] = [
                                                    'label' => $trackLabel,
                                                    'audio' => asset('storage/'.$part->audio_file),
                                                    'download' => route('content.download.audiobook-part', $part),
                                                    'lang' => in_array($part->language, ['rw', 'en', 'fr'], true) ? $part->language : 'rw',
                                                ];
                                            }
                                        } else {
                                            $playable = $ab->resolvePlayableAudioFile();
                                            if (!empty($playable)) {
                                                $modalBookQueue[] = [
                                                    'label' => $ab->title,
                                                    'audio' => asset('storage/'.$playable),
                                                    'download' => asset('storage/'.$playable),
                                                    'lang' => 'rw',
                                                ];
                                            }
                                        }
                                    }
                                @endphp
                                @if (count($modalBookQueue) > 0)
                                    <div class="px-4 py-3 border-b border-slate-100">
                                        <audio id="modalBookAudioPlayer" class="hidden" preload="none">
                                            <source id="modalBookAudioSource" src="{{ $modalBookQueue[0]['audio'] }}" type="audio/mpeg">
                                        </audio>
                                        <div id="modalBookNowPlaying" class="text-sm font-semibold text-slate-800 truncate mb-2">{{ $modalBookQueue[0]['label'] }}</div>
                                        <div class="flex items-center gap-2">
                                            <button type="button" id="modalBookPlayPause" class="px-3 py-1.5 rounded bg-slate-900 text-white text-xs">Play</button>
                                            <button type="button" id="modalBookPrev" class="px-2.5 py-1.5 rounded border border-slate-200 text-xs">Prev</button>
                                            <button type="button" id="modalBookNext" class="px-2.5 py-1.5 rounded border border-slate-200 text-xs">Next</button>
                                            <select id="modalBookLang" class="ml-auto text-xs border border-slate-200 rounded px-2 py-1">
                                                <option value="all">All</option>
                                                <option value="rw">RW</option>
                                                <option value="en">EN</option>
                                                <option value="fr">FR</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="modalBookQueue" class="overflow-y-auto flex-1">
                                        @foreach ($modalBookQueue as $index => $track)
                                            <button
                                                type="button"
                                                class="w-full text-left px-4 py-3 border-b border-slate-100 hover:bg-slate-50 transition-colors modal-book-track"
                                                data-modal-track
                                                data-track-index="{{ $index }}"
                                                data-track-title="{{ $track['label'] }}"
                                                data-track-src="{{ $track['audio'] }}"
                                                data-track-lang="{{ $track['lang'] }}"
                                            >
                                                <div class="flex items-center justify-between gap-2">
                                                    <div class="text-xs font-semibold text-slate-700 truncate">{{ $loop->iteration }}. {{ $track['label'] }}</div>
                                                    <a href="{{ $track['download'] }}" download class="text-blue-700 hover:text-blue-900" data-modal-track-download title="{{ __('messages.common.download') }}" aria-label="{{ __('messages.common.download') }}">
                                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                            <path d="M12 3v11m0 0l4-4m-4 4l-4-4M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                    </a>
                                                </div>
                                            </button>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="p-4 text-sm text-slate-500">{{ __('messages.books.no_audiobooks_filter') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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
                    notify(@json(__('messages.common.shared_successfully')), 'success');
                })
                .catch(() => {});
        } else {
            navigator.clipboard.writeText(shareData.url).then(() => {
                trackBook('share', { share_channel: 'copy' });
                notify(@json(__('messages.common.link_copied')), 'success');
            }).catch(() => {
                notify(@json(__('messages.common.copy_link_failed')), 'error');
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
            notify(data.liked ? @json(__('messages.common.added_to_liked')) : @json(__('messages.common.removed_from_liked')), 'success');
        })
        .catch(() => {
            notify(@json(__('messages.common.request_failed')), 'error');
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
            notify(@json(__('messages.common.comment_submitted')), 'success');
        })
        .catch(() => {
            notify(@json(__('messages.common.comment_failed')), 'error');
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
        const aboutModal = document.getElementById('bookAboutModal');
        const readModal = document.getElementById('bookReadModal');
        const modalPdfFrame = document.getElementById('modalPdfFrame');
        const modalPageInput = document.getElementById('modalPageNumber');
        const modalReaderViewport = document.getElementById('modalReaderViewport');
        const normalReaderLoading = document.getElementById('normalReaderLoading');
        const modalReaderLoading = document.getElementById('modalReaderLoading');
        let page = 1;
        let modalPage = 1;

        frame?.addEventListener('load', () => {
            normalReaderLoading?.classList.add('hidden');
        });
        modalPdfFrame?.addEventListener('load', () => {
            modalReaderLoading?.classList.add('hidden');
        });

        document.getElementById('openBookAboutModal')?.addEventListener('click', () => {
            aboutModal?.classList.remove('hidden');
        });
        document.getElementById('closeBookAboutModal')?.addEventListener('click', () => {
            aboutModal?.classList.add('hidden');
        });
        aboutModal?.addEventListener('click', (event) => {
            if (event.target === aboutModal) {
                aboutModal.classList.add('hidden');
            }
        });

        const updateModalFramePage = () => {
            if (!modalPdfFrame) return;
            const base = modalPdfFrame.getAttribute('data-base-src') || @json(asset('storage/'.$book->file_path));
            modalPdfFrame.src = `${base}#toolbar=0&view=FitH&page=${modalPage}`;
            if (modalPageInput) modalPageInput.value = modalPage;
            modalReaderLoading?.classList.remove('hidden');
        };

        document.getElementById('openReadModal')?.addEventListener('click', () => {
            if (!readModal) return;
            modalPage = page;
            updateModalFramePage();
            readModal.classList.remove('hidden');
        });
        document.getElementById('closeReadModal')?.addEventListener('click', () => {
            readModal?.classList.add('hidden');
            if (modalPdfFrame) modalPdfFrame.src = 'about:blank';
        });
        readModal?.addEventListener('click', (event) => {
            if (event.target === readModal) {
                readModal.classList.add('hidden');
                if (modalPdfFrame) modalPdfFrame.src = 'about:blank';
            }
        });
        document.getElementById('modalPrevPage')?.addEventListener('click', () => {
            modalPage = Math.max(1, modalPage - 1);
            updateModalFramePage();
        });
        document.getElementById('modalNextPage')?.addEventListener('click', () => {
            modalPage += 1;
            updateModalFramePage();
        });
        modalPageInput?.addEventListener('change', () => {
            const value = parseInt(modalPageInput.value, 10);
            if (!Number.isFinite(value) || value < 1) {
                modalPageInput.value = String(modalPage);
                return;
            }
            modalPage = value;
            updateModalFramePage();
        });
        document.getElementById('modalReaderFullscreen')?.addEventListener('click', () => {
            if (!modalReaderViewport) return;
            if (!document.fullscreenElement) {
                modalReaderViewport.requestFullscreen?.();
            } else {
                document.exitFullscreen?.();
            }
        });

        const modalAudioPlayer = document.getElementById('modalBookAudioPlayer');
        const modalAudioSource = document.getElementById('modalBookAudioSource');
        const modalNowPlaying = document.getElementById('modalBookNowPlaying');
        const modalAudioRows = Array.from(document.querySelectorAll('[data-modal-track]'));
        const modalPlayPause = document.getElementById('modalBookPlayPause');
        const modalPrev = document.getElementById('modalBookPrev');
        const modalNext = document.getElementById('modalBookNext');
        const modalLang = document.getElementById('modalBookLang');
        let modalTrackIndex = 0;

        const visibleModalRows = () => modalAudioRows.filter((row) => !row.classList.contains('hidden'));

        const selectModalTrack = (index, play = true) => {
            if (!modalAudioPlayer || !modalAudioSource) return;
            if (index < 0 || index >= modalAudioRows.length) return;
            modalTrackIndex = index;
            const row = modalAudioRows[index];
            modalAudioSource.src = row.getAttribute('data-track-src') || '';
            modalAudioPlayer.load();
            modalAudioRows.forEach((item) => item.classList.remove('bg-slate-100'));
            row.classList.add('bg-slate-100');
            if (modalNowPlaying) modalNowPlaying.textContent = row.getAttribute('data-track-title') || '';
            if (play) modalAudioPlayer.play().catch(() => {});
        };

        modalAudioRows.forEach((row, index) => {
            row.addEventListener('click', () => selectModalTrack(index, true));
            row.querySelector('[data-modal-track-download]')?.addEventListener('click', (event) => event.stopPropagation());
        });

        modalLang?.addEventListener('change', () => {
            const selected = modalLang.value || 'all';
            modalAudioRows.forEach((row) => {
                const lang = row.getAttribute('data-track-lang') || 'rw';
                row.classList.toggle('hidden', selected !== 'all' && lang !== selected);
            });
            const visible = visibleModalRows();
            if (visible.length > 0) {
                const first = Number(visible[0].getAttribute('data-track-index') || 0);
                selectModalTrack(first, false);
            }
        });
        modalPlayPause?.addEventListener('click', () => {
            if (!modalAudioPlayer) return;
            if (modalAudioPlayer.paused) modalAudioPlayer.play().catch(() => {});
            else modalAudioPlayer.pause();
        });
        modalPrev?.addEventListener('click', () => {
            const visible = visibleModalRows();
            const pos = visible.findIndex((row) => Number(row.getAttribute('data-track-index') || 0) === modalTrackIndex);
            if (pos > 0) {
                const nextIndex = Number(visible[pos - 1].getAttribute('data-track-index') || 0);
                selectModalTrack(nextIndex, true);
            }
        });
        modalNext?.addEventListener('click', () => {
            const visible = visibleModalRows();
            const pos = visible.findIndex((row) => Number(row.getAttribute('data-track-index') || 0) === modalTrackIndex);
            if (pos >= 0 && pos + 1 < visible.length) {
                const nextIndex = Number(visible[pos + 1].getAttribute('data-track-index') || 0);
                selectModalTrack(nextIndex, true);
            }
        });
        modalAudioPlayer?.addEventListener('play', () => {
            if (modalPlayPause) modalPlayPause.textContent = 'Pause';
        });
        modalAudioPlayer?.addEventListener('pause', () => {
            if (modalPlayPause) modalPlayPause.textContent = 'Play';
        });
        modalAudioPlayer?.addEventListener('ended', () => {
            const visible = visibleModalRows();
            const pos = visible.findIndex((row) => Number(row.getAttribute('data-track-index') || 0) === modalTrackIndex);
            if (pos >= 0 && pos + 1 < visible.length) {
                const nextIndex = Number(visible[pos + 1].getAttribute('data-track-index') || 0);
                selectModalTrack(nextIndex, true);
            }
        });
        if (modalAudioRows.length > 0) {
            selectModalTrack(0, false);
        }

        function updateFramePage() {
            if (!frame) return;
            const base = @json(asset('storage/'.$book->file_path));
            normalReaderLoading?.classList.remove('hidden');
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

