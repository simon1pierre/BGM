@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Audio Preview</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.audios.index') }}">Audios</a></li>
                    <li class="breadcrumb-item">Preview</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <a href="{{ route('admin.audios.edit', $audio) }}" class="btn btn-primary">Edit Audio</a>
            </div>
        </div>

        <div class="main-content">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-2">{{ $audio->title }}</h4>
                    <p class="text-muted">{{ $audio->description ?? 'No description provided.' }}</p>

                    <audio controls class="w-100 mb-4">
                        <source src="{{ asset('storage/'.$audio->audio_file) }}" type="audio/mpeg">
                    </audio>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="fw-semibold">Speaker</div>
                            <div class="text-muted">{{ $audio->speaker ?? '—' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="fw-semibold">Series</div>
                            <div class="text-muted">{{ $audio->series ?? '—' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="fw-semibold">Duration</div>
                            <div class="text-muted">{{ $audio->duration ?? '—' }}</div>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-4">
                            <div class="fw-semibold">Published</div>
                            <div class="text-muted">{{ $audio->published_at?->toDateString() ?? 'Draft' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="fw-semibold">Downloads</div>
                            <div class="text-muted">{{ $audio->download_count ?? 0 }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="fw-semibold">Plays</div>
                            <div class="text-muted">{{ $audio->play_count ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
