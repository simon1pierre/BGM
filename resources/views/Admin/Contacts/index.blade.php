@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Contact Inbox</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Contact Inbox</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <span class="badge bg-soft-primary text-primary">Unread: {{ $unreadCount }}</span>
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
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Name, email, subject, message...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All</option>
                                <option value="unread" @selected(request('status') === 'unread')>Unread</option>
                                <option value="read" @selected(request('status') === 'read')>Read</option>
                                <option value="replied" @selected(request('status') === 'replied')>Replied</option>
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
                                    <th>Sender</th>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Received</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($messages as $message)
                                    <tr>
                                        <td>{{ $message->name ?: 'Anonymous' }}</td>
                                        <td>{{ $message->email }}</td>
                                        <td>{{ $message->subject ?: 'No subject' }}</td>
                                        <td>
                                            @if (!$message->is_read)
                                                <span class="badge bg-soft-warning text-warning">Unread</span>
                                            @elseif ($message->replied_at)
                                                <span class="badge bg-soft-success text-success">Replied</span>
                                            @else
                                                <span class="badge bg-soft-primary text-primary">Read</span>
                                            @endif
                                        </td>
                                        <td>{{ $message->created_at?->diffForHumans() }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.contacts.show', $message) }}" class="btn btn-sm btn-primary">Open</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No contact messages found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $messages->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
