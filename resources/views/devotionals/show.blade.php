@extends('layouts.audiences.app')
@section('contents')
<main class="grow bg-slate-50">
    <div id="readingProgressBar" class="fixed top-0 left-0 z-[60] h-1 bg-blue-700 transition-all duration-150" style="width:0%"></div>

    <section class="pt-20 pb-10 bg-gradient-to-b from-blue-950 to-slate-900 text-white">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl">
                <a href="{{ route('devotionals.index') }}" class="inline-flex items-center text-sm text-blue-100 hover:text-white mb-5">
                    &larr; {{ __('messages.devotionals.back_to_list') }}
                </a>
                <h1 class="text-3xl md:text-5xl font-serif font-bold mb-4">{{ $devotional->title }}</h1>
                <div class="flex flex-wrap items-center gap-3 text-sm text-blue-100/90">
                    <span>{{ $devotional->author ?: __('messages.devotionals.ministry_editorial') }}</span>
                    <span>•</span>
                    <span>{{ optional($devotional->published_at)->format('F d, Y') ?: $devotional->created_at->format('F d, Y') }}</span>
                    @if ($devotional->scripture_reference)
                        <span>•</span>
                        <span>{{ $devotional->scripture_reference }}</span>
                    @endif
                    <span>•</span>
                    <span id="readTimeBadge">{{ max(2, (int) ceil(str_word_count(strip_tags($devotional->body)) / 200)) }} min read</span>
                </div>
            </div>
        </div>
    </section>

    <section class="py-10">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <article class="lg:col-span-8 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    @if ($devotional->cover_image_url)
                        <img src="{{ $devotional->cover_image_url }}" alt="{{ $devotional->title }}" class="w-full h-72 md:h-96 object-cover">
                    @endif
                    <div class="p-6 md:p-8" id="devotionalBodyWrap">
                        @if ($devotional->excerpt)
                            <p class="text-slate-600 text-lg leading-relaxed mb-6">{{ $devotional->excerpt }}</p>
                        @endif

                        <div class="mb-6 flex flex-wrap items-center gap-2">
                            <button type="button" id="fontDownBtn" class="px-3 py-1.5 rounded-lg border border-slate-300 text-slate-700 text-sm hover:bg-slate-100">A-</button>
                            <button type="button" id="fontUpBtn" class="px-3 py-1.5 rounded-lg border border-slate-300 text-slate-700 text-sm hover:bg-slate-100">A+</button>
                            <button type="button" id="copyLinkBtn" class="px-3 py-1.5 rounded-lg border border-slate-300 text-slate-700 text-sm hover:bg-slate-100">Copy Link</button>
                            <a href="https://wa.me/?text={{ urlencode($devotional->title.' - '.request()->fullUrl()) }}" target="_blank" rel="noopener" class="px-3 py-1.5 rounded-lg border border-slate-300 text-slate-700 text-sm hover:bg-slate-100">Share WhatsApp</a>
                        </div>

                        <div id="devotionalBody" class="prose prose-slate max-w-none leading-relaxed" style="font-size: 1.05rem;">
                            {!! nl2br(e($devotional->body)) !!}
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-100">
                            <h3 class="text-lg font-serif font-semibold text-slate-900 mb-3">Continue Reading</h3>
                            <div class="flex flex-wrap items-center gap-2">
                                @if ($previousDevotional)
                                    <a href="{{ route('devotionals.show', $previousDevotional) }}" class="px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm">← Previous</a>
                                @endif
                                @if ($nextDevotional)
                                    <a href="{{ route('devotionals.show', $nextDevotional) }}" class="px-4 py-2 rounded-lg bg-blue-700 hover:bg-blue-800 text-white text-sm">Next →</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </article>

                <aside class="lg:col-span-4 space-y-4">
                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 sticky top-24">
                        <h2 class="text-lg font-serif text-slate-900 mb-3">{{ __('messages.devotionals.more_reflections') }}</h2>
                        <div class="space-y-3">
                            @forelse($relatedDevotionals as $related)
                                <a href="{{ route('devotionals.show', $related) }}" class="block p-3 rounded-xl border border-slate-100 hover:border-blue-200 hover:bg-blue-50/30 transition-colors">
                                    <div class="text-sm font-semibold text-slate-800 line-clamp-2">{{ $related->title }}</div>
                                    <div class="text-xs text-slate-500 mt-1">{{ optional($related->published_at)->format('M d, Y') ?: $related->created_at->format('M d, Y') }}</div>
                                </a>
                            @empty
                                <div class="text-sm text-slate-500">{{ __('messages.devotionals.no_related') }}</div>
                            @endforelse
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</main>

<script>
(() => {
    const body = document.getElementById('devotionalBody');
    const copyBtn = document.getElementById('copyLinkBtn');
    const progress = document.getElementById('readingProgressBar');
    const down = document.getElementById('fontDownBtn');
    const up = document.getElementById('fontUpBtn');
    let fontSize = 1.05;

    const updateProgress = () => {
        const total = document.documentElement.scrollHeight - window.innerHeight;
        const current = window.scrollY || window.pageYOffset || 0;
        const ratio = total > 0 ? Math.min(100, Math.max(0, (current / total) * 100)) : 0;
        progress.style.width = ratio + '%';
    };

    window.addEventListener('scroll', updateProgress, { passive: true });
    updateProgress();

    if (copyBtn) {
        copyBtn.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(window.location.href);
                if (window.appToast) window.appToast('Link copied', 'success');
            } catch (e) {
                if (window.appToast) window.appToast('Unable to copy link', 'error');
            }
        });
    }

    const applyFont = () => {
        if (!body) return;
        body.style.fontSize = fontSize.toFixed(2) + 'rem';
    };
    down?.addEventListener('click', () => {
        fontSize = Math.max(0.9, fontSize - 0.05);
        applyFont();
    });
    up?.addEventListener('click', () => {
        fontSize = Math.min(1.4, fontSize + 0.05);
        applyFont();
    });
})();
</script>
@endsection

