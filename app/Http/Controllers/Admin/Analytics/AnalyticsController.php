<?php

namespace App\Http\Controllers\Admin\Analytics;

use App\Http\Controllers\Controller;
use App\Models\ContentComment;
use App\Models\ContentEvent;
use App\Models\ContentLike;
use App\Models\audio;
use App\Models\book;
use App\Models\video;
use App\Models\VideoEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        $videoTotals = [
            'views' => video::query()->sum('view_count'),
            'plays' => VideoEvent::query()->where('event_type', 'play')->count(),
            'impressions' => VideoEvent::query()->where('event_type', 'impression')->count(),
            'shares' => VideoEvent::query()->where('event_type', 'share')->count(),
            'watch_minutes' => (int) (VideoEvent::query()->where('event_type', 'watch')->sum('watch_seconds') / 60),
        ];

        $audioType = (new audio())->getMorphClass();
        $bookType = (new book())->getMorphClass();

        $audioTotals = [
            'plays' => ContentEvent::query()->where('content_type', $audioType)->where('event_type', 'play')->count(),
            'shares' => ContentEvent::query()->where('content_type', $audioType)->where('event_type', 'share')->count(),
            'downloads' => ContentEvent::query()->where('content_type', $audioType)->where('event_type', 'download')->count(),
        ];

        $bookTotals = [
            'reads' => ContentEvent::query()->where('content_type', $bookType)->where('event_type', 'read')->count(),
            'shares' => ContentEvent::query()->where('content_type', $bookType)->where('event_type', 'share')->count(),
            'downloads' => ContentEvent::query()->where('content_type', $bookType)->where('event_type', 'download')->count(),
        ];

        $engagementTotals = [
            'likes' => ContentLike::query()->count(),
            'comments' => ContentComment::query()->count(),
        ];

        $deviceBreakdown = $this->deviceBreakdown();
        $topReferrers = $this->topReferrers();
        $topLiked = $this->topLikedContent();

        $funnels = [
            'video' => [
                'impressions' => $videoTotals['impressions'],
                'plays' => $videoTotals['plays'],
                'shares' => $videoTotals['shares'],
            ],
            'audio' => [
                'plays' => $audioTotals['plays'],
                'shares' => $audioTotals['shares'],
                'downloads' => $audioTotals['downloads'],
            ],
            'book' => [
                'reads' => $bookTotals['reads'],
                'shares' => $bookTotals['shares'],
                'downloads' => $bookTotals['downloads'],
            ],
        ];

        return view('Admin.Analytics.index', compact(
            'videoTotals',
            'audioTotals',
            'bookTotals',
            'engagementTotals',
            'deviceBreakdown',
            'topReferrers',
            'topLiked',
            'funnels'
        ));
    }

    public function events(Request $request)
    {
        $type = $request->query('type', 'all');
        $event = $request->query('event', 'all');

        $videoQuery = VideoEvent::query()
            ->select('id', 'video_id as content_id', DB::raw("'video' as content_type"), 'event_type', 'ip_address', 'user_agent', 'referrer', 'page_url', 'device_type', 'created_at');

        $contentQuery = ContentEvent::query()
            ->select('id', 'content_id', 'content_type', 'event_type', 'ip_address', 'user_agent', 'referrer', 'page_url', 'device_type', 'created_at');

        $events = $videoQuery->unionAll($contentQuery);

        $events = DB::query()->fromSub($events, 'events')
            ->when($type !== 'all', function ($query) use ($type) {
                $query->where('content_type', $type);
            })
            ->when($event !== 'all', function ($query) use ($event) {
                $query->where('event_type', $event);
            })
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('Admin.Analytics.events', compact('events', 'type', 'event'));
    }

    public function audiences(Request $request)
    {
        $rows = ContentEvent::query()
            ->select('device_hash', DB::raw('COUNT(*) as total'), DB::raw('MAX(created_at) as last_seen'))
            ->whereNotNull('device_hash')
            ->groupBy('device_hash')
            ->orderByDesc('last_seen')
            ->paginate(20)
            ->withQueryString();

        return view('Admin.Analytics.audiences', compact('rows'));
    }

    public function content()
    {
        $topVideos = video::query()->orderByDesc('view_count')->limit(10)->get();
        $topAudios = ContentEvent::query()
            ->select('content_id', DB::raw('COUNT(*) as total'))
            ->where('content_type', (new audio())->getMorphClass())
            ->where('event_type', 'play')
            ->groupBy('content_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                $row->audio = audio::find($row->content_id);
                return $row;
            });
        $topBooks = ContentEvent::query()
            ->select('content_id', DB::raw('COUNT(*) as total'))
            ->where('content_type', (new book())->getMorphClass())
            ->where('event_type', 'read')
            ->groupBy('content_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                $row->book = book::find($row->content_id);
                return $row;
            });

        return view('Admin.Analytics.content', compact('topVideos', 'topAudios', 'topBooks'));
    }

    private function deviceBreakdown(): array
    {
        $videoDevices = VideoEvent::query()
            ->select('device_type', DB::raw('COUNT(*) as total'))
            ->groupBy('device_type')
            ->pluck('total', 'device_type')
            ->toArray();

        $contentDevices = ContentEvent::query()
            ->select('device_type', DB::raw('COUNT(*) as total'))
            ->groupBy('device_type')
            ->pluck('total', 'device_type')
            ->toArray();

        $merged = [];
        foreach (array_keys(array_merge($videoDevices, $contentDevices)) as $key) {
            $merged[$key ?: 'unknown'] = ($videoDevices[$key] ?? 0) + ($contentDevices[$key] ?? 0);
        }

        return $merged;
    }

    private function topReferrers()
    {
        return VideoEvent::query()
            ->select('referrer', DB::raw('COUNT(*) as total'))
            ->whereNotNull('referrer')
            ->groupBy('referrer')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
    }

    private function topLikedContent()
    {
        $likes = ContentLike::query()
            ->select('content_type', 'content_id', DB::raw('COUNT(*) as total'))
            ->groupBy('content_type', 'content_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return $likes->map(function ($row) {
            if ($row->content_type === (new video())->getMorphClass()) {
                $row->title = video::find($row->content_id)?->title;
            } elseif ($row->content_type === (new audio())->getMorphClass()) {
                $row->title = audio::find($row->content_id)?->title;
            } elseif ($row->content_type === (new book())->getMorphClass()) {
                $row->title = book::find($row->content_id)?->title;
            } else {
                $row->title = 'Unknown';
            }
            return $row;
        });
    }
}
