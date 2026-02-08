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
                <a href="{{ route('admin.analytics.events') }}" class="btn btn-light">View Events</a>
            </div>
        </div>

        <div class="main-content">
            <div class="row">
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="fs-12 text-muted">Video Views</div>
                            <div class="fs-4 fw-bold text-dark">{{ $videoTotals['views'] }}</div>
                            <div class="fs-12 text-muted mt-2">Plays: {{ $videoTotals['plays'] }} | Shares: {{ $videoTotals['shares'] }}</div>
                            <a href="{{ route('admin.analytics.events', ['type' => 'video']) }}" class="btn btn-sm btn-light mt-3">Details</a>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="fs-12 text-muted">Audio Plays</div>
                            <div class="fs-4 fw-bold text-dark">{{ $audioTotals['plays'] }}</div>
                            <div class="fs-12 text-muted mt-2">Shares: {{ $audioTotals['shares'] }} | Downloads: {{ $audioTotals['downloads'] }}</div>
                            <a href="{{ route('admin.analytics.events', ['type' => 'audio']) }}" class="btn btn-sm btn-light mt-3">Details</a>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="fs-12 text-muted">Book Reads</div>
                            <div class="fs-4 fw-bold text-dark">{{ $bookTotals['reads'] }}</div>
                            <div class="fs-12 text-muted mt-2">Shares: {{ $bookTotals['shares'] }} | Downloads: {{ $bookTotals['downloads'] }}</div>
                            <a href="{{ route('admin.analytics.events', ['type' => 'book']) }}" class="btn btn-sm btn-light mt-3">Details</a>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="fs-12 text-muted">Engagement</div>
                            <div class="fs-4 fw-bold text-dark">{{ $engagementTotals['likes'] }}</div>
                            <div class="fs-12 text-muted mt-2">Comments: {{ $engagementTotals['comments'] }}</div>
                            <a href="{{ route('admin.analytics.content') }}" class="btn btn-sm btn-light mt-3">Details</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
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
