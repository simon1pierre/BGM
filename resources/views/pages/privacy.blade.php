@extends('layouts.audiences.app')

@section('contents')
<main class="flex-1">
  <section class="bg-gradient-to-b from-brand-blue via-blue-900 to-slate-900 text-white">
    <div class="container mx-auto px-6 py-18 lg:py-24">
      <div class="max-w-3xl">
        <p class="text-sm uppercase tracking-[0.3em] text-brand-gold mb-4">{{ __('messages.privacy_page.badge') }}</p>
        <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-5">
          {{ __('messages.privacy_page.title') }}
        </h1>
        <p class="text-base sm:text-lg text-blue-100 leading-relaxed">
          {{ __('messages.privacy_page.subtitle') }}
        </p>
      </div>
    </div>
  </section>

  <section class="container mx-auto px-6 py-14 lg:py-18">
    <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm space-y-6 text-sm text-slate-600 leading-relaxed">
      <div>
        <h2 class="font-serif text-xl text-slate-900 mb-2">{{ __('messages.privacy_page.collect_title') }}</h2>
        <p>{{ __('messages.privacy_page.collect_body') }}</p>
      </div>
      <div>
        <h2 class="font-serif text-xl text-slate-900 mb-2">{{ __('messages.privacy_page.use_title') }}</h2>
        <p>{{ __('messages.privacy_page.use_body') }}</p>
      </div>
      <div>
        <h2 class="font-serif text-xl text-slate-900 mb-2">{{ __('messages.privacy_page.protection_title') }}</h2>
        <p>{{ __('messages.privacy_page.protection_body') }}</p>
      </div>
      <div>
        <h2 class="font-serif text-xl text-slate-900 mb-2">{{ __('messages.privacy_page.contact_title') }}</h2>
        <p>{{ __('messages.privacy_page.contact_body') }}</p>
      </div>
    </div>
  </section>
</main>
@endsection
