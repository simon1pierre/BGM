<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
@php
  $siteName = $siteSettings?->translated('site_name') ?: __('messages.site.name');
  $siteTagline = $siteSettings?->translated('site_tagline') ?: __('messages.site.tagline');
  $siteDescription = $siteSettings?->translated('site_description')
    ?: __('messages.site.description');
  $siteTitle = trim($siteName . ' | ' . $siteTagline, ' |');
  $logoPath = $siteSettings?->logo ? asset('storage/' . $siteSettings->logo) : asset('images/logo.png');
  $faviconPath = $siteSettings?->favicon ? asset('storage/' . $siteSettings->favicon) : asset('logo/favicon-32x32.png');
  $faviconPathSmall = $siteSettings?->favicon ? asset('storage/' . $siteSettings->favicon) : asset('logo/favicon-16x16.png');
  $contactEmail = $siteSettings?->contact_email ?: 'contact@beaconsofgod.org';
  $contactAddress = $siteSettings?->contact_address ?: 'Global Online Ministry';
  $footerText = $siteSettings?->translated('footer_text')
    ?: __('messages.site.footer_text');
  $normalizeUrl = function (?string $value, string $fallback) {
    if (empty($value)) {
      return $fallback;
    }
    if (!preg_match('~^https?://~i', $value)) {
      // return 'https://'.$value;
    }
    return $value;
  };
  $facebookUrl = $normalizeUrl($siteSettings?->facebook_url, 'https://www.facebook.com/');
  $instagramUrl = $normalizeUrl($siteSettings?->instagram_url, 'https://www.instagram.com/');
  $youtubeUrl = $normalizeUrl($siteSettings?->youtube_channel, 'https://www.youtube.com/');
  $twitterUrl = $normalizeUrl($siteSettings?->twitter_url, 'https://x.com/');
  $tiktokUrl = $normalizeUrl($siteSettings?->tiktok_url, 'https://www.tiktok.com/');
  $whatsappUrl = $normalizeUrl($siteSettings?->whatsapp_url, 'https://www.whatsapp.com/');
  $telegramUrl = $normalizeUrl($siteSettings?->telegram_url, 'https://telegram.org/');
  $currentLocale = app()->getLocale();
  $isHome = request()->routeIs('home');
  $homeUrl = route('home');
  $resourcesLink = $isHome ? '#resources' : $homeUrl . '#resources';
  $sermonsLink = $isHome ? '#sermons' : $homeUrl . '#sermons';
  $homeAboutLink = $isHome ? '#about' : $homeUrl . '#about';
  $themeColor = $siteSettings?->primary_color ?: '#0f2b5e';
  $tawkEnabled = (bool) ($siteSettings?->live_chat_enabled ?? false);
  $tawkProperty = $siteSettings?->tawk_property_id;
  $tawkWidget = $siteSettings?->tawk_widget_id;
@endphp
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $siteTitle }}</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="description" content="{{ $siteDescription }}">
  <meta name="application-name" content="{{ $siteName }}">
  <meta name="theme-color" content="{{ $themeColor }}">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
  <link rel="canonical" href="https://beaconsofgod.org">
 <!-- Favicon -->
<link rel="icon" type="image/png" sizes="18x18" href="{{ $faviconPathSmall }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ $faviconPath }}">
<!-- Apple Touch Icon -->
<link rel="apple-touch-icon" sizes="180x180" href="{{ $faviconPath }}">
<link rel="apple-touch-icon" sizes="192x192" href="{{ asset('pwa/icon-192.png') }}">
<link rel="apple-touch-icon" sizes="512x512" href="{{ asset('pwa/icon-512.png') }}">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://beaconsofgod.org">
  <meta property="og:title" content="{{ $siteTitle }}">
  <meta property="og:description" content="{{ $siteDescription }}">
  <meta property="og:image" content="https://beaconsofgod.org/og-image.jpg">


  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:url" content="https://beaconsofgod.org">
  <meta property="twitter:title" content="{{ $siteTitle }}">
  <meta property="twitter:description" content="{{ $siteDescription }}">
  <meta property="twitter:image" content="https://beaconsofgod.org/twitter-image.jpg">


  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">


  <!-- Tailwind & Lucide -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <script src="https://unpkg.com/lucide@latest"></script>


  


  <style>
    /* Wider audience layout on laptop/desktop to avoid large empty side space */
    .container {
      max-width: none !important;
      width: 100%;
      margin-left: auto;
      margin-right: auto;
    }

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

    .ambient-stage {
      position: fixed;
      inset: 0;
      pointer-events: none;
      z-index: 0;
      overflow: hidden;
    }

    .ambient-orb {
      position: absolute;
      border-radius: 9999px;
      filter: blur(80px);
      opacity: 0.18;
      animation: orb-drift 24s ease-in-out infinite;
      transform: translate3d(0, 0, 0);
    }

    .ambient-orb--one {
      width: 24rem;
      height: 24rem;
      top: 12%;
      left: -6rem;
      background: rgba(15, 43, 94, 0.65);
    }

    .ambient-orb--two {
      width: 20rem;
      height: 20rem;
      top: 45%;
      right: -5rem;
      background: rgba(212, 175, 55, 0.45);
      animation-delay: 3s;
    }

    .ambient-orb--three {
      width: 16rem;
      height: 16rem;
      bottom: 6%;
      left: 25%;
      background: rgba(56, 189, 248, 0.35);
      animation-delay: 6s;
    }

    @keyframes orb-drift {
      0%, 100% { transform: translate3d(0, 0, 0) scale(1); }
      50% { transform: translate3d(0, -18px, 0) scale(1.04); }
    }

    [data-reveal] {
      opacity: 0;
      transform: translate3d(0, 22px, 0);
      transition:
        opacity 720ms cubic-bezier(0.2, 0.7, 0.2, 1),
        transform 720ms cubic-bezier(0.2, 0.7, 0.2, 1);
      transition-delay: var(--reveal-delay, 0ms);
      will-change: opacity, transform;
    }

    [data-reveal="left"] {
      transform: translate3d(-20px, 0, 0);
    }

    [data-reveal="right"] {
      transform: translate3d(20px, 0, 0);
    }

    [data-reveal].is-visible {
      opacity: 1;
      transform: translate3d(0, 0, 0);
    }

    .interactive-card {
      transition:
        transform 320ms cubic-bezier(0.2, 0.7, 0.2, 1),
        box-shadow 320ms ease,
        border-color 320ms ease;
      transform-style: preserve-3d;
    }

    .interactive-card:hover {
      transform: translate3d(0, -8px, 0);
      box-shadow: 0 18px 36px rgba(15, 43, 94, 0.13);
      border-color: rgba(15, 43, 94, 0.2);
    }

    @media (prefers-reduced-motion: reduce) {
      *, *::before, *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
        scroll-behavior: auto !important;
      }

      [data-reveal] {
        opacity: 1 !important;
        transform: none !important;
      }
    }

    .page-loader {
      position: fixed;
      inset: 0;
      z-index: 9999;
      display: flex;
      align-items: center;
      justify-content: center;
      background:
        radial-gradient(circle at 20% 20%, rgba(15, 43, 94, 0.24), transparent 45%),
        radial-gradient(circle at 80% 80%, rgba(212, 175, 55, 0.2), transparent 40%),
        rgba(248, 250, 252, 0.95);
      transition: opacity 260ms ease, visibility 260ms ease;
    }

    .page-loader.hidden {
      opacity: 0;
      visibility: hidden;
      pointer-events: none;
    }

    .loader-card {
      min-width: 230px;
      max-width: 86vw;
      border-radius: 1rem;
      border: 1px solid rgba(15, 43, 94, 0.12);
      background: rgba(255, 255, 255, 0.9);
      box-shadow: 0 20px 40px rgba(15, 23, 42, 0.14);
      padding: 1rem 1.1rem;
      text-align: center;
      backdrop-filter: blur(8px);
    }

    .loader-core {
      width: 56px;
      height: 56px;
      margin: 0 auto .7rem auto;
      border-radius: 9999px;
      border: 3px solid rgba(15, 43, 94, 0.14);
      border-top-color: rgba(15, 43, 94, 0.95);
      animation: spin .9s linear infinite;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .loader-logo {
      width: 18px;
      height: 18px;
      object-fit: contain;
      border-radius: 9999px;
      background: #fff;
      padding: 1px;
    }

    .loader-text {
      font-size: .83rem;
      color: #0f2b5e;
      font-weight: 600;
      letter-spacing: .01em;
    }

    .loader-subtext {
      margin-top: .2rem;
      font-size: .72rem;
      color: #64748b;
    }

    .route-progress {
      position: fixed;
      top: 0;
      left: 0;
      height: 3px;
      width: 0%;
      z-index: 9998;
      background: linear-gradient(90deg, #0f2b5e, #d4af37);
      box-shadow: 0 0 12px rgba(15, 43, 94, 0.35);
      transition: width 240ms ease;
    }

    .media-skeleton {
      position: relative;
      overflow: hidden;
      background: #e2e8f0 !important;
    }

    .media-skeleton::after {
      content: '';
      position: absolute;
      inset: 0;
      transform: translateX(-100%);
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.58), transparent);
      animation: shimmer-slide 1.25s infinite;
    }

    @keyframes shimmer-slide {
      100% { transform: translateX(100%); }
    }

    .toast-wrap {
      position: fixed;
      top: 5rem;
      right: 1rem;
      z-index: 9997;
      display: flex;
      flex-direction: column;
      gap: 0.6rem;
      max-width: min(24rem, calc(100vw - 1.5rem));
    }

    .toast-item {
      border-radius: 0.75rem;
      border: 1px solid rgba(15, 43, 94, 0.12);
      background: rgba(255, 255, 255, 0.96);
      box-shadow: 0 12px 28px rgba(15, 23, 42, 0.15);
      padding: 0.75rem 0.9rem;
      font-size: 0.82rem;
      color: #1e293b;
      transform: translateY(-8px);
      opacity: 0;
      animation: toast-in 240ms ease forwards;
    }

    .toast-item.success { border-left: 4px solid #10b981; }
    .toast-item.error { border-left: 4px solid #ef4444; }
    .toast-item.info { border-left: 4px solid #3b82f6; }

    @keyframes toast-in {
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }
  </style>
</head>
<body class="font-sans antialiased flex flex-col min-h-screen relative">
  <div id="routeProgress" class="route-progress" aria-hidden="true"></div>
  <div id="pageLoader" class="page-loader" aria-live="polite" aria-label="{{ __('messages.site.loading') }}">
    <div class="loader-card">
      <div class="loader-core">
        <img src="{{ $logoPath }}" alt="{{ $siteName }}" class="loader-logo">
      </div>
      <div class="loader-text">{{ __('messages.site.loading') }}</div>
      <div id="pageLoaderHint" class="loader-subtext">Preparing an engaging experience...</div>
    </div>
  </div>
  <div id="toastWrap" class="toast-wrap" aria-live="polite" aria-atomic="true"></div>
  <div class="ambient-stage" aria-hidden="true">
    <div class="ambient-orb ambient-orb--one"></div>
    <div class="ambient-orb ambient-orb--two"></div>
    <div class="ambient-orb ambient-orb--three"></div>
  </div>


  <!-- Header -->
  <header class="glass-nav sticky top-0 z-50 transition-all duration-300 shadow-sm">
    <div class="container mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
      <!-- Logo & Branding -->
      <a href="/" class="flex items-center gap-2 sm:gap-3 group shrink-0">
        <img
          src="{{ $logoPath }}"
          alt="{{ $siteName }} Logo"
          class="h-10 w-auto sm:h-12 transition-transform duration-300 group-hover:scale-105"
        />
        <div class="hidden sm:block">
          <div class="font-serif text-base sm:text-lg font-bold text-brand-blue leading-tight">Beacons of God Ministries</div>
        </div>
      </a>


      <!-- Desktop Navigation -->
      <nav class="hidden lg:flex items-center gap-1">
        <a href="{{ $homeUrl }}" class="px-4 py-2 text-slate-700 hover:text-brand-blue font-medium transition-all duration-200 border-b-2 border-transparent hover:border-brand-blue">{{ __('messages.nav.home') }}</a>
        <a href="{{ $resourcesLink }}" class="px-4 py-2 text-slate-700 hover:text-brand-blue font-medium transition-all duration-200 border-b-2 border-transparent hover:border-brand-blue">{{ __('messages.nav.resources') }}</a>
        <a href="{{ $sermonsLink }}" class="px-4 py-2 text-slate-700 hover:text-brand-blue font-medium transition-all duration-200 border-b-2 border-transparent hover:border-brand-blue">{{ __('messages.nav.sermons') }}</a>
        <a href="{{ route('devotionals.index') }}" class="px-4 py-2 text-slate-700 hover:text-brand-blue font-medium transition-all duration-200 border-b-2 border-transparent hover:border-brand-blue">{{ __('messages.nav.devotionals') }}</a>
        <a href="{{ route('about') }}" class="px-4 py-2 text-slate-700 hover:text-brand-blue font-medium transition-all duration-200 border-b-2 border-transparent hover:border-brand-blue">{{ __('messages.nav.about') }}</a>
        <a href="{{ route('contact') }}" class="px-4 py-2 text-slate-700 hover:text-brand-blue font-medium transition-all duration-200 border-b-2 border-transparent hover:border-brand-blue">{{ __('messages.nav.contact') }}</a>
      </nav>


      <!-- CTA, Language Switcher & Mobile Menu Toggle -->
      <div class="flex items-center gap-2 sm:gap-4">
        <a href="#newslatter" class="hidden md:inline-flex items-center justify-center px-4 sm:px-6 py-2 text-xs sm:text-sm font-semibold text-white bg-blue-950 rounded-full hover:bg-blue-800 transition-all duration-200 shadow-md hover:shadow-lg hover:-translate-y-0.5">
          {{ __('messages.nav.join_ministry') }}
        </a>
        <div class="hidden sm:block">
          <select
            onchange="window.location.href=this.value"
            class="px-3 py-2 rounded-full border border-slate-200 text-xs sm:text-sm text-slate-700 bg-white"
            aria-label="Language"
          >
            <option value="{{ route('locale.switch', 'rw') }}" {{ $currentLocale === 'rw' ? 'selected' : '' }}>Kinyarwanda</option>
            <option value="{{ route('locale.switch', 'en') }}" {{ $currentLocale === 'en' ? 'selected' : '' }}>English</option>
            <option value="{{ route('locale.switch', 'fr') }}" {{ $currentLocale === 'fr' ? 'selected' : '' }}>Français</option>
          </select>
        </div>
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
        <a href="{{ $homeUrl }}" class="block px-4 py-2 text-slate-700 hover:text-brand-blue hover:bg-slate-50 rounded-lg font-medium transition-colors duration-200">{{ __('messages.nav.home') }}</a>
        <a href="{{ $resourcesLink }}" class="block px-4 py-2 text-slate-700 hover:text-brand-blue hover:bg-slate-50 rounded-lg font-medium transition-colors duration-200">{{ __('messages.nav.resources') }}</a>
        <a href="{{ $sermonsLink }}" class="block px-4 py-2 text-slate-700 hover:text-brand-blue hover:bg-slate-50 rounded-lg font-medium transition-colors duration-200">{{ __('messages.nav.sermons') }}</a>
        <a href="{{ route('devotionals.index') }}" class="block px-4 py-2 text-slate-700 hover:text-brand-blue hover:bg-slate-50 rounded-lg font-medium transition-colors duration-200">{{ __('messages.nav.devotionals') }}</a>
        <a href="{{ route('about') }}" class="block px-4 py-2 text-slate-700 hover:text-brand-blue hover:bg-slate-50 rounded-lg font-medium transition-colors duration-200">{{ __('messages.nav.about') }}</a>
        <a href="{{ route('contact') }}" class="block px-4 py-2 text-slate-700 hover:text-brand-blue hover:bg-slate-50 rounded-lg font-medium transition-colors duration-200">{{ __('messages.nav.contact') }}</a>
        <a href="#newsletter" class="block w-full mt-3 px-4 py-2 text-center text-white bg-brand-blue rounded-lg font-semibold hover:bg-blue-800 transition-colors duration-200">{{ __('messages.nav.join_ministry') }}</a>
        <div class="mt-3">
          <label class="block text-xs font-semibold uppercase tracking-wide text-slate-500 mb-1">{{ __('messages.nav.language') }}</label>
          <select
            onchange="window.location.href=this.value"
            class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm text-slate-700 bg-white"
            aria-label="Language"
          >
            <option value="{{ route('locale.switch', 'rw') }}" {{ $currentLocale === 'rw' ? 'selected' : '' }}>Kinyarwanda</option>
            <option value="{{ route('locale.switch', 'en') }}" {{ $currentLocale === 'en' ? 'selected' : '' }}>English</option>
            <option value="{{ route('locale.switch', 'fr') }}" {{ $currentLocale === 'fr' ? 'selected' : '' }}>Fran�ais</option>
          </select>
        </div>
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
  <footer class="bg-blue-950 text-slate-300 py-12 border-t border-blue-950">
    <div class="container mx-auto px-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-10">
        <!-- Brand Column -->
        <div class="col-span-1 md:col-span-1">
          <div class="flex items-center gap-2 mb-4 text-white">
            @if($siteName)
            <img src="{{$faviconPath}}" alt="">
            @else
            <i data-lucide="flame" class="w-6 h-6 text-brand-gold"></i>
            @endif
            <h4 class="text-white font-serif font-semibold mb-4">{{ $siteName }}</h4>
          </div>
          <p class="text-sm leading-relaxed text-blue-100 opacity-80">
            {{ $footerText }}
          </p>
        </div>


        <!-- Links 1 -->
        <div>
          <h4 class="text-white font-serif font-semibold mb-4">{{ __('messages.footer.ministry') }}</h4>
          <ul class="space-y-2 text-sm">
            <li><a href="{{ route('about') }}" class="hover:text-brand-gold transition-colors">{{ __('messages.footer.about_us') }}</a></li>
            <li><a href="{{ route('about') }}#faith" class="hover:text-brand-gold transition-colors">{{ __('messages.footer.faith_statement') }}</a></li>
            <li><a href="{{ route('about') }}#leadership" class="hover:text-brand-gold transition-colors">{{ __('messages.footer.leadership') }}</a></li>
            <li><a href="{{ route('contact') }}" class="hover:text-brand-gold transition-colors">{{ __('messages.footer.contact') }}</a></li>
          </ul>
        </div>


        <!-- Links 2 -->
        <div>
          <h4 class="text-white font-serif font-semibold mb-4">{{ __('messages.footer.resources') }}</h4>
          <ul class="space-y-2 text-sm">
            <li><a href="{{ route('videos.index') }}" class="hover:text-brand-gold transition-colors">{{ __('messages.footer.latest_sermons') }}</a></li>
            <li><a href="{{ route('audios.index') }}" class="hover:text-brand-gold transition-colors">{{ __('messages.footer.audio_teachings') }}</a></li>
            <li><a href="{{ route('books.index') }}" class="hover:text-brand-gold transition-colors">{{ __('messages.footer.ebooks') }}</a></li>
            <li><a href="{{ route('devotionals.index') }}" class="hover:text-brand-gold transition-colors">{{ __('messages.footer.devotionals') }}</a></li>
            <li><a href="{{ route('resources') }}" class="hover:text-brand-gold transition-colors">{{ __('messages.footer.devotionals') }}</a></li>
          </ul>
        </div>


        <!-- Contact / Social -->
        <div>
          <h4 class="text-white font-serif font-semibold mb-4">{{ __('messages.footer.connect') }}</h4>
          <ul class="space-y-3 text-sm">
            <li class="flex items-center gap-2">
              <i data-lucide="mail" class="w-4 h-4 text-brand-gold"></i>
              <span>{{ $contactEmail }}</span>
            </li>
            <li class="flex items-center gap-2">
              <i data-lucide="map-pin" class="w-4 h-4 text-brand-gold"></i>
              <span>{{ $contactAddress }}</span>
            </li>
            <li class="flex flex-wrap gap-3 mt-2">
              <a href="{{ $youtubeUrl }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-red-600/90 text-white flex items-center justify-center hover:bg-red-700 transition-colors"><i data-lucide="youtube" class="w-4 h-4"></i></a>
              <a href="{{ $facebookUrl }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-blue-600/90 text-white flex items-center justify-center hover:bg-blue-700 transition-colors"><i data-lucide="facebook" class="w-4 h-4"></i></a>
              <a href="{{ $instagramUrl }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-pink-600/90 text-white flex items-center justify-center hover:bg-pink-700 transition-colors"><i data-lucide="instagram" class="w-4 h-4"></i></a>
              <a href="{{ $twitterUrl }}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-slate-900/90 text-white flex items-center justify-center hover:bg-slate-900 transition-colors"><i data-lucide="twitter" class="w-4 h-4"></i></a>
              <a href="https://wa.me/+25{{$whatsappUrl}}" target="_blank" rel="noopener" class="w-9 h-9 rounded-full bg-emerald-500/90 text-white flex items-center justify-center hover:bg-emerald-600 transition-colors"><i data-lucide="message-circle" class="w-4 h-4"></i></a>
            </li>
          </ul>
        </div>
      </div>


      <div class="border-t border-blue-800 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-blue-200 opacity-60">
        <p>&copy; {{ date('Y') }} {{ $siteName }}. {{ __('messages.footer.rights') }}</p>
        <p class="mt-2 md:mt-0">{{ __('messages.footer.made_with_care') }}</p>
      </div>
    </div>
  </footer>

  <!-- PWA Install Button + Modal -->
  <button
    id="pwa-install-button"
    class="fixed bottom-5 right-4 sm:right-6 z-50 flex items-center justify-center gap-3 px-5 py-3 bg-white/95 text-slate-900 text-sm font-semibold rounded-full shadow-xl border border-white/60 backdrop-blur hover:-translate-y-0.5 hover:shadow-2xl transition-all"
    type="button"
  >
    <span class="flex items-center justify-center w-9 h-9 rounded-full bg-blue-900/10">
      <img src="{{ $logoPath }}" alt="{{ $siteName }}" class="w-6 h-6 object-contain">
    </span>
    <span>{{ __('messages.site.install_app') }}</span>
  </button>
  <div id="pwa-install-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-slate-900/70 px-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl">
      <div class="flex items-start justify-between gap-4 mb-4">
        <div class="flex items-center gap-3">
          <div class="w-14 h-14 rounded-2xl bg-blue-900/10 flex items-center justify-center">
            <img src="{{ $logoPath }}" alt="{{ $siteName }}" class="w-9 h-9 object-contain">
          </div>
          <div>
            <h3 class="text-lg sm:text-xl font-semibold text-slate-900">{{ __('messages.site.install_title') }}</h3>
            <p class="text-xs text-slate-500">Beacons of God Ministries</p>
          </div>
        </div>
        <button id="pwa-install-close" class="text-slate-400 hover:text-slate-900" type="button" aria-label="Close">
          <svg viewBox="0 0 24 24" class="w-5 h-5" aria-hidden="true"><path fill="currentColor" d="M18.3 5.71L12 12l6.3 6.29-1.41 1.42L10.59 13.4 4.29 19.71 2.88 18.3 9.17 12 2.88 5.71 4.29 4.29 10.59 10.6l6.3-6.31z"/></svg>
        </button>
      </div>
      <p class="text-sm text-slate-600 mb-5" id="pwa-install-copy">
        {{ __('messages.site.install_prompt', ['name' => $siteName]) }}
      </p>
      <p class="hidden text-xs text-slate-500 mb-5" id="pwa-install-ios">
        On iPhone/iPad: tap Share, then “Add to Home Screen”.
      </p>
      <p class="hidden text-xs text-slate-500 mb-5" id="pwa-install-desktop">
        On desktop Chrome/Edge: open the browser menu (⋮) and choose “Install app”.
      </p>
      <div class="grid gap-3 mb-6 sm:grid-cols-2">
        <div class="flex items-start gap-3 rounded-2xl border border-slate-100 bg-slate-50 px-3 py-3">
          <span class="text-blue-900 text-sm font-semibold">1</span>
          <div>
            <div class="text-sm font-semibold text-slate-900">Instant Access</div>
            <div class="text-xs text-slate-500">Open sermons, books, and devotionals faster.</div>
          </div>
        </div>
        <div class="flex items-start gap-3 rounded-2xl border border-slate-100 bg-slate-50 px-3 py-3">
          <span class="text-blue-900 text-sm font-semibold">2</span>
          <div>
            <div class="text-sm font-semibold text-slate-900">App-like Experience</div>
            <div class="text-xs text-slate-500">Full screen, smooth navigation, saved session.</div>
          </div>
        </div>
      </div>
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-end gap-2">
        <button id="pwa-install-later" class="px-4 py-2 text-sm rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50" type="button">
          {{ __('messages.site.later') }}
        </button>
        <button id="pwa-install-now" class="px-4 py-2 text-sm rounded-lg bg-blue-900 text-white hover:bg-blue-800" type="button">
          {{ __('messages.site.install') }}
        </button>
      </div>
    </div>
  </div>


  <!-- Scroll Animation Trigger Script -->
  <script>
    (() => {
      const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      const revealElements = [];

      const pushReveal = (element, delayMs = 0, direction = 'up') => {
        if (!element || element.dataset.revealPrepared === '1') return;
        element.dataset.revealPrepared = '1';
        element.setAttribute('data-reveal', direction);
        element.style.setProperty('--reveal-delay', `${delayMs}ms`);
        revealElements.push(element);
      };

      document.querySelectorAll('.scroll-animate, [data-reveal]').forEach((element, index) => {
        pushReveal(element, Math.min((index % 6) * 70, 350));
      });

      document.querySelectorAll('main section').forEach((section) => {
        section.querySelectorAll('h1, h2').forEach((element, index) => {
          pushReveal(element, index * 60, index % 2 === 0 ? 'left' : 'right');
        });
        section.querySelectorAll('.bg-white.rounded-2xl, .bg-white.rounded-xl, article.rounded-2xl').forEach((element, index) => {
          pushReveal(element, Math.min((index % 6) * 80, 400), 'up');
          element.classList.add('interactive-card');
        });
      });

      if (!reducedMotion) {
        const revealObserver = new IntersectionObserver((entries) => {
          entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible');
            revealObserver.unobserve(entry.target);
          });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

        revealElements.forEach((element) => revealObserver.observe(element));
      } else {
        revealElements.forEach((element) => element.classList.add('is-visible'));
      }

      // Ripple micro-interaction for CTA/button surfaces.
      document.querySelectorAll('.ripple-container, button, a[class*="rounded"]').forEach((element) => {
        element.addEventListener('click', (event) => {
          if (reducedMotion) return;
          const rect = element.getBoundingClientRect();
          const ripple = document.createElement('span');
          const size = Math.max(rect.width, rect.height);
          ripple.className = 'ripple';
          ripple.style.width = `${size}px`;
          ripple.style.height = `${size}px`;
          ripple.style.left = `${event.clientX - rect.left - size / 2}px`;
          ripple.style.top = `${event.clientY - rect.top - size / 2}px`;
          element.classList.add('ripple-container');
          element.appendChild(ripple);
          setTimeout(() => ripple.remove(), 620);
        }, { passive: true });
      });

      // Lucide icon initialization
      if (typeof lucide !== 'undefined') {
        lucide.createIcons();
      }
    })();
  </script>
  <script>
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
        navigator.serviceWorker.register('{{ asset('sw.js') }}')
          .catch(() => {});
      });
    }
  </script>
  @if ($tawkEnabled && $tawkProperty && $tawkWidget)
    <script>
      var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
      (function(){
        var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
        s1.async = true;
        s1.src = "https://embed.tawk.to/{{ $tawkProperty }}/{{ $tawkWidget }}";
        s1.charset = "UTF-8";
        s1.setAttribute("crossorigin", "*");
        s0.parentNode.insertBefore(s1, s0);
      })();
    </script>
  @endif
  <script>
    let deferredPrompt = null;
    const installButton = document.getElementById('pwa-install-button');
    const installModal = document.getElementById('pwa-install-modal');
    const installNow = document.getElementById('pwa-install-now');
    const installLater = document.getElementById('pwa-install-later');
    const installClose = document.getElementById('pwa-install-close');
    const iosHint = document.getElementById('pwa-install-ios');
    const desktopHint = document.getElementById('pwa-install-desktop');
    const genericCopy = document.getElementById('pwa-install-copy');
    const isIos = /iphone|ipad|ipod/i.test(navigator.userAgent || '');
    const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone;

    const showInstallButton = () => installButton?.classList.remove('hidden');
    const hideInstallButton = () => installButton?.classList.add('hidden');

    window.addEventListener('beforeinstallprompt', (event) => {
      event.preventDefault();
      deferredPrompt = event;
      showInstallButton();
    });

    function hideInstallModal() {
      if (installModal) {
        installModal.classList.add('hidden');
        installModal.classList.remove('flex');
      }
    }

    function showInstallModal() {
      if (installModal) {
        installModal.classList.remove('hidden');
        installModal.classList.add('flex');
      }
    }

    if (installButton) {
      installButton.addEventListener('click', () => {
        if (!deferredPrompt) {
          if (isIos) {
            iosHint?.classList.remove('hidden');
            desktopHint?.classList.add('hidden');
          } else {
            desktopHint?.classList.remove('hidden');
            iosHint?.classList.add('hidden');
          }
          genericCopy?.classList.add('hidden');
          installNow?.classList.add('hidden');
        }
        showInstallModal();
      });
    }

    [data-tap-reveal].is-active .tap-overlay,
    [data-tap-reveal]:focus-within .tap-overlay {
      opacity: 1 !important;
      transform: translateY(0) !important;
    }
    if (installLater) {
      installLater.addEventListener('click', hideInstallModal);
    }
    if (installClose) {
      installClose.addEventListener('click', hideInstallModal);
    }
    if (installModal) {
      installModal.addEventListener('click', (event) => {
        if (event.target === installModal) {
          hideInstallModal();
        }
      });
    }
    if (installNow) {
      installNow.addEventListener('click', async () => {
        if (!deferredPrompt) return;
        deferredPrompt.prompt();
        await deferredPrompt.userChoice;
        deferredPrompt = null;
        if (installButton) {
          installButton.classList.add('hidden');
        }
        hideInstallModal();
      });
    }

    if (isStandalone) {
      hideInstallButton();
    } else {
      showInstallButton();
      if (isIos) {
        iosHint?.classList.remove('hidden');
        genericCopy?.classList.add('hidden');
        installNow?.classList.add('hidden');
      }
    }
  </script>
  <script>
    (() => {
      const endpoint = @json(route('content.audience.track'));
      const routeName = @json(optional(request()->route())->getName());
      const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
      const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
      const sessionIdKey = 'bgm_audience_session_id';
      const visitorIdKey = 'bgm_audience_visitor_id';
      const sessionStartedKey = 'bgm_audience_session_started';
      const scrollTrackedKey = 'bgm_audience_scroll_steps';
      let engagedSeconds = 0;
      let sessionEnded = false;

      const makeId = (prefix) => `${prefix}_${Math.random().toString(36).slice(2)}_${Date.now()}`;
      const getOrCreateStorage = (storage, key, prefix) => {
        let value = storage.getItem(key);
        if (!value) {
          value = makeId(prefix);
          storage.setItem(key, value);
        }
        return value;
      };

      const visitorId = getOrCreateStorage(localStorage, visitorIdKey, 'v');
      const sessionId = getOrCreateStorage(sessionStorage, sessionIdKey, 's');
      const url = new URL(window.location.href);
      const metrics = () => {
        const width = window.innerWidth || 0;
        let deviceType = 'unknown';
        if (width < 768) deviceType = 'mobile';
        else if (width < 1024) deviceType = 'tablet';
        else deviceType = 'desktop';

        return {
          visitor_id: visitorId,
          session_id: sessionId,
          route_name: routeName,
          page_url: window.location.href,
          referrer: document.referrer || null,
          utm_source: url.searchParams.get('utm_source'),
          utm_medium: url.searchParams.get('utm_medium'),
          utm_campaign: url.searchParams.get('utm_campaign'),
          utm_term: url.searchParams.get('utm_term'),
          utm_content: url.searchParams.get('utm_content'),
          screen_width: window.screen?.width || null,
          screen_height: window.screen?.height || null,
          timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
          language: navigator.language || null,
          platform: navigator.platform || null,
          device_type: deviceType,
        };
      };

      const send = (eventType, extra = {}, beacon = false) => {
        const payload = { event_type: eventType, ...metrics(), ...extra };
        if (beacon && navigator.sendBeacon) {
          const form = new FormData();
          form.append('_token', csrf);
          Object.entries(payload).forEach(([key, value]) => {
            if (value !== null && typeof value !== 'undefined') {
              form.append(key, String(value));
            }
          });
          navigator.sendBeacon(endpoint, form);
          return;
        }

        fetch(endpoint, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrf,
          },
          body: JSON.stringify(payload),
          keepalive: true,
        }).catch(() => {});
      };

      const markSessionStart = () => {
        if (sessionStorage.getItem(sessionStartedKey) === '1') return;
        send('session_start');
        sessionStorage.setItem(sessionStartedKey, '1');
      };

      const trackPageView = () => send('page_view');

      const trackScrollDepth = () => {
        const doc = document.documentElement;
        const scrollable = Math.max(doc.scrollHeight - window.innerHeight, 1);
        const progress = Math.round((window.scrollY / scrollable) * 100);
        const steps = [25, 50, 75, 100];
        const seen = new Set((sessionStorage.getItem(scrollTrackedKey) || '').split(',').filter(Boolean));

        steps.forEach((step) => {
          if (progress < step || seen.has(String(step))) return;
          send('scroll_depth', { scroll_depth: step });
          seen.add(String(step));
        });

        sessionStorage.setItem(scrollTrackedKey, Array.from(seen).join(','));
      };

      const endSession = () => {
        if (sessionEnded) return;
        sessionEnded = true;
        send('session_end', { engaged_seconds: engagedSeconds }, true);
      };

      if (!reducedMotion) {
        window.addEventListener('scroll', trackScrollDepth, { passive: true });
      }

      document.addEventListener('click', (event) => {
        const target = event.target.closest('a, button');
        if (!target) return;
        const className = String(target.className || '');
        const looksLikeCta = target.hasAttribute('data-cta') || /bg-|btn|rounded-full|rounded-lg/.test(className);
        if (!looksLikeCta) return;
        const label = (target.getAttribute('data-cta') || target.textContent || '').trim().slice(0, 180);
        const href = target.getAttribute('href');
        send('cta_click', {
          cta_label: label || 'unknown',
          cta_target: href || window.location.href,
        });
      }, { passive: true });

      setInterval(() => {
        if (document.visibilityState === 'visible') {
          engagedSeconds += 5;
        }
      }, 5000);

      document.addEventListener('visibilitychange', () => {
        if (document.visibilityState === 'hidden') {
          endSession();
        }
      });

      window.addEventListener('beforeunload', endSession);
      window.addEventListener('pagehide', endSession);

      markSessionStart();
      trackPageView();
    })();
  </script>
  <script>
    (() => {
      const pageLoader = document.getElementById('pageLoader');
      const pageLoaderHint = document.getElementById('pageLoaderHint');
      const progress = document.getElementById('routeProgress');
      const toastWrap = document.getElementById('toastWrap');
      let loaderHintTimer = null;

      const hintMessages = [
        'Preparing an engaging experience...',
        'Loading content and media...',
        'Almost ready...'
      ];
      let hintIndex = 0;

      if (pageLoaderHint) {
        loaderHintTimer = setInterval(() => {
          hintIndex = (hintIndex + 1) % hintMessages.length;
          pageLoaderHint.textContent = hintMessages[hintIndex];
        }, 1900);
      }

      const hideLoader = () => {
        if (!pageLoader) return;
        pageLoader.classList.add('hidden');
        if (loaderHintTimer) {
          clearInterval(loaderHintTimer);
          loaderHintTimer = null;
        }
      };

      let loaderFailSafe = setTimeout(hideLoader, 3500);

      const startProgress = () => {
        if (!progress) return;
        progress.style.width = '18%';
        requestAnimationFrame(() => {
          progress.style.width = '78%';
        });
      };

      const finishProgress = () => {
        if (!progress) return;
        progress.style.width = '100%';
        setTimeout(() => { progress.style.width = '0%'; }, 280);
      };

      const showToast = (message, type = 'info', timeout = 3800) => {
        if (!toastWrap || !message) return;
        const node = document.createElement('div');
        node.className = `toast-item ${type}`;
        node.textContent = String(message);
        toastWrap.appendChild(node);

        setTimeout(() => {
          node.style.opacity = '0';
          node.style.transform = 'translateY(-6px)';
          setTimeout(() => node.remove(), 220);
        }, timeout);
      };

      window.appToast = showToast;

      document.querySelectorAll('a[href]').forEach((link) => {
        link.addEventListener('click', (event) => {
          if (event.defaultPrevented) return;
          const href = link.getAttribute('href') || '';
          const target = link.getAttribute('target');
          const isHash = href.startsWith('#');
          const isJs = href.startsWith('javascript:');
          const isExternal = /^https?:\/\//i.test(href) && !href.startsWith(window.location.origin);

          if (target === '_blank' || isHash || isJs || isExternal) return;
          startProgress();
          if (pageLoader) pageLoader.classList.remove('hidden');
          setTimeout(hideLoader, 4500);
        }, { passive: true });
      });

      const tapCards = Array.from(document.querySelectorAll('[data-tap-reveal]'));
      const clearActive = () => tapCards.forEach((card) => card.classList.remove('is-active'));

      tapCards.forEach((card) => {
        card.addEventListener('touchstart', (event) => {
          const isActive = card.classList.contains('is-active');
          clearActive();
          if (!isActive) {
            card.classList.add('is-active');
            event.stopPropagation();
          }
        }, { passive: true });
      });

      document.addEventListener('touchstart', clearActive, { passive: true });

      const mediaSelector = 'img, iframe, audio, video';
      document.querySelectorAll(mediaSelector).forEach((media) => {
        const markDone = () => media.classList.remove('media-skeleton');
        media.classList.add('media-skeleton');

        if (media.tagName === 'IMG') {
          if (media.complete) {
            markDone();
          } else {
            media.addEventListener('load', markDone, { once: true });
            media.addEventListener('error', markDone, { once: true });
          }
          return;
        }

        media.addEventListener('loadeddata', markDone, { once: true });
        media.addEventListener('canplay', markDone, { once: true });
        media.addEventListener('load', markDone, { once: true });
        media.addEventListener('error', markDone, { once: true });
      });

      window.addEventListener('DOMContentLoaded', () => {
        hideLoader();
        finishProgress();
      });

      window.addEventListener('load', () => {
        hideLoader();
        finishProgress();
        if (loaderFailSafe) {
          clearTimeout(loaderFailSafe);
          loaderFailSafe = null;
        }
      });

      @if (session('status'))
        showToast(@json(session('status')), 'success');
      @endif

      @if (session('error'))
        showToast(@json(session('error')), 'error');
      @endif

      @if ($errors->any())
        showToast(@json($errors->first()), 'error');
      @endif
    })();
  </script>
  <script>
    (() => {
      const getHeaderOffset = () => {
        const header = document.querySelector('header.glass-nav');
        return (header?.offsetHeight || 0) + 12;
      };

      const scrollToHash = (hash, smooth = true) => {
        if (!hash || hash === '#') return;
        const id = hash.replace('#', '');
        const target = document.getElementById(id);
        if (!target) return;

        const top = target.getBoundingClientRect().top + window.pageYOffset - getHeaderOffset();
        window.scrollTo({ top: Math.max(top, 0), behavior: smooth ? 'smooth' : 'auto' });
      };

      window.addEventListener('load', () => {
        if (window.location.hash) {
          scrollToHash(window.location.hash, false);
        }
      });

      window.addEventListener('hashchange', () => {
        scrollToHash(window.location.hash, true);
      });
    })();
  </script>


</body>
</html>








