@extends('layouts.audiences.app')

@section('contents')
<main class="flex-1">
  <section class="bg-gradient-to-b from-brand-blue via-blue-900 to-slate-900 text-white">
    <div class="container mx-auto px-6 py-18 lg:py-24">
      <div class="max-w-3xl">
        <p class="text-sm uppercase tracking-[0.3em] text-brand-gold mb-4">{{ __('messages.resources_page.badge') }}</p>
        <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-5">{{ __('messages.resources_page.title') }}</h1>
        <p class="text-base sm:text-lg text-blue-100 leading-relaxed">{{ __('messages.resources_page.subtitle') }}</p>
      </div>
    </div>
  </section>

  <section class="container mx-auto px-6 py-14 lg:py-18">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="h-44 bg-slate-100">
          @if (!empty($featuredVideo?->thumbnail_url))
            <img src="{{ $featuredVideo->thumbnail_url }}" alt="{{ $featuredVideo->title }}" class="w-full h-full object-cover">
          @else
            <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">{{ __('messages.resources_page.no_video_preview') }}</div>
          @endif
        </div>
        <div class="p-5">
          <div class="text-xs uppercase text-slate-500 mb-2">{{ __('messages.resources_page.featured_video') }}</div>
          <div class="font-semibold text-slate-900 mb-2">{{ $featuredVideo?->title ?? __('messages.resources_page.no_featured_video') }}</div>
          <p class="text-sm text-slate-600 mb-4">{{ \Illuminate\Support\Str::limit($featuredVideo?->description ?? __('messages.resources_page.featured_video_fallback'), 120) }}</p>
          <a href="{{ $featuredVideo ? route('videos.index') : '#' }}" class="text-sm font-semibold text-brand-blue hover:text-blue-800">{{ __('messages.resources_page.watch_sermons') }}</a>
        </div>
      </div>
      <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="h-44 bg-slate-100">
          @if (!empty($featuredBook?->cover_image))
            <img src="{{ asset('storage/' . $featuredBook->cover_image) }}" alt="{{ $featuredBook->title }}" class="w-full h-full object-cover">
          @else
            <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">{{ __('messages.resources_page.no_book_cover') }}</div>
          @endif
        </div>
        <div class="p-5">
          <div class="text-xs uppercase text-slate-500 mb-2">{{ __('messages.resources_page.featured_book') }}</div>
          <div class="font-semibold text-slate-900 mb-2">{{ $featuredBook?->title ?? __('messages.resources_page.no_featured_book') }}</div>
          <p class="text-sm text-slate-600 mb-4">{{ \Illuminate\Support\Str::limit($featuredBook?->description ?? __('messages.resources_page.featured_book_fallback'), 120) }}</p>
          <a href="{{ $featuredBook ? route('books.index') : '#' }}" class="text-sm font-semibold text-brand-blue hover:text-blue-800">{{ __('messages.resources_page.read_library') }}</a>
        </div>
      </div>
      <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="h-44 bg-slate-100">
          @if (!empty($featuredAudio?->thumbnail))
            <img src="{{ asset('storage/' . $featuredAudio->thumbnail) }}" alt="{{ $featuredAudio->title }}" class="w-full h-full object-cover">
          @else
            <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">{{ __('messages.resources_page.no_audio_artwork') }}</div>
          @endif
        </div>
        <div class="p-5">
          <div class="text-xs uppercase text-slate-500 mb-2">{{ __('messages.resources_page.featured_audio') }}</div>
          <div class="font-semibold text-slate-900 mb-2">{{ $featuredAudio?->title ?? __('messages.resources_page.no_featured_audio') }}</div>
          <p class="text-sm text-slate-600 mb-4">{{ \Illuminate\Support\Str::limit($featuredAudio?->description ?? __('messages.resources_page.featured_audio_fallback'), 120) }}</p>
          <a href="{{ $featuredAudio ? route('audios.index') : '#' }}" class="text-sm font-semibold text-brand-blue hover:text-blue-800">{{ __('messages.resources_page.listen_now') }}</a>
        </div>
      </div>
    </div>
  </section>

  <section class="bg-white">
    <div class="container mx-auto px-6 py-14 lg:py-18">
      <div class="flex items-center justify-between mb-6">
        <h2 class="font-serif text-2xl sm:text-3xl text-slate-900">{{ __('messages.resources_page.latest_videos') }}</h2>
        <a href="{{ route('videos.index') }}" class="text-sm font-semibold text-brand-blue">{{ __('messages.common.view_all') }}</a>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($videos as $video)
          <a href="{{ route('videos.index') }}" class="group bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden hover-lift">
            <div class="h-40 bg-slate-100">
              @if (!empty($video->thumbnail_url))
                <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" class="w-full h-full object-cover">
              @else
                <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">{{ __('messages.resources_page.no_video_preview') }}</div>
              @endif
            </div>
            <div class="p-4">
              <div class="text-xs text-slate-500 mb-2">{{ __('messages.resources_page.video_sermon') }}</div>
              <div class="font-semibold text-slate-900 group-hover:text-brand-blue">{{ $video->title }}</div>
            </div>
          </a>
        @empty
          <div class="text-sm text-slate-500">{{ __('messages.resources_page.no_videos') }}</div>
        @endforelse
      </div>
    </div>
  </section>

  <section class="container mx-auto px-6 py-14 lg:py-18">
    <div class="flex items-center justify-between mb-6">
      <h2 class="font-serif text-2xl sm:text-3xl text-slate-900">{{ __('messages.resources_page.books_guides') }}</h2>
      <a href="{{ route('books.index') }}" class="text-sm font-semibold text-brand-blue">{{ __('messages.common.view_all') }}</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @forelse ($books as $book)
        <a href="{{ route('books.show', $book) }}" class="group bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden hover-lift">
          <div class="h-40 bg-slate-100">
            @if (!empty($book->cover_image))
              <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
            @else
              <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">{{ __('messages.books.no_cover') }}</div>
            @endif
          </div>
          <div class="p-4">
            <div class="text-xs text-slate-500 mb-2">{{ __('messages.common.book') }}</div>
            <div class="font-semibold text-slate-900 group-hover:text-brand-blue">{{ $book->title }}</div>
          </div>
        </a>
      @empty
        <div class="text-sm text-slate-500">{{ __('messages.resources_page.no_books') }}</div>
      @endforelse
    </div>
  </section>

  <section class="bg-white">
    <div class="container mx-auto px-6 py-14 lg:py-18">
      <div class="flex items-center justify-between mb-6">
        <h2 class="font-serif text-2xl sm:text-3xl text-slate-900">{{ __('messages.resources_page.audio_teachings') }}</h2>
        <a href="{{ route('audios.index') }}" class="text-sm font-semibold text-brand-blue">{{ __('messages.common.view_all') }}</a>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($audios as $audio)
          <a href="{{ route('audios.show', $audio) }}" class="group bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden hover-lift">
            <div class="h-40 bg-slate-100">
              @if (!empty($audio->thumbnail))
                <img src="{{ asset('storage/' . $audio->thumbnail) }}" alt="{{ $audio->title }}" class="w-full h-full object-cover">
              @else
                <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">{{ __('messages.resources_page.no_audio_artwork') }}</div>
              @endif
            </div>
            <div class="p-4">
              <div class="text-xs text-slate-500 mb-2">{{ __('messages.common.audio') }}</div>
              <div class="font-semibold text-slate-900 group-hover:text-brand-blue">{{ $audio->title }}</div>
            </div>
          </a>
        @empty
          <div class="text-sm text-slate-500">{{ __('messages.resources_page.no_audio') }}</div>
        @endforelse
      </div>
    </div>
  </section>
</main>
@endsection
