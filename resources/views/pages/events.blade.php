@extends('layouts.audiences.app')

@section('contents')
<main class="flex-1">
  <section class="bg-gradient-to-b from-brand-blue via-blue-900 to-slate-900 text-white">
    <div class="container mx-auto px-6 py-18 lg:py-24">
      <div class="max-w-3xl">
        <p class="text-sm uppercase tracking-[0.3em] text-brand-gold mb-4">{{ $siteSettings?->translated('events_title') ?: 'Events' }}</p>
        <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-5">
          {{ $siteSettings?->translated('events_feature_title') ?: 'Upcoming gatherings and ministry moments.' }}
        </h1>
        <p class="text-base sm:text-lg text-blue-100 leading-relaxed">
          {{ $siteSettings?->translated('events_subtitle') ?: 'Upcoming services, conferences, and outreach gatherings are listed below.' }}
        </p>
      </div>
    </div>
  </section>

  <section class="container mx-auto px-6 py-14 lg:py-18">
    <form method="GET" action="{{ route('events') }}" class="bg-white border border-slate-100 rounded-2xl p-5 mb-8 shadow-sm">
      <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
        <div class="md:col-span-2">
          <input
            type="text"
            name="q"
            value="{{ request('q') }}"
            placeholder="Search events, venue, location..."
            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
          >
        </div>
        <div>
          <select name="type" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            <option value="">All types</option>
            <option value="prayer_meeting" @selected(request('type') === 'prayer_meeting')>Prayer Meeting</option>
            <option value="service" @selected(request('type') === 'service')>Service</option>
            <option value="conference" @selected(request('type') === 'conference')>Conference</option>
            <option value="other" @selected(request('type') === 'other')>Other</option>
          </select>
        </div>
        <div>
          <select name="platform" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            <option value="">Any platform</option>
            <option value="zoom" @selected(request('platform') === 'zoom')>Zoom</option>
            <option value="youtube" @selected(request('platform') === 'youtube')>YouTube Live</option>
            <option value="other" @selected(request('platform') === 'other')>Other</option>
            <option value="none" @selected(request('platform') === 'none')>Offline</option>
          </select>
        </div>
        <div>
          <select name="period" class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            <option value="upcoming" @selected(request('period', 'upcoming') === 'upcoming')>Upcoming</option>
            <option value="past" @selected(request('period') === 'past')>Past</option>
          </select>
        </div>
      </div>
      <div class="mt-4 flex items-center gap-3">
        <button type="submit" class="inline-flex px-5 py-2.5 rounded-xl bg-brand-blue text-white text-sm font-semibold hover:bg-blue-800 transition-colors">Filter</button>
        <a href="{{ route('events') }}" class="inline-flex px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-sm font-semibold hover:bg-slate-50 transition-colors">Reset</a>
      </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @forelse ($upcomingEvents as $event)
        <article class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden hover-lift">
          <div class="h-44 bg-slate-100">
            @if (!empty($event->image_path))
              <img src="{{ asset('storage/'.$event->image_path) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
            @else
              <div class="w-full h-full flex items-center justify-center text-slate-400 text-sm">Event</div>
            @endif
          </div>
          <div class="p-5">
            @if ($event->is_featured)
              <span class="inline-flex mb-3 text-[11px] px-2 py-1 rounded-full bg-brand-blue/10 text-brand-blue">Featured</span>
            @endif
            <h2 class="font-serif text-xl text-slate-900 mb-2">{{ $event->title }}</h2>
            <p class="text-sm text-slate-600 mb-3 leading-relaxed">
              {{ \Illuminate\Support\Str::limit($event->description, 120) }}
            </p>
            <div class="text-xs text-slate-500 space-y-1 mb-4">
              <div>{{ $event->starts_at?->format('M d, Y H:i') }} ({{ $event->timezone }})</div>
              @if ($event->live_platform)
                <div>{{ $event->live_platform === 'youtube' ? 'YouTube Live' : ucfirst($event->live_platform) }}</div>
              @endif
              @if ($event->venue || $event->location)
                <div>{{ $event->venue ?: $event->location }}</div>
              @endif
            </div>
            <div class="flex flex-wrap gap-2">
              <a href="{{ route('events.show', $event) }}" class="inline-flex px-4 py-2 rounded-full border border-slate-300 text-slate-700 text-xs font-semibold hover:bg-slate-50 transition-colors">
                View Details
              </a>
              @if ($event->live_url)
                <a href="{{ $event->live_url }}" target="_blank" rel="noopener" class="inline-flex px-4 py-2 rounded-full bg-brand-blue text-white text-xs font-semibold hover:bg-blue-800 transition-colors">
                  {{ $event->live_platform === 'zoom' ? 'Join Zoom' : 'Watch Live' }}
                </a>
              @endif
              @if ($event->registration_url)
                <a href="{{ $event->registration_url }}" target="_blank" rel="noopener" class="inline-flex px-4 py-2 rounded-full border border-brand-blue text-brand-blue text-xs font-semibold hover:bg-blue-50 transition-colors">
                  Register
                </a>
              @endif
            </div>
          </div>
        </article>
      @empty
        <div class="col-span-full bg-white rounded-2xl p-8 border border-slate-100 shadow-sm text-center">
          <h2 class="font-serif text-2xl text-slate-900 mb-3">No upcoming events yet</h2>
          <p class="text-sm text-slate-600 leading-relaxed max-w-2xl mx-auto">
            Check back soon or subscribe to the newsletter for updates.
          </p>
        </div>
      @endforelse
    </div>
    <div class="mt-6">
      {{ $upcomingEvents->links() }}
    </div>
  </section>
</main>
@endsection
