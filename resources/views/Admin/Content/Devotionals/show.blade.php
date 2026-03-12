@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">View Devotional</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.devotionals.index') }}">Devotionals</a></li>
                    <li class="breadcrumb-item">View</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto d-flex gap-2">
                <form method="POST" action="{{ route('admin.devotionals.toggle-published', $devotional) }}">
                    @csrf
                    <button class="btn btn-light">{{ $devotional->is_published ? 'Set Draft' : 'Publish' }}</button>
                </form>
                <form method="POST" action="{{ route('admin.devotionals.toggle-featured', $devotional) }}">
                    @csrf
                    <button class="btn {{ $devotional->featured ? 'btn-warning' : 'btn-outline-warning' }}">{{ $devotional->featured ? 'Highlighted' : 'Highlight' }}</button>
                </form>
                <a href="{{ route('admin.devotionals.edit', $devotional) }}" class="btn btn-primary">Edit</a>
            </div>
        </div>

        <div class="main-content">
            <div class="card">
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-lg-4">
                            @if ($devotional->cover_image_url)
                                <img src="{{ $devotional->cover_image_url }}" alt="{{ $devotional->title }}" class="w-100 rounded" style="max-height: 320px; object-fit: cover;">
                            @endif
                            <div class="mt-3">
                                <span class="badge {{ $devotional->is_published ? 'bg-success' : 'bg-secondary' }}">{{ $devotional->is_published ? 'Published' : 'Draft' }}</span>
                                @if ($devotional->featured)
                                    <span class="badge bg-warning text-dark ms-2">Highlighted</span>
                                @endif
                            </div>
                            <div class="small text-muted mt-3">
                                <div><strong>Reference:</strong> {{ $devotional->scripture_reference ?: '-' }}</div>
                                <div><strong>Author:</strong> {{ $devotional->author ?: '-' }}</div>
                                <div><strong>Publish Date:</strong> {{ optional($devotional->published_at)->format('Y-m-d H:i') ?: '-' }}</div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <h3 class="mb-2">{{ $devotional->title }}</h3>
                            @if ($devotional->excerpt)
                                <p class="text-muted">{{ $devotional->excerpt }}</p>
                            @endif
                            <div class="p-3 bg-light rounded" style="white-space: pre-wrap;">{{ $devotional->body }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection








