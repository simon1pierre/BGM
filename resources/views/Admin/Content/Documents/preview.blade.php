@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Document Preview</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Documents</a></li>
                    <li class="breadcrumb-item">Preview</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <a href="{{ route('admin.documents.edit', $document) }}" class="btn btn-primary">Edit Document</a>
            </div>
        </div>

        <div class="main-content">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-2">{{ $document->title }}</h4>
                    <p class="text-muted">{{ $document->description ?? 'No description provided.' }}</p>

                    <div class="ratio ratio-4x3 mb-4">
                        <iframe src="{{ asset('storage/'.$document->file_path) }}" title="{{ $document->title }}"></iframe>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="fw-semibold">Author</div>
                            <div class="text-muted">{{ $document->author ?? '—' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="fw-semibold">Category</div>
                            <div class="text-muted">{{ $document->category?->name ?? '—' }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="fw-semibold">Published</div>
                            <div class="text-muted">{{ $document->published_at?->toDateString() ?? 'Draft' }}</div>
                        </div>
                    </div>

                    <div class="row g-3 mt-2">
                        <div class="col-md-4">
                            <div class="fw-semibold">Downloads</div>
                            <div class="text-muted">{{ $document->download_count ?? 0 }}</div>
                        </div>
                        <div class="col-md-8">
                            <div class="fw-semibold">File</div>
                            <a href="{{ asset('storage/'.$document->file_path) }}" target="_blank" class="text-decoration-none">
                                Open PDF in new tab
                            </a>
                        </div>
                    </div>

                    @if ($document->cover_image)
                        <div class="mt-4">
                            <div class="fw-semibold mb-2">Cover Image</div>
                            <img src="{{ asset('storage/'.$document->cover_image) }}" alt="cover" class="rounded" style="max-width: 360px; width: 100%;">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection








