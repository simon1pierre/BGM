@extends('layouts.audiences.app')
@section('contents')
<main class="grow bg-slate-50">
    <section class="pt-20 pb-10 bg-gradient-to-b from-blue-950 to-slate-900 text-white">
        <div class="container mx-auto px-6">
            <div class="max-w-3xl">
                <span class="inline-block py-1 px-3 rounded-full bg-blue-500/20 border border-blue-300/30 text-blue-100 text-xs font-medium tracking-widest uppercase mb-4">
                    {{ __('messages.videos.badge') }}
                </span>
                <h1 class="text-3xl md:text-5xl font-serif font-bold mb-4">{{ __('messages.videos.title') }}</h1>
                <p class="text-blue-100/90 text-lg">{{ __('messages.videos.subtitle') }}</p>
            </div>
        </div>
    </section>

    <section class="py-10">
        <div class="container mx-auto px-6">
            <div class="flex flex-col lg:flex-row lg:items-center gap-4 pb-4">
                <form method="GET" action="{{ route('videos.index') }}" class="w-full lg:max-w-md">
                    <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-full px-4 py-2 shadow-sm">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"></path>
                        </svg>
                        <input
                            type="text"
                            name="q"
                            value="{{ $search ?? '' }}"
                            placeholder="{{ __('messages.videos.search_placeholder') }}"
                            class="w-full bg-transparent text-sm text-slate-700 placeholder:text-slate-400 focus:outline-none"
                        >
                        @if (!empty($activeCategory))
                            <input type="hidden" name="category" value="{{ $activeCategory }}">
                        @endif
                        @if (!empty($onlyFeatured))
                            <input type="hidden" name="featured" value="1">
                        @endif
                    </div>
                </form>
                <div class="flex items-center gap-3 overflow-x-auto">
                @php
                    $allActive = empty($activeCategory);
                @endphp
                <a href="{{ route('videos.index') }}"
                   class="whitespace-nowrap px-4 py-2 rounded-full border text-sm font-medium inline-flex items-center gap-2 {{ $allActive && !$onlyFeatured ? 'bg-blue-900 text-white border-blue-900' : 'bg-white text-slate-700 border-slate-200' }}">
                    {{ __('messages.common.all') }}
                    <span class="text-[11px] px-2 py-0.5 rounded-full {{ $allActive && !$onlyFeatured ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700' }}">{{ $allCount ?? 0 }}</span>
                </a>
                <a href="{{ route('videos.index', ['featured' => 1]) }}"
                   class="whitespace-nowrap px-4 py-2 rounded-full border text-sm font-medium inline-flex items-center gap-2 {{ $onlyFeatured ? 'bg-amber-500 text-white border-amber-500' : 'bg-white text-slate-700 border-slate-200' }}">
                    {{ __('messages.common.featured') }}
                    <span class="text-[11px] px-2 py-0.5 rounded-full {{ $onlyFeatured ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700' }}">{{ $featuredCount ?? 0 }}</span>
                </a>
                @foreach ($categories as $category)
                    <a href="{{ route('videos.index', ['category' => $category->id, 'featured' => $onlyFeatured ? 1 : null]) }}"
                       class="whitespace-nowrap px-4 py-2 rounded-full border text-sm font-medium inline-flex items-center gap-2 {{ (string) $activeCategory === (string) $category->id ? 'bg-blue-900 text-white border-blue-900' : 'bg-white text-slate-700 border-slate-200' }}">
                        {{ $category->name }}
                        <span class="text-[11px] px-2 py-0.5 rounded-full {{ (string) $activeCategory === (string) $category->id ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-700' }}">{{ $category->videos_count ?? 0 }}</span>
                    </a>
                @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-6">
                @forelse ($videos as $video)
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow border border-slate-100 flex flex-col">
                        <div class="relative aspect-video overflow-hidden" data-video-card data-video-db-id="{{ $video->id }}">
                            @if ($video->thumbnail_url)
                                <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-500">{{ __('messages.common.no_thumbnail') }}</div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/70 via-transparent to-transparent"></div>
                            <div class="absolute bottom-3 left-3 text-white text-sm font-medium">
                                {{ $video->category?->name ?? __('messages.common.sermon') }}
                            </div>
                            @if ($video->featured)
                                <span class="absolute top-3 left-3 bg-amber-500 text-white text-xs font-semibold px-3 py-1 rounded-full shadow">{{ __('messages.common.featured') }}</span>
                            @endif
                            @if ($video->youtube_id)
                                <button
                                    class="absolute inset-0 flex items-center justify-center"
                                    data-video-title="{{ $video->title }}"
                                    data-video-id="{{ $video->youtube_id }}"
                                    data-video-url="{{ $video->youtube_url }}"
                                    data-video-db-id="{{ $video->id }}"
                                    onclick="openVideoModal(this)"
                                    type="button" aria-label="Play video"
                                >
                                    <span class="w-14 h-14 rounded-full bg-white/90 text-blue-900 flex items-center justify-center shadow-lg">
                                        <svg viewBox="0 0 24 24" class="w-6 h-6" aria-hidden="true"><path fill="currentColor" d="M8 5v14l11-7z" /></svg>
                                    </span>
                                </button>
                            @endif
                        </div>
                        <div class="p-6 flex-1 flex flex-col">
                            <h3 class="text-xl font-serif font-bold text-blue-950 mb-2">{{ $video->title }}</h3>
                            <p class="text-slate-600 text-sm mb-4">{{ \Illuminate\Support\Str::limit($video->description, 140) }}</p>
                            <div class="flex items-center gap-4 text-xs text-slate-500 mb-4">
                                <button
                                    type="button"
                                    class="inline-flex items-center gap-1 text-slate-600 hover:text-rose-600 transition-colors"
                                    data-like-button
                                    data-video-id="{{ $video->id }}"
                                    onclick="toggleLike(this)"
                                >
                                    <svg viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true">
                                        <path fill="currentColor" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 6 3.99 4 6.5 4c1.74 0 3.41 0.81 4.5 2.09C12.09 4.81 13.76 4 15.5 4 18.01 4 20 6 20 8.5c0 3.78-3.4 6.86-8.55 11.54z"/>
                                    </svg>
                                    <span>{{ __('messages.common.like') }}</span>
                                    <span data-like-count>{{ $video->likes_count ?? 0 }}</span>
                                </button>
                                <button
                                    type="button"
                                    class="text-slate-600 hover:text-blue-700 transition-colors"
                                    data-comment-toggle
                                    onclick="toggleCommentPanel(this)"
                                >
                                    {{ __('messages.common.comment') }} (<span data-comment-count>{{ $video->comments_count ?? 0 }}</span>)
                                </button>
                                <button
                                    type="button"
                                    class="text-slate-600 hover:text-emerald-600 transition-colors"
                                    data-share-button
                                    data-video-id="{{ $video->id }}"
                                    data-video-title="{{ $video->title }}"
                                    data-video-url="{{ $video->youtube_url }}"
                                    onclick="shareFromCard(this)"
                                >
                                    {{ __('messages.common.share') }}
                                </button>
                            </div>
                            <div class="hidden space-y-3 border-t border-slate-100 pt-4" data-comment-panel>
                                <div class="space-y-2" data-comment-list>
                                    @foreach ($video->comments as $comment)
                                        <div class="text-xs text-slate-600 bg-slate-50 rounded-lg p-3">
                                            <div class="font-semibold text-slate-700">{{ $comment->name ?: __('messages.common.anonymous') }}</div>
                                            <div class="mt-1">{{ $comment->body }}</div>
                                        </div>
                                    @endforeach
                                </div>
                                <form data-comment-form data-video-id="{{ $video->id }}" onsubmit="return submitComment(this)">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        <input type="text" name="name" placeholder="{{ __('messages.common.name_optional') }}" class="rounded-lg border border-slate-200 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400">
                                        <input type="email" name="email" placeholder="{{ __('messages.common.email_optional') }}" class="rounded-lg border border-slate-200 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    </div>
                                    <textarea name="body" rows="3" placeholder="{{ __('messages.common.write_comment') }}" class="mt-2 w-full rounded-lg border border-slate-200 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-400" required></textarea>
                                    <button type="submit" class="mt-2 inline-flex items-center justify-center px-4 py-2 text-xs font-semibold text-white bg-blue-900 rounded-lg hover:bg-blue-800 transition-colors">
                                        {{ __('messages.common.post_comment') }}
                                    </button>
                                </form>
                            </div>
                            <div class="mt-auto flex items-center justify-between">
                                <span class="text-xs text-slate-500">{{ $video->published_at?->toDateString() ?? $video->created_at?->toDateString() }}</span>
                                <a href="{{ $video->youtube_url }}" target="_blank" class="text-blue-700 font-medium text-sm hover:text-blue-900" onclick="trackYoutubeClick({{ $video->id }})">{{ __('messages.common.watch_on_youtube') }}</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center text-slate-500">{{ __('messages.videos.none') }}</div>
                @endforelse
            </div>

            @if (!empty($recommendedVideos) && $recommendedVideos->count())
                <div class="mt-12">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-serif font-bold text-blue-950">{{ __('messages.common.recommended_for_you') }}</h3>
                        <a href="{{ route('videos.index') }}" class="text-sm text-blue-700 hover:text-blue-900">{{ __('messages.common.view_all') }}</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach ($recommendedVideos as $video)
                            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100">
                                <div class="relative aspect-video overflow-hidden">
                                    @if ($video->thumbnail_url)
                                        <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-slate-200 flex items-center justify-center text-slate-500">{{ __('messages.common.no_thumbnail') }}</div>
                                    @endif
                                    <div class="absolute bottom-3 left-3 text-white text-xs font-medium drop-shadow">
                                        {{ $video->category?->name ?? __('messages.common.sermon') }}
                                    </div>
                                    @if ($video->featured)
                                        <span class="absolute top-3 left-3 bg-amber-500 text-white text-xs font-semibold px-3 py-1 rounded-full shadow">{{ __('messages.common.featured') }}</span>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <div class="font-serif text-blue-950 font-semibold text-sm">{{ $video->title }}</div>
                                    <div class="text-xs text-slate-500 mt-1">{{ $video->published_at?->toDateString() ?? $video->created_at?->toDateString() }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mt-8">
                {{ $videos->links() }}
            </div>
        </div>
    </section>

    <div id="videoModal" class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl overflow-hidden w-full max-w-4xl">
            <div class="flex items-center justify-between px-4 py-3 border-b">
                <h3 id="videoModalTitle" class="font-semibold text-slate-900"></h3>
                <button type="button" class="text-slate-500 hover:text-slate-900" onclick="closeVideoModal()" aria-label="Close video"><svg viewBox="0 0 24 24" class="w-5 h-5" aria-hidden="true"><path fill="currentColor" d="M18.3 5.71L12 12l6.3 6.29-1.41 1.42L10.59 13.4 4.29 19.71 2.88 18.3 9.17 12 2.88 5.71 4.29 4.29 10.59 10.6l6.3-6.31z"/></svg></button>
            </div>
            <div class="relative aspect-video bg-black">
                <iframe id="videoModalFrame" class="absolute inset-0 w-full h-full" src="" title="Video" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>
            <div class="px-4 py-3 border-t flex flex-wrap gap-3 justify-end">
                <button type="button" class="px-4 py-2 border border-slate-200 text-slate-700 rounded-lg hover:bg-slate-50" onclick="shareVideo()">{{ __('messages.common.share') }}</button>
                <a id="videoModalLink" href="#" target="_blank" class="px-4 py-2 bg-blue-900 text-white rounded-lg">{{ __('messages.common.watch_on_youtube') }}</a>
            </div>
        </div>
    </div>
</main>
<script>
    let activeVideoId = null;
    let activeVideoTitle = null;
    let activeVideoUrl = null;
    let ytPlayer = null;
    let watchInterval = null;

    function csrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function notify(message, type = 'info') {
        if (window.appToast) {
            window.appToast(message, type);
            return;
        }
    }

    function collectClientMetrics() {
        const w = window.screen ? window.screen.width : null;
        const h = window.screen ? window.screen.height : null;
        const tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
        const lang = navigator.language || '';
        const platform = navigator.platform || '';
        const width = window.innerWidth || 0;
        let deviceType = 'desktop';
        if (width < 768) {
            deviceType = 'mobile';
        } else if (width < 1024) {
            deviceType = 'tablet';
        }

        return {
            screen_width: w,
            screen_height: h,
            timezone: tz,
            language: lang,
            platform: platform,
            device_type: deviceType
        };
    }

    function trackEvent(videoId, event, pageUrl) {
        const payload = Object.assign(
            { event, page_url: pageUrl || window.location.href },
            collectClientMetrics()
        );

        fetch(`/videos/${videoId}/track`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken()
            },
            body: JSON.stringify(payload)
        });
    }

    function openVideoModal(button) {
        const id = button.getAttribute('data-video-id');
        const dbId = button.getAttribute('data-video-db-id');
        const title = button.getAttribute('data-video-title');
        const url = button.getAttribute('data-video-url');

        activeVideoId = dbId;
        activeVideoTitle = title || @json(__('messages.common.video'));
        activeVideoUrl = url || window.location.href;

        document.getElementById('videoModalTitle').textContent = title || @json(__('messages.common.video'));
        const frame = document.getElementById('videoModalFrame');
        frame.src = `https://www.youtube.com/embed/${id}?autoplay=1&controls=1&modestbranding=1&rel=0&enablejsapi=1&origin=${window.location.origin}`;
        document.getElementById('videoModalLink').href = url || '#';
        document.getElementById('videoModal').classList.remove('hidden');
        document.getElementById('videoModal').classList.add('flex');

        trackEvent(dbId, 'play', window.location.href);
        initYouTubePlayer(id);
    }

    function closeVideoModal() {
        const modal = document.getElementById('videoModal');
        const frame = document.getElementById('videoModalFrame');
        stopWatchTracking();
        frame.src = '';
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function trackYoutubeClick(videoId) {
        trackEvent(videoId, 'youtube_click', window.location.href);
    }

    function toggleCommentPanel(button) {
        const card = button.closest('.bg-white');
        if (!card) return;
        const panel = card.querySelector('[data-comment-panel]');
        if (!panel) return;
        panel.classList.toggle('hidden');
    }

    function toggleLike(button) {
        const videoId = button.getAttribute('data-video-id');
        if (!videoId) return;

        const payload = collectClientMetrics();

        fetch(`/videos/${videoId}/like`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken()
            },
            body: JSON.stringify(payload)
        })
        .then((res) => res.json())
        .then((data) => {
            if (!data) return;
            const countEl = button.querySelector('[data-like-count]');
            if (countEl && typeof data.likes_count !== 'undefined') {
                countEl.textContent = data.likes_count;
            }
            button.classList.toggle('text-rose-600', data.liked);
            button.classList.toggle('text-slate-600', !data.liked);
            notify(data.liked ? 'Added to liked items.' : 'Removed from liked items.', 'success');
        })
        .catch(() => {
            notify('Request failed. Please try again.', 'error');
        });
    }

    function submitComment(form) {
        const videoId = form.getAttribute('data-video-id');
        if (!videoId) return false;

        const formData = new FormData(form);
        const payload = Object.assign({
            name: formData.get('name'),
            email: formData.get('email'),
            body: formData.get('body')
        }, collectClientMetrics());

        fetch(`/videos/${videoId}/comment`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken()
            },
            body: JSON.stringify(payload)
        })
        .then((res) => res.json())
        .then((data) => {
            if (!data || !data.comment) return;
            const card = form.closest('.bg-white');
            const list = card ? card.querySelector('[data-comment-list]') : null;
            if (list) {
                const item = document.createElement('div');
                item.className = 'text-xs text-slate-600 bg-slate-50 rounded-lg p-3';
                const safeName = escapeHtml(data.comment.name);
                const safeBody = escapeHtml(data.comment.body);
                item.innerHTML = `<div class="font-semibold text-slate-700">${safeName}</div><div class="mt-1">${safeBody}</div>`;
                list.prepend(item);
            }
            const countEl = card ? card.querySelector('[data-comment-count]') : null;
            if (countEl && typeof data.comments_count !== 'undefined') {
                countEl.textContent = data.comments_count;
            }
            form.reset();
            notify('Comment submitted successfully.', 'success');
        })
        .catch(() => {
            notify('Unable to post comment. Please try again.', 'error');
        });

        return false;
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function shareVideo() {
        if (!activeVideoId) return;
        const shareData = {
            title: activeVideoTitle || @json(__('messages.common.video')),
            text: activeVideoTitle || @json(__('messages.common.watch_this_video')),
            url: activeVideoUrl || window.location.href
        };

        if (navigator.share) {
            navigator.share(shareData)
                .then(() => {
                    trackEvent(activeVideoId, 'share', window.location.href);
                    trackShareChannel(activeVideoId, 'native');
                    notify('Shared successfully.', 'success');
                })
                .catch(() => {});
        } else {
            navigator.clipboard.writeText(shareData.url).then(() => {
                trackEvent(activeVideoId, 'share', window.location.href);
                trackShareChannel(activeVideoId, 'copy');
                notify(@json(__('messages.common.link_copied')), 'success');
            }).catch(() => {
                notify('Unable to copy link right now.', 'error');
            });
        }
    }

    function shareFromCard(button) {
        const videoId = button.getAttribute('data-video-id');
        const title = button.getAttribute('data-video-title') || @json(__('messages.common.video'));
        const url = button.getAttribute('data-video-url') || window.location.href;
        if (!videoId) return;

        const shareData = {
            title,
            text: title,
            url
        };

        if (navigator.share) {
            navigator.share(shareData)
                .then(() => {
                    trackEvent(videoId, 'share', window.location.href);
                    trackShareChannel(videoId, 'native');
                    notify('Shared successfully.', 'success');
                })
                .catch(() => {});
        } else {
            navigator.clipboard.writeText(shareData.url).then(() => {
                trackEvent(videoId, 'share', window.location.href);
                trackShareChannel(videoId, 'copy');
                notify(@json(__('messages.common.link_copied')), 'success');
            }).catch(() => {
                notify('Unable to copy link right now.', 'error');
            });
        }
    }

    function trackShareChannel(videoId, channel) {
        if (!videoId) return;
        const payload = Object.assign(
            { event: 'share', page_url: window.location.href, share_channel: channel },
            collectClientMetrics()
        );
        fetch(`/videos/${videoId}/track`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken()
            },
            body: JSON.stringify(payload)
        });
    }

    function initYouTubePlayer(videoId) {
        if (window.YT && window.YT.Player) {
            if (ytPlayer) {
                ytPlayer.loadVideoById(videoId);
            } else {
                ytPlayer = new YT.Player('videoModalFrame', {
                    events: {
                        'onStateChange': onPlayerStateChange
                    }
                });
            }
            return;
        }

        if (!document.getElementById('yt-api')) {
            const tag = document.createElement('script');
            tag.id = 'yt-api';
            tag.src = 'https://www.youtube.com/iframe_api';
            document.body.appendChild(tag);
        }

        window.onYouTubeIframeAPIReady = () => {
            ytPlayer = new YT.Player('videoModalFrame', {
                events: {
                    'onStateChange': onPlayerStateChange
                }
            });
        };
    }

    function onPlayerStateChange(event) {
        if (!activeVideoId) return;
        if (event.data === YT.PlayerState.PLAYING) {
            startWatchTracking();
        } else if (event.data === YT.PlayerState.PAUSED || event.data === YT.PlayerState.ENDED) {
            stopWatchTracking();
        }
    }

    function startWatchTracking() {
        if (!ytPlayer || watchInterval) return;
        watchInterval = setInterval(() => {
            if (!activeVideoId || !ytPlayer || typeof ytPlayer.getCurrentTime !== 'function') {
                return;
            }
            const seconds = Math.floor(ytPlayer.getCurrentTime());
            const payload = Object.assign(
                { event: 'watch', page_url: window.location.href, watch_seconds: seconds },
                collectClientMetrics()
            );
            fetch(`/videos/${activeVideoId}/track`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken()
                },
                body: JSON.stringify(payload)
            });
        }, 10000);
    }

    function stopWatchTracking() {
        if (watchInterval) {
            clearInterval(watchInterval);
            watchInterval = null;
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const seen = new Set();
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const id = entry.target.getAttribute('data-video-db-id');
                if (!id || seen.has(id)) return;
                seen.add(id);
                trackEvent(id, 'impression', window.location.href);
            });
        }, { threshold: 0.5 });

        document.querySelectorAll('[data-video-card]').forEach((el) => observer.observe(el));
    });
</script>
@endsection


