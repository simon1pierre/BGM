@extends('layouts.audiences.app')

@section('contents')
<main class="flex-1">
  <section class="relative overflow-hidden bg-gradient-to-b from-brand-blue via-blue-900 to-slate-900 text-white">
    <div class="container mx-auto px-6 py-20 lg:py-28">
      <div class="max-w-3xl">
        <p class="text-sm uppercase tracking-[0.3em] text-brand-gold mb-4">About Beacons of God Ministries</p>
        <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-5">
          Shining the light of Scripture through teaching, worship, and community.
        </h1>
        <p class="text-base sm:text-lg text-blue-100 leading-relaxed">
          We exist to equip believers with biblical truth, foster spiritual growth, and make Christ known
          through accessible, Spirit-led resources.
        </p>
      </div>
    </div>
  </section>

  <section class="container mx-auto px-6 py-14 lg:py-18">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover-lift">
        <h3 class="font-serif text-xl text-slate-900 mb-3">Our Mission</h3>
        <p class="text-sm text-slate-600 leading-relaxed">
          To illuminate the path of righteousness through the unwavering truth of Scripture and Spirit-led teaching.
        </p>
      </div>
      <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover-lift">
        <h3 class="font-serif text-xl text-slate-900 mb-3">Our Vision</h3>
        <p class="text-sm text-slate-600 leading-relaxed">
          A global community of believers growing in faith, grounded in the Word, and walking in divine purpose.
        </p>
      </div>
      <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover-lift">
        <h3 class="font-serif text-xl text-slate-900 mb-3">Our Values</h3>
        <p class="text-sm text-slate-600 leading-relaxed">
          Biblical integrity, compassion, excellence, humility, and a passion for discipleship.
        </p>
      </div>
    </div>
  </section>

  <section class="bg-white">
    <div class="container mx-auto px-6 py-14 lg:py-18">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
        <div>
          <h2 class="font-serif text-2xl sm:text-3xl text-slate-900 mb-4">What We Do</h2>
          <p class="text-sm sm:text-base text-slate-600 leading-relaxed mb-4">
            We deliver sermons, audio teachings, and study resources that help believers grow deeper in
            Scripture and live out their faith with confidence.
          </p>
          <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
            Our ministry supports discipleship, evangelism, and spiritual formation through digital
            resources available anywhere in the world.
          </p>
        </div>
        <div class="bg-brand-light rounded-2xl p-6 border border-slate-100">
          <h3 class="font-serif text-xl text-slate-900 mb-4">Impact Snapshot</h3>
          <div class="grid grid-cols-2 gap-4">
            <div class="bg-white rounded-xl p-4 text-center border border-slate-100">
              <div class="text-2xl font-bold text-brand-blue">{{ $stats['videos'] ?? 0 }}</div>
              <div class="text-xs text-slate-500">Published Videos</div>
            </div>
            <div class="bg-white rounded-xl p-4 text-center border border-slate-100">
              <div class="text-2xl font-bold text-brand-blue">{{ $stats['audios'] ?? 0 }}</div>
              <div class="text-xs text-slate-500">Audio Teachings</div>
            </div>
            <div class="bg-white rounded-xl p-4 text-center border border-slate-100">
              <div class="text-2xl font-bold text-brand-blue">{{ $stats['books'] ?? 0 }}</div>
              <div class="text-xs text-slate-500">Books & Guides</div>
            </div>
            <div class="bg-white rounded-xl p-4 text-center border border-slate-100">
              <div class="text-2xl font-bold text-brand-blue">{{ $stats['subscribers'] ?? 0 }}</div>
              <div class="text-xs text-slate-500">Active Subscribers</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="faith" class="container mx-auto px-6 py-14 lg:py-18">
    <div class="max-w-3xl">
      <h2 class="font-serif text-2xl sm:text-3xl text-slate-900 mb-4">Statement of Faith</h2>
      <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
        We believe the Bible is the inspired Word of God and the foundation for faith and practice.
        We affirm salvation by grace through faith in Jesus Christ and commit to living Spirit-led lives
        that reflect His love and truth.
      </p>
    </div>
  </section>

  <section id="leadership" class="bg-brand-blue text-white">
    <div class="container mx-auto px-6 py-14 lg:py-18">
      <div class="max-w-3xl">
        <h2 class="font-serif text-2xl sm:text-3xl mb-4">Leadership</h2>
        <p class="text-sm sm:text-base text-blue-100 leading-relaxed">
          Our leadership team is committed to sound doctrine, compassionate ministry, and faithful stewardship.
          We welcome collaboration with churches, leaders, and believers who share the vision of advancing
          God’s kingdom.
        </p>
      </div>
    </div>
  </section>

  <section class="container mx-auto px-6 py-14 lg:py-18">
    <div class="bg-white rounded-2xl border border-slate-100 p-8 lg:p-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
      <div>
        <h3 class="font-serif text-2xl text-slate-900 mb-2">Join the Ministry</h3>
        <p class="text-sm text-slate-600">Explore our resources or reach out to partner with us.</p>
      </div>
      <div class="flex flex-wrap gap-3">
        <a href="{{ route('resources') }}" class="px-5 py-2 rounded-full bg-brand-blue text-white text-sm font-semibold hover:bg-blue-800 transition-colors">Explore Resources</a>
        <a href="{{ route('contact') }}" class="px-5 py-2 rounded-full border border-brand-blue text-brand-blue text-sm font-semibold hover:bg-blue-50 transition-colors">Contact Us</a>
      </div>
    </div>
  </section>
</main>
@endsection
