<nav class="nxl-navigation" style="background-color: #10295E; color:white">
        <div class="navbar-wrapper">
            <div class="m-header" style="background-color: #10295E">
                <a href="{{route('admin.dashboard')}}" class="b-brand">
                    <h4 class="logo logo-lg" style="color: white">Dashboard</h4>
                    <img src="{{ asset('images/logo.png') }}" alt="" class="logo logo-sm" />
                </a>
            </div>
            @php
                $isDashboard = request()->routeIs('admin.dashboard');
                $isUsers = request()->routeIs('admin.users.*');
                $isRegister = request()->routeIs('admin.register*');
                $isUsersMenu = $isUsers || $isRegister;
                $isSettings = request()->routeIs('admin.settings.*');
                $isCampaigns = request()->routeIs('admin.campaigns.*');
                $isSubscribers = request()->routeIs('admin.subscribers.*');
                $isVideos = request()->routeIs('admin.videos.*');
                $isAudios = request()->routeIs('admin.audios.*');
                $isDocuments = request()->routeIs('admin.documents.*');
                $isCategories = request()->routeIs('admin.categories.*');
                $isContentNotifications = request()->routeIs('admin.content-notifications.*');
                $isPlaylists = request()->routeIs('admin.playlists.*');
                $isAnalytics = request()->routeIs('admin.analytics.*');
                $isContentMenu = $isVideos || $isAudios || $isDocuments || $isCategories || $isContentNotifications || $isPlaylists;
            @endphp
            <div class="navbar-content">
                <ul class="nxl-navbar">
                    <li class="nxl-item nxl-caption">
                        <label style="color: white">Navigation</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu {{ $isDashboard ? 'active' : '' }}" style="color: white">
                        <a href="{{ route('admin.dashboard') }}" class="nxl-link">
                            <span class="nxl-micon"><i style="color: white" class="feather-airplay"></i></span>
                            <span class="nxl-mtext" style="color: white">Dashboard</span>
                        </a>
                    </li>
                    <li class="nxl-item nxl-hasmenu {{ $isUsersMenu ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i style="color: white" class="feather-cast"></i></span>
                            <span class="nxl-mtext" style="color: white">Users</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item {{ $isUsers ? 'active' : '' }}"><a class="nxl-link" style="color: white" href="{{ route('admin.users.index') }}">View</a></li>
                            <li class="nxl-item {{ $isRegister ? 'active' : '' }}"><a class="nxl-link" style="color: white" href="{{ route('admin.register')}}">Register</a></li>
                        </ul>
                    </li>
                    <li class="nxl-item nxl-hasmenu {{ $isCampaigns ? 'active' : '' }}">
                        <a href="{{ route('admin.campaigns.index') }}" class="nxl-link">
                            <span class="nxl-micon"><i style="color: white" class="feather-mail"></i></span>
                            <span class="nxl-mtext" style="color: white">Campaigns</span>
                        </a>
                    </li>
                    <li class="nxl-item nxl-hasmenu {{ $isContentMenu ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i style="color: white" class="feather-film"></i></span>
                            <span class="nxl-mtext" style="color: white">Content</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item {{ $isCategories ? 'active' : '' }}"><a class="nxl-link" style="color: white" href="{{ route('admin.categories.index') }}">Categories</a></li>
                            <li class="nxl-item {{ $isPlaylists ? 'active' : '' }}"><a class="nxl-link" style="color: white" href="{{ route('admin.playlists.index') }}">Playlists</a></li>
                            <li class="nxl-item {{ $isVideos ? 'active' : '' }}"><a class="nxl-link" style="color: white" href="{{ route('admin.videos.index') }}">Videos</a></li>
                            <li class="nxl-item {{ $isAudios ? 'active' : '' }}"><a class="nxl-link" style="color: white" href="{{ route('admin.audios.index') }}">Audios</a></li>
                            <li class="nxl-item {{ $isDocuments ? 'active' : '' }}"><a class="nxl-link" style="color: white" href="{{ route('admin.documents.index') }}">Documents</a></li>
                            <li class="nxl-item {{ $isContentNotifications ? 'active' : '' }}"><a class="nxl-link" style="color: white" href="{{ route('admin.content-notifications.index') }}">Content Emails</a></li>
                        </ul>
                    </li>
                    <li class="nxl-item nxl-hasmenu {{ $isSubscribers ? 'active' : '' }}">
                        <a href="{{ route('admin.subscribers.index') }}" class="nxl-link">
                            <span class="nxl-micon"><i style="color: white" class="feather-users"></i></span>
                            <span class="nxl-mtext" style="color: white">Subscribers</span>
                        </a>
                    </li>
                    <li class="nxl-item nxl-hasmenu {{ $isAnalytics ? 'active' : '' }}">
                        <a href="{{ route('admin.analytics.index') }}" class="nxl-link">
                            <span class="nxl-micon"><i style="color: white" class="feather-activity"></i></span>
                            <span class="nxl-mtext" style="color: white">Analytics</span>
                        </a>
                    </li>
                    <li class="nxl-item nxl-hasmenu {{ $isSettings ? 'active' : '' }}">
                        <a href="{{ route('admin.settings.edit') }}" class="nxl-link">
                            <span class="nxl-micon"><i style="color: white" class="feather-settings"></i></span>
                            <span class="nxl-mtext" style="color: white">Settings</span>
                        </a>
                    </li>
                    
                    <!-- Authentication links intentionally hidden (secret admin URL) -->
                    
                </ul> 
            </div>
        </div>
    </nav>
