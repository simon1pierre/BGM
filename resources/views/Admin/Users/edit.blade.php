@extends('layouts.admin.app')
@section('contents')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Edit User</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
                <li class="breadcrumb-item">Edit</li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        @if (session('status'))
            <div class="alert alert-success mb-4">{{ session('status') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-4">Profile Details</h6>
                        <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">First Name</label>
                                    <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">Last Name</label>
                                    <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">User Name</label>
                                    <input type="text" name="user_name" value="{{ old('user_name', $user->user_name) }}" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">Email</label>
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">Role</label>
                                    <select name="role" class="form-select">
                                        <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                                        <option value="editor" @selected(old('role', $user->role) === 'editor')>Editor</option>
                                        <option value="staff" @selected(old('role', $user->role) === 'staff')>Staff</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold">Profile Photo</label>
                                    <input type="file" name="avatar" class="form-control" accept="image/*">
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isActive" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isActive">Active account</label>
                                    </div>
                                </div>
                                <div class="col-12 mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" name="email_verified" value="1" class="form-check-input" id="emailVerified" {{ old('email_verified', (bool) $user->email_verified_at) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="emailVerified">Email verified</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary btn-lg">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="mb-3">Quick Actions</h6>
                        <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="mb-3">
                            @csrf
                            <button class="btn btn-warning w-100">Toggle Status</button>
                        </form>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="mb-3">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger w-100">Delete User</button>
                        </form>
                        <form action="{{ route('admin.users.force-logout', $user) }}" method="POST" class="mb-3">
                            @csrf
                            <button class="btn btn-secondary w-100">Force Logout</button>
                        </form>
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-light w-100">View Details</a>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-3">Reset Password</h6>
                        <form action="{{ route('admin.users.reset-password', $user) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <input type="password" name="password" class="form-control" placeholder="New password" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
                            </div>
                            <button class="btn btn-dark w-100">Reset Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


