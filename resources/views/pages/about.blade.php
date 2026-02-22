@extends('layouts.audiences.app')

@section('contents')
<main class="flex-1">
  <section class="relative overflow-hidden bg-gradient-to-b from-brand-blue via-blue-900 to-slate-900 text-white">
    <div class="container mx-auto px-6 py-20 lg:py-28">
      <div class="max-w-3xl">
        <p class="text-sm uppercase tracking-[0.3em] text-brand-gold mb-4">{{ __('messages.about_page.badge') }}</p>
        <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-5">
          {{ __('messages.about_page.title') }}
        </h1>
        <p class="text-base sm:text-lg text-blue-100 leading-relaxed">
          {{ __('messages.about_page.subtitle') }}
        </p>
      </div>
    </div>
  </section>

  <section class="container mx-auto px-6 py-14 lg:py-18">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover-lift">
        <h3 class="font-serif text-xl text-slate-900 mb-3">{{ __('messages.about_page.mission_title') }}</h3>
        <p class="text-sm text-slate-600 leading-relaxed">{{ __('messages.about_page.mission_body') }}</p>
      </div>
      <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover-lift">
        <h3 class="font-serif text-xl text-slate-900 mb-3">{{ __('messages.about_page.vision_title') }}</h3>
        <p class="text-sm text-slate-600 leading-relaxed">{{ __('messages.about_page.vision_body') }}</p>
      </div>
      <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover-lift">
        <h3 class="font-serif text-xl text-slate-900 mb-3">{{ __('messages.about_page.values_title') }}</h3>
        <p class="text-sm text-slate-600 leading-relaxed">{{ __('messages.about_page.values_body') }}</p>
      </div>
    </div>
  </section>

  <section class="bg-white">
    <div class="container mx-auto px-6 py-14 lg:py-18">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
        <div>
          <h2 class="font-serif text-2xl sm:text-3xl text-slate-900 mb-4">{{ __('messages.about_page.what_we_do_title') }}</h2>
          <p class="text-sm sm:text-base text-slate-600 leading-relaxed mb-4">{{ __('messages.about_page.what_we_do_body_one') }}</p>
          <p class="text-sm sm:text-base text-slate-600 leading-relaxed">{{ __('messages.about_page.what_we_do_body_two') }}</p>
        </div>
        <div class="bg-brand-light rounded-2xl p-6 border border-slate-100">
          <h3 class="font-serif text-xl text-slate-900 mb-4">{{ __('messages.about_page.impact_title') }}</h3>
          <div class="grid grid-cols-2 gap-4">
            <div class="bg-white rounded-xl p-4 text-center border border-slate-100">
              <div class="text-2xl font-bold text-brand-blue">{{ $stats['videos'] ?? 0 }}</div>
              <div class="text-xs text-slate-500">{{ __('messages.about_page.published_videos') }}</div>
            </div>
            <div class="bg-white rounded-xl p-4 text-center border border-slate-100">
              <div class="text-2xl font-bold text-brand-blue">{{ $stats['audios'] ?? 0 }}</div>
              <div class="text-xs text-slate-500">{{ __('messages.about_page.audio_teachings') }}</div>
            </div>
            <div class="bg-white rounded-xl p-4 text-center border border-slate-100">
              <div class="text-2xl font-bold text-brand-blue">{{ $stats['books'] ?? 0 }}</div>
              <div class="text-xs text-slate-500">{{ __('messages.about_page.books_guides') }}</div>
            </div>
            <div class="bg-white rounded-xl p-4 text-center border border-slate-100">
              <div class="text-2xl font-bold text-brand-blue">{{ $stats['subscribers'] ?? 0 }}</div>
              <div class="text-xs text-slate-500">{{ __('messages.about_page.active_subscribers') }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="faith" class="container mx-auto px-6 py-14 lg:py-18">
    <div class="max-w-3xl">
      <h2 class="font-serif text-2xl sm:text-3xl text-slate-900 mb-4">{{ __('messages.about_page.faith_title') }}</h2>
      <p class="text-sm sm:text-base text-slate-600 leading-relaxed">{{ __('messages.about_page.faith_body') }}</p>
    </div>
  </section>

  <section id="leadership" class="bg-brand-blue text-white">
    <div class="container mx-auto px-6 py-14 lg:py-18">
      <div class="max-w-3xl">
        <h2 class="font-serif text-2xl sm:text-3xl mb-4">{{ __('messages.about_page.leadership_title') }}</h2>
        <p class="text-sm sm:text-base text-blue-100 leading-relaxed">{{ __('messages.about_page.leadership_body') }}</p>
      </div>
    </div>
  </section>

  <section class="container mx-auto px-6 py-14 lg:py-18">
    <div class="bg-white rounded-2xl border border-slate-100 p-8 lg:p-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
      <div>
        <h3 class="font-serif text-2xl text-slate-900 mb-2">{{ __('messages.about_page.join_title') }}</h3>
        <p class="text-sm text-slate-600">{{ __('messages.about_page.join_body') }}</p>
      </div>
      <div class="flex flex-wrap gap-3">
        <a href="{{ route('resources') }}" class="px-5 py-2 rounded-full bg-brand-blue text-white text-sm font-semibold hover:bg-blue-800 transition-colors">{{ __('messages.about_page.explore_resources') }}</a>
        <a href="{{ route('contact') }}" class="px-5 py-2 rounded-full border border-brand-blue text-brand-blue text-sm font-semibold hover:bg-blue-50 transition-colors">{{ __('messages.about_page.contact_us') }}</a>
      </div>
    </div>
  </section>
</main>
@endsection
