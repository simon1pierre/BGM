@extends('layouts.admin.app')
@section('contents')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Subscribers</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Subscribers</li>
            </ul>
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
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Name or email">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All</option>
                            <option value="active" @selected(request('status') === 'active')>Active</option>
                            <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
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
                                <th>Name</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Subscribed</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($subscribers as $subscriber)
                                <tr>
                                    <td>{{ $subscriber->name ?? '—' }}</td>
                                    <td>{{ $subscriber->email }}</td>
                                    <td>
                                        @if ($subscriber->trashed())
                                            <span class="badge bg-soft-secondary text-muted">Deleted</span>
                                        @elseif ($subscriber->is_active)
                                            <span class="badge bg-soft-success text-success">Active</span>
                                        @else
                                            <span class="badge bg-soft-danger text-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-muted fs-12">{{ $subscriber->subscribed_at?->toDateString() }}</td>
                                    <td class="text-end">
                                        @if ($subscriber->trashed())
                                            <form action="{{ route('admin.subscribers.restore', $subscriber->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-success">Restore</button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.subscribers.toggle', $subscriber) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-warning">Toggle</button>
                                            </form>
                                            <form action="{{ route('admin.subscribers.destroy', $subscriber) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No subscribers found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $subscribers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
