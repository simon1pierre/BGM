@extends('layouts.audiences.app')
@section('contents')
<main class="grow bg-slate-50">
    <section class="pt-20 pb-10 bg-gradient-to-b from-blue-950 to-slate-900 text-white">
        <div class="container mx-auto px-6">
            <div class="max-w-3xl">
                <span class="inline-block py-1 px-3 rounded-full bg-blue-500/20 border border-blue-300/30 text-blue-100 text-xs font-medium tracking-widest uppercase mb-4">
                    Audiobooks
                </span>
                <h1 class="text-3xl md:text-5xl font-serif font-bold mb-4">Audiobook Library</h1>
                <p class="text-blue-100/90 text-lg">Listen to narrated books and study resources.</p>
            </div>
        </div>
    </section>

    <section class="py-10">
        <div class="container mx-auto px-6">
            <div class="flex flex-col lg:flex-row lg:items-center gap-4 pb-4">
                <form method="GET" action="{{ route('audiobooks.index') }}" class="w-full lg:max-w-md">
                    <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-full px-4 py-2 shadow-sm">
                        <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Search audiobooks..." class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none">
                        @if (!empty($activeCategory))
                            <input type="hidden" name="category" value="{{ $activeCategory }}">
                        @endif
                    </div>
                </form>
                <div class="flex items-center gap-3 overflow-x-auto">
                    @php $allActive = empty($activeCategory); @endphp
                    <a href="{{ route('audiobooks.index') }}" class="whitespace-nowrap px-4 py-2 rounded-full border text-sm font-medium inline-flex items-center gap-2 {{ $allActive ? 'bg-blue-900 text-white border-blue-900' : 'bg-white text-slate-700 border-slate-200' }}">
                        All
                        <span class="text-[11px] px-2 py-0.5 rounded-full {{ $allActive ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700' }}">{{ $allCount ?? 0 }}</span>
                    </a>
                    @foreach ($categories as $category)
                        <a href="{{ route('audiobooks.index', ['category' => $category->id]) }}" class="whitespace-nowrap px-4 py-2 rounded-full border text-sm font-medium inline-flex items-center gap-2 {{ (string) $activeCategory === (string) $category->id ? 'bg-blue-900 text-white border-blue-900' : 'bg-white text-slate-700 border-slate-200' }}">
                            {{ $category->name }}
                            <span class="text-[11px] px-2 py-0.5 rounded-full {{ (string) $activeCategory === (string) $category->id ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700' }}">{{ $category->audiobooks_count ?? 0 }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            @php
                $baseAudiobookQuery = array_filter(
                    ['q' => $search, 'category' => $activeCategory],
                    static fn ($value) => !is_null($value) && $value !== ''
                );
            @endphp
            <div class="flex items-center gap-2 overflow-x-auto">
                <a href="{{ route('audiobooks.index', $baseAudiobookQuery) }}" class="whitespace-nowrap px-3 py-1.5 rounded-full border text-xs font-medium {{ is_null($prayerFilter ?? null) ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-700 border-slate-200' }}">
                    All
                </a>
                <a href="{{ route('audiobooks.index', $baseAudiobookQuery + ['prayer' => '1']) }}" class="whitespace-nowrap px-3 py-1.5 rounded-full border text-xs font-medium {{ ($prayerFilter ?? null) === true ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-700 border-slate-200' }}">
                    Prayer Audio
                </a>
                <a href="{{ route('audiobooks.index', $baseAudiobookQuery + ['prayer' => '0']) }}" class="whitespace-nowrap px-3 py-1.5 rounded-full border text-xs font-medium {{ ($prayerFilter ?? null) === false ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-700 border-slate-200' }}">
                    Non-Prayer
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-6">
                @forelse ($audiobooks as $audiobook)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow border border-slate-100 flex flex-col">
                        <div class="relative aspect-[3/2] overflow-hidden bg-slate-100">
                            @if ($audiobook->thumbnail)
                                <img src="{{ asset('storage/'.$audiobook->thumbnail) }}" alt="{{ $audiobook->title }}" class="w-full h-full object-cover">
                            @else
                                <img src="{{ asset('landingpage/download-audio.webp') }}" alt="{{ $audiobook->title }}" class="w-full h-full object-cover">
                            @endif
                            @if ($audiobook->featured)
                                <span class="absolute top-3 left-3 bg-amber-500 text-white text-xs font-semibold px-3 py-1 rounded-full shadow">Featured</span>
                            @endif
                            @if ($audiobook->is_prayer_audio)
                                <span class="absolute top-3 right-3 bg-emerald-600 text-white text-xs font-semibold px-3 py-1 rounded-full shadow">Prayer</span>
                            @endif
                            @if (($audiobook->parts_count ?? 0) > 0)
                                <span class="absolute bottom-3 left-3 bg-slate-900/80 text-white text-xs font-semibold px-3 py-1 rounded-full shadow">{{ $audiobook->parts_count }} parts</span>
                            @endif
                        </div>
                        <div class="p-6 flex-1 flex flex-col">
                            <h3 class="text-xl font-serif font-bold text-blue-950 mb-2">{{ $audiobook->title }}</h3>
                            <p class="text-slate-600 text-sm mb-4">{{ \Illuminate\Support\Str::limit($audiobook->description, 140) }}</p>
                            @if ($audiobook->linkedBook)
                                <div class="text-xs text-slate-500 mb-4">Linked book: {{ $audiobook->linkedBook->title }}</div>
                            @endif
                            <div class="mt-auto">
                                <a href="{{ route('audiobooks.show', $audiobook) }}" class="text-blue-700 font-medium text-sm hover:text-blue-900">Open Player</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center text-slate-500">No audiobooks found.</div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $audiobooks->links() }}
            </div>
        </div>
    </section>
</main>
@endsection
