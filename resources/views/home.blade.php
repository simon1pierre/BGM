@extends('layouts.audiences.app')
@section('contents')
 <main class="grow">
<section class="relative w-full min-h-[85vh] flex items-center justify-center overflow-hidden bg-slate-900 text-white">
  <!-- Background Image with Overlay -->
  <div class="absolute inset-0 z-0">
    <img
      data-ai="generate"
      data-slot="hero-bg"
      data-prompt="Divine rays of light breaking through soft clouds in a deep blue sky, cinematic, ethereal, peaceful, 8k resolution"
      data-ar="16:9"
      src="https://cdn.ailandingpage.ai/landingpage_io/user-generate/552f4586-c83c-46ac-b326-367fa6ccc9f3/552f4586-c83c-46ac-b326-367fa6ccc9f3/hero/hero-bg-fbd45f0e618f4b3db7207f45845a2c9f.png"
      alt="Divine light background"
      class="w-full h-full object-cover opacity-60"
      loading="eager"
    />
    <div class="absolute inset-0 bg-gradient-to-b from-blue-900/80 via-blue-950/70 to-slate-900/90 mix-blend-multiply"></div>
  </div>


  <!-- Dove Animation Styles -->
  <style>
    @keyframes float-dove {
      0% { transform: translate(0, 0) scale(0.8); opacity: 0; }
      10% { opacity: 0.4; }
      90% { opacity: 0.4; }
      100% { transform: translate(100vw, -20vh) scale(1.2); opacity: 0; }
    }
    .dove-anim {
      position: absolute;
      animation: float-dove 20s linear infinite;
      opacity: 0;
      color: rgba(255, 255, 255, 0.6);
    }
    .dove-1 { top: 60%; left: -10%; animation-duration: 25s; animation-delay: 0s; }
    .dove-2 { top: 40%; left: -10%; animation-duration: 30s; animation-delay: 8s; transform: scale(0.7); }
    .dove-3 { top: 70%; left: -10%; animation-duration: 22s; animation-delay: 15s; transform: scale(0.9); }
  </style>


  <!-- Animated Doves Layer -->
  <div class="absolute inset-0 z-10 pointer-events-none overflow-hidden">
    <!-- Dove 1 -->
    <div class="dove-anim dove-1">
      <svg width="64" height="64" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path d="M20.5 12.5L16 15L12.5 11.5L9 15L4.5 12.5L2 10L5.5 9L9 10.5L12.5 7L16 10.5L19.5 9L23 10L20.5 12.5Z" stroke="none" />
        <path d="M12 3C14 5 16 6 19 6C17 8 15 9 12 11C9 9 7 8 5 6C8 6 10 5 12 3Z" fill="currentColor" opacity="0.8"/>
      </svg>
    </div>
    <!-- Dove 2 -->
    <div class="dove-anim dove-2">
      <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 3C14 5 16 6 19 6C17 8 15 9 12 11C9 9 7 8 5 6C8 6 10 5 12 3Z" fill="currentColor"/>
      </svg>
    </div>
    <!-- Dove 3 -->
    <div class="dove-anim dove-3">
      <svg width="56" height="56" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 3C14 5 16 6 19 6C17 8 15 9 12 11C9 9 7 8 5 6C8 6 10 5 12 3Z" fill="currentColor"/>
      </svg>
    </div>
  </div>


  <!-- Content -->
  <div class="relative z-20 container mx-auto px-6 text-center">
    <span class="inline-block py-1 px-3 rounded-full bg-blue-500/20 border border-blue-300/30 text-blue-100 text-sm font-medium tracking-widest uppercase mb-6 backdrop-blur-sm animate-fade-in-down">
      Welcome to Beacons of God Ministries
    </span>
    <h1 class="text-4xl md:text-6xl lg:text-7xl font-serif font-medium leading-tight mb-6 drop-shadow-lg animate-fade-in-up" style="animation-delay: 0.2s;">
      Shining God’s Truth in a <br class="hidden md:block" /> Darkened World
    </h1>
    <p class="text-lg md:text-xl text-blue-100/90 max-w-2xl mx-auto mb-10 font-light leading-relaxed animate-fade-in-up" style="animation-delay: 0.4s;">
      We are beacons of His light for this generation. Join us for biblical teaching, evangelism, and spiritual growth in the presence of the Lord.
    </p>
    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-stagger">
      <button class="px-8 py-4 bg-white text-blue-900 rounded-full font-semibold hover:bg-blue-50 transition-all shadow-[0_0_20px_rgba(255,255,255,0.3)] hover:shadow-[0_0_30px_rgba(255,255,255,0.5)] hover-lift">
        Watch Sermons
      </button>
      <button class="px-8 py-4 bg-transparent border border-white/40 text-white rounded-full font-medium hover:bg-white/10 transition-all backdrop-blur-sm hover-lift">
        Explore Resources
      </button>
    </div>
  </div>
</section>
<!-- About Section -->
<section id="about" class="py-6 bg-slate-50 text-slate-800">
  <div class="container mx-auto px-6">
    <div class="text-center mb-8">
      <h2 class="text-4xl md:text-5xl font-serif font-bold text-brand-blue mb-4">
        About Our Ministry
      </h2>
      <div class="w-12 h-1 bg-brand-gold mx-auto"></div>
    </div>


    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <!-- Mission Card -->
      <div class="bg-white rounded-lg shadow-md p-8 hover:shadow-lg transition-shadow">
        <h3 class="text-2xl font-serif font-bold text-brand-blue mb-4">Our Mission</h3>
        <p class="text-lg text-slate-700 leading-relaxed">
          We're here to shine God's light in a world searching for answers. Through powerful biblical teaching and genuine spiritual support, we help believers discover their divine purpose and transform their lives with faith.
        </p>
      </div>


      <!-- What We Offer Card - Animated -->
      <div class="bg-white rounded-lg shadow-md p-8 hover:shadow-lg transition-shadow animate-float-slow">
        <h3 class="text-2xl font-serif font-bold text-brand-blue mb-4">What We Offer</h3>
        <ul class="space-y-3 text-lg text-slate-700">
          <li class="flex items-start gap-3 hover:translate-x-1 transition-transform">
            <span class="text-brand-gold font-bold text-xl">✦</span>
            <span><strong>Powerful Sermons</strong> — Stories and truths that challenge and inspire</span>
          </li>
          <li class="flex items-start gap-3 hover:translate-x-1 transition-transform">
            <span class="text-brand-gold font-bold text-xl">✦</span>
            <span><strong>Study Resources</strong> — Tools to deepen your biblical knowledge</span>
          </li>
          <li class="flex items-start gap-3 hover:translate-x-1 transition-transform">
            <span class="text-brand-gold font-bold text-xl">✦</span>
            <span><strong>Inspiring Audio</strong> — Spiritual teachings for your daily walk</span>
          </li>
          <li class="flex items-start gap-3 hover:translate-x-1 transition-transform">
            <span class="text-brand-gold font-bold text-xl">✦</span>
            <span><strong>Connected Community</strong> — Find encouragement and belonging</span>
          </li>
        </ul>
      </div>


      <!-- Vision Card -->
      <div class="bg-white rounded-lg shadow-md p-8 hover:shadow-lg transition-shadow">
        <h3 class="text-2xl font-serif font-bold text-brand-blue mb-4">Our Vision</h3>
        <p class="text-lg text-slate-700 leading-relaxed">
          We believe every person deserves access to life-changing spiritual guidance. Join thousands discovering deeper faith, finding hope, and living with renewed purpose through God's eternal truth.
        </p>
      </div>
    </div>
  </div>
</section>
<section id="resources" class="py-24 bg-slate-50">
  <div class="container mx-auto px-6">
    <div class="text-center mb-16">
      <h2 class="text-3xl md:text-4xl font-serif text-blue-900 mb-4">Spiritual Resources for Your Journey</h2>
      <p class="text-slate-600 max-w-2xl mx-auto">Explore our three core pillars of ministry designed to help you grow in faith and understanding.</p>
    </div>
   
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <!-- Feature 1: Sermons -->
      <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100 scroll-animate hover-lift hover-glow-intense ripple-container">
        <div class="aspect-[3/2] overflow-hidden">
          <img
            data-ai="generate"
            data-slot="feature-sermons"
            a ministry of A peaceful church pulpit with an open bible and soft warm lighting, inviting and holy, photorealistic"
            data-ar="3:2"
            src="https://cdn.ailandingpage.ai/landingpage_io/user-generate/552f4586-c83c-46ac-b326-367fa6ccc9f3/552f4586-c83c-46ac-b326-367fa6ccc9f3/features/feature-sermons-1caa032ab92e4984897d6a1257a4868a.png"
            alt="Video Sermons"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            loading="lazy"
          />
        </div>
        <div class="p-8 text-center">
          <h3 class="text-xl font-serif text-blue-900 mb-3">Video Sermons</h3>
          <p class="text-slate-600 mb-6 text-sm leading-relaxed">
            Watch powerful, scripture-based messages that bring the Bible to life and speak directly to your heart.
          </p>
          <button class="text-blue-700 font-medium hover:text-blue-900 inline-flex items-center gap-2 text-sm uppercase tracking-wide">
            Watch Now
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
          </button>
        </div>
      </div>


      <!-- Feature 2: Books -->
      <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100 scroll-animate hover-lift hover-glow-intense ripple-container">
        <div class="aspect-[3/2] overflow-hidden">
          <img
            data-ai="generate"
            data-slot="feature-books"
            a ministry of light"A stack of elegant christian books on a wooden table with a cup of tea, soft daylight, reading atmosphere"
            data-ar="3:2"
            src="https://cdn.ailandingpage.ai/landingpage_io/user-generate/552f4586-c83c-46ac-b326-367fa6ccc9f3/552f4586-c83c-46ac-b326-367fa6ccc9f3/features/feature-books-5049c06b4fcb4e0887e246668bb8b930.png"
            alt="Downloadable Books"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            loading="lazy"
          />
        </div>
        <div class="p-8 text-center">
          <h3 class="text-xl font-serif text-blue-900 mb-3">Downloadable Books</h3>
          <p class="text-slate-600 mb-6 text-sm leading-relaxed">
            Deepen your study with our library of PDF books and guides, available for free download to aid your walk.
          </p>
          <button class="text-blue-700 font-medium hover:text-blue-900 inline-flex items-center gap-2 text-sm uppercase tracking-wide">
            Browse Library
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
          </button>
        </div>
      </div>


      <!-- Feature 3: Audio -->
      <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100 scroll-animate hover-lift hover-glow-intense ripple-container">
        <div class="aspect-[3/2] overflow-hidden">
          <img
            data-ai="generate"
            data-slot="feature-audio"
            A Ministry of Light and Truth Close up of vintage headphones resting on a bible, calm and serene composition, soft focus"
            data-ar="3:2"
            src="https://cdn.ailandingpage.ai/landingpage_io/user-generate/552f4586-c83c-46ac-b326-367fa6ccc9f3/552f4586-c83c-46ac-b326-367fa6ccc9f3/features/feature-audio-8fdfc52770dd449da1f89949a5b099e2.png"
            alt="Audio Teachings"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            loading="lazy"
          />
        </div>
        <div class="p-8 text-center">
          <h3 class="text-xl font-serif text-blue-900 mb-3">Audio Teachings</h3>
          <p class="text-slate-600 mb-6 text-sm leading-relaxed">
            Listen to teachings on the go. Perfect for your commute or quiet time, bringing God's word to your ears.
          </p>
          <button class="text-blue-700 font-medium hover:text-blue-900 inline-flex items-center gap-2 text-sm uppercase tracking-wide">
            Start Listening
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</section>
<section id="sermons" class="py-20 bg-white text-slate-800">
  <div class="container mx-auto px-6 max-w-6xl text-center">
    <span class="block text-amber-600 font-semibold tracking-widest uppercase text-sm mb-3">Latest Messages</span>
    <h2 class="text-3xl md:text-4xl font-serif font-bold text-blue-950 mb-4">Walking in Divine Light</h2>
    <p class="text-lg text-slate-600 mb-10 italic">"Your word is a lamp for my feet, a light on my path." — Psalm 119:105</p>
   
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <!-- Video 1 -->
      <div class="flex flex-col">
        <div class="relative w-full aspect-video bg-slate-100 rounded-xl overflow-hidden shadow-lg border border-slate-200 mb-4">
          <iframe class="absolute top-0 left-0 w-full h-full" src="https://www.youtube.com/embed/dQw4w9WgXcQ?si=placeholder" title="Sermon Video 1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        </div>
        <h3 class="text-lg font-serif font-semibold text-blue-900 text-left">The Power of Prayer</h3>
        <p class="text-sm text-slate-500 text-left">Sunday Service • 45 min</p>
      </div>


      <!-- Video 2 -->
      <div class="flex flex-col">
        <div class="relative w-full aspect-video bg-slate-100 rounded-xl overflow-hidden shadow-lg border border-slate-200 mb-4">
          <iframe class="absolute top-0 left-0 w-full h-full" src="https://www.youtube.com/embed/dQw4w9WgXcQ?si=placeholder" title="Sermon Video 2" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        </div>
        <h3 class="text-lg font-serif font-semibold text-blue-900 text-left">Walking in Faith</h3>
        <p class="text-sm text-slate-500 text-left">Bible Study • 38 min</p>
      </div>


      <!-- Video 3 -->
      <div class="flex flex-col">
        <div class="relative w-full aspect-video bg-slate-100 rounded-xl overflow-hidden shadow-lg border border-slate-200 mb-4">
          <iframe class="absolute top-0 left-0 w-full h-full" src="https://www.youtube.com/embed/dQw4w9WgXcQ?si=placeholder" title="Sermon Video 3" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        </div>
        <h3 class="text-lg font-serif font-semibold text-blue-900 text-left">Grace Abounds</h3>
        <p class="text-sm text-slate-500 text-left">Youth Ministry • 32 min</p>
      </div>
    </div>
  </div>
</section>
<section class="py-24 bg-slate-50">
  <div class="container mx-auto px-6 max-w-6xl">
    <div class="text-center mb-16">
      <h2 class="text-3xl md:text-4xl font-serif font-bold text-blue-950 mb-4">Ministry Resources</h2>
      <p class="text-slate-600 max-w-2xl mx-auto text-lg">Deepen your understanding with our curated collection of study guides and audio teachings, available for free download.</p>
    </div>


    <!-- PDF Downloads Section -->
    <div class="mb-16">
      <h3 class="text-2xl font-serif font-bold text-blue-950 mb-8 text-center">Downloadable PDF Resources</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- PDF Card 1 -->
        <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow border border-slate-100 flex flex-col scroll-animate hover-lift hover-glow-intense">
          <div class="relative h-48 overflow-hidden bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center">
            <div class="text-center">
              <i data-lucide="book-open" class="w-16 h-16 text-blue-900 mx-auto"></i>
              <p class="text-sm text-blue-700 mt-2">Study Guide</p>
            </div>
          </div>
          <div class="p-8 flex-1 flex flex-col">
            <h3 class="text-xl font-serif font-bold text-blue-950 mb-3">Foundations of Faith</h3>
            <p class="text-slate-600 mb-6 flex-1 leading-relaxed">A comprehensive guide to understanding the core pillars of our spiritual walk and biblical truth.</p>
            <button class="w-full py-3 px-6 bg-blue-50 text-blue-900 font-medium rounded-lg hover:bg-blue-100 transition-colors flex items-center justify-center gap-2 hover:scale-105 transform">
              <i data-lucide="download" class="w-4 h-4"></i> Download PDF
            </button>
          </div>
        </div>


        <!-- PDF Card 2 -->
        <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow border border-slate-100 flex flex-col scroll-animate hover-lift hover-glow-intense">
          <div class="relative h-48 overflow-hidden bg-gradient-to-br from-amber-100 to-amber-50 flex items-center justify-center">
            <div class="text-center">
              <i data-lucide="scroll" class="w-16 h-16 text-amber-900 mx-auto"></i>
              <p class="text-sm text-amber-700 mt-2">Prayer Guide</p>
            </div>
          </div>
          <div class="p-8 flex-1 flex flex-col">
            <h3 class="text-xl font-serif font-bold text-blue-950 mb-3">The Power of Prayer</h3>
            <p class="text-slate-600 mb-6 flex-1 leading-relaxed">Learn effective prayer techniques and biblical foundations that will transform your spiritual life and connection with God.</p>
            <button class="w-full py-3 px-6 bg-blue-50 text-blue-900 font-medium rounded-lg hover:bg-blue-100 transition-colors flex items-center justify-center gap-2 hover:scale-105 transform">
              <i data-lucide="download" class="w-4 h-4"></i> Download PDF
            </button>
          </div>
        </div>


        <!-- PDF Card 3 -->
        <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow border border-slate-100 flex flex-col scroll-animate hover-lift hover-glow-intense">
          <div class="relative h-48 overflow-hidden bg-gradient-to-br from-green-100 to-green-50 flex items-center justify-center">
            <div class="text-center">
              <i data-lucide="bible" class="w-16 h-16 text-green-900 mx-auto"></i>
              <p class="text-sm text-green-700 mt-2">Scripture Study</p>
            </div>
          </div>
          <div class="p-8 flex-1 flex flex-col">
            <h3 class="text-xl font-serif font-bold text-blue-950 mb-3">Walking in Scripture</h3>
            <p class="text-slate-600 mb-6 flex-1 leading-relaxed">Deep dive into key biblical passages with commentary, historical context, and practical applications for modern life.</p>
            <button class="w-full py-3 px-6 bg-blue-50 text-blue-900 font-medium rounded-lg hover:bg-blue-100 transition-colors flex items-center justify-center gap-2 hover:scale-105 transform">
              <i data-lucide="download" class="w-4 h-4"></i> Download PDF
            </button>
          </div>
        </div>
      </div>
    </div>


    <!-- Audio Resources Section -->
    <div>
      <h3 class="text-2xl font-serif font-bold text-blue-950 mb-8 text-center">Audio Teachings</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Audio Card 1 -->
        <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow border border-slate-100 flex flex-col scroll-animate hover-lift hover-glow-intense">
          <div class="relative h-48 overflow-hidden bg-gradient-to-br from-purple-100 to-purple-50 flex items-center justify-center">
            <div class="text-center">
              <i data-lucide="headphones" class="w-16 h-16 text-purple-900 mx-auto"></i>
              <p class="text-sm text-purple-700 mt-2">Sermon Series</p>
            </div>
          </div>
          <div class="p-8 flex-1 flex flex-col">
            <h3 class="text-xl font-serif font-bold text-blue-950 mb-3">Echoes of Grace</h3>
            <p class="text-slate-600 mb-6 flex-1 leading-relaxed">Listen to our latest series on finding peace and grace in everyday life through the Holy Spirit and God's love.</p>
            <div class="space-y-2">
              <button class="w-full py-3 px-6 bg-purple-50 text-purple-900 font-medium rounded-lg hover:bg-purple-100 transition-colors flex items-center justify-center gap-2 hover:scale-105 transform">
                <i data-lucide="play-circle" class="w-4 h-4"></i> Play Audio
              </button>
              <button class="w-full py-2 px-6 bg-blue-50 text-blue-900 font-medium rounded-lg hover:bg-blue-100 transition-colors flex items-center justify-center gap-2 text-sm">
                <i data-lucide="download" class="w-4 h-4"></i> Download
              </button>
            </div>
          </div>
        </div>


        <!-- Audio Card 2 -->
        <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow border border-slate-100 flex flex-col scroll-animate hover-lift hover-glow-intense">
          <div class="relative h-48 overflow-hidden bg-gradient-to-br from-indigo-100 to-indigo-50 flex items-center justify-center">
            <div class="text-center">
              <i data-lucide="volume-2" class="w-16 h-16 text-indigo-900 mx-auto"></i>
              <p class="text-sm text-indigo-700 mt-2">Daily Devotional</p>
            </div>
          </div>
          <div class="p-8 flex-1 flex flex-col">
            <h3 class="text-xl font-serif font-bold text-blue-950 mb-3">Daily Strength</h3>
            <p class="text-slate-600 mb-6 flex-1 leading-relaxed">Short daily audio devotions to strengthen your faith and set the right spiritual tone for each day.</p>
            <div class="space-y-2">
              <button class="w-full py-3 px-6 bg-indigo-50 text-indigo-900 font-medium rounded-lg hover:bg-indigo-100 transition-colors flex items-center justify-center gap-2 hover:scale-105 transform">
                <i data-lucide="play-circle" class="w-4 h-4"></i> Play Audio
              </button>
              <button class="w-full py-2 px-6 bg-blue-50 text-blue-900 font-medium rounded-lg hover:bg-blue-100 transition-colors flex items-center justify-center gap-2 text-sm">
                <i data-lucide="download" class="w-4 h-4"></i> Download
              </button>
            </div>
          </div>
        </div>
        <!-- Audio Card 3 -->
        <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow border border-slate-100 flex flex-col scroll-animate hover-lift hover-glow-intense">
          <div class="relative h-48 overflow-hidden bg-gradient-to-br from-rose-100 to-rose-50 flex items-center justify-center">
            <div class="text-center">
              <i data-lucide="music" class="w-16 h-16 text-rose-900 mx-auto"></i>
              <p class="text-sm text-rose-700 mt-2">Worship Music</p>
            </div>
          </div>
          <div class="p-8 flex-1 flex flex-col">
            <h3 class="text-xl font-serif font-bold text-blue-950 mb-3">Hymns of Faith</h3>
            <p class="text-slate-600 mb-6 flex-1 leading-relaxed">Beautiful worship music and hymns to inspire your prayers and strengthen your connection with the Divine.</p>
            <div class="space-y-2">
              <button class="w-full py-3 px-6 bg-rose-50 text-rose-900 font-medium rounded-lg hover:bg-rose-100 transition-colors flex items-center justify-center gap-2 hover:scale-105 transform">
                <i data-lucide="play-circle" class="w-4 h-4"></i> Play Audio
              </button>
              <button class="w-full py-2 px-6 bg-blue-50 text-blue-900 font-medium rounded-lg hover:bg-blue-100 transition-colors flex items-center justify-center gap-2 text-sm">
                <i data-lucide="download" class="w-4 h-4"></i> Download
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<section id="newslatter" class="py-24 bg-blue-950 text-white relative overflow-hidden">
  <!-- Decorative background element -->
  <div class="absolute top-0 left-0 w-full h-full opacity-5 pointer-events-none">
    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
      <path d="M0 100 C 30 50 70 50 100 100 Z" fill="white" />
    </svg>
  </div>
 
  <div class="container mx-auto px-6 max-w-3xl text-center relative z-10">
    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-900 mb-6">
      <i data-lucide="mail" class="w-8 h-8 text-amber-500"></i>
    </div>
    <h2 class="text-3xl md:text-4xl font-serif font-bold mb-4">Receive Spiritual Nourishment</h2>
    <p class="text-blue-100 text-lg mb-10 leading-relaxed">Join our community to receive weekly sermons, prayer points, and ministry updates directly in your inbox.</p>
   
    <form class="flex flex-col sm:flex-row gap-4 max-w-lg mx-auto">
      <input
        type="email"
        placeholder="Your email address"
        class="flex-1 px-6 py-4 rounded-lg text-slate-900 bg-white focus:outline-none focus:ring-2 focus:ring-amber-500 placeholder-slate-400"
        required
      />
      <button
        type="submit"
        class="px-8 py-4 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg transition-colors shadow-lg whitespace-nowrap"
      >
        Subscribe
      </button>
    </form>
    <p class="text-blue-400 text-sm mt-6">We respect your privacy. Unsubscribe at any time.</p>
  </div>
</section>
  </main>
@endsection