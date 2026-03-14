<header class="nxl-header" style="background-color: #10295E; color:white">
   <div class="header-wrapper" style="color:white">
      <div class="header-left d-flex align-items-center gap-4" style="color: white">
         <a href="javascript:void(0);" class="nxl-head-mobile-toggler" style="color: white" id="mobile-collapse">
            <div class="hamburger hamburger--arrowturn" style="color: white">
               <div class="hamburger-box">
                  <div class="hamburger-inner" style="color: white"></div>
               </div>
            </div>
         </a>
         <div class="nxl-navigation-toggle">
            <a href="javascript:void(0);" id="menu-mini-button" style="color: white">
               <i class="feather-align-left" style="color: white"></i>
            </a>
            <a href="javascript:void(0);" id="menu-expend-button" style="display: none">
               <i class="feather-arrow-right" style="color:white"></i>
            </a>
         </div>
      </div>

      <div class="header-right ms-auto">
         <div class="d-flex align-items-center">
            <div class="nxl-h-item d-none d-sm-flex">
               <div class="full-screen-switcher">
                  <a href="javascript:void(0);" class="nxl-head-link me-0" onclick="$(`body`).fullScreenHelper(`toggle`);">
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
                  'user_created', 'user_updated', 'user_status_toggled', 'user_deleted', 'user_restored',
                  'password_reset', 'subscriber_created', 'subscriber_status_toggled', 'subscriber_deleted', 'subscriber_restored',
                  'video_created', 'video_updated', 'video_deleted', 'video_restored',
                  'audio_created', 'audio_updated', 'audio_deleted', 'audio_restored',
                  'audiobook_created', 'audiobook_updated', 'audiobook_deleted', 'audiobook_restored',
                  'document_created', 'document_updated', 'document_deleted', 'document_restored',
                  'category_created', 'category_updated', 'category_deleted', 'category_restored',
                  'playlist_created', 'playlist_updated', 'playlist_deleted', 'playlist_restored',
                  'event_created', 'event_updated', 'event_deleted', 'contact_message_replied',
               ];

               $labels = [
                  'user_created' => 'User registration',
                  'user_updated' => 'User account updated',
                  'user_status_toggled' => 'User status changed',
                  'user_deleted' => 'User account deleted',
                  'user_restored' => 'User account restored',
                  'password_reset' => 'Password reset by admin',
                  'subscriber_created' => 'New newsletter subscriber',
                  'subscriber_status_toggled' => 'Subscriber status changed',
                  'subscriber_deleted' => 'Subscriber deleted',
                  'subscriber_restored' => 'Subscriber restored',
                  'video_created' => 'Video added',
                  'video_updated' => 'Video updated',
                  'video_deleted' => 'Video deleted',
                  'video_restored' => 'Video restored',
                  'audio_created' => 'Audio added',
                  'audio_updated' => 'Audio updated',
                  'audio_deleted' => 'Audio deleted',
                  'audio_restored' => 'Audio restored',
                  'audiobook_created' => 'Audiobook added',
                  'audiobook_updated' => 'Audiobook updated',
                  'audiobook_deleted' => 'Audiobook deleted',
                  'audiobook_restored' => 'Audiobook restored',
                  'document_created' => 'Document added',
                  'document_updated' => 'Document updated',
                  'document_deleted' => 'Document deleted',
                  'document_restored' => 'Document restored',
                  'category_created' => 'Category added',
                  'category_updated' => 'Category updated',
                  'category_deleted' => 'Category deleted',
                  'category_restored' => 'Category restored',
                  'playlist_created' => 'Playlist added',
                  'playlist_updated' => 'Playlist updated',
                  'playlist_deleted' => 'Playlist deleted',
                  'playlist_restored' => 'Playlist restored',
                  'event_created' => 'Event added',
                  'event_updated' => 'Event updated',
                  'event_deleted' => 'Event deleted',
                  'contact_message_replied' => 'Contact message replied',
               ];

               $icons = [
                  'user_created' => 'feather-user-plus',
                  'user_updated' => 'feather-user-check',
                  'user_deleted' => 'feather-user-x',
                  'video_created' => 'feather-video',
                  'audio_created' => 'feather-headphones',
                  'audiobook_created' => 'feather-mic',
                  'document_created' => 'feather-file-text',
                  'event_created' => 'feather-calendar',
                  'contact_message_replied' => 'feather-mail',
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

               <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown p-0 border-0 shadow-lg overflow-hidden" style="width: 360px; border-radius: 14px;">
                  <div class="d-flex align-items-center justify-content-between px-3 py-3 bg-light border-bottom">
                     <div>
                        <div class="fw-bold text-dark">Notifications</div>
                        <div class="fs-11 text-muted">{{ $unreadCount }} unread</div>
                     </div>
                     <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                        @csrf
                        <button class="btn btn-sm btn-primary">Mark all</button>
                     </form>
                  </div>

                  <div class="px-2 py-2" style="max-height: 340px; overflow-y: auto;">
                     @forelse ($notifications as $notification)
                        @php
                           $title = $labels[$notification->action] ?? 'System update';
                           $icon = $icons[$notification->action] ?? 'feather-bell';
                           $actor = $notification->actorUser?->email ?? 'Guest';
                           $metaEmail = $notification->meta['email'] ?? null;
                           $isUnread = !$notification->reads->contains('user_id', $currentAdminId);
                        @endphp

                        <a href="{{ route('admin.notifications.show', $notification) }}" class="d-flex align-items-start gap-2 text-decoration-none px-2 py-2 rounded-3 {{ $isUnread ? 'bg-soft-primary' : '' }}">
                           <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white shadow-sm" style="width:34px;height:34px;">
                              <i class="{{ $icon }} text-primary"></i>
                           </span>
                           <span class="flex-grow-1">
                              <span class="d-flex align-items-center justify-content-between">
                                 <span class="fw-semibold text-dark">{{ $title }}</span>
                                 @if ($isUnread)
                                    <span class="badge bg-primary">New</span>
                                 @endif
                              </span>
                              <span class="d-block fs-12 text-muted">{{ $metaEmail ? $metaEmail.' � ' : '' }}{{ $actor }}</span>
                              <span class="d-block fs-11 text-muted">{{ $notification->created_at?->diffForHumans() }}</span>
                           </span>
                        </a>
                     @empty
                        <div class="text-center py-4">
                           <div class="mb-2"><i class="feather-bell-off text-muted fs-4"></i></div>
                           <div class="text-muted fs-12">No notifications yet.</div>
                        </div>
                     @endforelse
                  </div>

                  <div class="border-top px-3 py-2 bg-white text-end">
                     <a href="{{ route('admin.analytics.events') }}" class="fs-12 fw-semibold text-primary text-decoration-none">Open activity logs</a>
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
   </div>
</header>







