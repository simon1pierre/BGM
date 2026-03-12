@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Event Logs</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.analytics.index') }}">Analytics</a></li>
                    <li class="breadcrumb-item">Events</li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Content Type</label>
                            <select name="type" class="form-select">
                                <option value="all" @selected($type === 'all')>All</option>
                                <option value="video" @selected($type === 'video')>Video</option>
                                <option value="audio" @selected($type === 'audio')>Audio</option>
                                <option value="book" @selected($type === 'book')>Book</option>
                                <option value="audiobook" @selected($type === 'audiobook')>Audiobook</option>
                                <option value="audience" @selected($type === 'audience')>Audience</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Event</label>
                            <select name="event" class="form-select">
                                <option value="all" @selected($event === 'all')>All</option>
                                <option value="play" @selected($event === 'play')>Play</option>
                                <option value="share" @selected($event === 'share')>Share</option>
                                <option value="download" @selected($event === 'download')>Download</option>
                                <option value="read" @selected($event === 'read')>Read</option>
                                <option value="impression" @selected($event === 'impression')>Impression</option>
                                <option value="watch" @selected($event === 'watch')>Watch</option>
                                <option value="view" @selected($event === 'view')>View</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Event</th>
                                    <th>Device</th>
                                    <th>IP</th>
                                    <th>Referrer</th>
                                    <th>Page</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($events as $row)
                                    <tr>
                                        <td class="text-capitalize">{{ $row->content_type }}</td>
                                        <td class="text-uppercase">{{ $row->event_type }}</td>
                                        <td>{{ $row->device_type ?? 'unknown' }}</td>
                                        <td class="text-muted fs-12">{{ $row->ip_address }}</td>
                                        <td class="text-muted fs-12 text-truncate" style="max-width: 200px;">{{ $row->referrer }}</td>
                                        <td class="text-muted fs-12 text-truncate" style="max-width: 200px;">{{ $row->page_url }}</td>
                                        <td class="text-muted fs-12">{{ $row->created_at }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">No events found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $events->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


