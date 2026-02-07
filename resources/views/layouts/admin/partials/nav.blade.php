<nav class="nxl-navigation" style="background-color: #10295E; color:white">
        <div class="navbar-wrapper">
            <div class="m-header" style="background-color: #10295E">
                <a href="#" class="b-brand">
                    <h4 class="logo logo-lg" style="color: white">Dashboard</h4>
                    <img src="{{ asset('images/logo.png') }}" alt="" class="logo logo-sm" />
                </a>
            </div>
            <div class="navbar-content">
                <ul class="nxl-navbar">
                    <li class="nxl-item nxl-caption">
                        <label style="color: white">Navigation</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu" style="color: white">
                        <a href="{{ route('admin.dashboard') }}" class="nxl-link">
                            <span class="nxl-micon"><i style="color: white" class="feather-airplay"></i></span>
                            <span class="nxl-mtext" style="color: white">Dashboard</span>
                        </a>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i style="color: white" class="feather-cast"></i></span>
                            <span class="nxl-mtext" style="color: white">Reports</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item"><a class="nxl-link" style="color: white" href="#">Sales Report</a></li>
                            <li class="nxl-item"><a class="nxl-link" style="color: white" href="#">Leads Report</a></li>
                        </ul>
                    </li>
                    <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i style="color: white" class="feather-cast"></i></span>
                            <span class="nxl-mtext" style="color: white">Users</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item"><a class="nxl-link" style="color: white" href="{{ route('admin.users.index') }}">View</a></li>
                            <li class="nxl-item"><a class="nxl-link" style="color: white" href="{{ route('admin.register')}}">Register</a></li>
                        </ul>
                    </li>
                    
                    <!-- Authentication links intentionally hidden (secret admin URL) -->
                    
                </ul> 
            </div>
        </div>
    </nav>
