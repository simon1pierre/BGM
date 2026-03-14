<nav class="nxl-navigation" style="background-color: #10295E; color:white">
        <div class="navbar-wrapper">
            <div class="m-header" style="background-color: #10295E">
                <a href="{{route('admin.dashboard')}}" class="b-brand">
                    <h4 class="logo logo-lg" style="color: white">Dashboard</h4>
                    <img src="{{ asset('images/logo.png') }}" alt="" class="logo logo-sm" />
                </a>
            </div>
            @php
                $adminNavCounts = $adminNavCounts ?? [];
                $count = function (string $key) use ($adminNavCounts): int {
                    return (int) ($adminNavCounts[$key] ?? 0);
                };
                $isDashboard = request()->routeIs('admin.dashboard');
                $isUsers = request()->routeIs('admin.users.*');
                $isSettings = request()->routeIs('admin.settings.*');
                $isCampaigns = request()->routeIs('admin.campaigns.*');
                $isSubscribers = request()->routeIs('admin.subscribers.*');
                $isContacts = request()->routeIs('admin.contacts.*');
                $isEvents = request()->routeIs('admin.events.*');
                $isMinistryLeaders = request()->routeIs('admin.ministry-leaders.*');
                $isVideos = request()->routeIs('admin.videos.*');
                $isAudios = request()->routeIs('admin.audios.*');
                $isDocuments = request()->routeIs('admin.documents.*') || request()->routeIs('admin.audiobooks.*');
                $isCategories = request()->routeIs('admin.categories.*');
                $isContentNotifications = request()->routeIs('admin.content-notifications.*');
                $isPlaylists = request()->routeIs('admin.playlists.*');
                $isVideoSeries = request()->routeIs('admin.video-series.*');
                $isDevotionals = request()->routeIs('admin.devotionals.*');
                $isAnalytics = request()->routeIs('admin.analytics.*');
                $isTrash = request()->routeIs('admin.trash.*');
                $isTranslations = request()->routeIs('admin.translations.*');
                $translationStatus = request()->query('status', 'needs_review');
                $translationTranslatedBy = request()->query('translated_by', 'all');
                $isTranslationQueue = $isTranslations && ($translationStatus === 'all' || $translationStatus === '');
                $isTranslationNeedsReview = $isTranslations && $translationStatus === 'needs_review';
                $isTranslationApproved = $isTranslations && $translationStatus === 'approved';
                $isTranslationManual = $isTranslations && $translationTranslatedBy === 'manual';
                $isTranslationSearch = request()->routeIs('admin.translations.search');
                $isContentMenu = $isVideos || $isAudios || $isDocuments || $isCategories || $isContentNotifications || $isPlaylists || $isVideoSeries || $isDevotionals;
            @endphp
            <div class="navbar-content" style="background-color: #10295E">
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
                    <li class="nxl-item nxl-hasmenu {{ $isUsers ? 'active' : '' }}">
                        <a href="{{ route('admin.users.index') }}" class="nxl-link">
                            <div class="d-flex align-items-center justify-content-between w-100">
                                <div class="d-flex align-items-center">
                                    <span class="nxl-micon"><i style="color: white" class="feather-cast"></i></span>
                                    <span class="nxl-mtext" style="color: white">Users</span>
                                </div>
                                <span class="badge bg-light text-dark ms-2">{{ $count('users') }}</span>
                            </div>
                        </a>
                    </li>
                    <li class="nxl-item nxl-hasmenu {{ $isMinistryLeaders ? 'active' : '' }}">
                        <a href="{{ route('admin.ministry-leaders.index') }}" class="nxl-link">
                            <div class="d-flex align-items-center justify-content-between w-100">
                                <div class="d-flex align-items-center">
                                    <span class="nxl-micon"><i style="color: white" class="feather-user-check"></i></span>
                                    <span class="nxl-mtext" style="color: white">Ministry Team</span>
                                </div>
                                <span class="badge bg-light text-dark ms-2">{{ $count('ministry') }}</span>
                            </div>
                        </a>
                    </li>
                    <li class="nxl-item nxl-hasmenu {{ $isSubscribers ? 'active' : '' }}">
                        <a href="{{ route('admin.subscribers.index') }}" class="nxl-link">
                            <div class="d-flex align-items-center justify-content-between w-100">
                                <div class="d-flex align-items-center">
                                    <span class="nxl-micon"><i style="color: white" class="feather-users"></i></span>
                                    <span class="nxl-mtext" style="color: white">Subscribers</span>
                                </div>
                                <span class="badge bg-light text-dark ms-2">{{ $count('subscribers') }}</span>
                            </div>
                        </a>
                    </li>
                    <li class="nxl-item nxl-hasmenu {{ $isCampaigns ? 'active' : '' }}">
                        <a href="{{ route('admin.campaigns.index') }}" class="nxl-link">
                            <div class="d-flex align-items-center justify-content-between w-100">
                                <div class="d-flex align-items-center">
                                    <span class="nxl-micon"><i style="color: white" class="feather-mail"></i></span>
                                    <span class="nxl-mtext" style="color: white">Campaigns</span>
                                </div>
                                <span class="badge bg-light text-dark ms-2">{{ $count('campaigns') }}</span>
                            </div>
                        </a>
                    </li>
                    <li class="nxl-item nxl-hasmenu {{ $isContentMenu ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i style="color: white" class="feather-film"></i></span>
                            <span class="nxl-mtext" style="color: white">Content</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item {{ $isCategories ? 'active' : '' }}"><a class="nxl-link d-flex justify-content-between" style="color: white" href="{{ route('admin.categories.index') }}"><span>Categories</span><span class="badge bg-light text-dark">{{ $count('categories') }}</span></a></li>
                            <li class="nxl-item {{ $isVideoSeries ? 'active' : '' }}"><a class="nxl-link d-flex justify-content-between" style="color: white" href="{{ route('admin.video-series.index') }}"><span>Video Series</span><span class="badge bg-light text-dark">{{ $count('video_series') }}</span></a></li>
                            <li class="nxl-item {{ $isPlaylists ? 'active' : '' }}"><a class="nxl-link d-flex justify-content-between" style="color: white" href="{{ route('admin.playlists.index') }}"><span>Audio Playlists</span><span class="badge bg-light text-dark">{{ $count('playlists') }}</span></a></li>
                            <li class="nxl-item {{ $isVideos ? 'active' : '' }}"><a class="nxl-link d-flex justify-content-between" style="color: white" href="{{ route('admin.videos.index') }}"><span>Videos</span><span class="badge bg-light text-dark">{{ $count('videos') }}</span></a></li>
                            <li class="nxl-item {{ $isAudios ? 'active' : '' }}"><a class="nxl-link d-flex justify-content-between" style="color: white" href="{{ route('admin.audios.index') }}"><span>Audios</span><span class="badge bg-light text-dark">{{ $count('audios') }}</span></a></li>
                            <li class="nxl-item {{ $isDocuments ? 'active' : '' }}"><a class="nxl-link d-flex justify-content-between" style="color: white" href="{{ route('admin.documents.index') }}"><span>Books</span><span class="badge bg-light text-dark">{{ $count('documents') }}</span></a></li>
                            <li class="nxl-item {{ $isDevotionals ? 'active' : '' }}"><a class="nxl-link d-flex justify-content-between" style="color: white" href="{{ route('admin.devotionals.index') }}"><span>Devotionals</span><span class="badge bg-light text-dark">{{ $count('devotionals') }}</span></a></li>
                            <li class="nxl-item {{ $isContentNotifications ? 'active' : '' }}"><a class="nxl-link" style="color: white" href="{{ route('admin.content-notifications.index') }}">Content Emails</a></li>
                        </ul>
                    </li>
                    <li class="nxl-item nxl-hasmenu {{ $isContacts ? 'active' : '' }}">
                        <a href="{{ route('admin.contacts.index') }}" class="nxl-link">
                            <div class="d-flex align-items-center justify-content-between w-100">
                                <div class="d-flex align-items-center">
                                    <span class="nxl-micon"><i style="color: white" class="feather-inbox"></i></span>
                                    <span class="nxl-mtext" style="color: white">Contact Inbox</span>
                                </div>
                                <span class="badge bg-warning text-dark ms-2">{{ $count('contacts_unread') }}</span>
                            </div>
                        </a>
                    </li>
                    <li class="nxl-item nxl-hasmenu {{ $isEvents ? 'active' : '' }}">
                        <a href="{{ route('admin.events.index') }}" class="nxl-link">
                            <div class="d-flex align-items-center justify-content-between w-100">
                                <div class="d-flex align-items-center">
                                    <span class="nxl-micon"><i style="color: white" class="feather-calendar"></i></span>
                                    <span class="nxl-mtext" style="color: white">Events</span>
                                </div>
                                <span class="badge bg-light text-dark ms-2">{{ $count('events') }}</span>
                            </div>
                        </a>
                    </li>
                    <li class="nxl-item nxl-hasmenu {{ $isAnalytics ? 'active' : '' }}">
                        <a href="{{ route('admin.analytics.index') }}" class="nxl-link">
                            <span class="nxl-micon"><i style="color: white" class="feather-activity"></i></span>
                            <span class="nxl-mtext" style="color: white">Analytics</span>
                        </a>
                    </li>
                    <li class="nxl-item nxl-hasmenu {{ $isTrash ? 'active' : '' }}">
                        <a href="{{ route('admin.trash.index') }}" class="nxl-link">
                            <div class="d-flex align-items-center justify-content-between w-100">
                                <div class="d-flex align-items-center">
                                    <span class="nxl-micon"><i style="color: white" class="feather-trash-2"></i></span>
                                    <span class="nxl-mtext" style="color: white">Trash</span>
                                </div>
                                <span class="badge bg-danger text-white ms-2">{{ $count('trash') }}</span>
                            </div>
                        </a>
                    </li>
                    <li class="nxl-item nxl-hasmenu {{ $isTranslations ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i style="color: white" class="feather-check-square"></i></span>
                            <span class="nxl-mtext" style="color: white">Translations</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item {{ $isTranslationQueue ? 'active' : '' }}">
                                <a class="nxl-link" style="color: white" href="{{ route('admin.translations.review', ['status' => 'all']) }}">Queue</a>
                            </li>
                            <li class="nxl-item {{ $isTranslationNeedsReview ? 'active' : '' }}">
                                <a class="nxl-link" style="color: white" href="{{ route('admin.translations.review', ['status' => 'needs_review']) }}">Needs Review</a>
                            </li>
                            <li class="nxl-item {{ $isTranslationApproved ? 'active' : '' }}">
                                <a class="nxl-link" style="color: white" href="{{ route('admin.translations.review', ['status' => 'approved']) }}">Approved</a>
                            </li>
                            <li class="nxl-item {{ $isTranslationManual ? 'active' : '' }}">
                                <a class="nxl-link" style="color: white" href="{{ route('admin.translations.review', ['translated_by' => 'manual', 'status' => 'all']) }}">Manual</a>
                            </li>
                            <li class="nxl-item {{ $isTranslationSearch ? 'active' : '' }}">
                                <a class="nxl-link" style="color: white" href="{{ route('admin.translations.search') }}">Search & Edit</a>
                            </li>
                        </ul>
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







