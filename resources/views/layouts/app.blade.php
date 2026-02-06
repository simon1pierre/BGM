<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Beacons of God Ministries')</title>
  <meta name="description" content="@yield('description', 'Beacons of God Ministries - Spiritual guidance and biblical teaching')">
  <meta property="og:title" content="@yield('title', 'Beacons of God Ministries')">
  <meta property="og:type" content="website">
  
  <!-- Favicon -->
  <link rel="icon" type="image/png" sizes="32x32" href="/favicon.ico">
  <link rel="apple-touch-icon" href="/apple-touch-icon.png">
  <link rel="manifest" href="/site.webmanifest">

  <!-- Tailwind CSS (CDN - Replace with production build) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    window.tailwind = window.tailwind || {};
    window.tailwind.config = {
      theme: {
        extend: {
          colors: {
            'brand-blue': '#0f2b5e',
            'brand-gold': '#d4af37',
            'brand-off-white': '#f8fafc',
          }
        }
      }
    };
  </script>

  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>

  <!-- Component Styles -->
  <link rel="stylesheet" href="{{ asset('css/components.css') }}">

  @yield('extra-styles')
</head>
<body class="bg-white text-slate-900">
  @include('components.header')

  <main>
    @yield('content')
  </main>

  @include('components.footer')

  <!-- Lucide Icons Initialization -->
  <script>
    lucide.createIcons();
  </script>

  @yield('extra-scripts')
</body>
</html>
