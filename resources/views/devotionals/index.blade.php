@extends('layouts.audiences.app')
@section('contents')
<main class="grow bg-slate-50">
    <section class="pt-20 pb-10 bg-gradient-to-b from-blue-950 to-slate-900 text-white">
        <div class="container mx-auto px-6">
            <div class="max-w-3xl">
                <span class="inline-block py-1 px-3 rounded-full bg-blue-500/20 border border-blue-300/30 text-blue-100 text-xs font-medium tracking-widest uppercase mb-4">
                    {{ __('messages.devotionals.badge') }}
                </span>
                <h1 class="text-3xl md:text-5xl font-serif font-bold mb-4">{{ __('messages.devotionals.title') }}</h1>
                <p class="text-blue-100/90 text-lg">{{ __('messages.devotionals.subtitle') }}</p>
            </div>
        </div>
    </section>

    <section class="py-10">
        <div class="container mx-auto px-6">
            <form method="GET" action="{{ route('devotionals.index') }}" class="flex flex-col lg:flex-row lg:items-center gap-3">
                <div class="w-full lg:max-w-md">
                    <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-full px-4 py-2 shadow-sm">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"></path>
                        </svg>
                        <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="{{ __('messages.devotionals.search_placeholder') }}" class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none">
                    </div>
                </div>
                <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="featured" value="1" class="rounded border-slate-300 text-blue-700 focus:ring-blue-600" @checked($featuredOnly)>
                    <span>{{ __('messages.devotionals.featured_only') }} ({{ $featuredCount }})</span>
                </label>
                <div class="flex items-center gap-2">
                    <button type="submit" class="px-4 py-2 rounded-full bg-blue-900 text-white text-sm font-semibold">{{ __('messages.devotionals.filter') }}</button>
                    <a href="{{ route('devotionals.index') }}" class="px-4 py-2 rounded-full bg-white border border-slate-200 text-slate-700 text-sm font-semibold">{{ __('messages.devotionals.reset') }}</a>
                </div>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                @forelse ($devotionals as $devotional)
                    <article class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-lg transition-shadow">
                        <a href="{{ route('devotionals.show', $devotional) }}" class="block">
                            <div class="aspect-[16/9] bg-slate-100 overflow-hidden">
                                @if ($devotional->cover_image)
                                    <img src="{{ $devotional->cover_image_url }}" alt="{{ $devotional->title }}" class="w-full h-full object-cover">
                                @else
                                    <img src="{{ asset('landingpage/download-book.webp') }}" alt="{{ $devotional->title }}" class="w-full h-full object-cover">
                                @endif
                            </div>
                        </a>
                        <div class="p-5">
                            <div class="flex items-center justify-between gap-3 mb-2">
                                <span class="text-xs text-blue-900 font-semibold">{{ $devotional->scripture_reference ?: __('messages.devotionals.daily_reflection') }}</span>
                                @if ($devotional->featured)
                                    <span class="text-[11px] px-2 py-0.5 rounded-full bg-amber-100 text-amber-700">{{ __('messages.common.featured') }}</span>
                                @endif
                            </div>
                            <h3 class="text-lg font-serif text-slate-900 leading-tight">
                                <a href="{{ route('devotionals.show', $devotional) }}" class="hover:text-blue-900 transition-colors">{{ $devotional->title }}</a>
                            </h3>
                            <p class="text-sm text-slate-600 mt-2">{{ \Illuminate\Support\Str::limit($devotional->excerpt ?: strip_tags($devotional->body), 140) }}</p>
                            <div class="mt-4 flex items-center justify-between text-xs text-slate-500">
                                <span>{{ $devotional->author ?: __('messages.devotionals.ministry_editorial') }}</span>
                                <span>{{ optional($devotional->published_at)->format('M d, Y') ?: $devotional->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full text-center text-slate-500 py-10">{{ __('messages.devotionals.none') }}</div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $devotionals->links('pagination.audience') }}
            </div>
        </div>
    </section>
</main>
@endsection







