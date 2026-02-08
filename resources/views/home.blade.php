@extends('layouts.audiences.app')
@section('contents')
@php
  $siteName = $siteSettings?->translated('site_name') ?: config('app.name', 'Beacons of God Ministries');
  $heroTitle = $siteSettings?->translated('hero_title') ?: "Shining God's Truth in a Darkened World";
  $heroSubtitle = $siteSettings?->translated('hero_subtitle') ?: 'We are beacons of His light for this generation. Join us for biblical teaching, evangelism, and spiritual growth in the presence of the Lord.';
  $heroPrimaryLabel = $siteSettings?->translated('hero_primary_label') ?: 'Watch Sermons';
  $heroPrimaryUrl = $siteSettings?->hero_primary_url ?: route('videos.index');
  $heroSecondaryLabel = $siteSettings?->translated('hero_secondary_label') ?: 'Explore Resources';
  $heroSecondaryUrl = $siteSettings?->hero_secondary_url ?: '#resources';
@endphp
 <main class="grow">
<section class="relative w-full min-h-[85vh] flex items-center justify-center overflow-hidden bg-slate-900 text-white">
  <!-- Background Image with Overlay -->
  <div class="absolute inset-0 z-0">
    <img
      data-ai="generate"
      data-slot="hero-bg"
      data-prompt="Divine rays of light breaking through soft clouds in a deep blue sky, cinematic, ethereal, peaceful, 8k resolution"
      data-ar="16:9"
      src="{{asset('landingpage/hero-divine-background.webp')}}"
      alt="Divine light background"
      class="w-full h-full object-cover opacity-60"
      loading="eager"
    />
    <div class="absolute inset-0 bg-gradient-to-b from-blue-900/80 via-blue-950/70 to-slate-900/90 mix-blend-multiply"></div>
  </div>


  <!-- Dove Animation Styles -->
  <style>
    @keyframes float-dove {
      0% { transform: translate(0, 0) scale(0.8); opacity: 0; }
      10% { opacity: 0.4; }
      90% { opacity: 0.4; }
      100% { transform: translate(100vw, -20vh) scale(1.2); opacity: 0; }
    }
    .dove-anim {
      position: absolute;
      animation: float-dove 20s linear infinite;
      opacity: 0;
      color: rgba(255, 255, 255, 0.6);
    }
    .dove-1 { top: 60%; left: -10%; animation-duration: 25s; animation-delay: 0s; }
    .dove-2 { top: 40%; left: -10%; animation-duration: 30s; animation-delay: 8s; transform: scale(0.7); }
    .dove-3 { top: 70%; left: -10%; animation-duration: 22s; animation-delay: 15s; transform: scale(0.9); }
  </style>


  <!-- Animated Doves Layer -->
  <div class="absolute inset-0 z-10 pointer-events-none overflow-hidden">
    <!-- Dove 1 -->
    <div class="dove-anim dove-1">
      <svg width="64" height="64" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path d="M20.5 12.5L16 15L12.5 11.5L9 15L4.5 12.5L2 10L5.5 9L9 10.5L12.5 7L16 10.5L19.5 9L23 10L20.5 12.5Z" stroke="none" />
        <path d="M12 3C14 5 16 6 19 6C17 8 15 9 12 11C9 9 7 8 5 6C8 6 10 5 12 3Z" fill="currentColor" opacity="0.8"/>
      </svg>
    </div>
    <!-- Dove 2 -->
    <div class="dove-anim dove-2">
      <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 3C14 5 16 6 19 6C17 8 15 9 12 11C9 9 7 8 5 6C8 6 10 5 12 3Z" fill="currentColor"/>
      </svg>
    </div>
    <!-- Dove 3 -->
    <div class="dove-anim dove-3">
      <svg width="56" height="56" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 3C14 5 16 6 19 6C17 8 15 9 12 11C9 9 7 8 5 6C8 6 10 5 12 3Z" fill="currentColor"/>
      </svg>
    </div>
  </div>


  <!-- Content -->
  <div class="relative z-20 container mx-auto px-6 text-center">
    <span class="inline-block py-1 px-3 rounded-full bg-blue-500/20 border border-blue-300/30 text-blue-100 text-sm font-medium tracking-widest uppercase mb-6 backdrop-blur-sm animate-fade-in-down">
      {{ __('messages.home.welcome', ['name' => $siteName]) }}
    </span>
    <h1 class="text-4xl md:text-6xl lg:text-7xl font-serif font-medium leading-tight mb-6 drop-shadow-lg animate-fade-in-up" style="animation-delay: 0.2s;">
      {{ $heroTitle }}
    </h1>
    <p class="text-lg md:text-xl text-blue-100/90 max-w-2xl mx-auto mb-10 font-light leading-relaxed animate-fade-in-up" style="animation-delay: 0.4s;">
      {{ $heroSubtitle }}
    </p>
    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-stagger">
      <a href="{{ $heroPrimaryUrl }}" class="px-8 py-4 bg-white text-blue-900 rounded-full font-semibold hover:bg-blue-50 transition-all shadow-[0_0_20px_rgba(255,255,255,0.3)] hover:shadow-[0_0_30px_rgba(255,255,255,0.5)] hover-lift">
        {{ $heroPrimaryLabel }}
      </a>
      <a href="{{ $heroSecondaryUrl }}" class="px-8 py-4 bg-transparent border border-white/40 text-white rounded-full font-medium hover:bg-white/10 transition-all backdrop-blur-sm hover-lift">
        {{ $heroSecondaryLabel }}
      </a>
    </div>
  </div>
</section>
<!-- About Section -->
<section id="about" class="py-6 bg-slate-50 text-slate-800">
  <div class="container mx-auto px-6">
    <div class="text-center mb-8">
    <h2 class="text-4xl md:text-5xl font-serif font-bold text-brand-blue mb-4">
        {{ __('messages.home.about_title') }}
    </h2>
      <div class="w-12 h-1 bg-brand-gold mx-auto"></div>
    </div>


    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <!-- Mission Card -->
      <div class="bg-white rounded-lg shadow-md p-8 hover:shadow-lg transition-shadow">
        <h3 class="text-2xl font-serif font-bold text-brand-blue mb-4">{{ __('messages.home.mission_title') }}</h3>
        <p class="text-lg text-slate-700 leading-relaxed">
          {{ __('messages.home.mission_body') }}
        </p>
      </div>


      <!-- What We Offer Card - Animated -->
      <div class="bg-white rounded-lg shadow-md p-8 hover:shadow-lg transition-shadow animate-float-slow">
        <h3 class="text-2xl font-serif font-bold text-brand-blue mb-4">{{ __('messages.home.offer_title') }}</h3>
        <ul class="space-y-3 text-lg text-slate-700">
          <li class="flex items-start gap-3 hover:translate-x-1 transition-transform">
            <span class="text-brand-gold font-bold text-xl">✦</span>
            <span><strong>{{ __('messages.home.offer_sermons_title') }}</strong> — {{ __('messages.home.offer_sermons_body') }}</span>
          </li>
          <li class="flex items-start gap-3 hover:translate-x-1 transition-transform">
            <span class="text-brand-gold font-bold text-xl">✦</span>
            <span><strong>{{ __('messages.home.offer_resources_title') }}</strong> — {{ __('messages.home.offer_resources_body') }}</span>
          </li>
          <li class="flex items-start gap-3 hover:translate-x-1 transition-transform">
            <span class="text-brand-gold font-bold text-xl">✦</span>
            <span><strong>{{ __('messages.home.offer_audio_title') }}</strong> — {{ __('messages.home.offer_audio_body') }}</span>
          </li>
          <li class="flex items-start gap-3 hover:translate-x-1 transition-transform">
            <span class="text-brand-gold font-bold text-xl">✦</span>
            <span><strong>{{ __('messages.home.offer_community_title') }}</strong> — {{ __('messages.home.offer_community_body') }}</span>
          </li>
        </ul>
      </div>


      <!-- Vision Card -->
      <div class="bg-white rounded-lg shadow-md p-8 hover:shadow-lg transition-shadow">
        <h3 class="text-2xl font-serif font-bold text-brand-blue mb-4">{{ __('messages.home.vision_title') }}</h3>
        <p class="text-lg text-slate-700 leading-relaxed">
          {{ __('messages.home.vision_body') }}
        </p>
      </div>
    </div>
  </div>
</section>
<section id="resources" class="py-24 bg-slate-50">
  <div class="container mx-auto px-6">
    <div class="text-center mb-16">
      <h2 class="text-3xl md:text-4xl font-serif text-blue-900 mb-4">{{ __('messages.home.resources_title') }}</h2>
      <p class="text-slate-600 max-w-2xl mx-auto">{{ __('messages.home.resources_subtitle') }}</p>
    </div>
   
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <!-- Feature 1: Sermons -->
      <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100 scroll-animate hover-lift hover-glow-intense ripple-container">
        <div class="aspect-[3/2] overflow-hidden bg-slate-100">
          @if (!empty($featuredVideo?->youtube_id))
            <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $featuredVideo->youtube_id }}?controls=1&modestbranding=1&rel=0" title="{{ $featuredVideo->title }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
          @elseif (!empty($featuredVideo?->thumbnail_url))
            <img
              src="{{ $featuredVideo->thumbnail_url }}"
              alt="{{ $featuredVideo->title }}"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              loading="lazy"
            />
          @else
            <img
              src="{{asset('landingpage/video-sermons.webp')}}"
              alt="{{ __('messages.home.video_sermons') }}"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              loading="lazy"
            />
          @endif
        </div>
        <div class="p-8 text-center">
          <h3 class="text-xl font-serif text-blue-900 mb-3">{{ $featuredVideo?->title ?? __('messages.home.video_sermons') }}</h3>
          <p class="text-slate-600 mb-6 text-sm leading-relaxed">
            {{ $featuredVideo?->description ? \Illuminate\Support\Str::limit($featuredVideo->description, 140) : __('messages.home.video_sermons_body') }}
          </p>
          <a href="{{ route('videos.index') }}" class="text-blue-700 font-medium hover:text-blue-900 inline-flex items-center gap-2 text-sm uppercase tracking-wide">
            {{ __('messages.home.watch_more') }}
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
          </a>
        </div>
      </div>


      <!-- Feature 2: Books -->
      <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100 scroll-animate hover-lift hover-glow-intense ripple-container">
        <div class="aspect-[3/2] overflow-hidden bg-slate-100">
          @if ($featuredBook && $featuredBook->cover_image)
            <img
              src="{{ asset('storage/'.$featuredBook->cover_image) }}"
              alt="{{ $featuredBook->title }}"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              loading="lazy"
            />
          @else
            <img
              src="{{asset('landingpage/download-book.webp')}}"
              alt="{{ __('messages.home.downloadable_books') }}"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              loading="lazy"
            />
          @endif
        </div>
        <div class="p-8 text-center">
          <h3 class="text-xl font-serif text-blue-900 mb-3">{{ $featuredBook?->title ?? __('messages.home.downloadable_books') }}</h3>
          <p class="text-slate-600 mb-6 text-sm leading-relaxed">
            {{ $featuredBook?->description ? \Illuminate\Support\Str::limit($featuredBook->description, 140) : __('messages.home.downloadable_books_body') }}
          </p>
          <a href="{{ $featuredBook ? route('books.show', $featuredBook) : route('books.index') }}" class="text-blue-700 font-medium hover:text-blue-900 inline-flex items-center gap-2 text-sm uppercase tracking-wide">
            {{ __('messages.home.read_library') }}
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
          </a>
        </div>
      </div>


      <!-- Feature 3: Audio -->
      <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100 scroll-animate hover-lift hover-glow-intense ripple-container">
        <div class="aspect-[3/2] overflow-hidden bg-slate-100 relative">
          @if ($featuredAudio && $featuredAudio->thumbnail)
            <img
              src="{{ asset('storage/'.$featuredAudio->thumbnail) }}"
              alt="{{ $featuredAudio->title }}"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              loading="lazy"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/70 via-transparent to-transparent"></div>
            <div class="absolute bottom-4 left-4 right-4">
              <div class="text-xs uppercase tracking-widest text-slate-200 mb-2">Listen Now</div>
              <audio controls class="w-full">
                <source src="{{ asset('storage/'.$featuredAudio->audio_file) }}" type="audio/mpeg">
              </audio>
            </div>
          @else
            <img
              src="{{asset('landingpage/download-audio.webp')}}"
              alt="{{ __('messages.home.audio_teachings') }}"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              loading="lazy"
            />
          @endif
        </div>
        <div class="p-8 text-center">
          <h3 class="text-xl font-serif text-blue-900 mb-3">{{ $featuredAudio?->title ?? __('messages.home.audio_teachings') }}</h3>
          <p class="text-slate-600 mb-6 text-sm leading-relaxed">
            {{ $featuredAudio?->description ? \Illuminate\Support\Str::limit($featuredAudio->description, 140) : __('messages.home.audio_teachings_body') }}
          </p>
          <a href="{{ $featuredAudio ? route('audios.show', $featuredAudio) : route('audios.index') }}" class="text-blue-700 font-medium hover:text-blue-900 inline-flex items-center gap-2 text-sm uppercase tracking-wide">
            {{ __('messages.home.start_listening') }}
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>
<section id="sermons" class="py-6 bg-white text-slate-800">
  <div class="container mx-auto px-6 max-w-6xl text-center">
    <span class="block text-amber-600 font-semibold tracking-widest uppercase text-sm mb-3">{{ __('messages.home.latest_messages') }}</span>
    <h2 class="text-3xl md:text-4xl font-serif font-bold text-blue-950 mb-4">{{ __('messages.home.walking_light') }}</h2>
    <p class="text-lg text-slate-600 mb-10 italic">"Your word is a lamp for my feet, a light on my path." — Psalm 119:105</p>
   
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      @forelse ($latestVideos as $video)
        <div class="flex flex-col">
          <div class="relative w-full aspect-video bg-slate-100 rounded-xl overflow-hidden shadow-lg border border-slate-200 mb-4">
            @if ($video->youtube_id)
              <iframe class="absolute top-0 left-0 w-full h-full" src="https://www.youtube.com/embed/{{ $video->youtube_id }}" title="{{ $video->title }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            @else
              <div class="absolute inset-0 flex items-center justify-center text-slate-500">{{ __('messages.home.no_video_preview') }}</div>
            @endif
            @if ($video->featured)
              <span class="absolute top-3 left-3 bg-amber-500 text-white text-xs font-semibold px-3 py-1 rounded-full shadow">{{ __('messages.common.featured') }}</span>
            @endif
          </div>
          <h3 class="text-lg font-serif font-semibold text-blue-900 text-left">{{ $video->title }}</h3>
          <p class="text-sm text-slate-500 text-left">
            {{ $video->category?->name ?? __('messages.common.sermon') }} • {{ $video->published_at?->toDateString() ?? $video->created_at?->toDateString() }}
          </p>
        </div>
      @empty
        <div class="col-span-3 text-center text-slate-500">{{ __('messages.home.no_videos_available') }}</div>
      @endforelse
    </div>
    <div class="mt-10">
      <a href="{{ route('videos.index') }}" class="block w-full text-center px-6 py-4 bg-blue-900 text-white font-semibold rounded-xl hover:bg-blue-800 transition-colors shadow-lg">
        {{ __('messages.home.explore_more_videos') }}
      </a>
    </div>
  </div>
</section>
<section class="py-24 bg-slate-50">
  <div class="container mx-auto px-6 max-w-6xl">
    <div class="text-center mb-16">
      <h2 class="text-3xl md:text-4xl font-serif font-bold text-blue-950 mb-4">{{ __('messages.home.ministry_resources') }}</h2>
      <p class="text-slate-600 max-w-2xl mx-auto text-lg">{{ __('messages.home.ministry_resources_body') }}</p>
    </div>


    <!-- PDF Downloads Section -->
    <div class="mb-16">
      <h3 class="text-2xl font-serif font-bold text-blue-950 mb-8 text-center">{{ __('messages.home.recommended_books') }}</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @forelse ($recommendedBooks as $book)
          <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow border border-slate-100 flex flex-col scroll-animate hover-lift hover-glow-intense">
            <div class="relative h-48 overflow-hidden bg-slate-100">
              @if ($book->cover_image)
                <img src="{{ asset('storage/'.$book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
              @else
                <img src="{{ asset('landingpage/download-book.webp') }}" alt="{{ __('messages.home.downloadable_books') }}" class="w-full h-full object-cover">
              @endif
              <div class="absolute bottom-3 left-3 text-white text-xs font-semibold drop-shadow">
                {{ $book->category?->name ?? __('messages.common.book') }}
              </div>
              @if ($book->featured)
                <span class="absolute top-3 left-3 bg-amber-500 text-white text-xs font-semibold px-3 py-1 rounded-full shadow">{{ __('messages.common.featured') }}</span>
              @endif
            </div>
            <div class="p-8 flex-1 flex flex-col">
              <h3 class="text-xl font-serif font-bold text-blue-950 mb-3">{{ $book->title }}</h3>
              <p class="text-slate-600 mb-6 flex-1 leading-relaxed">{{ \Illuminate\Support\Str::limit($book->description, 120) }}</p>
              <div class="space-y-2">
                <a href="{{ route('books.show', $book) }}" class="w-full py-3 px-6 bg-blue-50 text-blue-900 font-medium rounded-lg hover:bg-blue-100 transition-colors flex items-center justify-center gap-2 hover:scale-105 transform">
                  <i data-lucide="book-open" class="w-4 h-4"></i> {{ __('messages.home.read_online') }}
                </a>
                <a href="{{ route('content.download.document', $book) }}" class="w-full py-2 px-6 bg-white text-blue-900 font-medium rounded-lg hover:bg-blue-50 transition-colors flex items-center justify-center gap-2 text-sm border border-blue-100">
                  <i data-lucide="download" class="w-4 h-4"></i> {{ __('messages.home.download_pdf') }}
                </a>
              </div>
            </div>
          </div>
        @empty
          <div class="col-span-3 text-center text-slate-500">{{ __('messages.home.no_recommended_books') }}</div>
        @endforelse
      </div>
    </div>


    <!-- Audio Resources Section -->
    <div>
      <h3 class="text-2xl font-serif font-bold text-blue-950 mb-8 text-center">{{ __('messages.home.recommended_audios') }}</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @forelse ($recommendedAudios as $audio)
          <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow border border-slate-100 flex flex-col scroll-animate hover-lift hover-glow-intense">
            <div class="relative h-48 overflow-hidden bg-slate-100">
              @if ($audio->thumbnail)
                <img src="{{ asset('storage/'.$audio->thumbnail) }}" alt="{{ $audio->title }}" class="w-full h-full object-cover">
              @else
                <img src="{{ asset('landingpage/download-audio.webp') }}" alt="{{ __('messages.home.audio_teachings') }}" class="w-full h-full object-cover">
              @endif
              <div class="absolute bottom-3 left-3 text-white text-xs font-semibold drop-shadow">
                {{ $audio->category?->name ?? __('messages.common.audio') }}
              </div>
              @if ($audio->featured)
                <span class="absolute top-3 left-3 bg-amber-500 text-white text-xs font-semibold px-3 py-1 rounded-full shadow">{{ __('messages.common.featured') }}</span>
              @endif
            </div>
            <div class="p-8 flex-1 flex flex-col">
              <h3 class="text-xl font-serif font-bold text-blue-950 mb-3">{{ $audio->title }}</h3>
              <p class="text-slate-600 mb-6 flex-1 leading-relaxed">{{ \Illuminate\Support\Str::limit($audio->description, 120) }}</p>
              <div class="space-y-2">
                <a href="{{ route('audios.show', $audio) }}" class="w-full py-3 px-6 bg-purple-50 text-purple-900 font-medium rounded-lg hover:bg-purple-100 transition-colors flex items-center justify-center gap-2 hover:scale-105 transform">
                  <i data-lucide="play-circle" class="w-4 h-4"></i> {{ __('messages.home.play_audio') }}
                </a>
                <a href="{{ route('content.download.audio', $audio) }}" class="w-full py-2 px-6 bg-white text-blue-900 font-medium rounded-lg hover:bg-blue-50 transition-colors flex items-center justify-center gap-2 text-sm border border-blue-100">
                  <i data-lucide="download" class="w-4 h-4"></i> {{ __('messages.home.download') }}
                </a>
              </div>
            </div>
          </div>
        @empty
          <div class="col-span-3 text-center text-slate-500">{{ __('messages.home.no_recommended_audios') }}</div>
        @endforelse
      </div>
    </div>
  </div>
</section>
<section id="newslatter" class="py-24 bg-blue-950 text-white relative overflow-hidden">
  <!-- Decorative background element -->
  <div class="absolute top-0 left-0 w-full h-full opacity-5 pointer-events-none">
    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
      <path d="M0 100 C 30 50 70 50 100 100 Z" fill="white" />
    </svg>
  </div>
 
  <div class="container mx-auto px-6 max-w-3xl text-center relative z-10">
    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-900 mb-6">
      <i data-lucide="mail" class="w-8 h-8 text-amber-500"></i>
    </div>
    <h2 class="text-3xl md:text-4xl font-serif font-bold mb-4">{{ __('messages.home.newsletter_title') }}</h2>
    <p class="text-blue-100 text-lg mb-10 leading-relaxed">{{ __('messages.home.newsletter_body') }}</p>
   
    <form method="POST" action="{{ route('subscribe') }}" class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto">
      @csrf
      <input
        type="text"
        name="name"
        required
        placeholder="{{ __('messages.home.form_name') }}"
        class="flex-1 px-6 py-4 rounded-lg text-slate-900 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 placeholder-slate-400"
      />
      <input
        type="email"
        name="email"
        placeholder="{{ __('messages.home.form_email') }}"
        class="flex-1 px-6 py-4 rounded-lg text-slate-900 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 placeholder-slate-400"
        required
      />
      <button
        type="submit"
        class="px-8 py-4 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg transition-colors shadow-lg whitespace-nowrap"
      >
        {{ __('messages.home.subscribe') }}
      </button>
    </form>
    <p class="text-blue-400 text-sm mt-6">{{ __('messages.home.privacy_note') }}</p>
  </div>
</section>
  </main>
@endsection
