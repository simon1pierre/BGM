@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Ministry Overview</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Dashboard</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.videos.create') }}" class="btn btn-primary">
                        <i class="feather-plus me-2"></i>
                        Add Video
                    </a>
                    <a href="{{ route('admin.audios.create') }}" class="btn btn-light-brand">
                        <i class="feather-plus me-2"></i>
                        Add Audio
                    </a>
                    <a href="{{ route('admin.documents.create') }}" class="btn btn-light-brand">
                        <i class="feather-plus me-2"></i>
                        Add Document
                    </a>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="row">
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between mb-4">
                                <div class="d-flex gap-4 align-items-center">
                                    <div class="avatar-text avatar-lg bg-gray-200">
                                        <i class="feather-film"></i>
                                    </div>
                                    <div>
                                        <div class="fs-4 fw-bold text-dark">{{ $videoCount }}</div>
                                        <h3 class="fs-13 fw-semibold text-truncate-1-line">Videos</h3>
                                    </div>
                                </div>
                                <a href="{{ route('admin.videos.index') }}"><i class="feather-arrow-right"></i></a>
                            </div>
                            <div class="fs-12 text-muted">Sermons and messages</div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between mb-4">
                                <div class="d-flex gap-4 align-items-center">
                                    <div class="avatar-text avatar-lg bg-gray-200">
                                        <i class="feather-mic"></i>
                                    </div>
                                    <div>
                                        <div class="fs-4 fw-bold text-dark">{{ $audioCount }}</div>
                                        <h3 class="fs-13 fw-semibold text-truncate-1-line">Audios</h3>
                                    </div>
                                </div>
                                <a href="{{ route('admin.audios.index') }}"><i class="feather-arrow-right"></i></a>
                            </div>
                            <div class="fs-12 text-muted">Teachings and devotionals</div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between mb-4">
                                <div class="d-flex gap-4 align-items-center">
                                    <div class="avatar-text avatar-lg bg-gray-200">
                                        <i class="feather-book-open"></i>
                                    </div>
                                    <div>
                                        <div class="fs-4 fw-bold text-dark">{{ $documentCount }}</div>
                                        <h3 class="fs-13 fw-semibold text-truncate-1-line">Documents</h3>
                                    </div>
                                </div>
                                <a href="{{ route('admin.documents.index') }}"><i class="feather-arrow-right"></i></a>
                            </div>
                            <div class="fs-12 text-muted">PDF guides and study notes</div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between mb-4">
                                <div class="d-flex gap-4 align-items-center">
                                    <div class="avatar-text avatar-lg bg-gray-200">
                                        <i class="feather-users"></i>
                                    </div>
                                    <div>
                                        <div class="fs-4 fw-bold text-dark">{{ $subscriberCount }}</div>
                                        <h3 class="fs-13 fw-semibold text-truncate-1-line">Subscribers</h3>
                                    </div>
                                </div>
                                <a href="{{ route('admin.subscribers.index') }}"><i class="feather-arrow-right"></i></a>
                            </div>
                            <div class="fs-12 text-muted">Newsletter community</div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between mb-4">
                                <div class="d-flex gap-4 align-items-center">
                                    <div class="avatar-text avatar-lg bg-gray-200">
                                        <i class="feather-download"></i>
                                    </div>
                                    <div>
                                        <div class="fs-4 fw-bold text-dark">{{ $totalDownloads }}</div>
                                        <h3 class="fs-13 fw-semibold text-truncate-1-line">Total Downloads</h3>
                                    </div>
                                </div>
                                <span class="text-muted fs-12">All time</span>
                            </div>
                            <div class="fs-12 text-muted">Audio and document downloads</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-4">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Latest Videos</h5>
                            <a href="{{ route('admin.videos.index') }}" class="btn btn-sm btn-light">Manage</a>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                @forelse ($latestVideos as $video)
                                    <li class="mb-3">
                                        <div class="fw-semibold text-dark">{{ $video->title }}</div>
                                        <div class="fs-12 text-muted">{{ $video->published_at?->toDateString() ?? 'Draft' }}</div>
                                    </li>
                                @empty
                                    <li class="text-muted fs-12">No videos yet.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Latest Audios</h5>
                            <a href="{{ route('admin.audios.index') }}" class="btn btn-sm btn-light">Manage</a>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                @forelse ($latestAudios as $audio)
                                    <li class="mb-3">
                                        <div class="fw-semibold text-dark">{{ $audio->title }}</div>
                                        <div class="fs-12 text-muted">{{ $audio->published_at?->toDateString() ?? 'Draft' }}</div>
                                    </li>
                                @empty
                                    <li class="text-muted fs-12">No audios yet.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Latest Documents</h5>
                            <a href="{{ route('admin.documents.index') }}" class="btn btn-sm btn-light">Manage</a>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                @forelse ($latestDocuments as $document)
                                    <li class="mb-3">
                                        <div class="fw-semibold text-dark">{{ $document->title }}</div>
                                        <div class="fs-12 text-muted">{{ $document->published_at?->toDateString() ?? 'Draft' }}</div>
                                    </li>
                                @empty
                                    <li class="text-muted fs-12">No documents yet.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-12">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Recent Activity</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>Action</th>
                                            <th>Actor</th>
                                            <th>Details</th>
                                            <th>Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($recentActivity as $activity)
                                            <tr>
                                                <td class="text-capitalize">{{ str_replace('_', ' ', $activity->action) }}</td>
                                                <td>{{ $activity->actorUser?->email ?? 'System' }}</td>
                                                <td class="text-muted fs-12">{{ $activity->meta['title'] ?? $activity->meta['email'] ?? '—' }}</td>
                                                <td class="text-muted fs-12">{{ $activity->created_at?->diffForHumans() }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">No activity yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
