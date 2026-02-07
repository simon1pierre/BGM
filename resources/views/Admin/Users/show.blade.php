@extends('layouts.admin.app')
@section('contents')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">User Details</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                <li class="breadcrumb-item">Details</li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="avatar-text avatar-xl bg-light mb-3">
                            <i class="feather-user"></i>
                        </div>
                        <h5 class="fw-bold">{{ $user->name ?? $user->first_name.' '.$user->last_name }}</h5>
                        <div class="text-muted">{{ $user->email }}</div>
                        <div class="text-muted">{{ $user->user_name }}</div>
                        <div class="mt-3">
                            <span class="badge bg-soft-primary text-primary">{{ $user->role }}</span>
                            @if ($user->is_active)
                                <span class="badge bg-soft-success text-success">Active</span>
                            @else
                                <span class="badge bg-soft-danger text-danger">Inactive</span>
                            @endif
                        </div>
                        <div class="mt-4 d-flex gap-2 justify-content-center">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">Edit</a>
                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST">
                                @csrf
                                <button class="btn btn-warning">Toggle</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-body">
                        <h6 class="mb-3">Account Info</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Created</span>
                            <span>{{ $user->created_at ? \Illuminate\Support\Carbon::parse($user->created_at)->toDayDateTimeString() : '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Last Login</span>
                            <span>{{ $user->last_login_at ? \Illuminate\Support\Carbon::parse($user->last_login_at)->toDayDateTimeString() : '—' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Email Verified</span>
                            <span>{{ $user->email_verified_at ? \Illuminate\Support\Carbon::parse($user->email_verified_at)->toDayDateTimeString() : '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="mb-3">Recent Login Activity</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>IP</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($loginLogs as $log)
                                        <tr>
                                            <td>{{ $log->logged_in_at ? \Illuminate\Support\Carbon::parse($log->logged_in_at)->toDayDateTimeString() : '—' }}</td>
                                            <td>{{ $log->ip_address ?? '—' }}</td>
                                            <td>
                                                @if ($log->success)
                                                    <span class="badge bg-soft-success text-success">Success</span>
                                                @else
                                                    <span class="badge bg-soft-danger text-danger">Failed</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-muted text-center">No login records.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="mb-3">Status Changes</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Old</th>
                                        <th>New</th>
                                        <th>Reason</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($statusLogs as $log)
                                        <tr>
                                            <td>{{ $log->created_at ? \Illuminate\Support\Carbon::parse($log->created_at)->toDayDateTimeString() : '—' }}</td>
                                            <td>{{ $log->old_status ? 'Active' : 'Inactive' }}</td>
                                            <td>{{ $log->new_status ? 'Active' : 'Inactive' }}</td>
                                            <td>{{ $log->reason ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-muted text-center">No status changes.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-3">Admin Activity</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($activityLogs as $log)
                                        <tr>
                                            <td>{{ $log->created_at ? \Illuminate\Support\Carbon::parse($log->created_at)->toDayDateTimeString() : '—' }}</td>
                                            <td>{{ $log->action }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-muted text-center">No activity logs.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
