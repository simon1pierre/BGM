@php
    $title = 'Session Expired';
    $message = 'Your session has expired. Please refresh and try again.';
    $eyebrow = '419 Error';
@endphp

@extends('errors.layout')

@section('content')
    <div class="text-sm text-slate-600">
        Your session expired or the form token is invalid. Please go back and resubmit the form.
    </div>
    @include('errors.partials.admin-details')
@endsection

