@extends('layouts.audiences.app')
@section('contents')
<main class="grow bg-slate-50">
    <section class="pt-16 pb-10 bg-gradient-to-b from-blue-950 to-slate-900 text-white">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl">
                <span class="inline-block py-1 px-3 rounded-full bg-blue-500/20 border border-blue-300/30 text-blue-100 text-xs font-medium tracking-widest uppercase mb-4">
                    Audiobook
                </span>
                <h1 class="text-3xl md:text-5xl font-serif font-bold mb-3">{{ $audiobook->title }}</h1>
                <p class="text-blue-100/90 text-lg">{{ $audiobook->category?->name ?? 'Audio' }}</p>
            </div>
        </div>
    </section>

    <section class="py-10">
        <div class="container mx-auto px-6">
            @php
                $parts = $audiobook->publishedParts ?? collect();
                $defaultAudio = $parts->first()?->audio_file ?: $audiobook->audio_file;
            @endphp
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="font-serif text-xl font-bold text-blue-950">Now Playing</h2>
                            @if ($parts->count() > 0)
                                <span class="text-xs font-semibold px-3 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-100">{{ $parts->count() }} parts</span>
                            @endif
                        </div>
                        <div id="nowPlayingTitle" class="text-sm text-slate-600 mb-3">
                            {{ $parts->count() > 0 ? ($parts->first()->title ?: 'Part 1') : $audiobook->title }}
                        </div>
                        @if ($defaultAudio)
                            <audio id="audiobookMainPlayer" controls class="w-full mb-4">
                                <source id="audiobookMainSource" src="{{ asset('storage/'.$defaultAudio) }}" type="audio/mpeg">
                            </audio>
                        @else
                            <div class="mb-4 text-sm text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2">
                                No playable part is published yet.
                            </div>
                        @endif
                        @if ($parts->count() > 1)
                            <label class="inline-flex items-center gap-2 text-xs text-slate-600 mb-4">
                                <input type="checkbox" id="autoNextPart" class="rounded border-slate-300" checked>
                                Auto play next part
                            </label>
                        @endif
                        <p class="text-slate-600 text-sm leading-relaxed">{{ $audiobook->description }}</p>
                    </div>

                    @if ($parts->count() > 0)
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                            <h3 class="font-serif text-xl font-bold text-blue-950 mb-4">All Parts</h3>
                            <div class="space-y-3 max-h-[480px] overflow-auto pr-1" id="partsList">
                                @foreach ($parts as $index => $part)
                                    <button
                                        type="button"
                                        class="w-full text-left p-3 rounded-xl border border-slate-200 hover:bg-slate-50 transition-colors"
                                        data-part-title="{{ $part->title ?: 'Part '.($index + 1) }}"
                                        data-part-src="{{ asset('storage/'.$part->audio_file) }}"
                                    >
                                        <div class="flex items-center justify-between gap-3">
                                            <div>
                                                <div class="text-xs uppercase tracking-wide text-slate-400">Part {{ $index + 1 }}</div>
                                                <div class="text-sm font-semibold text-slate-800">{{ $part->title ?: 'Part '.($index + 1) }}</div>
                                            </div>
                                            <div class="text-xs text-slate-500">{{ $part->duration ?: '-' }}</div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                        <h2 class="text-xl font-serif font-bold text-blue-950 mb-3">Details</h2>
                        <div class="text-sm text-slate-600 space-y-2">
                            <div>Narrator: {{ $audiobook->narrator ?: '-' }}</div>
                            <div>Series: {{ $audiobook->series ?: '-' }}</div>
                            <div>Published: {{ $audiobook->published_at?->toDateString() ?? $audiobook->created_at?->toDateString() }}</div>
                            <div>Prayer Audio: {{ $audiobook->is_prayer_audio ? 'Yes' : 'No' }}</div>
                        </div>
                        @if ($audiobook->linkedBook)
                            <a href="{{ route('books.show', $audiobook->linkedBook) }}" class="mt-4 inline-flex text-blue-700 hover:text-blue-900 text-sm font-medium">
                                Open linked book
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            @if (!empty($relatedAudiobooks) && $relatedAudiobooks->count())
                <div class="mt-12">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-serif font-bold text-blue-950">You may also like</h3>
                        <a href="{{ route('audiobooks.index') }}" class="text-sm text-blue-700 hover:text-blue-900">Browse all</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ($relatedAudiobooks as $item)
                            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100">
                                <div class="relative aspect-[3/2] overflow-hidden bg-slate-100">
                                    @if ($item->thumbnail)
                                        <img src="{{ asset('storage/'.$item->thumbnail) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                    @endif
                                    @if (($item->parts_count ?? 0) > 0)
                                        <span class="absolute bottom-3 left-3 bg-slate-900/80 text-white text-xs font-semibold px-3 py-1 rounded-full">{{ $item->parts_count }} parts</span>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <div class="font-serif text-blue-950 font-semibold text-sm">{{ $item->title }}</div>
                                    <a href="{{ route('audiobooks.show', $item) }}" class="inline-flex text-sm text-blue-700 hover:text-blue-900 mt-3">Open</a>
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
    document.addEventListener('DOMContentLoaded', () => {
        const player = document.getElementById('audiobookMainPlayer');
        const source = document.getElementById('audiobookMainSource');
        const titleNode = document.getElementById('nowPlayingTitle');
        const autoNext = document.getElementById('autoNextPart');
        const buttons = document.querySelectorAll('#partsList [data-part-src]');
        let currentIndex = 0;

        buttons.forEach((button, index) => {
            button.addEventListener('click', () => {
                if (!player || !source) return;
                currentIndex = index;
                source.src = button.dataset.partSrc || '';
                player.load();
                player.play().catch(() => {});
                if (titleNode) {
                    titleNode.textContent = button.dataset.partTitle || '';
                }
                buttons.forEach((node) => node.classList.remove('ring-2', 'ring-blue-400'));
                button.classList.add('ring-2', 'ring-blue-400');
            });
        });

        player?.addEventListener('ended', () => {
            if (!autoNext || !autoNext.checked) return;
            const next = buttons[currentIndex + 1];
            if (!next) return;
            next.click();
        });
    });
</script>
@endsection
