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
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                        <audio controls class="w-full mb-4">
                            <source src="{{ asset('storage/'.$audiobook->audio_file) }}" type="audio/mpeg">
                        </audio>
                        <p class="text-slate-600 text-sm leading-relaxed">{{ $audiobook->description }}</p>
                    </div>
                </div>
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
                        <h2 class="text-xl font-serif font-bold text-blue-950 mb-3">Details</h2>
                        <div class="text-sm text-slate-600 space-y-2">
                            <div>Narrator: {{ $audiobook->narrator ?: '-' }}</div>
                            <div>Series: {{ $audiobook->series ?: '-' }}</div>
                            <div>Published: {{ $audiobook->published_at?->toDateString() ?? $audiobook->created_at?->toDateString() }}</div>
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
@endsection
