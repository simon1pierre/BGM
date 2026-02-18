@extends('layouts.audiences.app')
@section('contents')
<main class="grow bg-slate-100">
    <section class="pt-8 pb-4 bg-gradient-to-b from-blue-950 to-slate-900 text-white">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="text-xs uppercase tracking-[0.2em] text-blue-200 mb-1">{{ __('messages.books.badge') }}</div>
                    <h1 class="text-2xl md:text-3xl font-serif font-bold">{{ $book->title }}</h1>
                    <p class="text-blue-100 text-sm mt-1">{{ $book->category?->name ?? __('messages.common.book') }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('books.show', $book) }}" class="px-4 py-2 text-sm rounded-lg border border-blue-300/30 text-blue-100 hover:bg-white/10 transition-colors">
                        {{ __('messages.common.open') }} Details
                    </a>
                    <a href="{{ asset('storage/'.$book->file_path) }}" target="_blank" rel="noopener" class="px-4 py-2 text-sm rounded-lg border border-blue-300/30 text-blue-100 hover:bg-white/10 transition-colors">
                        Browser Reader
                    </a>
                    <a href="{{ route('content.download.document', $book) }}" class="px-4 py-2 text-sm rounded-lg bg-amber-500 text-white hover:bg-amber-600 transition-colors">
                        {{ __('messages.home.download_pdf') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-6">
        <div class="container mx-auto px-6">
            @if (!$book->file_path)
                <div class="bg-white rounded-2xl border border-slate-200 p-10 text-center text-slate-500">
                    {{ __('messages.books.no_pdf') }}
                </div>
            @else
                <div id="readerShell" class="grid grid-cols-1 lg:grid-cols-12 gap-5">
                    <div id="aboutPanel" class="lg:col-span-3 bg-white rounded-2xl border border-slate-200 shadow-sm h-fit">
                        <div class="p-5 border-b border-slate-100">
                            <h2 class="font-serif text-lg text-slate-900 font-bold">About this book</h2>
                        </div>
                        <div class="p-5 space-y-4 text-sm text-slate-700">
                            <div class="text-slate-600 leading-relaxed">
                                {{ $book->description ?: 'No description provided yet.' }}
                            </div>
                            <div class="pt-2 border-t border-slate-100 space-y-2">
                                <div><span class="text-slate-500">Author:</span> {{ $book->author ?: '-' }}</div>
                                <div><span class="text-slate-500">Series:</span> {{ $book->series ?: '-' }}</div>
                                <div><span class="text-slate-500">Published:</span> {{ $book->published_at?->toDateString() ?? $book->created_at?->toDateString() }}</div>
                            </div>
                        </div>
                    </div>

                    <div id="readerPanel" class="lg:col-span-9 bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                        <div class="p-4 border-b border-slate-200 bg-slate-50 flex flex-wrap items-center gap-2">
                            <button type="button" id="prevPage" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white">Prev</button>
                            <button type="button" id="nextPage" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white">Next</button>
                            <div class="flex items-center gap-2 text-sm text-slate-700">
                                <span>Page</span>
                                <input id="pageNumber" type="number" min="1" value="1" class="w-16 px-2 py-1.5 border border-slate-200 rounded-lg text-sm">
                                <span>/</span>
                                <span id="pageCount">-</span>
                            </div>
                            <div class="mx-2 h-6 border-l border-slate-200"></div>
                            <button type="button" id="zoomOut" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white">-</button>
                            <button type="button" id="zoomIn" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white">+</button>
                            <button type="button" id="fitWidth" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white">Fit Width</button>
                            <button type="button" id="rotate" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white">Rotate</button>
                            <button type="button" id="toggleAbout" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white">About</button>
                            <div class="ml-auto flex gap-2">
                                <a
                                    href="https://docs.google.com/viewer?url={{ urlencode(asset('storage/'.$book->file_path)) }}"
                                    target="_blank"
                                    rel="noopener"
                                    class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white"
                                >
                                    Compatibility Reader
                                </a>
                                <button type="button" id="focusMode" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white">Focus Mode</button>
                                <button type="button" id="toggleFullscreen" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-white">Fullscreen</button>
                            </div>
                        </div>

                        <div class="p-3 border-b border-slate-200 bg-white flex flex-wrap items-center gap-2">
                            <select id="readingLanguage" class="px-3 py-2 rounded-lg border border-slate-200 text-sm text-slate-700">
                                <option value="rw-RW">Kinyarwanda</option>
                                <option value="en-US">English</option>
                                <option value="fr-FR">French</option>
                            </select>
                            <select id="readingSource" class="px-3 py-2 rounded-lg border border-slate-200 text-sm text-slate-700">
                                <option value="pdf">Read PDF text</option>
                                <option value="ocr">Read OCR (image)</option>
                            </select>
                            <select id="ttsVoice" class="px-3 py-2 rounded-lg border border-slate-200 text-sm text-slate-700 min-w-[200px]">
                                <option value="">System voice</option>
                            </select>
                            <label class="text-xs text-slate-500">Speed</label>
                            <input id="ttsRate" type="range" min="0.6" max="1.5" step="0.1" value="1" class="w-24">
                            <button type="button" id="readPage" class="px-3 py-2 rounded-lg bg-blue-900 text-white text-sm hover:bg-blue-800">Read Page</button>
                            <button type="button" id="pauseRead" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-slate-50">Pause/Resume</button>
                            <button type="button" id="stopRead" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-700 text-sm hover:bg-slate-50">Stop</button>
                            <span id="readerStatus" class="text-xs text-slate-500 ml-2"></span>
                        </div>

                        <div id="readerContainer" class="bg-slate-200 overflow-auto" style="height: 75vh;">
                            <canvas id="pdfCanvas" class="mx-auto my-4 shadow-md bg-white"></canvas>
                        </div>
                        <div class="p-3 border-t border-slate-200 bg-white">
                            <div class="text-xs text-slate-500 mb-1">Text to be read</div>
                            <textarea id="readPreview" rows="4" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-xs text-slate-700" readonly></textarea>
                        </div>
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
    let pdfDoc = null;
    let currentPage = 1;
    let totalPages = 0;
    let zoom = 1.2;
    let rotation = 0;
    let rendering = false;
    let pendingPage = null;
    let readSeconds = 0;
    let focusEnabled = false;
    let pageTextCache = {};
    let voices = [];
    let currentUtterance = null;
    let ocrRunning = false;

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
        setReaderStatus('Running OCR for scanned page...');
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
            setReaderStatus(text ? 'OCR text extracted.' : 'OCR could not detect readable text.');
            return text;
        } catch (error) {
            setReaderStatus('OCR failed. Use another page or Browser Reader.');
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
            setReaderStatus(`Voice set for ${selectedLang()}`);
        } else if (prefix === 'rw') {
            setReaderStatus('No native Kinyarwanda voice detected on this device.');
        }
    }

    async function speakCurrentPage() {
        if (!window.speechSynthesis) {
            alert('Your browser does not support speech reader.');
            return;
        }

        const rate = parseFloat(document.getElementById('ttsRate').value || '1');
        let text = await getPageText(currentPage);

        const preview = document.getElementById('readPreview');
        if (preview) {
            preview.value = text || '';
        }

        if (!text) {
            setReaderStatus('No readable text detected on this page. Try OCR mode.');
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
                setReaderStatus('No Kinyarwanda voice installed on this device.');
            }
        }

        window.speechSynthesis.speak(currentUtterance);
        trackBook('read_aloud', { watch_seconds: readSeconds });
        setReaderStatus('Reading aloud...');
    }

    function toggleFocusMode() {
        const shell = document.getElementById('readerShell');
        const readerPanel = document.getElementById('readerPanel');
        const container = document.getElementById('readerContainer');
        if (!shell || !readerPanel || !container) {
            return;
        }

        focusEnabled = !focusEnabled;
        if (focusEnabled) {
            shell.classList.add('fixed', 'inset-0', 'z-[70]', 'bg-slate-900', 'p-3', 'md:p-5');
            readerPanel.classList.remove('lg:col-span-9');
            readerPanel.classList.add('col-span-1');
            container.style.height = 'calc(100vh - 180px)';
            document.body.style.overflow = 'hidden';
            document.getElementById('aboutPanel')?.classList.add('hidden');
            setReaderStatus('Focus mode enabled');
        } else {
            shell.classList.remove('fixed', 'inset-0', 'z-[70]', 'bg-slate-900', 'p-3', 'md:p-5');
            readerPanel.classList.remove('col-span-1');
            readerPanel.classList.add('lg:col-span-9');
            container.style.height = '75vh';
            document.body.style.overflow = '';
            setReaderStatus('Focus mode disabled');
        }
    }

    function initReader() {
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
        pdfjsLib.getDocument(PDF_URL).promise.then((pdf) => {
            pdfDoc = pdf;
            totalPages = pdf.numPages;
            document.getElementById('pageCount').textContent = totalPages;
            queueRender(1);
            trackBook('open_reader');
        }).catch(() => {
            alert('Unable to load PDF in advanced reader. Use Browser Reader.');
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
            const preview = document.getElementById('readPreview');
            if (preview) preview.value = '';
            trackBook('read');
        });

        document.getElementById('nextPage').addEventListener('click', () => {
            if (currentPage >= totalPages) return;
            queueRender(currentPage + 1);
            const preview = document.getElementById('readPreview');
            if (preview) preview.value = '';
            trackBook('read');
        });

        document.getElementById('pageNumber').addEventListener('change', (event) => {
            const page = parseInt(event.target.value, 10);
            if (!page || page < 1 || page > totalPages) {
                event.target.value = currentPage;
                return;
            }
            queueRender(page);
            const preview = document.getElementById('readPreview');
            if (preview) preview.value = '';
            trackBook('read');
        });

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

        document.getElementById('toggleAbout').addEventListener('click', () => {
            const panel = document.getElementById('aboutPanel');
            panel?.classList.toggle('hidden');
        });

        document.getElementById('focusMode').addEventListener('click', toggleFocusMode);
        document.getElementById('readPage').addEventListener('click', speakCurrentPage);
        document.getElementById('pauseRead').addEventListener('click', () => {
            if (!window.speechSynthesis) return;
            if (window.speechSynthesis.paused) {
                window.speechSynthesis.resume();
            } else {
                window.speechSynthesis.pause();
            }
        });
        document.getElementById('stopRead').addEventListener('click', () => {
            if (!window.speechSynthesis) return;
            window.speechSynthesis.cancel();
            setReaderStatus('Reading stopped');
        });

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
            trackBook('read', { watch_seconds: readSeconds });
        }, 15000);
    });
</script>
@endif
@endsection
