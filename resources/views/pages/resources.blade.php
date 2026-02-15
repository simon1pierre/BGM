@extends('layouts.audiences.app')

@section('contents')
<main class="flex-1">
  <section class="bg-gradient-to-b from-brand-blue via-blue-900 to-slate-900 text-white">
    <div class="container mx-auto px-6 py-18 lg:py-24">
      <div class="max-w-3xl">
        <p class="text-sm uppercase tracking-[0.3em] text-brand-gold mb-4">Resources</p>
        <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-5">
          Discover sermons, audio teachings, and study guides.
        </h1>
        <p class="text-base sm:text-lg text-blue-100 leading-relaxed">
          Explore curated content to help you grow in faith and understanding.
        </p>
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
            <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">No video preview</div>
          @endif
        </div>
        <div class="p-5">
          <div class="text-xs uppercase text-slate-500 mb-2">Featured Video</div>
          <div class="font-semibold text-slate-900 mb-2">{{ $featuredVideo?->title ?? 'No featured video yet' }}</div>
          <p class="text-sm text-slate-600 mb-4">
            {{ \Illuminate\Support\Str::limit($featuredVideo?->description ?? 'Check back for the latest featured message.', 120) }}
          </p>
          <a href="{{ $featuredVideo ? route('videos.index') : '#' }}" class="text-sm font-semibold text-brand-blue hover:text-blue-800">
            Watch sermons
          </a>
        </div>
      </div>
      <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="h-44 bg-slate-100">
          @if (!empty($featuredBook?->cover_image))
            <img src="{{ asset('storage/' . $featuredBook->cover_image) }}" alt="{{ $featuredBook->title }}" class="w-full h-full object-cover">
          @else
            <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">No book cover</div>
          @endif
        </div>
        <div class="p-5">
          <div class="text-xs uppercase text-slate-500 mb-2">Featured Book</div>
          <div class="font-semibold text-slate-900 mb-2">{{ $featuredBook?->title ?? 'No featured book yet' }}</div>
          <p class="text-sm text-slate-600 mb-4">
            {{ \Illuminate\Support\Str::limit($featuredBook?->description ?? 'Browse study resources and guides.', 120) }}
          </p>
          <a href="{{ $featuredBook ? route('books.index') : '#' }}" class="text-sm font-semibold text-brand-blue hover:text-blue-800">
            Read the library
          </a>
        </div>
      </div>
      <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="h-44 bg-slate-100">
          @if (!empty($featuredAudio?->thumbnail))
            <img src="{{ asset('storage/' . $featuredAudio->thumbnail) }}" alt="{{ $featuredAudio->title }}" class="w-full h-full object-cover">
          @else
            <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">No audio artwork</div>
          @endif
        </div>
        <div class="p-5">
          <div class="text-xs uppercase text-slate-500 mb-2">Featured Audio</div>
          <div class="font-semibold text-slate-900 mb-2">{{ $featuredAudio?->title ?? 'No featured audio yet' }}</div>
          <p class="text-sm text-slate-600 mb-4">
            {{ \Illuminate\Support\Str::limit($featuredAudio?->description ?? 'Listen to teachings anywhere.', 120) }}
          </p>
          <a href="{{ $featuredAudio ? route('audios.index') : '#' }}" class="text-sm font-semibold text-brand-blue hover:text-blue-800">
            Listen now
          </a>
        </div>
      </div>
    </div>
  </section>

  <section class="bg-white">
    <div class="container mx-auto px-6 py-14 lg:py-18">
      <div class="flex items-center justify-between mb-6">
        <h2 class="font-serif text-2xl sm:text-3xl text-slate-900">Latest Videos</h2>
        <a href="{{ route('videos.index') }}" class="text-sm font-semibold text-brand-blue">View all</a>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($videos as $video)
          <a href="{{ route('videos.index') }}" class="group bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden hover-lift">
            <div class="h-40 bg-slate-100">
              @if (!empty($video->thumbnail_url))
                <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" class="w-full h-full object-cover">
              @else
                <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">No video preview</div>
              @endif
            </div>
            <div class="p-4">
              <div class="text-xs text-slate-500 mb-2">Video Sermon</div>
              <div class="font-semibold text-slate-900 group-hover:text-brand-blue">{{ $video->title }}</div>
            </div>
          </a>
        @empty
          <div class="text-sm text-slate-500">No videos published yet.</div>
        @endforelse
      </div>
    </div>
  </section>

  <section class="container mx-auto px-6 py-14 lg:py-18">
    <div class="flex items-center justify-between mb-6">
      <h2 class="font-serif text-2xl sm:text-3xl text-slate-900">Books & Guides</h2>
      <a href="{{ route('books.index') }}" class="text-sm font-semibold text-brand-blue">View all</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @forelse ($books as $book)
        <a href="{{ route('books.show', $book) }}" class="group bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden hover-lift">
          <div class="h-40 bg-slate-100">
            @if (!empty($book->cover_image))
              <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
            @else
              <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">No cover</div>
            @endif
          </div>
          <div class="p-4">
            <div class="text-xs text-slate-500 mb-2">Book</div>
            <div class="font-semibold text-slate-900 group-hover:text-brand-blue">{{ $book->title }}</div>
          </div>
        </a>
      @empty
        <div class="text-sm text-slate-500">No books published yet.</div>
      @endforelse
    </div>
  </section>

  <section class="bg-white">
    <div class="container mx-auto px-6 py-14 lg:py-18">
      <div class="flex items-center justify-between mb-6">
        <h2 class="font-serif text-2xl sm:text-3xl text-slate-900">Audio Teachings</h2>
        <a href="{{ route('audios.index') }}" class="text-sm font-semibold text-brand-blue">View all</a>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($audios as $audio)
          <a href="{{ route('audios.show', $audio) }}" class="group bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden hover-lift">
            <div class="h-40 bg-slate-100">
              @if (!empty($audio->thumbnail))
                <img src="{{ asset('storage/' . $audio->thumbnail) }}" alt="{{ $audio->title }}" class="w-full h-full object-cover">
              @else
                <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">No artwork</div>
              @endif
            </div>
            <div class="p-4">
              <div class="text-xs text-slate-500 mb-2">Audio</div>
              <div class="font-semibold text-slate-900 group-hover:text-brand-blue">{{ $audio->title }}</div>
            </div>
          </a>
        @empty
          <div class="text-sm text-slate-500">No audio published yet.</div>
        @endforelse
      </div>
    </div>
  </section>
</main>
@endsection
