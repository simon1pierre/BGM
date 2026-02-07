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
        <div class="page-header-right ms-auto">
            <a class="btn btn-primary" href="{{ route('admin.campaigns.create') }}">New Campaign</a>
        </div>
    </div>

    <div class="main-content">
        @if (session('status'))
            <div class="alert alert-success mb-4">{{ session('status') }}</div>
        @endif

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
                                    <td><span class="badge bg-soft-primary text-primary">{{ $campaign->status }}</span></td>
                                    <td class="text-muted fs-12">{{ $campaign->scheduled_at?->toDateTimeString() ?? '—' }}</td>
                                    <td class="text-muted fs-12">{{ $campaign->sent_at?->toDateTimeString() ?? '—' }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.campaigns.preview', $campaign) }}" class="btn btn-sm btn-outline-primary">Preview</a>
                                        <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="btn btn-sm btn-light">Edit</a>
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
                    {{ $campaigns->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
