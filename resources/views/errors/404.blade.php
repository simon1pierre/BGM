@php
    $title = 'Page Not Found';
    $message = 'The page you are looking for may have been moved or removed.';
    $eyebrow = '404 Error';
@endphp

@extends('errors.layout')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h2 class="text-lg font-semibold text-slate-800 mb-2">Try this</h2>
            <ul class="text-sm text-slate-600 space-y-2">
                <li>Check the URL for typos.</li>
                <li>Return to the homepage and browse resources.</li>
                <li>Use the libraries to find videos, books, or audios.</li>
            </ul>
        </div>
        <div class="bg-slate-50 rounded-xl p-4 text-sm text-slate-600">
            <div class="font-semibold text-slate-700 mb-2">Need help?</div>
            If you believe this page should exist, please contact an administrator and provide the URL you attempted to access.
        </div>
    </div>
@endsection







