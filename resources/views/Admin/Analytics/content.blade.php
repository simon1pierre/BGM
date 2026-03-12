@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Content Insights</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.analytics.index') }}">Analytics</a></li>
                    <li class="breadcrumb-item">Content</li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            <div class="row">
                <div class="col-xxl-4">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Top Videos (Views)</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                @forelse ($topVideos as $video)
                                    <li class="mb-3">
                                        <div class="fw-semibold text-dark">{{ $video->title }}</div>
                                        <div class="fs-12 text-muted">Views: {{ $video->view_count ?? 0 }}</div>
                                    </li>
                                @empty
                                    <li class="text-muted fs-12">No video data.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Top Audios (Plays)</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                @forelse ($topAudios as $row)
                                    <li class="mb-3">
                                        <div class="fw-semibold text-dark">{{ $row->audio?->title ?? 'Unknown' }}</div>
                                        <div class="fs-12 text-muted">Plays: {{ $row->total }}</div>
                                    </li>
                                @empty
                                    <li class="text-muted fs-12">No audio data.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Top Books (Reads)</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                @forelse ($topBooks as $row)
                                    <li class="mb-3">
                                        <div class="fw-semibold text-dark">{{ $row->book?->title ?? 'Unknown' }}</div>
                                        <div class="fs-12 text-muted">Reads: {{ $row->total }}</div>
                                    </li>
                                @empty
                                    <li class="text-muted fs-12">No book data.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection








