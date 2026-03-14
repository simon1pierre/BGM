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

        .admin-confirm-modal .modal-content {
            border-radius: .9rem;
            border: 1px solid #e2e8f0;
        }

        .admin-confirm-modal .modal-body {
            font-size: .95rem;
            color: #334155;
        }

        @media (max-width: 991.98px) {
            .nxl-header .header-wrapper {
                flex-wrap: wrap;
                gap: .75rem;
                padding: .75rem 1rem;
            }

            .nxl-header .header-left,
            .nxl-header .header-right {
                width: 100%;
            }

            .nxl-header .header-right .d-flex {
                flex-wrap: wrap;
                justify-content: flex-end;
                gap: .5rem;
            }

            .nxl-navigation {
                position: fixed;
                inset: 0 auto 0 0;
                width: min(86vw, 320px);
                transform: translateX(-105%);
                transition: transform .2s ease;
                z-index: 1050;
            }

            .nxl-navigation.mobile-open {
                transform: translateX(0);
            }

            .admin-mobile-overlay {
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, .55);
                opacity: 0;
                pointer-events: none;
                transition: opacity .2s ease;
                z-index: 1040;
            }

            .admin-mobile-overlay.show {
                opacity: 1;
                pointer-events: auto;
            }

            .nxl-container,
            .nxl-content,
            .main-content {
                padding-inline: 1rem !important;
            }

            .card,
            .card-body {
                border-radius: .9rem;
            }

            .table {
                display: block;
                width: 100%;
                overflow-x: auto;
                white-space: nowrap;
            }

            .table thead th,
            .table td {
                padding: .65rem .75rem;
            }

            .form-control,
            .form-select,
            .btn {
                min-height: 40px;
            }
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
    <div id="adminMobileOverlay" class="admin-mobile-overlay" aria-hidden="true"></div>
    <div class="modal fade admin-confirm-modal" id="adminConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title mb-0">Confirm Action</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3" id="adminConfirmMessage">
                    Are you sure?
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger btn-sm" id="adminConfirmAccept">Confirm</button>
                </div>
            </div>
        </div>
    </div>
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
            const modalElement = document.getElementById('adminConfirmModal');
            const messageElement = document.getElementById('adminConfirmMessage');
            const acceptElement = document.getElementById('adminConfirmAccept');
            const confirmModal = (window.bootstrap && modalElement) ? new window.bootstrap.Modal(modalElement) : null;
            let manualBackdrop = null;
            let onConfirm = null;

            const showConfirm = () => {
                if (!modalElement) return;
                if (confirmModal) {
                    confirmModal.show();
                    return;
                }

                modalElement.style.display = 'block';
                modalElement.classList.add('show');
                modalElement.removeAttribute('aria-hidden');
                document.body.classList.add('modal-open');

                manualBackdrop = document.createElement('div');
                manualBackdrop.className = 'modal-backdrop fade show';
                document.body.appendChild(manualBackdrop);
            };

            const hideConfirm = () => {
                if (!modalElement) return;
                if (confirmModal) {
                    confirmModal.hide();
                    return;
                }

                modalElement.style.display = 'none';
                modalElement.classList.remove('show');
                modalElement.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('modal-open');
                manualBackdrop?.remove();
                manualBackdrop = null;
            };

            const openConfirmModal = (message, callback, actionLabel = 'Confirm') => {
                if (!modalElement || !messageElement || !acceptElement) {
                    return;
                }

                onConfirm = callback;
                messageElement.textContent = String(message || 'Are you sure?');
                acceptElement.textContent = String(actionLabel || 'Confirm');
                showConfirm();
            };

            acceptElement?.addEventListener('click', () => {
                const callback = onConfirm;
                onConfirm = null;
                hideConfirm();
                if (typeof callback === 'function') callback();
            });

            modalElement?.addEventListener('hidden.bs.modal', () => {
                onConfirm = null;
            });

            modalElement?.querySelectorAll('[data-bs-dismiss="modal"]').forEach((button) => {
                button.addEventListener('click', () => {
                    onConfirm = null;
                    hideConfirm();
                });
            });

            document.addEventListener('keydown', (event) => {
                if (event.key !== 'Escape') return;
                if (!modalElement?.classList.contains('show')) return;
                onConfirm = null;
                hideConfirm();
            });

            document.addEventListener('submit', (event) => {
                const form = event.target;
                if (!(form instanceof HTMLFormElement)) return;
                if (!form.dataset.confirm) return;
                if (form.dataset.confirmed === '1') {
                    form.dataset.confirmed = '';
                    return;
                }

                event.preventDefault();

                openConfirmModal(
                    form.dataset.confirm,
                    () => {
                        form.dataset.confirmed = '1';
                        if (typeof form.requestSubmit === 'function') form.requestSubmit();
                        else form.submit();
                    },
                    form.dataset.confirmAction || 'Confirm'
                );
            }, true);

            document.addEventListener('click', (event) => {
                const trigger = event.target.closest('[data-confirm-trigger]');
                if (!trigger) return;

                event.preventDefault();
                const targetId = trigger.getAttribute('data-confirm-target');
                const target = targetId ? document.getElementById(targetId) : null;
                if (!target || !(target instanceof HTMLFormElement)) return;

                openConfirmModal(
                    trigger.getAttribute('data-confirm-message') || 'Are you sure?',
                    () => {
                        if (typeof target.requestSubmit === 'function') target.requestSubmit();
                        else target.submit();
                    },
                    trigger.getAttribute('data-confirm-action') || 'Confirm'
                );
            });

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

            const prettySize = (bytes) => {
                if (!Number.isFinite(bytes) || bytes <= 0) return '0 MB';
                const mb = bytes / (1024 * 1024);
                if (mb >= 1024) return `${(mb / 1024).toFixed(2)} GB`;
                return `${mb.toFixed(1)} MB`;
            };

            const updateUploadSummary = (input) => {
                if (!(input instanceof HTMLInputElement)) return;
                const selector = input.dataset.uploadSummaryTarget;
                if (!selector) return;
                const target = document.querySelector(selector);
                if (!target) return;

                const files = Array.from(input.files || []);
                if (files.length === 0) {
                    target.textContent = input.multiple ? 'No files selected.' : 'No file selected.';
                    target.classList.remove('text-danger');
                    target.classList.add('text-muted');
                    return;
                }

                const totalBytes = files.reduce((sum, file) => sum + (file?.size || 0), 0);
                target.textContent = `${files.length} file(s), total ${prettySize(totalBytes)}.`;
                target.classList.remove('text-muted');
                target.classList.add('text-dark');

                const warnMb = Number(input.dataset.uploadWarnMb || '0');
                const maxFiles = Number(input.dataset.uploadMaxFiles || '0');
                const tooManyFiles = maxFiles > 0 && files.length > maxFiles;
                const tooLarge = warnMb > 0 && totalBytes > (warnMb * 1024 * 1024);

                if (tooManyFiles || tooLarge) {
                    target.classList.remove('text-dark');
                    target.classList.add('text-warning');
                    const parts = [];
                    if (tooManyFiles) parts.push(`more than ${maxFiles} files`);
                    if (tooLarge) parts.push(`over ${warnMb} MB total`);
                    window.adminNotify(`Large upload selected (${parts.join(', ')}). Upload will still continue, but may take longer.`, 'info', 6000);
                }
            };

            document.querySelectorAll('input[data-upload-monitor]').forEach((input) => {
                input.addEventListener('change', () => updateUploadSummary(input));
                updateUploadSummary(input);
            });
        })();
    </script>
    <script>
        (() => {
            const toggle = document.getElementById('mobile-collapse');
            const nav = document.querySelector('.nxl-navigation');
            const overlay = document.getElementById('adminMobileOverlay');

            const closeNav = () => {
                nav?.classList.remove('mobile-open');
                overlay?.classList.remove('show');
            };

            toggle?.addEventListener('click', (event) => {
                event.preventDefault();
                nav?.classList.toggle('mobile-open');
                overlay?.classList.toggle('show');
            });

            overlay?.addEventListener('click', closeNav);
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 992) closeNav();
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







