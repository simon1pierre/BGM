@php
    $title = 'Server Error';
    $message = 'An internal error occurred. Our team has been notified.';
    $eyebrow = '500 Error';
@endphp

@extends('errors.layout')

@section('content')
    <div class="text-sm text-slate-600">
        Please try again in a few minutes. If the issue persists, contact the administrator.
    </div>
    @include('errors.partials.admin-details')
@endsection

