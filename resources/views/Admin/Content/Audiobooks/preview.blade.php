@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Audiobook Preview</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.audiobooks.index') }}">Audiobooks</a></li>
                    <li class="breadcrumb-item">Preview</li>
                </ul>
            </div>
        </div>
        <div class="main-content">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-2">{{ $audiobook->title }}</h4>
                    <p class="text-muted mb-3">{{ $audiobook->description }}</p>
                    <audio controls class="w-100">
                        <source src="{{ asset('storage/'.$audiobook->audio_file) }}" type="audio/mpeg">
                    </audio>
                    @if($audiobook->linkedBook)
                        <div class="mt-3">Linked Book: <strong>{{ $audiobook->linkedBook->title }}</strong></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
