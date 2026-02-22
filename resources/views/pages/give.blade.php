@extends('layouts.audiences.app')

@section('contents')
<main class="flex-1">
  <section class="bg-gradient-to-b from-brand-blue via-blue-900 to-slate-900 text-white">
    <div class="container mx-auto px-6 py-18 lg:py-24">
      <div class="max-w-3xl">
        <p class="text-sm uppercase tracking-[0.3em] text-brand-gold mb-4">{{ __('messages.give_page.badge') }}</p>
        <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-5">
          {{ __('messages.give_page.title') }}
        </h1>
        <p class="text-base sm:text-lg text-blue-100 leading-relaxed">
          {{ __('messages.give_page.subtitle') }}
        </p>
      </div>
    </div>
  </section>

  <section class="container mx-auto px-6 py-14 lg:py-18">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm">
        <h2 class="font-serif text-2xl text-slate-900 mb-4">{{ __('messages.give_page.why_title') }}</h2>
        <p class="text-sm text-slate-600 leading-relaxed mb-4">
          {{ __('messages.give_page.why_body_one') }}
        </p>
        <p class="text-sm text-slate-600 leading-relaxed">
          {{ __('messages.give_page.why_body_two') }}
        </p>
      </div>
      <div class="bg-brand-light rounded-2xl p-8 border border-slate-100">
        <h2 class="font-serif text-2xl text-slate-900 mb-4">{{ __('messages.give_page.how_title') }}</h2>
        <p class="text-sm text-slate-600 leading-relaxed mb-4">
          {{ __('messages.give_page.how_body') }}
        </p>
        <a href="{{ route('contact') }}" class="inline-flex px-6 py-3 rounded-full bg-brand-blue text-white text-sm font-semibold hover:bg-blue-800 transition-colors">
          {{ __('messages.give_page.cta') }}
        </a>
      </div>
    </div>
  </section>
</main>
@endsection
