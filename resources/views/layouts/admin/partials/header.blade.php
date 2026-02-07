<header class="nxl-header" style="background-color: #10295E; color:white">
   <div class="header-wrapper" style="color:white">
      <!--! [Start] Header Left !-->
      <div class="header-left d-flex align-items-center gap-4">
         <!--! [Start] nxl-head-mobile-toggler !-->
         <a href="javascript:void(0);" class="nxl-head-mobile-toggler" style="color: white" id="mobile-collapse">
            <div class="hamburger hamburger--arrowturn" style="color: white">
               <div class="hamburger-box">
                  <div class="hamburger-inner" style="color: white"></div>
               </div>
            </div>
         </a>
         <!--! [Start] nxl-head-mobile-toggler !-->
         <!--! [Start] nxl-navigation-toggle !-->
         <div class="nxl-navigation-toggle">
            <a href="javascript:void(0);" id="menu-mini-button">
            <i class="feather-align-left" style="color: white"></i>
            </a>
            <a href="javascript:void(0);" id="menu-expend-button" style="display: none">
            <i class="feather-arrow-right"></i>
            </a>
         </div>
        {{-- items removed --}}
      </div>
      <!--! [End] Header Left !-->
      <!--! [Start] Header Right !-->
      <div class="header-right ms-auto">
         <div class="d-flex align-items-center">
            <div class="nxl-h-item d-none d-sm-flex">
               <div class="full-screen-switcher">
                  <a href="javascript:void(0);" class="nxl-head-link me-0" onclick="$('body').fullScreenHelper('toggle');">
                  <i class="feather-maximize maximize" style="color: white"></i>
                  <i class="feather-minimize minimize" style="color: white"></i>
                  </a>
               </div>
            </div>
            <div class="nxl-h-item dark-light-theme">
               <a href="javascript:void(0);" class="nxl-head-link me-0 dark-button">
               <i class="feather-moon" style="color: white"></i>
               </a>
               <a href="javascript:void(0);" class="nxl-head-link me-0 light-button" style="display: none">
               <i class="feather-sun"></i>
               </a>
            </div>
            
            @php
               $inAppActions = [
                  'user_created',
                  'user_updated',
                  'user_status_toggled',
                  'user_deleted',
                  'user_restored',
                  'password_reset',
                  'subscriber_created',
               ];
               $currentAdminId = Auth::id();
               $unreadCount = 0;
               $notifications = collect();
               if ($currentAdminId) {
                  $unreadCount = \App\Models\UserActivityLog::query()
                     ->whereIn('action', $inAppActions)
                     ->whereDoesntHave('reads', function ($q) use ($currentAdminId) {
                        $q->where('user_id', $currentAdminId);
                     })
                     ->count();

                  $notifications = \App\Models\UserActivityLog::query()
                     ->whereIn('action', $inAppActions)
                     ->orderByDesc('created_at')
                     ->limit(6)
                     ->get();
               }
            @endphp
            <div class="dropdown nxl-h-item">
               <a class="nxl-head-link me-3" data-bs-toggle="dropdown" href="#" role="button" data-bs-auto-close="outside">
               <i class="feather-bell" style="color: white"></i>
               @if ($unreadCount > 0)
                  <span class="badge bg-danger nxl-h-badge">{{ $unreadCount }}</span>
               @endif
               </a>
               <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown">
                  <div class="dropdown-header d-flex align-items-center justify-content-between">
                     <span class="text-dark fw-semibold">Notifications</span>
                     <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                        @csrf
                        <button class="btn btn-sm btn-light">Mark all as read</button>
                     </form>
                  </div>
                  <div class="dropdown-divider"></div>
                  <div class="px-3 pb-2" style="max-height: 280px; overflow-y: auto;">
                     @forelse ($notifications as $notification)
                        @php
                           $labels = [
                              'user_created' => 'New user account created',
                              'user_updated' => 'User account updated',
                              'user_status_toggled' => 'User status changed',
                              'user_deleted' => 'User account deleted',
                              'user_restored' => 'User account restored',
                              'password_reset' => 'Password reset by admin',
                              'subscriber_created' => 'New newsletter subscriber',
                           ];
                           $title = $labels[$notification->action] ?? 'System update';
                           $actor = $notification->actorUser?->email ?? 'Guest';
                           $metaEmail = $notification->meta['email'] ?? null;
                        @endphp
                        <a href="{{ route('admin.notifications.show', $notification) }}" class="d-block text-decoration-none py-2 border-bottom">
                           <div class="fw-semibold text-dark">{{ $title }}</div>
                           <div class="fs-12 text-muted">
                              {{ $metaEmail ? $metaEmail.' • ' : '' }}{{ $actor }} • {{ $notification->created_at?->diffForHumans() }}
                           </div>
                        </a>
                     @empty
                        <div class="text-muted fs-12">No notifications.</div>
                     @endforelse
                  </div>
               </div>
            </div>
            <div class="dropdown nxl-h-item">
               <a href="javascript:void(0);" data-bs-toggle="dropdown" role="button" data-bs-auto-close="outside">
               <img src="{{ Auth::user()?->avatar ? asset('storage/'.Auth::user()->avatar) : asset('admin/assets/images/avatar/1.png') }}" alt="user-image" class="img-fluid user-avtar me-0" />
               </a>
               <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-user-dropdown">
                  <div class="dropdown-header">
                     <div class="d-flex align-items-center">
                        <img src="{{ Auth::user()?->avatar ? asset('storage/'.Auth::user()->avatar) : asset('admin/assets/images/avatar/1.png') }}" alt="user-image" class="img-fluid user-avtar" />
                        <div>
                           @auth
                              <h6 class="text-dark mb-0">{{ Auth::user()->name }}</h6>
                              <span class="fs-12 fw-medium text-muted">{{ Auth::user()->email }}</span>
                           @else
                              <h6 class="text-dark mb-0">Guest</h6>
                              <span class="fs-12 fw-medium text-muted">Not signed in</span>
                           @endauth
                        </div>
                     </div>
                  </div>
                  
                  <div class="dropdown-divider"></div>
                  <a href="javascript:void(0);" class="dropdown-item">
                  <i class="feather-user"></i>
                  <span>Profile Details</span>
                  </a>
                  <a href="javascript:void(0);" class="dropdown-item">
                  <i class="feather-settings"></i>
                  <span>Account Settings</span>
                  </a>
                  <div class="dropdown-divider"></div>
                  @auth
                     <form action="{{ route('admin.logout') }}" method="POST" class="px-2">
                        @csrf
                        <button type="submit" class="dropdown-item w-100 text-start">
                           <i class="feather-log-out"></i>
                           <span>Logout</span>
                        </button>
                     </form>
                  @endauth
               </div>
            </div>
         </div>
      </div>
      <!--! [End] Header Right !-->
   </div>
</header>
