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
</head>

<body>
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
</body>
</html>
