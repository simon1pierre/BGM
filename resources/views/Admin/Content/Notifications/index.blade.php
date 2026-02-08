@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Content Emails</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Content Emails</li>
                </ul>
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
                                    <th>Type</th>
                                    <th>Target</th>
                                    <th>Sent</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($notifications as $notification)
                                    @php
                                        $payload = $notification->payload ?? [];
                                        $subject = $payload['subject'] ?? 'Content Update';
                                        $type = strtoupper($payload['type'] ?? 'content');
                                        $target = $notification->target_type === 'custom' ? 'Custom' : 'All';
                                    @endphp
                                    <tr>
                                        <td class="fw-semibold text-dark">{{ $subject }}</td>
                                        <td>{{ $type }}</td>
                                        <td>{{ $target }}</td>
                                        <td class="text-muted fs-12">{{ $notification->sent_at?->diffForHumans() ?? '—' }}</td>
                                        <td class="text-end">
                                            <form method="POST" action="{{ route('admin.content-notifications.resend', $notification) }}">
                                                @csrf
                                                <button class="btn btn-sm btn-primary">Resend</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No content emails yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $notifications->links('pagination.admin') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
