@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Audience Insights</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Analytics</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <form method="GET" class="d-flex gap-2">
                    <input type="date" name="from" value="{{ $from->toDateString() }}" class="form-control form-control-sm">
                    <input type="date" name="to" value="{{ $to->toDateString() }}" class="form-control form-control-sm">
                    <button class="btn btn-sm btn-primary">Apply</button>
                </form>
            </div>
        </div>

        <div class="main-content">
            @php
                $deltaClass = static function (array $delta): string {
                    return match ($delta['direction']) {
                        'up' => 'text-success',
                        'down' => 'text-danger',
                        default => 'text-muted',
                    };
                };
            @endphp
            <div class="row">
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="fs-12 text-muted">Video Plays (Period)</div>
                            <div class="fs-4 fw-bold text-dark">{{ number_format($periodCurrent['video_plays']) }}</div>
                            <div class="fs-12 mt-2 {{ $deltaClass($periodDelta['video_plays']) }}">
                                {{ $periodDelta['video_plays']['value'] > 0 ? '+' : '' }}{{ number_format($periodDelta['video_plays']['value'], 1) }}% vs previous period
                            </div>
                            <a href="{{ route('admin.analytics.events', ['type' => 'video']) }}" class="btn btn-sm btn-light mt-3">Details</a>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="fs-12 text-muted">Audio Plays (Period)</div>
                            <div class="fs-4 fw-bold text-dark">{{ number_format($periodCurrent['audio_plays']) }}</div>
                            <div class="fs-12 mt-2 {{ $deltaClass($periodDelta['audio_plays']) }}">
                                {{ $periodDelta['audio_plays']['value'] > 0 ? '+' : '' }}{{ number_format($periodDelta['audio_plays']['value'], 1) }}% vs previous period
                            </div>
                            <a href="{{ route('admin.analytics.events', ['type' => 'audio']) }}" class="btn btn-sm btn-light mt-3">Details</a>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="fs-12 text-muted">Book Reads (Period)</div>
                            <div class="fs-4 fw-bold text-dark">{{ number_format($periodCurrent['book_reads']) }}</div>
                            <div class="fs-12 mt-2 {{ $deltaClass($periodDelta['book_reads']) }}">
                                {{ $periodDelta['book_reads']['value'] > 0 ? '+' : '' }}{{ number_format($periodDelta['book_reads']['value'], 1) }}% vs previous period
                            </div>
                            <a href="{{ route('admin.analytics.events', ['type' => 'book']) }}" class="btn btn-sm btn-light mt-3">Details</a>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="fs-12 text-muted">Audience Page Views (Period)</div>
                            <div class="fs-4 fw-bold text-dark">{{ number_format($periodCurrent['page_views']) }}</div>
                            <div class="fs-12 mt-2 {{ $deltaClass($periodDelta['page_views']) }}">
                                {{ $periodDelta['page_views']['value'] > 0 ? '+' : '' }}{{ number_format($periodDelta['page_views']['value'], 1) }}% vs previous period
                            </div>
                            <a href="{{ route('admin.analytics.audiences', ['from' => $from->toDateString(), 'to' => $to->toDateString()]) }}" class="btn btn-sm btn-light mt-3">Audience Details</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-12">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Engagement Trends</h5>
                        </div>
                        <div class="card-body">
                            <div id="analyticsTrendChart" style="height: 320px;"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Engagement Funnels</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="fw-semibold text-dark">Video</div>
                                <div class="text-muted fs-12">Impressions → Plays → Shares</div>
                                <div class="fs-12 text-muted mt-1">{{ $funnels['video']['impressions'] }} → {{ $funnels['video']['plays'] }} → {{ $funnels['video']['shares'] }}</div>
                            </div>
                            <div class="mb-3">
                                <div class="fw-semibold text-dark">Audio</div>
                                <div class="text-muted fs-12">Plays → Shares → Downloads</div>
                                <div class="fs-12 text-muted mt-1">{{ $funnels['audio']['plays'] }} → {{ $funnels['audio']['shares'] }} → {{ $funnels['audio']['downloads'] }}</div>
                            </div>
                            <div>
                                <div class="fw-semibold text-dark">Books</div>
                                <div class="text-muted fs-12">Reads → Shares → Downloads</div>
                                <div class="fs-12 text-muted mt-1">{{ $funnels['book']['reads'] }} → {{ $funnels['book']['shares'] }} → {{ $funnels['book']['downloads'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Device Breakdown</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                @forelse ($deviceBreakdown as $device => $count)
                                    <li class="d-flex justify-content-between mb-2">
                                        <span class="text-capitalize">{{ $device }}</span>
                                        <span class="fw-semibold">{{ $count }}</span>
                                    </li>
                                @empty
                                    <li class="text-muted fs-12">No device data yet.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-6">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Top Referrers</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                @forelse ($topReferrers as $row)
                                    <li class="d-flex justify-content-between mb-2">
                                        <span class="text-truncate" style="max-width: 70%">{{ $row->referrer }}</span>
                                        <span class="fw-semibold">{{ $row->total }}</span>
                                    </li>
                                @empty
                                    <li class="text-muted fs-12">No referrers yet.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-6">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Top Liked Content</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                @forelse ($topLiked as $row)
                                    <li class="d-flex justify-content-between mb-2">
                                        <span class="text-truncate" style="max-width: 70%">{{ $row->title }}</span>
                                        <span class="fw-semibold">{{ $row->total }}</span>
                                    </li>
                                @empty
                                    <li class="text-muted fs-12">No likes yet.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-12">
                    <div class="card stretch stretch-full">
                        <div class="card-body d-flex gap-2">
                            <a href="{{ route('admin.analytics.events') }}" class="btn btn-primary">View All Events</a>
                            <a href="{{ route('admin.analytics.audiences') }}" class="btn btn-light">Audience Profiles</a>
                            <a href="{{ route('admin.analytics.content') }}" class="btn btn-light">Content Insights</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    (() => {
        const trend = @json($trend);
        const el = document.querySelector('#analyticsTrendChart');
        if (!el || typeof ApexCharts === 'undefined') return;

        const chart = new ApexCharts(el, {
            chart: { type: 'line', height: 320, toolbar: { show: false } },
            stroke: { width: 3, curve: 'smooth' },
            markers: { size: 0 },
            series: [
                { name: 'Video Plays', data: trend.video_plays },
                { name: 'Audio Plays', data: trend.audio_plays },
                { name: 'Book Reads', data: trend.book_reads },
                { name: 'Page Views', data: trend.page_views },
            ],
            xaxis: { categories: trend.labels },
            yaxis: { labels: { formatter: (value) => Math.round(value) } },
            legend: { position: 'top' },
            colors: ['#3b82f6', '#22c55e', '#a855f7', '#f59e0b']
        });

        chart.render();
    })();
</script>
@endpush
