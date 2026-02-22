<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="" />
    <meta name="keyword" content="" />
    <meta name="author" content="flexilecode" />
    <title>{{ config('app.name') }} || Admin Dashboard</title>
    <link rel="icon" type="image/png" sizes="18x18" href="{{asset('logo/favicon-16x16.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('logo/favicon-32x32.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('logo/apple-touch-icon.png')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('admin/assets/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/vendors/css/vendors.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/vendors/css/daterangepicker.min.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/css/theme.min.css')}}" />
    <style>
        .admin-toast-wrap {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 1080;
            display: flex;
            flex-direction: column;
            gap: .65rem;
            max-width: min(24rem, calc(100vw - 1.5rem));
        }

        .admin-toast {
            border-radius: .75rem;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            box-shadow: 0 12px 28px rgba(15, 23, 42, .16);
            padding: .75rem .9rem;
            font-size: .86rem;
            color: #1e293b;
            opacity: 0;
            transform: translateY(-10px);
            animation: adminToastIn .2s ease forwards;
        }

        .admin-toast.success { border-left: 4px solid #22c55e; }
        .admin-toast.error { border-left: 4px solid #ef4444; }
        .admin-toast.warning { border-left: 4px solid #f59e0b; }
        .admin-toast.info { border-left: 4px solid #3b82f6; }

        @keyframes adminToastIn {
            to { opacity: 1; transform: translateY(0); }
        }

        .print-toolbar .btn {
            min-width: 9.5rem;
        }

        @media print {
            body {
                background: #fff !important;
            }

            .nxl-navigation,
            .nxl-header,
            .footer,
            .admin-toast-wrap,
            .no-print,
            .page-header-right,
            .pagination,
            .btn,
            form,
            .nxl-search,
            .theme-customizer {
                display: none !important;
            }

            .nxl-content,
            .main-content,
            .card,
            .card-body {
                box-shadow: none !important;
                border: 0 !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            table {
                width: 100% !important;
                font-size: 12px !important;
            }

            .print-report-title {
                display: block !important;
                margin-bottom: 12px;
                padding-bottom: 8px;
                border-bottom: 2px solid #10295E;
                font-weight: 700;
                font-size: 18px;
                color: #10295E;
            }
        }
    </style>
    @livewireStyles
</head>

<body>
    <div id="adminToastWrap" class="admin-toast-wrap" aria-live="polite" aria-atomic="true"></div>
    @include('layouts.admin.partials.nav')
    @include('layouts.admin.partials.header')
    <main class="nxl-container">
        @yield('contents')
        <!-- [ Footer ] start -->
        <footer class="footer">
            <p class="fs-11 text-muted fw-medium text-uppercase mb-0 copyright">
                <span>Copyright ©</span>
                <script>
                    document.write(new Date().getFullYear());
                </script>
            </p>
            <p><span>Developed by:<a href="#">Simon Pierre</a></span> • <span> For: <a href="" target="_blank">Beacons of God Ministries</a></span></p>
            <div class="d-flex align-items-center gap-4">
                <a href="javascript:void(0);" class="fs-11 fw-semibold text-uppercase">Help</a>
                <a href="javascript:void(0);" class="fs-11 fw-semibold text-uppercase">Terms</a>
                <a href="javascript:void(0);" class="fs-11 fw-semibold text-uppercase">Privacy</a>
            </div>
        </footer>
        <!-- [ Footer ] end -->
    </main>
    <!--! ================================================================ !-->
    <!--! [End] Main Content !-->
    
    <script src="{{asset('admin/assets/vendors/js/vendors.min.js')}}"></script>
    <script src="{{asset('admin/assets/vendors/js/daterangepicker.min.js')}}"></script>
    <script src="{{asset('admin/assets/vendors/js/apexcharts.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/common-init.min.js')}}"></script>
    <script src="{{asset('admin/assets/js/dashboard-init.min.js')}}"></script>
    <script src="{{ asset('admin/assets/js/theme-customizer-init.min.js')}}"></script>
    <script>
        (() => {
            const wrap = document.getElementById('adminToastWrap');

            window.adminNotify = function (message, type = 'info', timeout = 4200) {
                if (!wrap || !message) return;
                const node = document.createElement('div');
                node.className = `admin-toast ${type}`;
                node.textContent = String(message);
                wrap.appendChild(node);

                setTimeout(() => {
                    node.style.opacity = '0';
                    node.style.transform = 'translateY(-8px)';
                    setTimeout(() => node.remove(), 220);
                }, timeout);
            };

            @if (session('status'))
                window.adminNotify(@json(session('status')), 'success');
            @endif
            @if (session('error'))
                window.adminNotify(@json(session('error')), 'error');
            @endif
            @if (session('warning'))
                window.adminNotify(@json(session('warning')), 'warning');
            @endif
            @if (session('info'))
                window.adminNotify(@json(session('info')), 'info');
            @endif
            @if ($errors->any())
                window.adminNotify(@json($errors->first()), 'error');
            @endif

            document.querySelectorAll('.alert').forEach((alert) => {
                const message = alert.textContent?.trim();
                if (!message) return;
                if (alert.classList.contains('alert-success')) window.adminNotify(message, 'success');
                else if (alert.classList.contains('alert-danger')) window.adminNotify(message, 'error');
                else if (alert.classList.contains('alert-warning')) window.adminNotify(message, 'warning');
                else window.adminNotify(message, 'info');
                alert.remove();
            });
        })();
    </script>
    <script>
        (() => {
            window.printAdminReport = function (title) {
                const existing = document.querySelector('.print-report-title');
                if (existing) existing.remove();

                const node = document.createElement('div');
                node.className = 'print-report-title';
                node.textContent = `${title} - ${new Date().toLocaleDateString()}`;

                const target = document.querySelector('.main-content') || document.querySelector('.nxl-content') || document.body;
                target.prepend(node);

                window.print();
                setTimeout(() => node.remove(), 300);
            };
        })();
    </script>
    @livewireScripts
    @stack('scripts')
</body>
</html>
