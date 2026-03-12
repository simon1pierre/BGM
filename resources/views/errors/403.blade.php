@php
    $title = 'Access Forbidden';
    $message = 'You do not have permission to access this page.';
    $eyebrow = '403 Error';
@endphp

@extends('errors.layout')

@section('content')
    <div class="text-sm text-slate-600">
        If you think you should have access, please contact the administrator to verify your permissions.
    </div>
    @include('errors.partials.admin-details')
@endsection

