@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Audiobooks</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Audiobooks</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <a href="{{ route('admin.audiobooks.create') }}" class="btn btn-primary">
                    <i class="feather-plus me-2"></i>
                    Add Audiobook
                </a>
            </div>
        </div>

        <div class="main-content">
            @if (session('status'))
                <div class="alert alert-success mb-4">{{ session('status') }}</div>
            @endif

            <livewire:admin.content-table type="audiobooks" />
        </div>
    </div>
@endsection
