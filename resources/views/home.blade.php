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


    /* ===== ENGAGEMENT ANIMATIONS ===== */


    /* Fade In Animation */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }


    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }


    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }


    @keyframes slideInLeft {
      from {
        opacity: 0;
        transform: translateX(-50px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }


    @keyframes slideInRight {
      from {
        opacity: 0;
        transform: translateX(50px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }


    @keyframes scaleIn {
      from {
        opacity: 0;
        transform: scale(0.95);
      }
      to {
        opacity: 1;
        transform: scale(1);
      }
    }


    @keyframes bounce-light {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }


    /* ===== EVENT-DRIVEN CONTINUOUS ANIMATIONS ===== */


    /* Continuous Glow Pulse */
    @keyframes glow-pulse {
      0%, 100% {
        box-shadow: 0 0 20px rgba(15, 43, 94, 0.3);
      }
      50% {
        box-shadow: 0 0 40px rgba(15, 43, 94, 0.6);
      }
    }


    /* Shimmer Effect */
    @keyframes shimmer {
      0% {
        background-position: -1000px 0;
      }
      100% {
        background-position: 1000px 0;
      }
    }


    /* Continuous Float */
    @keyframes float-continuous {
      0%, 100% { transform: translateY(0px) rotate(0deg); }
      25% { transform: translateY(-8px) rotate(1deg); }
      50% { transform: translateY(0px) rotate(0deg); }
      75% { transform: translateY(-5px) rotate(-1deg); }
    }


    /* Ripple Click Effect */
    @keyframes ripple {
      0% {
        transform: scale(0);
        opacity: 1;
      }
      100% {
        transform: scale(4);
        opacity: 0;
      }
    }


    /* Subtle Rotate on Hover */
    @keyframes subtle-rotate {
      0%, 100% { transform: rotateY(0deg) rotateX(0deg); }
      50% { transform: rotateY(5deg) rotateX(-3deg); }
    }


    /* Continuous Background Pulse */
    @keyframes bg-pulse {
      0%, 100% { background-color: rgba(15, 43, 94, 0.02); }
      50% { background-color: rgba(15, 43, 94, 0.08); }
    }


    /* Text Wave Animation */
    @keyframes wave {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-8px); }
    }


    /* Icon Spin on Hover */
    @keyframes spin-smooth {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }


    /* ===== ANIMATION UTILITY CLASSES ===== */
    .animate-fade-in-up {
      animation: fadeInUp 0.8s ease-out;
    }


    .animate-fade-in-down {
      animation: fadeInDown 0.8s ease-out;
    }


    .animate-fade-in {
      animation: fadeIn 0.6s ease-out;
    }


    .animate-slide-in-left {
      animation: slideInLeft 0.8s ease-out;
    }


    .animate-slide-in-right {
      animation: slideInRight 0.8s ease-out;
    }


    .animate-scale-in {
      animation: scaleIn 0.6s ease-out;
    }


    .animate-bounce-light {
      animation: bounce-light 2s ease-in-out infinite;
    }


    /* Stagger animations for multiple elements */
    .animate-stagger > * {
      animation: fadeInUp 0.8s ease-out;
    }


    .animate-stagger > *:nth-child(1) { animation-delay: 0.1s; }
    .animate-stagger > *:nth-child(2) { animation-delay: 0.2s; }
    .animate-stagger > *:nth-child(3) { animation-delay: 0.3s; }
    .animate-stagger > *:nth-child(4) { animation-delay: 0.4s; }
    .animate-stagger > *:nth-child(5) { animation-delay: 0.5s; }


    /* Scroll-triggered animation state */
    .scroll-animate {
      opacity: 0;
    }


    .scroll-animate.is-visible {
      opacity: 1;
      animation: fadeInUp 0.8s ease-out forwards;
    }


    /* ===== EVENT-DRIVEN ANIMATION CLASSES ===== */


    /* Continuous Glow Pulse (Always Active) */
    .animate-glow-continuous {
      animation: glow-pulse 3s ease-in-out infinite;
    }


    /* Floating Animation (Continuous) */
    .animate-float-continuous {
      animation: float-continuous 4s ease-in-out infinite;
    }


    /* Floating Animation - Slow */
    .animate-float-slow {
      animation: float-continuous 5.5s ease-in-out infinite;
    }


    /* Background Pulse on Hover */
    .hover-bg-pulse:hover {
      animation: bg-pulse 2s ease-in-out infinite;
    }


    /* Ripple Effect Container */
    .ripple-container {
      position: relative;
      overflow: hidden;
    }


    .ripple {
      position: absolute;
      border-radius: 50%;
      background-color: rgba(255, 255, 255, 0.6);
      transform: scale(0);
      animation: ripple 0.6s ease-out;
      pointer-events: none;
    }


    /* Continuous Rotation on Hover */
    .hover-spin:hover svg,
    .hover-spin:hover i {
      animation: spin-smooth 1s linear infinite;
    }


    /* Card with Continuous Glow */
    .card-glow {
      animation: glow-pulse 3s ease-in-out infinite;
    }


    /* Text Wave for Headers */
    .animate-wave > * {
      display: inline-block;
    }


    .animate-wave > *:nth-child(1) { animation: wave 1.2s ease-in-out infinite; animation-delay: 0s; }
    .animate-wave > *:nth-child(2) { animation: wave 1.2s ease-in-out infinite; animation-delay: 0.1s; }
    .animate-wave > *:nth-child(3) { animation: wave 1.2s ease-in-out infinite; animation-delay: 0.2s; }
    .animate-wave > *:nth-child(4) { animation: wave 1.2s ease-in-out infinite; animation-delay: 0.3s; }


    /* Button Click Pulse Effect */
    .btn-pulse:active {
      animation: scaleIn 0.4s ease-out;
    }


    /* Continuous Hover Effects */
    .hover-glow-intense:hover {
      animation: glow-pulse 1.5s ease-in-out infinite;
    }


    /* Shimmer Overlay for Loading States (Optional) */
    .shimmer-overlay {
      background: linear-gradient(
        90deg,
        transparent,
        rgba(255, 255, 255, 0.2),
        transparent
      );
      background-size: 1000px 100%;
      animation: shimmer 2s infinite;
    }


    /* Hover Effects */
    .hover-lift {
      transition: all 0.3s ease-out;
    }


    .hover-lift:hover {
      transform: translateY(-8px);
      box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }


    .hover-scale {
      transition: transform 0.3s ease-out;
    }


    .hover-scale:hover {
      transform: scale(1.05);
    }


    .hover-glow {
      transition: all 0.3s ease-out;
    }


    .hover-glow:hover {
      box-shadow: 0 0 30px rgba(15, 43, 94, 0.3);
    }


    /* Pulse Animation */
    @keyframes pulse-slow {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.7; }
    }


    .animate-pulse-slow {
      animation: pulse-slow 3s ease-in-out infinite;
    }
  </style>
</head>
<body class="font-sans antialiased flex flex-col min-h-screen">


  <!-- Header -->
  <header class="glass-nav sticky top-0 z-50 transition-all duration-300 shadow-sm">
    <div class="container mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
      <!-- Logo & Branding -->
      <a href="/" class="flex items-center gap-2 sm:gap-3 group shrink-0">
        <img
          src="{{ asset('images/logo.png') }}"
          alt="Beacons of God Logo"
          class="h-10 w-auto sm:h-12 transition-transform duration-300 group-hover:scale-105"
        />
        <div class="hidden sm:block">
          <div class="font-serif text-base sm:text-lg font-bold text-brand-blue leading-tight">Beacons of God Ministries</div>
        </div>
      </a>


      <!-- Desktop Navigation -->
      <nav class="hidden lg:flex items-center gap-1">
        <a href="#hero" class="px-4 py-2 text-slate-700 hover:text-brand-blue font-medium transition-all duration-200 border-b-2 border-transparent hover:border-brand-blue">Home</a>
        <a href="#features" class="px-4 py-2 text-slate-700 hover:text-brand-blue font-medium transition-all duration-200 border-b-2 border-transparent hover:border-brand-blue">Resources</a>
        <a href="#featured-sermon" class="px-4 py-2 text-slate-700 hover:text-brand-blue font-medium transition-all duration-200 border-b-2 border-transparent hover:border-brand-blue">Sermons</a>
        <a href="#intro" class="px-4 py-2 text-slate-700 hover:text-brand-blue font-medium transition-all duration-200 border-b-2 border-transparent hover:border-brand-blue">About</a>
      </nav>


      <!-- CTA & Mobile Menu Toggle -->
      <div class="flex items-center gap-2 sm:gap-4">
        <a href="#newsletter" class="hidden md:inline-flex items-center justify-center px-4 sm:px-6 py-2 text-xs sm:text-sm font-semibold text-white bg-brand-blue rounded-full hover:bg-blue-800 transition-all duration-200 shadow-md hover:shadow-lg hover:-translate-y-0.5">
          Join Ministry
        </a>
        <button
          id="mobile-menu-toggle"
          class="lg:hidden inline-flex flex-col items-center justify-center w-10 h-10 rounded-lg text-slate-700 hover:bg-slate-100 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-brand-blue"
          aria-label="Toggle navigation menu"
        >
          <!-- Hamburger Menu Icon (3 lines) -->
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>
      </div>
    </div>


    <!-- Mobile Navigation Menu (Hidden by default) -->
    <nav id="mobile-menu" class="hidden lg:hidden bg-white border-t border-slate-200">
      <div class="container mx-auto px-4 sm:px-6 py-3 space-y-2">
        <a href="#hero" class="block px-4 py-2 text-slate-700 hover:text-brand-blue hover:bg-slate-50 rounded-lg font-medium transition-colors duration-200">Home</a>
        <a href="#features" class="block px-4 py-2 text-slate-700 hover:text-brand-blue hover:bg-slate-50 rounded-lg font-medium transition-colors duration-200">Resources</a>
        <a href="#featured-sermon" class="block px-4 py-2 text-slate-700 hover:text-brand-blue hover:bg-slate-50 rounded-lg font-medium transition-colors duration-200">Sermons</a>
        <a href="#intro" class="block px-4 py-2 text-slate-700 hover:text-brand-blue hover:bg-slate-50 rounded-lg font-medium transition-colors duration-200">About</a>
        <a href="#newsletter" class="block w-full mt-3 px-4 py-2 text-center text-white bg-brand-blue rounded-lg font-semibold hover:bg-blue-800 transition-colors duration-200">Join Ministry</a>
      </div>
    </nav>
  </header>


  <!-- Mobile Menu Toggle Script -->
  <script>
    const toggle = document.getElementById('mobile-menu-toggle');
    const menu = document.getElementById('mobile-menu');
   
    toggle.addEventListener('click', () => {
      menu.classList.toggle('hidden');
    });


    // Close menu when a link is clicked
    menu.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        menu.classList.add('hidden');
      });
    });
  </script>


  <!-- Main Content -->
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
<section id="intro" class="py-6 bg-slate-50 text-slate-800">
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
<section class="py-24 bg-slate-50">
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


  <!-- Scroll Animation Trigger Script -->
  <script>
    // Intersection Observer for scroll animations
    const observerOptions = {
      threshold: 0.1,
      rootMargin: '0px 0px -50px 0px'
    };


    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    }, observerOptions);


    // Observe all scroll-animate elements
    document.querySelectorAll('.scroll-animate').forEach(el => {
      observer.observe(el);
    });


    // Lucide icon initialization
    if (typeof lucide !== 'undefined') {
      lucide.createIcons();
    }
  </script>


</body>
</html>

