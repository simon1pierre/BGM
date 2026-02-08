@php
    $title = 'Maintenance Mode';
    $message = 'We are performing scheduled maintenance. Please check back soon.';
    $eyebrow = '503 Error';
@endphp

@extends('errors.layout')

@section('content')
    <div class="text-sm text-slate-600">
        The system is currently undergoing maintenance to improve reliability and performance.
    </div>
    @include('errors.partials.admin-details')
@endsection
