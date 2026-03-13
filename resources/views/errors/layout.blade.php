<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Error' }} - {{ config('app.name', 'Beacons of God Ministries') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            blue: '#0f2b5e',
                            gold: '#d4af37',
                            light: '#f8fafc'
                        }
                    },
                    fontFamily: {
                        serif: ['\"Playfair Display\"', 'serif'],
                        sans: ['\"Lato\"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-brand-light text-slate-800 font-sans min-h-screen">
    <main class="min-h-screen flex items-center justify-center px-6 py-16">
        <div class="max-w-4xl w-full bg-white shadow-xl rounded-3xl border border-slate-100 overflow-hidden">
            <div class="bg-gradient-to-br from-brand-blue to-slate-900 text-white p-8 md:p-10">
                <div class="text-sm uppercase tracking-widest text-blue-200">{{ $eyebrow ?? 'System Message' }}</div>
                <h1 class="text-3xl md:text-4xl font-serif font-bold mt-2">{{ $title ?? 'Something went wrong' }}</h1>
                <p class="text-blue-100 mt-3">{{ $message ?? 'An unexpected error occurred.' }}</p>
            </div>
            <div class="p-8 md:p-10 space-y-6">
                @yield('content')
                <div class="flex flex-col md:flex-row gap-3">
                    <a href="{{ url('/') }}" class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-brand-blue text-white font-semibold hover:bg-blue-800 transition-colors">
                        Go Home
                    </a>
                    <a href="{{ route('videos.index') }}" class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-white border border-slate-200 text-slate-700 font-semibold hover:bg-slate-50 transition-colors">
                        Browse Videos
                    </a>
                    <a href="{{ route('books.index') }}" class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-white border border-slate-200 text-slate-700 font-semibold hover:bg-slate-50 transition-colors">
                        Browse Books
                    </a>
                </div>
                <div class="text-xs text-slate-400">If you keep seeing this page, please contact the site administrator.</div>
            </div>
        </div>
    </main>
</body>
</html>







