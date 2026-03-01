<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Beacons of God Ministries</title>
    <meta name="description" content="Secure administrator access for Beacons of God Ministries.">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('logo/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('logo/favicon-16x16.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/vendors/css/vendors.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/css/theme.min.css')}}" />

    <style>
        :root {
            --brand-blue: #0f2b5e;
            --brand-gold: #d4af37;
            --brand-light: #f8fafc;
        }
        body {
            font-family: "Lato", sans-serif;
            background-color: #0b1530;
            color: #0f172a;
            min-height: 100vh;
        }
        .hero-bg {
            position: fixed;
            inset: 0;
            background: url('https://cdn.ailandingpage.ai/landingpage_io/user-generate/552f4586-c83c-46ac-b326-367fa6ccc9f3/552f4586-c83c-46ac-b326-367fa6ccc9f3/hero/hero-bg-fbd45f0e618f4b3db7207f45845a2c9f.png') center/cover no-repeat;
            opacity: 0.65;
            z-index: 0;
        }
        .hero-overlay {
            position: fixed;
            inset: 0;
            background: linear-gradient(180deg, rgba(5, 20, 45, 0.9) 0%, rgba(10, 25, 50, 0.85) 45%, rgba(10, 15, 30, 0.95) 100%);
            z-index: 1;
        }
        .glow {
            position: fixed;
            inset: -20% 10% auto auto;
            width: 420px;
            height: 420px;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.18) 0%, rgba(212, 175, 55, 0) 70%);
            z-index: 1;
            filter: blur(4px);
        }
        .login-shell {
            position: relative;
            z-index: 2;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 20px;
        }
        .login-card {
            max-width: 980px;
            width: 100%;
            background: rgba(248, 250, 252, 0.94);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(2, 10, 30, 0.55);
            backdrop-filter: blur(10px);
        }
        .login-left {
            background: linear-gradient(160deg, rgba(15, 43, 94, 0.98) 0%, rgba(13, 30, 70, 0.98) 55%, rgba(7, 18, 45, 0.98) 100%);
            color: #fff;
            padding: 48px 40px;
        }
        .login-left h1 {
            font-family: "Playfair Display", serif;
            font-size: 2.2rem;
        }
        .divider {
            width: 56px;
            height: 4px;
            background: var(--brand-gold);
            border-radius: 2px;
            margin: 18px 0 22px;
        }
        .login-right {
            padding: 48px 42px;
        }
        .form-label {
            font-weight: 600;
        }
        .btn-brand {
            background: var(--brand-blue);
            border-color: var(--brand-blue);
            color: #fff;
            font-weight: 600;
            letter-spacing: 0.2px;
        }
        .btn-brand:hover {
            background: #0c234d;
            border-color: #0c234d;
        }
        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid rgba(255, 255, 255, 0.35);
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .input-hint {
            font-size: 12px;
            color: #64748b;
        }
        @media (max-width: 991px) {
            .login-left, .login-right {
                padding: 36px 28px;
            }
        }
    </style>
</head>
<body>
    <div class="hero-bg"></div>
    <div class="hero-overlay"></div>
    <div class="glow"></div>

    <div class="login-shell">
        <div class="login-card row g-0">
            <div class="col-lg-5 login-left d-flex flex-column justify-content-between">
                <div>
                    <div class="badge-pill mb-4">
                        <i class="feather-shield"></i>
                        Secure Admin Access
                    </div>
                    <h1>Beacons of God Ministries</h1>
                    <div class="divider"></div>
                    <p class="opacity-75">
                        Welcome back, steward of the ministry. This space is reserved for trusted
                        administrators overseeing sermons, resources, and outreach.
                    </p>
                </div>
                <div class="mt-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="avatar-text avatar-md bg-warning text-white">
                            <i class="feather-book-open"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">Curate the Word</div>
                            <div class="fs-12 opacity-75">Publish sermons and study guides</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-text avatar-md bg-primary text-white">
                            <i class="feather-radio"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">Shepherd the Flock</div>
                            <div class="fs-12 opacity-75">Support members and campaigns</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 login-right">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Beacons of God Ministries" style="width:56px" class="rounded-circle border border-light shadow-sm bg-white p-1">
                    <div>
                        <h4 class="mb-1 fw-bold">Administrator Access</h4>
                        <div class="input-hint">Use your ministry email to continue.</div>
                    </div>
                </div>

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

                <form action="{{ route('admin.login.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control form-control-lg" placeholder="admin@beaconsofgod.org" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control form-control-lg" placeholder="Enter your password" required>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="form-check">
                            <input type="checkbox" name="remember" value="1" class="form-check-input" id="rememberMe" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="rememberMe">Remember me</label>
                        </div>
                        <span class="input-hint">Forgot password? Contact admin.</span>
                    </div>
                    <button type="submit" class="btn btn-brand btn-lg w-100">Enter Admin Portal</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
