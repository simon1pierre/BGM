@extends('layouts.admin.app')
@section('contents')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">User Management</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Users</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <button type="button" class="btn btn-light no-print" onclick="printAdminReport('Users Report')">
                <i class="feather-printer me-2"></i>
                Print Report
            </button>
            <a href="{{ route('admin.register') }}" class="btn btn-primary no-print">
                <i class="feather-user-plus me-2"></i>
                User Registration
            </a>
        </div>
    </div>

    <div class="main-content">
        @if (session('status'))
            <div class="alert alert-success mb-4">{{ session('status') }}</div>
        @endif

        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Name, email, username">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Role</label>
                        <select name="role" class="form-select">
                            <option value="">All</option>
                            <option value="admin" @selected(request('role') === 'admin')>Admin</option>
                            <option value="editor" @selected(request('role') === 'editor')>Editor</option>
                            <option value="staff" @selected(request('role') === 'staff')>Staff</option>
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
                    <div class="col-md-1">
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
                                <th>User</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Last Login</th>
                                <th>Created</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar-text avatar-md bg-light">
                                                @if($user->avatar)
                                                <img src="{{$user->avatar}}" alt="">
                                                @endif
                                                <i class="feather-user"></i>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $user->name ?? $user->first_name.' '.$user->last_name }}</div>
                                                <div class="text-muted fs-12">{{ $user->email }}</div>
                                                <div class="text-muted fs-12">{{ $user->user_name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-soft-primary text-primary">{{ $user->role }}</span></td>
                                    <td>
                                        @if ($user->trashed())
                                            <span class="badge bg-soft-secondary text-muted">Deleted</span>
                                        @elseif ($user->is_active)
                                            <span class="badge bg-soft-success text-success">Active</span>
                                        @else
                                            <span class="badge bg-soft-danger text-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="fs-12 text-muted">{{ $user->last_login_at?->diffForHumans() ?? '—' }}</td>
                                    <td class="fs-12 text-muted">{{ $user->created_at?->toDateString() }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-light">View</a>
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-warning">Toggle</button>
                                        </form>
                                        @if ($user->trashed())
                                            <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-success">Restore</button>
                                            </form>
                                            <form action="{{ route('admin.users.force-delete', $user->id) }}" method="POST" class="d-inline" data-confirm="Permanently delete this user? This cannot be undone." data-confirm-action="Permanent Delete">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">Permanent Delete</button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $users->links('pagination.admin') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


