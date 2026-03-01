<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Verification | Beacons of God Ministries</title>
    <meta name="description" content="Admin two-step verification for Beacons of God Ministries.">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('logo/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('logo/favicon-16x16.png') }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/vendors/css/vendors.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/theme.min.css')}}" />
    <style>
        body { background: #0b1530; min-height: 100vh; }
        .shell { min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; }
        .card-wrap { width: 100%; max-width: 440px; background: #fff; border-radius: 16px; box-shadow: 0 20px 60px rgba(2,10,30,.45); }
    </style>
</head>
<body>
    <div class="shell">
        <div class="card-wrap p-4 p-md-5">
            <div class="d-flex align-items-center gap-2 mb-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="rounded-circle bg-white border p-1" style="width: 44px; height: 44px;">
                <div>
                    <h4 class="mb-0 fw-bold">Two-Step Verification</h4>
                    <div class="text-muted fs-12">Admin secure sign-in</div>
                </div>
            </div>
            <p class="text-muted mb-4">Enter the code sent to your email to continue.</p>

            @if (session('status'))
                <div class="alert alert-success mb-3">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.verify.post') }}" class="row g-3">
                @csrf
                <div class="col-12">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control" value="{{ $email }}" readonly>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Verification Code</label>
                    <input type="text" name="code" class="form-control" required autocomplete="one-time-code" inputmode="numeric">
                    @error('code')<div class="text-danger fs-12">{{ $message }}</div>@enderror
                </div>
                <div class="col-12 d-grid">
                    <button class="btn btn-primary">Verify and Continue</button>
                </div>
            </form>

            <form method="POST" action="{{ route('admin.login.verify.resend') }}" class="mt-2">
                @csrf
                <button class="btn btn-light w-100">Resend Code</button>
            </form>
        </div>
    </div>
</body>
</html>
