@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Video Preview</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.videos.index') }}">Videos</a></li>
                    <li class="breadcrumb-item">Preview</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <a href="{{ route('admin.videos.edit', $video) }}" class="btn btn-primary">Edit Video</a>
            </div>
        </div>

        <div class="main-content">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-2">{{ $video->title }}</h4>
                    <p class="text-muted">{{ $video->description ?? 'No description provided.' }}</p>

                    @if ($video->youtube_id)
                        <div class="ratio ratio-16x9 mb-4">
                            <iframe src="https://www.youtube.com/embed/{{ $video->youtube_id }}" title="{{ $video->title }}" allowfullscreen></iframe>
                        </div>
                    @else
                        <div class="alert alert-warning">No YouTube ID found for this video.</div>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="fw-semibold">Speaker</div>
                            <div class="text-muted">{{ $video->speaker ?? '—' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="fw-semibold">Series</div>
                            <div class="text-muted">{{ $video->series ?? '—' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="fw-semibold">Published</div>
                            <div class="text-muted">{{ $video->published_at?->toDateString() ?? 'Draft' }}</div>
                        </div>
                    </div>

                    @if ($video->thumbnail_url)
                        <div class="mt-4">
                            <div class="fw-semibold mb-2">Thumbnail</div>
                            <img src="{{ $video->thumbnail_url }}" alt="thumbnail" class="rounded" style="max-width: 360px; width: 100%;">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
