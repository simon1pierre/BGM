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
                <div class="col-xxl-12">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-header">
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="card-title">Engagement Trends (Last 7 Days)</h5>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ route('admin.analytics.index') }}" class="btn btn-sm btn-light">Open Analytics</a>
                                    <span class="text-muted fs-12" id="statsLastUpdated">Updating...</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div style="height: 320px;">
                                <canvas id="engagementChart" height="320"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex gap-3 align-items-center">
                                    <div class="avatar-text avatar-lg bg-gray-200">
                                        <i class="feather-user"></i>
                                    </div>
                                    <div>
                                        <div class="fs-6 fw-semibold text-dark">Users</div>
                                        <div class="fs-12 text-muted">Manage accounts</div>
                                    </div>
                                </div>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-light">Open</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex gap-3 align-items-center">
                                    <div class="avatar-text avatar-lg bg-gray-200">
                                        <i class="feather-mail"></i>
                                    </div>
                                    <div>
                                        <div class="fs-6 fw-semibold text-dark">Campaigns</div>
                                        <div class="fs-12 text-muted">Email outreach</div>
                                    </div>
                                </div>
                                <a href="{{ route('admin.campaigns.index') }}" class="btn btn-sm btn-light">Open</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex gap-3 align-items-center">
                                    <div class="avatar-text avatar-lg bg-gray-200">
                                        <i class="feather-folder"></i>
                                    </div>
                                    <div>
                                        <div class="fs-6 fw-semibold text-dark">Contents</div>
                                        <div class="fs-12 text-muted">Videos, audios, books</div>
                                    </div>
                                </div>
                                <a href="{{ route('admin.videos.index') }}" class="btn btn-sm btn-light">Open</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex gap-3 align-items-center">
                                    <div class="avatar-text avatar-lg bg-gray-200">
                                        <i class="feather-users"></i>
                                    </div>
                                    <div>
                                        <div class="fs-6 fw-semibold text-dark">Subscribers</div>
                                        <div class="fs-12 text-muted">Audience list</div>
                                    </div>
                                </div>
                                <a href="{{ route('admin.subscribers.index') }}" class="btn btn-sm btn-light">Open</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="fs-12 text-muted">Video Views (Unique)</div>
                                    <div class="fs-4 fw-bold text-dark" data-stat="videoViews">{{ $totalVideoViews }}</div>
                                </div>
                                <span class="badge bg-soft-primary text-primary">All time</span>
                            </div>
                            <div class="fs-12 text-muted mt-2">Last 7 days: {{ $videoViewsLast7 }}</div>
                            <a href="{{ route('admin.analytics.events', ['type' => 'video']) }}" class="fs-12 text-primary d-inline-block mt-2">Details</a>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="fs-12 text-muted">Video Plays</div>
                                    <div class="fs-4 fw-bold text-dark" data-stat="videoPlays">{{ $totalVideoPlays }}</div>
                                </div>
                                <span class="badge bg-soft-secondary text-muted">All time</span>
                            </div>
                            <div class="fs-12 text-muted mt-2">Impressions: <span data-stat="videoImpressions">{{ $totalVideoImpressions }}</span></div>
                            <a href="{{ route('admin.analytics.events', ['type' => 'video', 'event' => 'play']) }}" class="fs-12 text-primary d-inline-block mt-2">Details</a>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="fs-12 text-muted">Video Shares</div>
                                    <div class="fs-4 fw-bold text-dark" data-stat="videoShares">{{ $totalVideoShares }}</div>
                                </div>
                                <span class="badge bg-soft-warning text-warning">All time</span>
                            </div>
                            <div class="fs-12 text-muted mt-2">Last 7 days: {{ $videoSharesLast7 }}</div>
                            <a href="{{ route('admin.analytics.events', ['type' => 'video', 'event' => 'share']) }}" class="fs-12 text-primary d-inline-block mt-2">Details</a>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="fs-12 text-muted">Watch Time</div>
                                    <div class="fs-4 fw-bold text-dark"><span data-stat="videoWatchMinutes">{{ number_format($totalVideoWatchSeconds / 60, 1) }}</span> min</div>
                                </div>
                                <span class="badge bg-soft-success text-success">All time</span>
                            </div>
                            <div class="fs-12 text-muted mt-2">Avg per play: {{ $totalVideoPlays ? number_format(($totalVideoWatchSeconds / 60) / $totalVideoPlays, 1) : 0 }} min</div>
                            <a href="{{ route('admin.analytics.events', ['type' => 'video', 'event' => 'watch']) }}" class="fs-12 text-primary d-inline-block mt-2">Details</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="fs-12 text-muted">Audio Plays</div>
                            <div class="fs-4 fw-bold text-dark" data-stat="audioPlays">{{ $audioPlays }}</div>
                            <div class="fs-12 text-muted mt-2">Shares: <span data-stat="audioShares">{{ $audioShares }}</span> | Downloads: <span data-stat="audioDownloads">{{ $audioDownloads }}</span></div>
                            <a href="{{ route('admin.analytics.events', ['type' => 'audio']) }}" class="fs-12 text-primary d-inline-block mt-2">Details</a>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="fs-12 text-muted">Book Reads</div>
                            <div class="fs-4 fw-bold text-dark" data-stat="bookReads">{{ $bookReads }}</div>
                            <div class="fs-12 text-muted mt-2">Shares: <span data-stat="bookShares">{{ $bookShares }}</span> | Downloads: <span data-stat="bookDownloads">{{ $bookDownloads }}</span></div>
                            <a href="{{ route('admin.analytics.events', ['type' => 'book']) }}" class="fs-12 text-primary d-inline-block mt-2">Details</a>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="fs-12 text-muted">Total Likes</div>
                            <div class="fs-4 fw-bold text-dark" data-stat="likes">{{ $totalLikes }}</div>
                            <div class="fs-12 text-muted mt-2">Total Comments: <span data-stat="comments">{{ $totalComments }}</span></div>
                            <a href="{{ route('admin.analytics.content') }}" class="fs-12 text-primary d-inline-block mt-2">Details</a>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="fs-12 text-muted">New Subscribers</div>
                            <div class="fs-4 fw-bold text-dark" data-stat="subscribers">{{ $newSubscribersLast7 }}</div>
                            <div class="fs-12 text-muted mt-2">Last 7 days</div>
                            <a href="{{ route('admin.analytics.audiences') }}" class="fs-12 text-primary d-inline-block mt-2">Audience</a>
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
                <div class="col-xxl-4">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Top Videos (Views)</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                @forelse ($topVideosByViews as $video)
                                    <li class="mb-3">
                                        <div class="fw-semibold text-dark">{{ $video->title }}</div>
                                        <div class="fs-12 text-muted">Views: {{ $video->view_count ?? 0 }}</div>
                                    </li>
                                @empty
                                    <li class="text-muted fs-12">No data yet.</li>
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
                                @forelse ($topAudiosByPlays as $row)
                                    <li class="mb-3">
                                        <div class="fw-semibold text-dark">{{ $row->audio?->title ?? 'Unknown' }}</div>
                                        <div class="fs-12 text-muted">Plays: {{ $row->total }}</div>
                                    </li>
                                @empty
                                    <li class="text-muted fs-12">No data yet.</li>
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
                                @forelse ($topBooksByReads as $row)
                                    <li class="mb-3">
                                        <div class="fw-semibold text-dark">{{ $row->book?->title ?? 'Unknown' }}</div>
                                        <div class="fs-12 text-muted">Reads: {{ $row->total }}</div>
                                    </li>
                                @empty
                                    <li class="text-muted fs-12">No data yet.</li>
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

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        let engagementChart = null;

        function buildChart(labels, series) {
            const ctx = document.getElementById('engagementChart');
            if (!ctx) return;

            const data = {
                labels: labels,
                datasets: [
                    {
                        label: 'Video Plays',
                        data: series.videoPlays,
                        borderColor: '#0f2b5e',
                        backgroundColor: 'rgba(15, 43, 94, 0.08)',
                        tension: 0.35,
                        fill: true,
                    },
                    {
                        label: 'Video Shares',
                        data: series.videoShares,
                        borderColor: '#d4af37',
                        backgroundColor: 'rgba(212, 175, 55, 0.12)',
                        tension: 0.35,
                        fill: true,
                    },
                    {
                        label: 'Audio Plays',
                        data: series.audioPlays,
                        borderColor: '#4b5563',
                        backgroundColor: 'rgba(75, 85, 99, 0.08)',
                        tension: 0.35,
                        fill: true,
                    },
                    {
                        label: 'Book Reads',
                        data: series.bookReads,
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22, 163, 74, 0.08)',
                        tension: 0.35,
                        fill: true,
                    },
                    {
                        label: 'Subscribers',
                        data: series.subscriberAdds,
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.08)',
                        tension: 0.35,
                        fill: true,
                    }
                ]
            };

            const options = {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: 'index' },
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: { enabled: true },
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true }
                }
            };

            if (engagementChart) {
                engagementChart.data = data;
                engagementChart.update();
                return;
            }

            engagementChart = new Chart(ctx, { type: 'line', data, options });
        }

        function updateTotals(totals) {
            const map = {
                videoViews: 'videoViews',
                videoPlays: 'videoPlays',
                videoImpressions: 'videoImpressions',
                videoShares: 'videoShares',
                videoWatchMinutes: 'videoWatchMinutes',
                audioPlays: 'audioPlays',
                audioDownloads: 'audioDownloads',
                audioShares: 'audioShares',
                bookReads: 'bookReads',
                bookDownloads: 'bookDownloads',
                bookShares: 'bookShares',
                likes: 'likes',
                comments: 'comments',
                subscribers: 'subscribers',
            };

            Object.keys(map).forEach((key) => {
                const el = document.querySelector(`[data-stat="${map[key]}"]`);
                if (el && totals[key] !== undefined) {
                    el.textContent = totals[key];
                }
            });
        }

        async function fetchStats() {
            try {
                const response = await fetch('{{ route('admin.stats') }}');
                if (!response.ok) return;
                const data = await response.json();
                buildChart(data.labels, data.series);
                updateTotals(data.totals);
                const stamp = new Date().toLocaleTimeString();
                const stampEl = document.getElementById('statsLastUpdated');
                if (stampEl) stampEl.textContent = `Updated ${stamp}`;
            } catch (e) {
                // no-op
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            fetchStats();
            setInterval(fetchStats, 30000);
        });
    </script>
@endpush
