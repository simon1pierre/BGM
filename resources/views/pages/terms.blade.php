@extends('layouts.audiences.app')

@section('contents')
<main class="flex-1">
  <section class="bg-gradient-to-b from-brand-blue via-blue-900 to-slate-900 text-white">
    <div class="container mx-auto px-6 py-18 lg:py-24">
      <div class="max-w-3xl">
        <p class="text-sm uppercase tracking-[0.3em] text-brand-gold mb-4">Terms of Use</p>
        <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-5">
          Guidelines for using our resources.
        </h1>
        <p class="text-base sm:text-lg text-blue-100 leading-relaxed">
          By accessing our content, you agree to the following terms.
        </p>
      </div>
    </div>
  </section>

  <section class="container mx-auto px-6 py-14 lg:py-18">
    <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm space-y-6 text-sm text-slate-600 leading-relaxed">
      <div>
        <h2 class="font-serif text-xl text-slate-900 mb-2">Use of Content</h2>
        <p>Resources are provided for personal spiritual growth and ministry encouragement.</p>
      </div>
      <div>
        <h2 class="font-serif text-xl text-slate-900 mb-2">Respectful Engagement</h2>
        <p>We ask all visitors to engage respectfully when commenting or sharing content.</p>
      </div>
      <div>
        <h2 class="font-serif text-xl text-slate-900 mb-2">Intellectual Property</h2>
        <p>All content is the property of Beacons of God Ministries unless otherwise noted.</p>
      </div>
      <div>
        <h2 class="font-serif text-xl text-slate-900 mb-2">Changes</h2>
        <p>We may update these terms as needed. Continued use indicates acceptance of updates.</p>
      </div>
    </div>
  </section>
</main>
@endsection
