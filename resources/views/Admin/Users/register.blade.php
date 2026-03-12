@extends('layouts.admin.app')
@section('contents')
<main class="auth-minimal-wrapper">
    <div class="auth-minimal-inner">
        <div class="container mt-5">
            <div class="card shadow-lg border-0">
                <div class="row g-0">

                    <!-- LEFT SIDE -->
                    <div class="col-lg-5 bg-primary text-white p-5 d-flex flex-column justify-content-center position-relative">

                        <div class="text-center mb-4">
                            <img src="{{ asset('images/logo.png') }}" style="width:90px" class="img-fluid bg-white p-2 rounded-circle shadow">
                        </div>

                        <h2 class="fw-bold mb-3">Register Admin</h2>
                        <h5 class="fw-semibold">Beacons of God Ministries</h5>

                        <p class="mt-3 opacity-75">
                            Create an admin account to manage sermons,
                            resources, events and ministry outreach.
                        </p>

                        <hr class="bg-white opacity-50">

                        <p class="small opacity-75">
                            Only authorized administrators can create accounts.
                            Ensure details are correct before submission.
                        </p>
                    </div>


                    <!-- RIGHT SIDE FORM -->
                    <div class="col-lg-7 bg-white">
                        <div class="card-body p-5">

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

                            <form action="{{ route('admin.register.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">

                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-semibold">First Name</label>
                                        <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-semibold">Last Name</label>
                                        <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-semibold">User Name</label>
                                        <input type="text" name="user_name" value="{{ old('user_name') }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-semibold">Email</label>
                                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-semibold">Role</label>
                                        <select name="role" class="form-select">
                                            <option value="admin">Admin</option>
                                            <option value="editor">Editor</option>
                                            <option value="staff">Staff</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-semibold">Profile Photo</label>
                                        <input type="file" name="avatar" class="form-control" accept="image/*">
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-semibold">Password</label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="form-label fw-semibold">Confirm Password</label>
                                        <input type="password" name="password_confirmation" class="form-control" required>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <div class="form-check">
                                            <input type="checkbox" name="is_active" value="1" class="form-check-input" checked>
                                            <label class="form-check-label">Activate this account</label>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-4">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" required>
                                            <label class="form-check-label">Agree to Terms & Conditions</label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button class="btn btn-primary btn-lg w-100">Create Account</button>
                                    </div>

                                </div>
                            </form>

                            <div class="text-center mt-4">
                                <span class="text-muted">Already have account?</span>
                                <span class="fw-bold">Use the secure admin URL.</span>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>
@endsection








