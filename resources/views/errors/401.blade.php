@php
    $title = 'Unauthorized';
    $message = 'You must be signed in to access this page.';
    $eyebrow = '401 Error';
@endphp

@extends('errors.layout')

@section('content')
    <div class="text-sm text-slate-600">
        Please sign in to continue. If you believe this is a mistake, contact the administrator.
    </div>
    @include('errors.partials.admin-details')
@endsection







