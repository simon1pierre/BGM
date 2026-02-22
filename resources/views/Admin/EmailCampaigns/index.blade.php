@extends('layouts.admin.app')
@section('contents')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Email Campaigns</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Campaigns</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <button type="button" class="btn btn-light no-print" onclick="printAdminReport('Email Campaigns Report')">
                <i class="feather-printer me-2"></i>Print Report
            </button>
            <a class="btn btn-primary" href="{{ route('admin.campaigns.create') }}">New Campaign</a>
        </div>
    </div>

    <div class="main-content">
        @if (session('status'))
            <div class="alert alert-success mb-4">{{ session('status') }}</div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Subject or message...">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            <option value="draft" @selected(request('status') === 'draft')>Draft</option>
                            <option value="scheduled" @selected(request('status') === 'scheduled')>Scheduled</option>
                            <option value="sent" @selected(request('status') === 'sent')>Sent</option>
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
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Scheduled</th>
                                <th>Sent</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($campaigns as $campaign)
                                <tr>
                                    <td>{{ $campaign->subject }}</td>
                                    <td>
                                        @if ($campaign->trashed())
                                            <span class="badge bg-soft-secondary text-muted">Deleted</span>
                                        @else
                                            <span class="badge bg-soft-primary text-primary">{{ $campaign->status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-muted fs-12">{{ $campaign->scheduled_at?->toDateTimeString() ?? '—' }}</td>
                                    <td class="text-muted fs-12">{{ $campaign->sent_at?->toDateTimeString() ?? '—' }}</td>
                                    <td class="text-end">
                                        @if ($campaign->trashed())
                                            <form method="POST" action="{{ route('admin.campaigns.restore', $campaign->id) }}" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-success">Restore</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.campaigns.force-delete', $campaign->id) }}" class="d-inline" onsubmit="return confirm('Permanently delete this campaign? This cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">Permanent Delete</button>
                                            </form>
                                        @else
                                            <a href="{{ route('admin.campaigns.preview', $campaign) }}" class="btn btn-sm btn-outline-primary">Preview</a>
                                            <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="btn btn-sm btn-light">Edit</a>
                                            <form method="POST" action="{{ route('admin.campaigns.destroy', $campaign) }}" class="d-inline" onsubmit="return confirm('Move this campaign to trash?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No campaigns found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $campaigns->links('pagination.admin') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

