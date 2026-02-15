@extends('layouts.audiences.app')

@section('contents')
@php
  $contactEmail = $siteSettings?->contact_email ?: 'contact@beaconsofgod.org';
  $contactPhone = $siteSettings?->contact_phone ?: '+000 000 000';
  $contactAddress = $siteSettings?->contact_address ?: 'Global Online Ministry';
@endphp
<main class="flex-1">
  <section class="bg-gradient-to-b from-brand-blue via-blue-900 to-slate-900 text-white">
    <div class="container mx-auto px-6 py-18 lg:py-24">
      <div class="max-w-3xl">
        <p class="text-sm uppercase tracking-[0.3em] text-brand-gold mb-4">Contact</p>
        <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-5">
          We are here to pray with you and support your journey.
        </h1>
        <p class="text-base sm:text-lg text-blue-100 leading-relaxed">
          Reach out for prayer, questions, partnerships, or support. We respond as quickly as possible.
        </p>
      </div>
    </div>
  </section>

  <section class="container mx-auto px-6 py-14 lg:py-18">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
        <h3 class="font-serif text-lg text-slate-900 mb-3">Contact Details</h3>
        <div class="space-y-4 text-sm text-slate-600">
          <div class="flex items-center gap-3">
            <i data-lucide="mail" class="w-4 h-4 text-brand-gold"></i>
            <span>{{ $contactEmail }}</span>
          </div>
          <div class="flex items-center gap-3">
            <i data-lucide="phone" class="w-4 h-4 text-brand-gold"></i>
            <span>{{ $contactPhone }}</span>
          </div>
          <div class="flex items-center gap-3">
            <i data-lucide="map-pin" class="w-4 h-4 text-brand-gold"></i>
            <span>{{ $contactAddress }}</span>
          </div>
        </div>
        <div class="mt-6 text-xs text-slate-500">
          Office hours: Monday - Friday, 9:00 AM - 5:00 PM (Local time)
        </div>
      </div>

      <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
        <h3 class="font-serif text-lg text-slate-900 mb-4">Send a Message</h3>

        @if (session('status'))
          <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3">
            {{ session('status') }}
          </div>
        @endif

        @if ($errors->any())
          <div class="mb-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 text-sm px-4 py-3 space-y-1">
            @foreach ($errors->all() as $error)
              <div>{{ $error }}</div>
            @endforeach
          </div>
        @endif

        <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
          @csrf
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Your name" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-brand-blue focus:ring-brand-blue">
            <input type="email" name="email" value="{{ old('email') }}" placeholder="Your email" required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-brand-blue focus:ring-brand-blue">
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Phone number (optional)" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-brand-blue focus:ring-brand-blue">
            <input type="text" name="subject" value="{{ old('subject') }}" placeholder="Subject (optional)" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-brand-blue focus:ring-brand-blue">
          </div>
          <textarea name="message" rows="6" placeholder="Write your message..." required class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm focus:border-brand-blue focus:ring-brand-blue">{{ old('message') }}</textarea>
          <div class="flex items-center justify-between flex-wrap gap-3">
            <span class="text-xs text-slate-500">We respect your privacy and will never share your information.</span>
            <button type="submit" class="px-6 py-3 rounded-full bg-brand-blue text-white text-sm font-semibold hover:bg-blue-800 transition-colors">
              Send Message
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>
</main>
@endsection
