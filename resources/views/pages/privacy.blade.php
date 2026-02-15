@extends('layouts.audiences.app')

@section('contents')
<main class="flex-1">
  <section class="bg-gradient-to-b from-brand-blue via-blue-900 to-slate-900 text-white">
    <div class="container mx-auto px-6 py-18 lg:py-24">
      <div class="max-w-3xl">
        <p class="text-sm uppercase tracking-[0.3em] text-brand-gold mb-4">Privacy Policy</p>
        <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-5">
          Your privacy matters to us.
        </h1>
        <p class="text-base sm:text-lg text-blue-100 leading-relaxed">
          This policy explains how we collect, use, and protect your information.
        </p>
      </div>
    </div>
  </section>

  <section class="container mx-auto px-6 py-14 lg:py-18">
    <div class="bg-white rounded-2xl p-8 border border-slate-100 shadow-sm space-y-6 text-sm text-slate-600 leading-relaxed">
      <div>
        <h2 class="font-serif text-xl text-slate-900 mb-2">Information We Collect</h2>
        <p>We collect information you provide when subscribing, contacting us, or engaging with content.</p>
      </div>
      <div>
        <h2 class="font-serif text-xl text-slate-900 mb-2">How We Use Information</h2>
        <p>We use your information to deliver resources, respond to requests, and improve the ministry experience.</p>
      </div>
      <div>
        <h2 class="font-serif text-xl text-slate-900 mb-2">Data Protection</h2>
        <p>We use reasonable security measures to protect your data and do not sell personal information.</p>
      </div>
      <div>
        <h2 class="font-serif text-xl text-slate-900 mb-2">Contact</h2>
        <p>If you have questions about this policy, please contact us through the contact page.</p>
      </div>
    </div>
  </section>
</main>
@endsection
