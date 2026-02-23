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
                    <li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Books</a></li>
                    <li class="breadcrumb-item">Preview</li>
                </ul>
            </div>
        </div>
        <div class="main-content">
            <div class="card">
                <div class="card-body">
                    @php
                        $previewAudio = $audiobook->resolvePlayableAudioFile();
                    @endphp
                    <h4 class="mb-2">{{ $audiobook->title }}</h4>
                    <p class="text-muted mb-3">{{ $audiobook->description }}</p>
                    @if ($previewAudio)
                        <audio controls class="w-100">
                            <source src="{{ asset('storage/'.$previewAudio) }}" type="audio/mpeg">
                        </audio>
                    @else
                        <div class="alert alert-warning mb-0">No playable audio is available yet. Add a main audio file or at least one part.</div>
                    @endif
                    @if($audiobook->linkedBook)
                        <div class="mt-3">Linked Book: <strong>{{ $audiobook->linkedBook->title }}</strong></div>
                        <div class="mt-3">
                            <a href="{{ route('admin.documents.edit', $audiobook->linkedBook->id) }}" class="btn btn-light btn-sm">Back to Book</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
