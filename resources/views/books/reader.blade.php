@extends('layouts.audiences.app')
@section('contents')
<main class="grow bg-slate-100">
    <section class="pt-8 pb-4 bg-gradient-to-b from-blue-950 to-slate-900 text-white">
        <div class="container mx-auto px-3 sm:px-4 lg:px-5">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="text-xs uppercase tracking-[0.2em] text-blue-200 mb-1">{{ __('messages.books.badge') }}</div>
                    <h1 class="text-2xl md:text-3xl font-serif font-bold">{{ $book->title }}</h1>
                    <p class="text-blue-100 text-sm mt-1">{{ $book->category?->name ?? __('messages.common.book') }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('books.show', $book) }}" class="px-4 py-2 text-sm rounded-lg border border-blue-300/30 text-blue-100 hover:bg-white/10 transition-colors">
                        {{ __('messages.common.open') }} {{ __('messages.common.details') }}
                    </a>
                    <a href="{{ asset('storage/'.$book->file_path) }}" target="_blank" rel="noopener" class="px-4 py-2 text-sm rounded-lg border border-blue-300/30 text-blue-100 hover:bg-white/10 transition-colors">
                        {{ __('messages.books.browser_reader') }}
                    </a>
                    <a href="{{ route('content.download.document', $book) }}" class="px-4 py-2 text-sm rounded-lg bg-amber-500 text-white hover:bg-amber-600 transition-colors">
                        {{ __('messages.home.download_pdf') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-6">
        <div class="container mx-auto px-3 sm:px-4 lg:px-5">
            @if (!$book->file_path)
                <div class="bg-white rounded-2xl border border-slate-200 p-10 text-center text-slate-500">
                    {{ __('messages.books.no_pdf') }}
                </div>
            @else
                <div id="readerShell" class="grid grid-cols-1 gap-5">
                    @php
                        $readerAudioPartsCount = 0;
                        if (!empty($linkedAudiobooks) && $linkedAudiobooks->count()) {
                            foreach ($linkedAudiobooks as $readerAudioBook) {
                                $readerAudioPartsCount += $readerAudioBook->publishedParts->count();
                            }
                        }
                    @endphp

                    @if (!empty($linkedAudiobooks) && $linkedAudiobooks->count() && $readerAudioPartsCount > 0)
                        <div class="bg-blue-50 border border-blue-200 text-blue-900 rounded-xl p-3 flex items-center justify-between gap-3">
                            <div class="text-sm">
                                <span class="font-semibold">Audiobook available:</span>
                                <span>{{ $readerAudioPartsCount }} parts ready for listening while reading.</span>
                            </div>
                            <button type="button" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-blue-700 text-white text-xs font-semibold hover:bg-blue-600" onclick="document.getElementById('toggleAbout')?.click();">
                                <i data-lucide="headphones" class="w-4 h-4"></i>
                                Open Audio
                            </button>
                        </div>
                    @endif

                    <div id="aboutPanel" class="hidden fixed inset-0 z-[80] bg-slate-950/70 p-4 md:p-8 overflow-y-auto">
                        <div class="mx-auto w-full max-w-4xl bg-white rounded-2xl border border-slate-200 shadow-2xl">
                        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                            <h2 class="font-serif text-lg text-slate-900 font-bold">{{ __('messages.books.about_this_book') }}</h2>
                            <button type="button" id="closeAboutModal" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs text-slate-600 hover:bg-slate-50">Close</button>
                        </div>
                        <div class="p-5 border-b border-slate-100 space-y-4 text-sm text-slate-700">
                            <div class="text-slate-600 leading-relaxed">
                                {{ $book->description ?: __('messages.books.no_description') }}
                            </div>
                            <div class="pt-2 border-t border-slate-100 space-y-2">
                                <div><span class="text-slate-500">{{ __('messages.books.author') }}:</span> {{ $book->author ?: '-' }}</div>
                                <div><span class="text-slate-500">{{ __('messages.books.series') }}:</span> {{ $book->series ?: '-' }}</div>
                                <div><span class="text-slate-500">{{ __('messages.common.published') }}:</span> {{ $book->published_at?->toDateString() ?? $book->created_at?->toDateString() }}</div>
                            </div>
                        </div>
                        @if (!empty($linkedAudiobooks) && $linkedAudiobooks->count())
                            <div class="p-5">
                                <h3 class="font-serif text-base text-slate-900 font-bold">{{ __('messages.books.audiobook_while_reading') }}</h3>
                                <p class="mt-1 text-xs text-slate-500">{{ __('messages.books.audio_tts_note') }}</p>
                                @php
                                    $readerQueue = [];
                                    $readerQueueByLang = ['rw' => [], 'en' => [], 'fr' => []];
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
                                                ];
                                                $readerQueue[] = $track;
                                                $readerQueueByLang[$track['lang']][] = $track;
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
                                                ];
                                                $readerQueue[] = $track;
                                                $readerQueueByLang['rw'][] = $track;
                                            }
                                        }
                                    }
                                @endphp
                                @if (count($readerQueue) > 0)
                                <style>
                                    .reader-track-row.reader-track-active { background-color: #343743; color: #ffffff; }
                                </style>
                                <div class="mt-3 rounded-2xl overflow-hidden border border-slate-700 bg-[#1b1c22] text-slate-100">
                                    <div class="px-3 py-2 border-b border-slate-700 bg-[#22242c]">
                                        <div id="linkedNowPlaying" class="text-xs font-semibold truncate">{{ $readerQueue[0]['label'] }}</div>
                                    </div>
                                    <div class="p-3 border-b border-slate-700">
                                        <audio id="linkedAudiobookPlayer" class="hidden" preload="none">
                                            <source id="linkedAudiobookSource" src="{{ $readerQueue[0]['audio'] }}" type="audio/mpeg">
                                        </audio>
                                        <div class="mb-3 flex flex-wrap gap-2">
                                            <button type="button" class="reader-lang-tab px-2.5 py-1 rounded border border-slate-500 text-[11px] bg-[#343743] text-white" data-linked-lang="rw">Kinyarwanda ({{ count($readerQueueByLang['rw']) }})</button>
                                            <button type="button" class="reader-lang-tab px-2.5 py-1 rounded border border-slate-500 text-[11px]" data-linked-lang="en">English ({{ count($readerQueueByLang['en']) }})</button>
                                            <button type="button" class="reader-lang-tab px-2.5 py-1 rounded border border-slate-500 text-[11px]" data-linked-lang="fr">French ({{ count($readerQueueByLang['fr']) }})</button>
                                        </div>
                                        <div class="flex items-center gap-2 mb-3">
                                            <button type="button" id="linkedPrev" class="px-2.5 py-1.5 rounded bg-[#2b2d36] hover:bg-[#373a45] text-xs">Prev</button>
                                            <button type="button" id="linkedPlayPause" class="px-3 py-1.5 rounded bg-[#ff006e] hover:bg-[#e00062] text-xs font-semibold min-w-[64px]">Play</button>
                                            <button type="button" id="linkedNext" class="px-2.5 py-1.5 rounded bg-[#2b2d36] hover:bg-[#373a45] text-xs">Next</button>
                                            <div class="ml-auto flex items-center gap-2">
                                                <span class="text-[11px] text-slate-300">Vol</span>
                                                <input id="linkedVolume" type="range" min="0" max="1" step="0.05" value="1" class="accent-pink-500 w-20">
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between text-[11px] text-slate-300">
                                            <span id="linkedCurrentTime">00:00</span>
                                            <label class="inline-flex items-center gap-1.5">
                                                <input id="linkedAudiobookAutoNext" type="checkbox" class="rounded border-slate-500 bg-[#2b2d36]" checked>
                                                Auto next
                                            </label>
                                            <span id="linkedDuration">00:00</span>
                                        </div>
                                        <div id="linkedAudiobookError" class="hidden mt-2 text-[11px] text-rose-400">{{ __('messages.books.unable_play_track') }}</div>
                                    </div>
                                    <div id="linkedQueue" class="max-h-80 overflow-y-auto">
                                        @foreach ($readerQueue as $index => $track)
                                            <button
                                                type="button"
                                                class="reader-track-row w-full text-left px-3 py-2.5 border-b border-slate-700/80 hover:bg-[#2a2c35] transition-colors"
                                                data-linked-track
                                                data-track-index="{{ $index }}"
                                                data-track-title="{{ $track['label'] }}"
                                                data-track-src="{{ $track['audio'] }}"
                                                data-track-lang="{{ $track['lang'] }}"
                                            >
                                                <div class="flex items-center justify-between gap-2">
                                                    <div class="text-xs font-semibold truncate">{{ $loop->iteration }}. {{ $track['label'] }}</div>
                                                    <a href="{{ $track['download'] }}" download class="inline-flex items-center justify-center text-slate-300 hover:text-white" data-track-download title="{{ __('messages.common.download') }}" aria-label="{{ __('messages.common.download') }}">
                                                        <i data-lucide="download" class="w-4 h-4"></i>
                                                    </a>
                                                </div>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        @endif
                        </div>
                    </div>

                    <div id="readerPanel" class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                        <div class="p-3 md:p-4 border-b border-slate-200 bg-slate-50 flex items-center gap-2 overflow-x-auto whitespace-nowrap">
                            <button type="button" id="prevPage" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white">&larr; {{ __('messages.common.prev') }}</button>
                            <button type="button" id="nextPage" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white">{{ __('messages.common.next') }} &rarr;</button>
                            <div class="flex items-center gap-2 text-sm text-slate-700">
                                <span>{{ __('messages.common.page') }}</span>
                                <input id="pageNumber" type="number" min="1" value="1" class="w-14 md:w-20 px-2 py-1.5 border border-slate-200 rounded-lg text-sm">
                                <button type="button" id="goPage" class="px-2.5 py-1.5 rounded-lg border border-slate-200 text-slate-700 text-xs hover:bg-white">{{ __('messages.common.go') }}</button>
                                <span>{{ __('messages.common.of') }}</span>
                                <span id="pageCount">-</span>
                            </div>
                            <div class="hidden md:block text-xs text-slate-500" id="readingHint">{{ __('messages.books.use_arrow_keys') }}</div>
                            <div class="hidden md:block mx-2 h-6 border-l border-slate-200"></div>
                            <button type="button" id="zoomOut" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white">-</button>
                            <button type="button" id="zoomIn" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white">+</button>
                            <button type="button" id="fitWidth" class="hidden md:inline-flex px-3 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white">{{ __('messages.books.fit_width') }}</button>
                            <button type="button" id="rotate" class="hidden md:inline-flex px-3 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white">{{ __('messages.books.rotate') }}</button>
                            <button type="button" id="toggleAbout" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white inline-flex items-center gap-1.5">
                                <i data-lucide="book-open" class="w-4 h-4"></i>
                                {{ __('messages.books.about_this_book') }}
                                @if (!empty($linkedAudiobooks) && $linkedAudiobooks->count() && $readerAudioPartsCount > 0)
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 text-[11px] font-semibold">
                                        <i data-lucide="headphones" class="w-3.5 h-3.5"></i>
                                        {{ $readerAudioPartsCount }}
                                    </span>
                                @endif
                            </button>
                            <div class="ml-auto flex gap-2">
                                <button type="button" id="toggleFullscreen" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white">{{ __('messages.common.fullscreen') }}</button>
                            </div>
                        </div>
                        <div class="px-4 py-2 bg-white border-b border-slate-200">
                            <div class="flex items-center justify-between text-xs text-slate-500 mb-1">
                                <span>{{ __('messages.books.reading_progress') }}</span>
                                <span id="readingProgressText">0%</span>
                            </div>
                            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div id="readingProgressBar" class="h-full bg-blue-700 transition-all duration-200" style="width: 0%"></div>
                            </div>
                        </div>

                        <div id="readerContainer" class="bg-slate-200 overflow-auto h-[62vh] md:h-[75vh]">
                            <canvas id="pdfCanvas" class="mx-auto my-4 shadow-md bg-white"></canvas>
                        </div>
                    </div>
                </div>
            @endif

            @if (!empty($recommendedBooks) && $recommendedBooks->count())
                <div class="mt-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg md:text-xl font-serif font-bold text-blue-950">{{ __('messages.common.you_may_also_like') }}</h3>
                        <a href="{{ route('books.index') }}" class="text-sm text-blue-700 hover:text-blue-900">{{ __('messages.common.browse_all') }}</a>
                    </div>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach ($recommendedBooks as $item)
                            <article class="group bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100">
                                <div class="relative aspect-[2/3] overflow-hidden bg-slate-100">
                                    @if ($item->cover_image)
                                        <img src="{{ asset('storage/'.$item->cover_image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                    @else
                                        <img src="{{ asset('landingpage/download-book.webp') }}" alt="{{ __('messages.common.book') }}" class="w-full h-full object-cover">
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/95 via-slate-900/70 to-slate-900/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    <div class="absolute inset-x-0 bottom-0 p-3 opacity-0 translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
                                        <div class="text-white text-sm font-semibold line-clamp-2">{{ $item->title }}</div>
                                        <a href="{{ route('books.reader', $item) }}" class="inline-flex mt-2 px-2.5 py-1.5 rounded bg-blue-600 text-white text-xs font-semibold hover:bg-blue-500">{{ __('messages.common.read') }}</a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif

            @if (!empty($recommendedAudiobooks) && $recommendedAudiobooks->count())
                <div class="mt-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg md:text-xl font-serif font-bold text-blue-950">{{ __('messages.home.featured_audiobooks') }}</h3>
                        <a href="{{ route('books.index') }}" class="text-sm text-blue-700 hover:text-blue-900">{{ __('messages.common.browse_all') }}</a>
                    </div>
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach ($recommendedAudiobooks as $ab)
                            <article class="group bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100">
                                <div class="relative aspect-[2/3] overflow-hidden bg-slate-100">
                                    @if ($ab->thumbnail)
                                        <img src="{{ asset('storage/'.$ab->thumbnail) }}" alt="{{ $ab->title }}" class="w-full h-full object-cover">
                                    @else
                                        <img src="{{ asset('landingpage/download-audio.webp') }}" alt="{{ __('messages.common.audio') }}" class="w-full h-full object-cover">
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/95 via-slate-900/70 to-slate-900/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    <div class="absolute inset-x-0 bottom-0 p-3 opacity-0 translate-y-2 group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-300">
                                        <div class="text-white text-sm font-semibold line-clamp-2">{{ $ab->title }}</div>
                                        <div class="text-slate-200 text-xs mt-1">{{ $ab->parts_count ?? 0 }} parts</div>
                                        <a href="{{ $ab->linkedBook ? route('books.reader', ['book' => $ab->linkedBook, 'audio' => 1]) : route('books.index') }}" class="inline-flex mt-2 px-2.5 py-1.5 rounded bg-blue-600 text-white text-xs font-semibold hover:bg-blue-500">{{ __('messages.home.listen_now') }}</a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
</main>
@if ($book->file_path)
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>
<script>
    const BOOK_ID = {{ $book->id }};
    const PDF_URL = @json(asset('storage/'.$book->file_path));
    const OPEN_AUDIO_PANEL = @json(request()->query('audio') == '1');
    const VISITOR_STORAGE_KEY = 'bgm_audience_visitor_id';
    const READER_SESSION_KEY = `bgm_reader_session_${BOOK_ID}`;
    const visitorId = (() => {
        let id = localStorage.getItem(VISITOR_STORAGE_KEY);
        if (!id) {
            id = `v_${Math.random().toString(36).slice(2)}_${Date.now()}`;
            localStorage.setItem(VISITOR_STORAGE_KEY, id);
        }
        return id;
    })();
    const readerSessionId = (() => {
        let id = sessionStorage.getItem(READER_SESSION_KEY);
        if (!id) {
            id = `rs_${BOOK_ID}_${Date.now()}_${Math.random().toString(36).slice(2, 10)}`;
            sessionStorage.setItem(READER_SESSION_KEY, id);
        }
        return id;
    })();
    let pdfDoc = null;
    let currentPage = 1;
    let totalPages = 0;
    let zoom = 1.2;
    let rotation = 0;
    let rendering = false;
    let pendingPage = null;
    let readSeconds = 0;
    let pageTextCache = {};
    let voices = [];
    let currentUtterance = null;
    let ocrRunning = false;
    let lastProgressKey = '';

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
            device_type: deviceType,
            visitor_id: visitorId
        };
    }

    function trackBook(eventType, extra) {
        const payload = Object.assign(
            { event: eventType, page_url: window.location.href },
            collectClientMetrics(),
            extra || {}
        );

        fetch(`/books/${BOOK_ID}/track`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken()
            },
            body: JSON.stringify(payload)
        }).catch(() => {});
    }

    function currentProgressPercent() {
        if (!totalPages) return 0;
        return Math.min(100, Math.max(0, (currentPage / totalPages) * 100));
    }

    function readerProgressPayload(extra) {
        return Object.assign({
            reader_session_id: readerSessionId,
            page_number: currentPage,
            total_pages: totalPages || null,
            progress_percent: Number(currentProgressPercent().toFixed(2)),
        }, extra || {});
    }

    function setReaderStatus(message) {
        const status = document.getElementById('readerStatus');
        if (status) {
            status.textContent = message || '';
        }
    }

    function normalizeTextForSpeech(text) {
        if (!text) return '';
        let normalized = String(text)
            .replace(/\r?\n/g, ' ')
            .replace(/\s+/g, ' ')
            .trim();

        // Merge OCR output sequences such as: "i j a m b o"
        normalized = normalized.replace(/(?:\b[A-Za-z]\b\s+){2,}\b[A-Za-z]\b/g, (match) => {
            return match.replace(/\s+/g, '');
        });

        normalized = normalized
            .replace(/\s+([,.;!?])/g, '$1')
            .replace(/([({[])\s+/g, '$1')
            .replace(/\s+([)}\]])/g, '$1');

        return normalized;
    }

    function lineAwarePdfText(items) {
        if (!Array.isArray(items) || items.length === 0) {
            return '';
        }

        const enriched = items
            .map((item) => {
                const tr = item.transform || [0, 0, 0, 0, 0, 0];
                return {
                    str: String(item.str || '').trim(),
                    x: Number(tr[4] || 0),
                    y: Number(tr[5] || 0),
                };
            })
            .filter((item) => item.str !== '');

        if (enriched.length === 0) {
            return '';
        }

        enriched.sort((a, b) => {
            if (Math.abs(b.y - a.y) > 2) {
                return b.y - a.y;
            }
            return a.x - b.x;
        });

        const lines = [];
        let current = [];
        let currentY = enriched[0].y;

        for (const token of enriched) {
            if (Math.abs(token.y - currentY) > 6) {
                if (current.length) {
                    lines.push(current.join(' '));
                }
                current = [token.str];
                currentY = token.y;
            } else {
                current.push(token.str);
            }
        }
        if (current.length) {
            lines.push(current.join(' '));
        }

        return lines.join('. ');
    }

    function selectedLang() {
        return document.getElementById('readingLanguage')?.value || 'rw-RW';
    }

    function langPrefix(lang) {
        return String(lang || 'en-US').split('-')[0].toLowerCase();
    }

    function queueRender(pageNumber) {
        if (rendering) {
            pendingPage = pageNumber;
            return;
        }
        renderPage(pageNumber);
    }

    function renderPage(pageNumber) {
        if (!pdfDoc || pageNumber < 1 || pageNumber > totalPages) {
            return;
        }
        rendering = true;
        pdfDoc.getPage(pageNumber).then((page) => {
            const container = document.getElementById('readerContainer');
            const canvas = document.getElementById('pdfCanvas');
            const context = canvas.getContext('2d');
            const viewport = page.getViewport({ scale: zoom, rotation });
            canvas.width = viewport.width;
            canvas.height = viewport.height;

            const renderContext = {
                canvasContext: context,
                viewport: viewport
            };

            return page.render(renderContext).promise.then(() => {
                currentPage = pageNumber;
                document.getElementById('pageNumber').value = currentPage;
                updateReadingProgress();
                trackReadProgress();
                if (container) {
                    container.scrollTop = 0;
                }
            });
        }).finally(() => {
            rendering = false;
            if (pendingPage !== null) {
                const next = pendingPage;
                pendingPage = null;
                renderPage(next);
            }
        });
    }

    async function getPageText(pageNumber) {
        if (pageTextCache[pageNumber]) {
            return pageTextCache[pageNumber];
        }
        if (!pdfDoc) {
            return '';
        }

        const page = await pdfDoc.getPage(pageNumber);
        const content = await page.getTextContent();
        const readingSource = document.getElementById('readingSource')?.value || 'pdf';
        let text = normalizeTextForSpeech(lineAwarePdfText(content.items));

        if (readingSource === 'ocr' || text.length < 8) {
            const ocrText = await getPageTextViaOcr(pageNumber);
            text = normalizeTextForSpeech(ocrText || text);
        }

        pageTextCache[pageNumber] = text;
        return pageTextCache[pageNumber];
    }

    async function getPageTextViaOcr(pageNumber) {
        if (!window.Tesseract || ocrRunning) {
            return '';
        }

        ocrRunning = true;
        setReaderStatus(@json(__('messages.books.ocr_running')));
        try {
            const page = await pdfDoc.getPage(pageNumber);
            const viewport = page.getViewport({ scale: 1.8, rotation });
            const offscreen = document.createElement('canvas');
            const ctx = offscreen.getContext('2d');
            offscreen.width = viewport.width;
            offscreen.height = viewport.height;
            await page.render({ canvasContext: ctx, viewport }).promise;

            const ocrLangMap = {
                'rw-RW': 'kin+eng',
                'fr-FR': 'fra+eng',
                'en-US': 'eng',
            };
            const ocrLang = ocrLangMap[selectedLang()] || 'eng';
            const result = await Tesseract.recognize(offscreen, ocrLang).catch(async () => {
                return Tesseract.recognize(offscreen, 'eng');
            });

            const text = (result?.data?.text || '').replace(/\s+/g, ' ').trim();
            setReaderStatus(text ? @json(__('messages.books.ocr_extracted')) : @json(__('messages.books.ocr_no_text')));
            return text;
        } catch (error) {
            setReaderStatus(@json(__('messages.books.ocr_failed')));
            return '';
        } finally {
            ocrRunning = false;
        }
    }

    function loadVoices() {
        voices = window.speechSynthesis ? window.speechSynthesis.getVoices() : [];
        const select = document.getElementById('ttsVoice');
        if (!select) return;
        select.innerHTML = '<option value="">System voice</option>';
        voices.forEach((voice, index) => {
            const option = document.createElement('option');
            option.value = String(index);
            option.textContent = `${voice.name} (${voice.lang})`;
            select.appendChild(option);
        });
    }

    function preselectVoiceForLanguage() {
        const select = document.getElementById('ttsVoice');
        if (!select) return;
        const prefix = langPrefix(selectedLang());
        const index = voices.findIndex((voice) => String(voice.lang || '').toLowerCase().startsWith(prefix));
        if (index >= 0) {
            select.value = String(index);
            setReaderStatus(`${@json(__('messages.books.voice_set_for'))} ${selectedLang()}`);
        } else if (prefix === 'rw') {
            setReaderStatus(@json(__('messages.books.no_native_rw_voice')));
        }
    }

    async function speakCurrentPage() {
        if (!window.speechSynthesis) {
            alert(@json(__('messages.books.speech_not_supported')));
            return;
        }

        const rate = parseFloat(document.getElementById('ttsRate').value || '1');
        let text = await getPageText(currentPage);

        if (!text) {
            setReaderStatus(@json(__('messages.books.no_readable_text')));
            return;
        }

        text = normalizeTextForSpeech(text);

        window.speechSynthesis.cancel();
        currentUtterance = new SpeechSynthesisUtterance(text);
        const lang = selectedLang();
        currentUtterance.rate = lang === 'rw-RW' ? Math.min(rate, 0.95) : rate;
        currentUtterance.pitch = 1;
        currentUtterance.lang = lang;

        const selectedVoiceIndex = document.getElementById('ttsVoice').value;
        if (selectedVoiceIndex !== '' && voices[selectedVoiceIndex]) {
            currentUtterance.voice = voices[selectedVoiceIndex];
        } else {
            const prefix = langPrefix(lang);
            const best = voices.find((voice) => String(voice.lang || '').toLowerCase().startsWith(prefix));
            if (best) {
                currentUtterance.voice = best;
            } else if (prefix === 'rw') {
                setReaderStatus(@json(__('messages.books.no_rw_voice_installed')));
            }
        }

        window.speechSynthesis.speak(currentUtterance);
        trackBook('read_aloud', { watch_seconds: readSeconds });
        setReaderStatus(@json(__('messages.books.reading_aloud')));
    }

    function updateReadingProgress() {
        const progressText = document.getElementById('readingProgressText');
        const progressBar = document.getElementById('readingProgressBar');
        if (!totalPages || !progressText || !progressBar) return;

        const percentage = Math.min(100, Math.max(0, Math.round((currentPage / totalPages) * 100)));
        progressText.textContent = `${percentage}%`;
        progressBar.style.width = `${percentage}%`;
    }

    function trackReadProgress() {
        if (!totalPages) return;
        const progress = currentProgressPercent();
        const dedupeKey = `${currentPage}:${Math.round(progress)}`;
        if (dedupeKey === lastProgressKey) return;
        lastProgressKey = dedupeKey;
        trackBook('read_progress', readerProgressPayload());
    }

    function initReader() {
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
        pdfjsLib.getDocument(PDF_URL).promise.then((pdf) => {
            pdfDoc = pdf;
            totalPages = pdf.numPages;
            document.getElementById('pageCount').textContent = totalPages;
            queueRender(1);
            trackBook('open_reader', readerProgressPayload());
        }).catch(() => {
            alert(@json(__('messages.books.reader_load_failed')));
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        initReader();
        loadVoices();
        preselectVoiceForLanguage();
        if (window.speechSynthesis) {
            window.speechSynthesis.onvoiceschanged = () => {
                loadVoices();
                preselectVoiceForLanguage();
            };
        }

        document.getElementById('readingLanguage')?.addEventListener('change', () => {
            preselectVoiceForLanguage();
            pageTextCache = {};
        });

        document.getElementById('readingSource')?.addEventListener('change', () => {
            pageTextCache = {};
        });

        document.getElementById('prevPage').addEventListener('click', () => {
            if (currentPage <= 1) return;
            queueRender(currentPage - 1);
            trackBook('read', readerProgressPayload());
        });

        document.getElementById('nextPage').addEventListener('click', () => {
            if (currentPage >= totalPages) return;
            queueRender(currentPage + 1);
            trackBook('read', readerProgressPayload());
        });

        const jumpToPage = () => {
            const event = { target: document.getElementById('pageNumber') };
            const page = parseInt(event.target.value, 10);
            if (!page || page < 1 || page > totalPages) {
                event.target.value = currentPage;
                return;
            }
            queueRender(page);
            trackBook('read', readerProgressPayload());
        };

        document.getElementById('pageNumber').addEventListener('change', jumpToPage);
        document.getElementById('goPage').addEventListener('click', jumpToPage);

        document.getElementById('zoomIn').addEventListener('click', () => {
            zoom = Math.min(zoom + 0.2, 3.0);
            queueRender(currentPage);
        });

        document.getElementById('zoomOut').addEventListener('click', () => {
            zoom = Math.max(zoom - 0.2, 0.6);
            queueRender(currentPage);
        });

        document.getElementById('fitWidth').addEventListener('click', () => {
            const container = document.getElementById('readerContainer');
            const width = container ? container.clientWidth : 1000;
            zoom = Math.max(0.6, Math.min(3.0, (width - 60) / 800));
            queueRender(currentPage);
        });

        document.getElementById('rotate').addEventListener('click', () => {
            rotation = (rotation + 90) % 360;
            pageTextCache = {};
            queueRender(currentPage);
        });

        document.getElementById('toggleFullscreen').addEventListener('click', () => {
            const container = document.getElementById('readerContainer');
            if (!document.fullscreenElement) {
                container.requestFullscreen?.();
            } else {
                document.exitFullscreen?.();
            }
        });

        const aboutPanel = document.getElementById('aboutPanel');
        document.getElementById('toggleAbout').addEventListener('click', () => {
            aboutPanel?.classList.remove('hidden');
        });
        document.getElementById('closeAboutModal')?.addEventListener('click', () => {
            aboutPanel?.classList.add('hidden');
        });
        aboutPanel?.addEventListener('click', (event) => {
            if (event.target === aboutPanel) {
                aboutPanel.classList.add('hidden');
            }
        });

        if (OPEN_AUDIO_PANEL) {
            aboutPanel?.classList.remove('hidden');
        }

        document.getElementById('readPage')?.addEventListener('click', speakCurrentPage);
        document.getElementById('pauseRead')?.addEventListener('click', () => {
            if (!window.speechSynthesis) return;
            if (window.speechSynthesis.paused) {
                window.speechSynthesis.resume();
            } else {
                window.speechSynthesis.pause();
            }
        });
        document.getElementById('stopRead')?.addEventListener('click', () => {
            if (!window.speechSynthesis) return;
            window.speechSynthesis.cancel();
            setReaderStatus(@json(__('messages.books.reading_stopped')));
        });

        const linkedPlayer = document.getElementById('linkedAudiobookPlayer');
        const linkedSource = document.getElementById('linkedAudiobookSource');
        const linkedNowPlaying = document.getElementById('linkedNowPlaying');
        const linkedAutoNext = document.getElementById('linkedAudiobookAutoNext');
        const linkedError = document.getElementById('linkedAudiobookError');
        const linkedPrev = document.getElementById('linkedPrev');
        const linkedNext = document.getElementById('linkedNext');
        const linkedPlayPause = document.getElementById('linkedPlayPause');
        const linkedVolume = document.getElementById('linkedVolume');
        const linkedCurrentTime = document.getElementById('linkedCurrentTime');
        const linkedDuration = document.getElementById('linkedDuration');
        const linkedRows = Array.from(document.querySelectorAll('[data-linked-track]'));
        const linkedLangTabs = Array.from(document.querySelectorAll('[data-linked-lang]'));
        let linkedIndex = 0;
        let activeLinkedLanguage = 'rw';

        const formatTime = (seconds) => {
            if (!Number.isFinite(seconds)) return '00:00';
            const total = Math.max(0, Math.floor(seconds));
            const m = String(Math.floor(total / 60)).padStart(2, '0');
            const s = String(total % 60).padStart(2, '0');
            return `${m}:${s}`;
        };

        const syncLinkedPlayState = () => {
            if (!linkedPlayPause || !linkedPlayer) return;
            linkedPlayPause.textContent = linkedPlayer.paused ? 'Play' : 'Pause';
        };

        const selectLinkedTrack = (index, play = true) => {
            if (!linkedPlayer || !linkedSource) return;
            if (index < 0 || index >= linkedRows.length) return;
            linkedIndex = index;
            const row = linkedRows[index];
            const src = row.getAttribute('data-track-src') || '';
            const title = row.getAttribute('data-track-title') || '';
            linkedSource.src = src;
            linkedPlayer.load();
            linkedRows.forEach((item) => item.classList.remove('reader-track-active'));
            row.classList.add('reader-track-active');
            if (linkedNowPlaying) linkedNowPlaying.textContent = title;
            linkedError?.classList.add('hidden');
            if (play) linkedPlayer.play().catch(() => {});
            syncLinkedPlayState();
        };

        const visibleLinkedRows = () => linkedRows.filter((row) => !row.classList.contains('hidden'));

        const applyLinkedLanguageFilter = (lang) => {
            activeLinkedLanguage = lang;
            linkedLangTabs.forEach((tab) => {
                const isActive = tab.getAttribute('data-linked-lang') === lang;
                tab.classList.toggle('bg-[#343743]', isActive);
                tab.classList.toggle('text-white', isActive);
            });
            linkedRows.forEach((row) => {
                const rowLang = row.getAttribute('data-track-lang') || 'rw';
                row.classList.toggle('hidden', rowLang !== lang);
            });
            const visible = visibleLinkedRows();
            if (visible.length === 0) return;
            const currentRow = linkedRows[linkedIndex];
            if (!currentRow || currentRow.classList.contains('hidden')) {
                const nextIndex = Number(visible[0].getAttribute('data-track-index') || 0);
                selectLinkedTrack(nextIndex, false);
            }
        };

        linkedRows.forEach((row, index) => {
            row.addEventListener('click', () => selectLinkedTrack(index, true));
        });
        linkedRows.forEach((row) => {
            row.querySelector('[data-track-download]')?.addEventListener('click', (event) => {
                event.stopPropagation();
            });
        });
        linkedLangTabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                const lang = tab.getAttribute('data-linked-lang') || 'rw';
                applyLinkedLanguageFilter(lang);
            });
        });

        linkedPrev?.addEventListener('click', () => {
            const visible = visibleLinkedRows();
            if (visible.length === 0) return;
            const currentPos = visible.findIndex((row) => Number(row.getAttribute('data-track-index')) === linkedIndex);
            if (currentPos > 0) {
                const nextIndex = Number(visible[currentPos - 1].getAttribute('data-track-index') || 0);
                selectLinkedTrack(nextIndex, true);
            }
        });

        linkedNext?.addEventListener('click', () => {
            const visible = visibleLinkedRows();
            if (visible.length === 0) return;
            const currentPos = visible.findIndex((row) => Number(row.getAttribute('data-track-index')) === linkedIndex);
            if (currentPos >= 0 && currentPos + 1 < visible.length) {
                const nextIndex = Number(visible[currentPos + 1].getAttribute('data-track-index') || 0);
                selectLinkedTrack(nextIndex, true);
            }
        });

        linkedPlayPause?.addEventListener('click', () => {
            if (!linkedPlayer) return;
            if (linkedPlayer.paused) linkedPlayer.play().catch(() => {});
            else linkedPlayer.pause();
        });

        linkedVolume?.addEventListener('input', () => {
            if (!linkedPlayer) return;
            linkedPlayer.volume = Number(linkedVolume.value || 1);
        });

        linkedPlayer?.addEventListener('timeupdate', () => {
            if (linkedCurrentTime) linkedCurrentTime.textContent = formatTime(linkedPlayer.currentTime);
            if (linkedDuration) linkedDuration.textContent = formatTime(linkedPlayer.duration);
        });

        linkedPlayer?.addEventListener('loadedmetadata', () => {
            if (linkedDuration) linkedDuration.textContent = formatTime(linkedPlayer.duration);
        });

        linkedPlayer?.addEventListener('play', syncLinkedPlayState);
        linkedPlayer?.addEventListener('pause', syncLinkedPlayState);

        linkedPlayer?.addEventListener('error', () => {
            linkedError?.classList.remove('hidden');
        });

        linkedPlayer?.addEventListener('ended', () => {
            if (!linkedAutoNext || !linkedAutoNext.checked) return;
            const visible = visibleLinkedRows();
            const currentPos = visible.findIndex((row) => Number(row.getAttribute('data-track-index')) === linkedIndex);
            if (currentPos >= 0 && currentPos + 1 < visible.length) {
                const nextIndex = Number(visible[currentPos + 1].getAttribute('data-track-index') || 0);
                selectLinkedTrack(nextIndex, true);
            }
        });

        if (linkedRows.length > 0) {
            if (!linkedRows.some((row) => (row.getAttribute('data-track-lang') || 'rw') === activeLinkedLanguage)) {
                activeLinkedLanguage = linkedRows[0].getAttribute('data-track-lang') || 'rw';
            }
            applyLinkedLanguageFilter(activeLinkedLanguage);
            const firstVisible = visibleLinkedRows()[0];
            if (firstVisible) {
                const firstIndex = Number(firstVisible.getAttribute('data-track-index') || 0);
                selectLinkedTrack(firstIndex, false);
            }
            syncLinkedPlayState();
        }

        document.addEventListener('keydown', (event) => {
            if (event.key === 'ArrowRight') {
                document.getElementById('nextPage')?.click();
            } else if (event.key === 'ArrowLeft') {
                document.getElementById('prevPage')?.click();
            } else if (event.key.toLowerCase() === 'f') {
                document.getElementById('toggleFullscreen')?.click();
            }
        });

        setInterval(() => {
            readSeconds += 15;
            trackBook('read', readerProgressPayload({ watch_seconds: 15 }));
        }, 15000);
    });
</script>
@endif
@endsection


