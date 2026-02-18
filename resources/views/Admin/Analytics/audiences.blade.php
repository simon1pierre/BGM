@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Audience Analytics</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.analytics.index') }}">Analytics</a></li>
                    <li class="breadcrumb-item">Audiences</li>
                </ul>
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
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">From</label>
                            <input type="date" name="from" class="form-control" value="{{ $from->toDateString() }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">To</label>
                            <input type="date" name="to" class="form-control" value="{{ $to->toDateString() }}">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">Apply</button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('admin.analytics.audiences') }}" class="btn btn-light w-100">Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-2 col-md-4">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-body">
                            <div class="fs-12 text-muted">Unique Visitors</div>
                            <div class="fs-3 fw-bold text-dark">{{ number_format($summary['unique_visitors']) }}</div>
                            <div class="fs-12 {{ $deltaClass($summaryDelta['unique_visitors']) }}">
                                {{ $summaryDelta['unique_visitors']['value'] > 0 ? '+' : '' }}{{ number_format($summaryDelta['unique_visitors']['value'], 1) }}%
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-2 col-md-4">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-body">
                            <div class="fs-12 text-muted">Sessions</div>
                            <div class="fs-3 fw-bold text-dark">{{ number_format($summary['sessions']) }}</div>
                            <div class="fs-12 {{ $deltaClass($summaryDelta['sessions']) }}">
                                {{ $summaryDelta['sessions']['value'] > 0 ? '+' : '' }}{{ number_format($summaryDelta['sessions']['value'], 1) }}%
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-2 col-md-4">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-body">
                            <div class="fs-12 text-muted">Page Views</div>
                            <div class="fs-3 fw-bold text-dark">{{ number_format($summary['page_views']) }}</div>
                            <div class="fs-12 {{ $deltaClass($summaryDelta['page_views']) }}">
                                {{ $summaryDelta['page_views']['value'] > 0 ? '+' : '' }}{{ number_format($summaryDelta['page_views']['value'], 1) }}%
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-2 col-md-4">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-body">
                            <div class="fs-12 text-muted">Avg Session (s)</div>
                            <div class="fs-3 fw-bold text-dark">{{ number_format($summary['avg_session_seconds']) }}</div>
                            <div class="fs-12 {{ $deltaClass($summaryDelta['avg_session_seconds']) }}">
                                {{ $summaryDelta['avg_session_seconds']['value'] > 0 ? '+' : '' }}{{ number_format($summaryDelta['avg_session_seconds']['value'], 1) }}%
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-2 col-md-4">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-body">
                            <div class="fs-12 text-muted">Bounce Rate</div>
                            <div class="fs-3 fw-bold text-dark">{{ number_format($summary['bounce_rate'], 1) }}%</div>
                            <div class="fs-12 {{ $deltaClass($summaryDelta['bounce_rate']) }}">
                                {{ $summaryDelta['bounce_rate']['value'] > 0 ? '+' : '' }}{{ number_format($summaryDelta['bounce_rate']['value'], 1) }}%
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-2 col-md-4">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-body">
                            <div class="fs-12 text-muted">Returning Visitors</div>
                            <div class="fs-3 fw-bold text-dark">{{ number_format($summary['returning_visitors']) }}</div>
                            <div class="fs-12 {{ $deltaClass($summaryDelta['returning_visitors']) }}">
                                {{ $summaryDelta['returning_visitors']['value'] > 0 ? '+' : '' }}{{ number_format($summaryDelta['returning_visitors']['value'], 1) }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-12">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-header"><h5 class="card-title">Audience Trend</h5></div>
                        <div class="card-body">
                            <div id="audienceTrendChart" style="height: 320px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-4">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-header"><h5 class="card-title">Top Pages</h5></div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                @forelse ($topPages as $row)
                                    <li class="d-flex justify-content-between mb-2">
                                        <span class="text-truncate" style="max-width: 72%">{{ $row->page_url }}</span>
                                        <span class="fw-semibold">{{ $row->total }}</span>
                                    </li>
                                @empty
                                    <li class="text-muted fs-12">No page view data.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-header"><h5 class="card-title">Top Referrers</h5></div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                @forelse ($topReferrers as $row)
                                    <li class="d-flex justify-content-between mb-2">
                                        <span class="text-truncate" style="max-width: 72%">{{ $row->referrer }}</span>
                                        <span class="fw-semibold">{{ $row->total }}</span>
                                    </li>
                                @empty
                                    <li class="text-muted fs-12">No referrer data.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-header"><h5 class="card-title">Devices</h5></div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                @forelse ($devices as $row)
                                    <li class="d-flex justify-content-between mb-2">
                                        <span class="text-capitalize">{{ $row->device_type ?: 'unknown' }}</span>
                                        <span class="fw-semibold">{{ $row->total }}</span>
                                    </li>
                                @empty
                                    <li class="text-muted fs-12">No device data.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-header"><h5 class="card-title">Top Countries</h5></div>
                        <div class="card-body">
                            <ul class="list-unstyled mb-0">
                                @forelse ($countries as $row)
                                    <li class="d-flex justify-content-between mb-2">
                                        <span>{{ $row->geo_country }}</span>
                                        <span class="fw-semibold">{{ $row->total }}</span>
                                    </li>
                                @empty
                                    <li class="text-muted fs-12">No geo data.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Recent Visitors</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Visitor</th>
                                    <th>Sessions</th>
                                    <th>Events</th>
                                    <th>Device</th>
                                    <th>Country</th>
                                    <th>Language</th>
                                    <th>Last Seen</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rows as $row)
                                    <tr>
                                        <td class="text-muted fs-12">{{ $row->visitor_key }}</td>
                                        <td>{{ $row->total_sessions }}</td>
                                        <td>{{ $row->total_events }}</td>
                                        <td class="text-capitalize">{{ $row->device_type ?: 'unknown' }}</td>
                                        <td>{{ $row->geo_country ?: 'Unknown' }}</td>
                                        <td>{{ $row->language ?: '-' }}</td>
                                        <td class="text-muted fs-12">{{ $row->last_seen }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No audience data found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $rows->links('pagination.admin') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    (() => {
        const trend = @json($audienceTrend);
        const el = document.querySelector('#audienceTrendChart');
        if (!el || typeof ApexCharts === 'undefined') return;

        const chart = new ApexCharts(el, {
            chart: { type: 'area', height: 320, toolbar: { show: false } },
            stroke: { curve: 'smooth', width: 2.5 },
            fill: { type: 'gradient', gradient: { shadeIntensity: 0.3, opacityFrom: 0.35, opacityTo: 0.05 } },
            markers: { size: 0 },
            series: [
                { name: 'Page Views', data: trend.page_views },
                { name: 'Unique Visitors', data: trend.unique_visitors },
                { name: 'Sessions', data: trend.sessions },
            ],
            xaxis: { categories: trend.labels },
            yaxis: { labels: { formatter: (value) => Math.round(value) } },
            legend: { position: 'top' },
            colors: ['#2563eb', '#22c55e', '#f59e0b']
        });

        chart.render();
    })();
</script>
@endpush
