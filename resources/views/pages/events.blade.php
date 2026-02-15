@extends('layouts.audiences.app')

@section('contents')
<main class="flex-1">
  <section class="bg-gradient-to-b from-brand-blue via-blue-900 to-slate-900 text-white">
    <div class="container mx-auto px-6 py-18 lg:py-24">
      <div class="max-w-3xl">
        <p class="text-sm uppercase tracking-[0.3em] text-brand-gold mb-4">Events</p>
        <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-5">
          Upcoming gatherings and ministry moments.
        </h1>
        <p class="text-base sm:text-lg text-blue-100 leading-relaxed">
          We will post upcoming services, webinars, and special events here.
        </p>
      </div>
    </div>
  </section>

  <section class="container mx-auto px-6 py-14 lg:py-18">
    <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm text-center">
      <h2 class="font-serif text-2xl text-slate-900 mb-3">Events Coming Soon</h2>
      <p class="text-sm text-slate-600 leading-relaxed max-w-2xl mx-auto">
        We are preparing a calendar of live services, online gatherings, and ministry initiatives.
        Check back soon or subscribe to receive updates.
      </p>
      <div class="mt-6">
        <a href="{{ route('home') }}#newsletter" class="px-6 py-3 rounded-full bg-brand-blue text-white text-sm font-semibold hover:bg-blue-800 transition-colors">
          Join the Newsletter
        </a>
      </div>
    </div>
  </section>
</main>
@endsection
