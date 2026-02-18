@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Events</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Events</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                    <i class="feather-plus me-2"></i>
                    Add Event
                </a>
            </div>
        </div>

        <div class="main-content">
            @if (session('status'))
                <div class="alert alert-success mb-4">{{ session('status') }}</div>
            @endif

            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Title, venue, location...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All</option>
                                <option value="published" @selected(request('status') === 'published')>Published</option>
                                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Deleted</label>
                            <select name="deleted" class="form-select">
                                <option value="">Exclude</option>
                                <option value="with" @selected(request('deleted') === 'with')>Include</option>
                                <option value="only" @selected(request('deleted') === 'only')>Only</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Type</label>
                            <select name="event_type" class="form-select">
                                <option value="">All</option>
                                <option value="prayer_meeting" @selected(request('event_type') === 'prayer_meeting')>Prayer Meeting</option>
                                <option value="service" @selected(request('event_type') === 'service')>Service</option>
                                <option value="conference" @selected(request('event_type') === 'conference')>Conference</option>
                                <option value="other" @selected(request('event_type') === 'other')>Other</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Featured</label>
                            <select name="featured" class="form-select">
                                <option value="">All</option>
                                <option value="1" @selected(request('featured') === '1')>Featured</option>
                                <option value="0" @selected(request('featured') === '0')>Not Featured</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Period</label>
                            <select name="period" class="form-select">
                                <option value="">All</option>
                                <option value="upcoming" @selected(request('period') === 'upcoming')>Upcoming</option>
                                <option value="past" @selected(request('period') === 'past')>Past</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
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
                                    <th>Title</th>
                                    <th>Start</th>
                                    <th>Type</th>
                                    <th>Live</th>
                                    <th>Venue</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($events as $event)
                                    <tr>
                                        <td>{{ $event->title }}</td>
                                        <td>{{ $event->starts_at?->format('M d, Y H:i') }} ({{ $event->timezone }})</td>
                                        <td>{{ str_replace('_', ' ', ucfirst($event->event_type ?? 'other')) }}</td>
                                        <td>
                                            @if ($event->live_platform)
                                                <span class="badge bg-soft-info text-info text-uppercase">{{ $event->live_platform === 'youtube' ? 'YouTube Live' : $event->live_platform }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ $event->venue ?: ($event->location ?: '-') }}</td>
                                        <td>
                                            @if ($event->trashed())
                                                <span class="badge bg-soft-secondary text-muted">Deleted</span>
                                            @elseif ($event->is_published)
                                                <span class="badge bg-soft-success text-success">Published</span>
                                            @else
                                                <span class="badge bg-soft-warning text-warning">Draft</span>
                                            @endif
                                            @if ($event->is_featured)
                                                <span class="badge bg-soft-primary text-primary">Featured</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if (!$event->trashed())
                                                <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-primary">Edit</a>
                                                <form action="{{ route('admin.events.toggle-published', $event) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button class="btn btn-sm btn-outline-secondary">{{ $event->is_published ? 'Draft' : 'Publish' }}</button>
                                                </form>
                                                <form action="{{ route('admin.events.toggle-featured', $event) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button class="btn btn-sm btn-outline-info">{{ $event->is_featured ? 'Unfeature' : 'Feature' }}</button>
                                                </form>
                                                <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.events.restore', $event->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button class="btn btn-sm btn-success">Restore</button>
                                                </form>
                                            @endif
                                        </td>
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
