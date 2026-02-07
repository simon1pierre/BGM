<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ config('app.name','Beacons of God Ministries || Light, Truth, and Guidance')}}</title>
  <meta name="description" content="Beacons of God Ministries: Shining God's light and truth. Watch sermons, explore biblical resources, and find spiritual guidance for your walk with Christ.">
  <link rel="canonical" href="https://beaconsofgod.org">
 <!-- Favicon -->
<link rel="icon" type="image/png" sizes="18x18" href="{{asset('logo/favicon-16x16.png')}}">
<link rel="icon" type="image/png" sizes="32x32" href="{{asset('logo/favicon-32x32.png')}}">
<!-- Apple Touch Icon -->
<link rel="apple-touch-icon" sizes="180x180" href="{{asset('logo/apple-touch-icon.png')}}">

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
        <a href="/" class="px-4 py-2 text-slate-700 hover:text-brand-blue font-medium transition-all duration-200 border-b-2 border-transparent hover:border-brand-blue">Home</a>
        <a href="#resources" class="px-4 py-2 text-slate-700 hover:text-brand-blue font-medium transition-all duration-200 border-b-2 border-transparent hover:border-brand-blue">Resources</a>
        <a href="#sermons" class="px-4 py-2 text-slate-700 hover:text-brand-blue font-medium transition-all duration-200 border-b-2 border-transparent hover:border-brand-blue">Sermons</a>
        <a href="#about" class="px-4 py-2 text-slate-700 hover:text-brand-blue font-medium transition-all duration-200 border-b-2 border-transparent hover:border-brand-blue">About</a>
      </nav>


      <!-- CTA & Mobile Menu Toggle -->
      <div class="flex items-center gap-2 sm:gap-4">
        <a href="#newslatter" class="hidden md:inline-flex items-center justify-center px-4 sm:px-6 py-2 text-xs sm:text-sm font-semibold text-white bg-brand-blue rounded-full hover:bg-blue-800 transition-all duration-200 shadow-md hover:shadow-lg hover:-translate-y-0.5">
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
        <a href="/" class="block px-4 py-2 text-slate-700 hover:text-brand-blue hover:bg-slate-50 rounded-lg font-medium transition-colors duration-200">Home</a>
        <a href="#resources" class="block px-4 py-2 text-slate-700 hover:text-brand-blue hover:bg-slate-50 rounded-lg font-medium transition-colors duration-200">Resources</a>
        <a href="#sermons" class="block px-4 py-2 text-slate-700 hover:text-brand-blue hover:bg-slate-50 rounded-lg font-medium transition-colors duration-200">Sermons</a>
        <a href="#about" class="block px-4 py-2 text-slate-700 hover:text-brand-blue hover:bg-slate-50 rounded-lg font-medium transition-colors duration-200">About</a>
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
    @yield('contents')
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