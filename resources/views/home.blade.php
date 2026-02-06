<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Beacons of God Ministries | Light, Truth, and Guidance</title>
  <meta name="description" content="Beacons of God Ministries: Shining God's light and truth. Watch sermons, explore biblical resources, and find spiritual guidance for your walk with Christ.">
  <link rel="canonical" href="https://beaconsofgod.org">
  
  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://beaconsofgod.org">
  <meta property="og:title" content="Beacons of God Ministries | Light, Truth, and Guidance">
  <meta property="og:description" content="Join us in spreading the light of God's truth. Access sermons, books, and audio teachings to deepen your faith.">
  <meta property="og:image" content="https://beaconsofgod.org/og-image.jpg">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:url" content="https://beaconsofgod.org">
  <meta property="twitter:title" content="Beacons of God Ministries | Light, Truth, and Guidance">
  <meta property="twitter:description" content="Biblical teaching and spiritual resources for all believers. Watch, read, and listen to the truth of God.">
  <meta property="twitter:image" content="https://beaconsofgod.org/twitter-image.jpg">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

  <!-- Tailwind & Lucide -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/lucide@latest"></script>

  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              blue: '#0f2b5e', /* Deep Royal Blue */
              light: '#f8fafc', /* Soft Off-white */
              gold: '#d4af37', /* Muted Gold */
              sky: '#e0f2fe', /* Soft Sky */
            }
          },
          fontFamily: {
            serif: ['"Playfair Display"', 'serif'],
            sans: ['"Lato"', 'sans-serif'],
          },
          animation: {
            'float-slow': 'float 8s ease-in-out infinite',
            'ray-spin': 'spin 60s linear infinite',
          },
          keyframes: {
            float: {
              '0%, 100%': { transform: 'translateY(0)' },
              '50%': { transform: 'translateY(-20px)' },
            }
          }
        }
      }
    }
  </script>

  <style>
    /* Custom styles for the divine atmosphere */
    body {
      background-color: #f8fafc;
      color: #1e293b;
    }
    .glass-nav {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
      border-bottom: 1px solid rgba(15, 43, 94, 0.05);
    }
    /* Dove Animation Utilities for Hero Section usage */
    .dove-container {
      position: absolute;
      top: 0; left: 0; width: 100%; height: 100%;
      overflow: hidden;
      pointer-events: none;
      z-index: 0;
    }
    .dove {
      position: absolute;
      opacity: 0.4;
      filter: blur(1px);
      animation: fly-across 25s linear infinite;
    }
    @keyframes fly-across {
      0% { transform: translate(-10vw, 10vh) scale(0.8) rotate(5deg); opacity: 0; }
      10% { opacity: 0.6; }
      90% { opacity: 0.6; }
      100% { transform: translate(110vw, -20vh) scale(1) rotate(0deg); opacity: 0; }
    }
    .light-ray {
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: conic-gradient(from 0deg, transparent 0deg, rgba(255,255,255,0.1) 20deg, transparent 40deg, rgba(255,255,255,0.1) 60deg, transparent 80deg);
      animation: spin 120s linear infinite;
      pointer-events: none;
    }
  </style>
</head>
<body class="font-sans antialiased flex flex-col min-h-screen">

  <!-- Header -->
  <header class="glass-nav sticky top-0 z-50 transition-all duration-300">
    <div class="container mx-auto px-6 h-20 flex items-center justify-between">
      <!-- Logo -->
      <a href="#" class="flex items-center gap-3 group">
        <div class="w-10 h-10 bg-brand-blue text-white rounded-full flex items-center justify-center shadow-md group-hover:bg-brand-gold transition-colors duration-300">
          <i data-lucide="flame" class="w-5 h-5"></i>
        </div>
        <span class="font-serif text-xl font-bold text-brand-blue tracking-wide">Beacons of God</span>
      </a>

      <!-- Desktop Nav -->
      <nav class="hidden md:flex items-center gap-8">
        <a href="#hero" class="text-slate-600 hover:text-brand-blue font-medium transition-colors">Home</a>
        <a href="#features" class="text-slate-600 hover:text-brand-blue font-medium transition-colors">Resources</a>
        <a href="#featured-sermon" class="text-slate-600 hover:text-brand-blue font-medium transition-colors">Sermons</a>
        <a href="#intro" class="text-slate-600 hover:text-brand-blue font-medium transition-colors">About</a>
      </nav>

      <!-- CTA & Mobile Menu -->
      <div class="flex items-center gap-4">
        <a href="#newsletter" class="hidden md:inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold text-white bg-brand-blue rounded-full hover:bg-blue-800 transition-all shadow-sm hover:shadow-md">
          Join Ministry
        </a>
        <button class="md:hidden text-slate-600 hover:text-brand-blue">
          <i data-lucide="menu" class="w-7 h-7"></i>
        </button>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="flex-grow">
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
    <span class="inline-block py-1 px-3 rounded-full bg-blue-500/20 border border-blue-300/30 text-blue-100 text-sm font-medium tracking-widest uppercase mb-6 backdrop-blur-sm">
      Welcome to Beacons of God Ministries
    </span>
    <h1 class="text-4xl md:text-6xl lg:text-7xl font-serif font-medium leading-tight mb-6 drop-shadow-lg">
      Shining God’s Truth in a <br class="hidden md:block" /> Darkened World
    </h1>
    <p class="text-lg md:text-xl text-blue-100/90 max-w-2xl mx-auto mb-10 font-light leading-relaxed">
      We are beacons of His light for this generation. Join us for biblical teaching, evangelism, and spiritual growth in the presence of the Lord.
    </p>
    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
      <button class="px-8 py-4 bg-white text-blue-900 rounded-full font-semibold hover:bg-blue-50 transition-all shadow-[0_0_20px_rgba(255,255,255,0.3)] hover:shadow-[0_0_30px_rgba(255,255,255,0.5)]">
        Watch Sermons
      </button>
      <button class="px-8 py-4 bg-transparent border border-white/40 text-white rounded-full font-medium hover:bg-white/10 transition-all backdrop-blur-sm">
        Explore Resources
      </button>
    </div>
  </div>
</section>
    <section class="py-20 bg-white text-slate-800">
  <div class="container mx-auto px-6">
    <div class="max-w-3xl mx-auto text-center">
      <h2 class="text-3xl md:text-4xl font-serif text-blue-900 mb-6">
        A Ministry of Light and Truth
      </h2>
      <div class="w-16 h-1 bg-amber-400 mx-auto mb-8 rounded-full"></div>
      <p class="text-lg md:text-xl leading-relaxed text-slate-600 mb-8">
        At Beacons of God Ministries, our mission is simple yet profound: to illuminate the path of righteousness through the unwavering truth of Scripture. In a world often clouded by confusion, we strive to be a steady source of divine guidance, offering peace and clarity to all believers.
      </p>
      <p class="text-lg md:text-xl leading-relaxed text-slate-600">
        Whether you are seeking a deeper understanding of the Bible or looking for spiritual encouragement, our doors and our hearts are open. Let us walk together in the light of His presence.
      </p>
    </div>
  </div>
</section>
    <section class="py-24 bg-slate-50">
  <div class="container mx-auto px-6">
    <div class="text-center mb-16">
      <h2 class="text-3xl md:text-4xl font-serif text-blue-900 mb-4">Spiritual Resources for Your Journey</h2>
      <p class="text-slate-600 max-w-2xl mx-auto">Explore our three core pillars of ministry designed to help you grow in faith and understanding.</p>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <!-- Feature 1: Sermons -->
      <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100">
        <div class="aspect-[3/2] overflow-hidden">
          <img
            data-ai="generate"
            data-slot="feature-sermons"
            data-prompt="A peaceful church pulpit with an open bible and soft warm lighting, inviting and holy, photorealistic"
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
      <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100">
        <div class="aspect-[3/2] overflow-hidden">
          <img
            data-ai="generate"
            data-slot="feature-books"
            data-prompt="A stack of elegant christian books on a wooden table with a cup of tea, soft daylight, reading atmosphere"
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
      <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow duration-300 border border-slate-100">
        <div class="aspect-[3/2] overflow-hidden">
          <img
            data-ai="generate"
            data-slot="feature-audio"
            data-prompt="Close up of vintage headphones resting on a bible, calm and serene composition, soft focus"
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
    <section class="py-20 bg-white text-slate-800">
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
    <div class="grid md:grid-cols-2 gap-10">
      <!-- Card 1: Book/PDF -->
      <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow border border-slate-100 flex flex-col">
        <div class="relative h-64 overflow-hidden">
          <img
            data-ai="generate"
            data-slot="resource-preview-1"
            data-prompt="A peaceful, open bible on a wooden table with soft morning light, spiritual study atmosphere, high quality photography"
            data-ar="3:2"
            src="https://cdn.ailandingpage.ai/landingpage_io/user-generate/552f4586-c83c-46ac-b326-367fa6ccc9f3/552f4586-c83c-46ac-b326-367fa6ccc9f3/resources/resource-preview-1-75b8e778cbf843c7be0aa4bdea54cb0a.png"
            alt="Bible study guide preview"
            width="1200"
            height="900"
            class="w-full h-full object-cover"
            loading="lazy"
            decoding="async"
            fetchpriority="auto"
          />
        </div>
        <div class="p-8 flex-1 flex flex-col">
          <div class="flex items-center gap-2 text-amber-600 text-sm font-semibold mb-3">
            <i data-lucide="book-open" class="w-4 h-4"></i>
            <span>Study Guide</span>
          </div>
          <h3 class="text-2xl font-serif font-bold text-blue-950 mb-3">Foundations of Faith</h3>
          <p class="text-slate-600 mb-8 flex-1 leading-relaxed">A comprehensive guide to understanding the core pillars of our spiritual walk and biblical truth.</p>
          <button class="w-full py-3 px-6 bg-blue-50 text-blue-900 font-medium rounded-lg hover:bg-blue-100 transition-colors flex items-center justify-center gap-2">
            <i data-lucide="download" class="w-4 h-4"></i> Download PDF
          </button>
        </div>
      </div>
      <!-- Card 2: Audio -->
      <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow border border-slate-100 flex flex-col">
        <div class="relative h-64 overflow-hidden">
          <img
            data-ai="generate"
            data-slot="resource-preview-2"
            data-prompt="A calm nature scene with headphones or a vintage radio, symbolizing listening to god's word, soft blue tones, cinematic lighting"
            data-ar="3:2"
            src="https://cdn.ailandingpage.ai/landingpage_io/user-generate/552f4586-c83c-46ac-b326-367fa6ccc9f3/552f4586-c83c-46ac-b326-367fa6ccc9f3/resources/resource-preview-2-b8f1457951f945ffab6c2b397686ca7d.png"
            alt="Audio sermon series preview"
            width="1200"
            height="900"
            class="w-full h-full object-cover"
            loading="lazy"
            decoding="async"
            fetchpriority="auto"
          />
        </div>
        <div class="p-8 flex-1 flex flex-col">
          <div class="flex items-center gap-2 text-amber-600 text-sm font-semibold mb-3">
            <i data-lucide="headphones" class="w-4 h-4"></i>
            <span>Audio Series</span>
          </div>
          <h3 class="text-2xl font-serif font-bold text-blue-950 mb-3">Echoes of Grace</h3>
          <p class="text-slate-600 mb-8 flex-1 leading-relaxed">Listen to our latest series on finding peace and grace in everyday life through the Holy Spirit.</p>
          <button class="w-full py-3 px-6 bg-blue-50 text-blue-900 font-medium rounded-lg hover:bg-blue-100 transition-colors flex items-center justify-center gap-2">
            <i data-lucide="play-circle" class="w-4 h-4"></i> Listen Now
          </button>
        </div>
      </div>
    </div>
  </div>
</section>
    <section class="py-24 bg-blue-950 text-white relative overflow-hidden">
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

  <!-- Footer -->
  <footer class="bg-brand-blue text-slate-300 py-12 border-t border-blue-800">
    <div class="container mx-auto px-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-10">
        <!-- Brand Column -->
        <div class="col-span-1 md:col-span-1">
          <div class="flex items-center gap-2 mb-4 text-white">
            <i data-lucide="flame" class="w-6 h-6 text-brand-gold"></i>
            <span class="font-serif text-xl font-bold">Beacons of God</span>
          </div>
          <p class="text-sm leading-relaxed text-blue-100 opacity-80">
            Shining the light of truth and biblical guidance to believers everywhere. Walking together in faith and grace.
          </p>
        </div>

        <!-- Links 1 -->
        <div>
          <h4 class="text-white font-serif font-semibold mb-4">Ministry</h4>
          <ul class="space-y-2 text-sm">
            <li><a href="#" class="hover:text-brand-gold transition-colors">About Us</a></li>
            <li><a href="#" class="hover:text-brand-gold transition-colors">Statement of Faith</a></li>
            <li><a href="#" class="hover:text-brand-gold transition-colors">Leadership</a></li>
            <li><a href="#" class="hover:text-brand-gold transition-colors">Contact</a></li>
          </ul>
        </div>

        <!-- Links 2 -->
        <div>
          <h4 class="text-white font-serif font-semibold mb-4">Resources</h4>
          <ul class="space-y-2 text-sm">
            <li><a href="#" class="hover:text-brand-gold transition-colors">Latest Sermons</a></li>
            <li><a href="#" class="hover:text-brand-gold transition-colors">Audio Teachings</a></li>
            <li><a href="#" class="hover:text-brand-gold transition-colors">E-Books</a></li>
            <li><a href="#" class="hover:text-brand-gold transition-colors">Devotionals</a></li>
          </ul>
        </div>

        <!-- Contact / Social -->
        <div>
          <h4 class="text-white font-serif font-semibold mb-4">Connect</h4>
          <ul class="space-y-3 text-sm">
            <li class="flex items-center gap-2">
              <i data-lucide="mail" class="w-4 h-4 text-brand-gold"></i>
              <span>contact@beaconsofgod.org</span>
            </li>
            <li class="flex items-center gap-2">
              <i data-lucide="map-pin" class="w-4 h-4 text-brand-gold"></i>
              <span>Global Online Ministry</span>
            </li>
            <li class="flex gap-4 mt-2">
              <a href="#" class="text-blue-200 hover:text-white transition-colors"><i data-lucide="youtube" class="w-5 h-5"></i></a>
              <a href="#" class="text-blue-200 hover:text-white transition-colors"><i data-lucide="facebook" class="w-5 h-5"></i></a>
              <a href="#" class="text-blue-200 hover:text-white transition-colors"><i data-lucide="instagram" class="w-5 h-5"></i></a>
            </li>
          </ul>
        </div>
      </div>

      <div class="border-t border-blue-800 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-blue-200 opacity-60">
        <p>&copy; 2023 Beacons of God Ministries. All rights reserved.</p>
        <p class="mt-2 md:mt-0">Designed & developed with care</p>
      </div>
    </div>
  </footer>

</body>
</html>