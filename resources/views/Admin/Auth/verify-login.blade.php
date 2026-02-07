@extends('layouts.admin.app')
@section('contents')
<div class="nxl-content">
    <div class="main-content">
        <div class="card wd-450 mx-auto mt-5">
            <div class="card-body">
                <h4 class="mb-2">Two-Step Verification</h4>
                <p class="text-muted mb-4">Enter the code sent to your email to continue.</p>

                @if (session('status'))
                    <div class="alert alert-success mb-3">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('admin.login.verify.post') }}" class="row g-3">
                    @csrf
                    <div class="col-12">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" class="form-control" value="{{ $email }}" readonly>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Verification Code</label>
                        <input type="text" name="code" class="form-control" required>
                        @error('code')<div class="text-danger fs-12">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 d-flex gap-2">
                        <button class="btn btn-primary">Verify</button>
                    </div>
                </form>

                <form method="POST" action="{{ route('admin.login.verify.resend') }}" class="mt-2">
                    @csrf
                    <button class="btn btn-light w-100">Resend Code</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
