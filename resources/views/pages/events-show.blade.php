@extends('layouts.audiences.app')

@section('contents')
<main class="flex-1 bg-slate-50">
  <section class="bg-gradient-to-b from-brand-blue via-blue-900 to-slate-900 text-white">
    <div class="container mx-auto px-6 py-16 lg:py-20">
      <a href="{{ route('events') }}" class="inline-flex items-center text-sm text-blue-100 hover:text-white mb-6">
        <span class="mr-2">&larr;</span> {{ __('messages.events_show_page.back_to_events') }}
      </a>
      <div class="max-w-4xl">
        @if ($event->is_featured)
          <span class="inline-flex mb-4 text-[11px] px-3 py-1 rounded-full bg-amber-400/20 text-amber-200 border border-amber-300/30">{{ __('messages.events_show_page.featured_event') }}</span>
        @endif
        <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-4">{{ $event->title }}</h1>
        <p class="text-blue-100 text-base sm:text-lg leading-relaxed">{{ \Illuminate\Support\Str::limit((string) $event->description, 220) }}</p>
      </div>
    </div>
  </section>

  <section class="container mx-auto px-6 py-10 lg:py-14">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="h-64 sm:h-80 bg-slate-100">
          @if (!empty($event->image_path))
            <img src="{{ asset('storage/'.$event->image_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
          @else
            <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">{{ __('messages.events_show_page.event_image') }}</div>
          @endif
        </div>
        <div class="p-6">
          <h2 class="font-serif text-2xl text-slate-900 mb-3">{{ __('messages.events_show_page.about_event') }}</h2>
          <p class="text-slate-700 leading-relaxed whitespace-pre-line">{{ $event->description ?: __('messages.events_show_page.details_soon') }}</p>
        </div>
      </div>

      <aside class="space-y-4">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
          <h3 class="font-serif text-xl text-slate-900 mb-4">{{ __('messages.events_show_page.event_details') }}</h3>
          <div class="space-y-3 text-sm text-slate-700">
            <div>
              <div class="text-slate-500 text-xs uppercase tracking-wider">{{ __('messages.events_show_page.type') }}</div>
              <div>{{ str_replace('_', ' ', ucfirst($event->event_type ?? 'other')) }}</div>
            </div>
            <div>
              <div class="text-slate-500 text-xs uppercase tracking-wider">{{ __('messages.events_show_page.starts') }}</div>
              <div>{{ $event->starts_at?->format('M d, Y H:i') }} ({{ $event->timezone }})</div>
            </div>
            @if ($event->ends_at)
              <div>
                <div class="text-slate-500 text-xs uppercase tracking-wider">{{ __('messages.events_show_page.ends') }}</div>
                <div>{{ $event->ends_at?->format('M d, Y H:i') }} ({{ $event->timezone }})</div>
              </div>
            @endif
            @if ($event->venue || $event->location)
              <div>
                <div class="text-slate-500 text-xs uppercase tracking-wider">{{ __('messages.events_show_page.venue_location') }}</div>
                <div>{{ $event->venue ?: $event->location }}</div>
              </div>
            @endif
            @if ($event->live_platform)
              <div>
                <div class="text-slate-500 text-xs uppercase tracking-wider">{{ __('messages.events_show_page.live_platform') }}</div>
                <div>{{ $event->live_platform === 'youtube' ? __('messages.events_page.youtube_live') : ($event->live_platform === 'zoom' ? __('messages.events_page.zoom') : ucfirst($event->live_platform)) }}</div>
              </div>
            @endif
          </div>
          <div class="mt-5 space-y-2">
            @if ($event->live_url)
              <a href="{{ $event->live_url }}" target="_blank" rel="noopener" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-brand-blue text-white text-sm font-semibold hover:bg-blue-800 transition-colors">{{ $event->live_platform === 'zoom' ? __('messages.events_page.join_zoom') : __('messages.events_page.watch_live') }}</a>
            @endif
            @if ($event->registration_url)
              <a href="{{ $event->registration_url }}" target="_blank" rel="noopener" class="w-full inline-flex items-center justify-center px-4 py-2.5 rounded-xl border border-brand-blue text-brand-blue text-sm font-semibold hover:bg-blue-50 transition-colors">{{ __('messages.events_page.register') }}</a>
            @endif
          </div>
        </div>
      </aside>
    </div>

    @if ($relatedEvents->count())
      <div class="mt-12">
        <h2 class="font-serif text-2xl text-slate-900 mb-5">{{ __('messages.events_show_page.related_events') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          @foreach ($relatedEvents as $related)
            <article class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
              <div class="h-36 bg-slate-100">
                @if (!empty($related->image_path))
                  <img src="{{ asset('storage/'.$related->image_path) }}" alt="{{ $related->title }}" class="w-full h-full object-cover">
                @else
                  <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">{{ __('messages.events_page.event') }}</div>
                @endif
              </div>
              <div class="p-4">
                <h3 class="font-semibold text-slate-900 mb-1">{{ $related->title }}</h3>
                <div class="text-xs text-slate-500 mb-3">{{ $related->starts_at?->format('M d, Y H:i') }}</div>
                <a href="{{ route('events.show', $related) }}" class="text-sm text-brand-blue font-semibold hover:text-blue-800">{{ __('messages.events_page.view_details') }}</a>
              </div>
            </article>
          @endforeach
        </div>
      </div>
    @endif
  </section>
</main>
@endsection
