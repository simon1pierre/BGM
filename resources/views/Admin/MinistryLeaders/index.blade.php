@extends('layouts.admin.app')

@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Ministry Leaders & Preachers</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Ministry Leaders</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto d-flex align-items-center gap-2">
                <button type="button" class="btn btn-light no-print" onclick="printAdminReport('Ministry Team Report')">
                    <i class="feather-printer me-2"></i>Print Report
                </button>
                <a href="{{ route('admin.ministry-leaders.create') }}" class="btn btn-primary">
                    <i class="feather-plus me-2"></i>Add Profile
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
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Name, position, email...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Role</label>
                            <select name="role_type" class="form-select">
                                <option value="">All</option>
                                <option value="leader" @selected(request('role_type') === 'leader')>Leader</option>
                                <option value="preacher" @selected(request('role_type') === 'preacher')>Preacher</option>
                            </select>
                        </div>
                        <div class="col-md-2">
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
                        <div class="col-md-1 d-flex align-items-end">
                            <button class="btn btn-primary w-100">Go</button>
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
                                    <th>Profile</th>
                                    <th>Role</th>
                                    <th>Contact</th>
                                    <th>Order</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($leaders as $leader)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img
                                                    src="{{ $leader->photo_path ? asset('storage/'.$leader->photo_path) : asset('images/logo.png') }}"
                                                    alt="{{ $leader->name }}"
                                                    style="width:42px;height:42px;object-fit:cover;border-radius:999px;"
                                                >
                                                <div>
                                                    <div class="fw-semibold">{{ $leader->name }}</div>
                                                    <div class="text-muted small">{{ $leader->position }}</div>
                                                    @if ($leader->country)
                                                        <div class="text-muted small">{{ $leader->country }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $leader->role_type === 'preacher' ? 'bg-soft-warning text-warning' : 'bg-soft-primary text-primary' }}">
                                                {{ ucfirst($leader->role_type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="small text-muted">{{ $leader->email ?: '-' }}</div>
                                            <div class="small text-muted">{{ $leader->phone ?: '-' }}</div>
                                        </td>
                                        <td>{{ $leader->sort_order }}</td>
                                        <td>
                                            @if ($leader->trashed())
                                                <span class="badge bg-soft-secondary text-muted">Deleted</span>
                                            @elseif ($leader->is_active)
                                                <span class="badge bg-soft-success text-success">Active</span>
                                            @else
                                                <span class="badge bg-soft-danger text-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if (!$leader->trashed())
                                                <a href="{{ route('admin.ministry-leaders.edit', $leader) }}" class="btn btn-sm btn-primary">Edit</a>
                                                <form action="{{ route('admin.ministry-leaders.toggle-active', $leader) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button class="btn btn-sm btn-outline-secondary">{{ $leader->is_active ? 'Hide' : 'Show' }}</button>
                                                </form>
                                                <form action="{{ route('admin.ministry-leaders.destroy', $leader) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.ministry-leaders.restore', $leader->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button class="btn btn-sm btn-success">Restore</button>
                                                </form>
                                                <form action="{{ route('admin.ministry-leaders.force-delete', $leader->id) }}" method="POST" class="d-inline" data-confirm="Permanently delete this profile? This cannot be undone." data-confirm-action="Permanent Delete">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger">Permanent Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No ministry profiles found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $leaders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
