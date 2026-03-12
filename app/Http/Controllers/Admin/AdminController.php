<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Audio;
use App\Models\Book;
use App\Models\Subscriber;
use App\Models\Video;
use App\Models\UserActivityLog;
use App\Models\VideoEvent;
use App\Models\ContentEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    public function index(){
        $since = Carbon::now()->subDays(7);

        $videoCount = Video::query()->count();
        $audioCount = Audio::query()->count();
        $documentCount = Book::query()->count();
        $subscriberCount = Subscriber::query()->count();
        $totalDownloads = Audio::query()->sum('download_count') + Book::query()->sum('download_count');

        $latestVideos = Video::query()->latest()->limit(5)->get();
        $latestAudios = Audio::query()->latest()->limit(5)->get();
        $latestDocuments = Book::query()->latest()->limit(5)->get();
        $recentActivity = UserActivityLog::query()
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        $totalVideoViews = Video::query()->sum('view_count');
        $totalVideoPlays = VideoEvent::query()->where('event_type', 'play')->count();
        $totalVideoImpressions = VideoEvent::query()->where('event_type', 'impression')->count();
        $totalVideoShares = VideoEvent::query()->where('event_type', 'share')->count();
        $totalVideoWatchSeconds = (int) VideoEvent::query()
            ->where('event_type', 'watch')
            ->sum('watch_seconds');

        $videoViewsLast7 = VideoEvent::query()
            ->where('event_type', 'play')
            ->where('created_at', '>=', $since)
            ->count();
        $videoSharesLast7 = VideoEvent::query()
            ->where('event_type', 'share')
            ->where('created_at', '>=', $since)
            ->count();

        $audioType = (new Audio())->getMorphClass();
        $bookType = (new Book())->getMorphClass();

        $audioPlays = ContentEvent::query()
            ->where('content_type', $audioType)
            ->where('event_type', 'play')
            ->count();
        $audioShares = ContentEvent::query()
            ->where('content_type', $audioType)
            ->where('event_type', 'share')
            ->count();
        $audioDownloads = ContentEvent::query()
            ->where('content_type', $audioType)
            ->where('event_type', 'download')
            ->count();

        $bookReads = ContentEvent::query()
            ->where('content_type', $bookType)
            ->where('event_type', 'read')
            ->count();
        $bookShares = ContentEvent::query()
            ->where('content_type', $bookType)
            ->where('event_type', 'share')
            ->count();
        $bookDownloads = ContentEvent::query()
            ->where('content_type', $bookType)
            ->where('event_type', 'download')
            ->count();

        $newSubscribersLast7 = Subscriber::query()
            ->where('subscribed_at', '>=', $since)
            ->count();

        $topVideosByViews = Video::query()
            ->orderByDesc('view_count')
            ->limit(5)
            ->get();

        $topVideosByShares = VideoEvent::query()
            ->select('video_id', DB::raw('COUNT(*) as total'))
            ->where('event_type', 'share')
            ->groupBy('video_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($row) {
                $row->video = Video::find($row->video_id);
                return $row;
            });

        $topAudiosByPlays = ContentEvent::query()
            ->select('content_id', DB::raw('COUNT(*) as total'))
            ->where('content_type', $audioType)
            ->where('event_type', 'play')
            ->groupBy('content_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($row) {
                $row->audio = Audio::find($row->content_id);
                return $row;
            });

        $topBooksByReads = ContentEvent::query()
            ->select('content_id', DB::raw('COUNT(*) as total'))
            ->where('content_type', $bookType)
            ->where('event_type', 'read')
            ->groupBy('content_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($row) {
                $row->book = Book::find($row->content_id);
                return $row;
            });

        $totalLikes = \App\Models\ContentLike::query()->count();
        $totalComments = \App\Models\ContentComment::query()->count();

        return view('Admin.dashboard', compact(
            'videoCount',
            'audioCount',
            'documentCount',
            'subscriberCount',
            'totalDownloads',
            'latestVideos',
            'latestAudios',
            'latestDocuments',
            'recentActivity',
            'totalVideoViews',
            'totalVideoPlays',
            'totalVideoImpressions',
            'totalVideoShares',
            'totalVideoWatchSeconds',
            'videoViewsLast7',
            'videoSharesLast7',
            'audioPlays',
            'audioShares',
            'audioDownloads',
            'bookReads',
            'bookShares',
            'bookDownloads',
            'newSubscribersLast7',
            'topVideosByViews',
            'topVideosByShares',
            'topAudiosByPlays',
            'topBooksByReads',
            'totalLikes',
            'totalComments'
        ));
    }

    public function stats(): JsonResponse
    {
        $since = Carbon::now()->subDays(6)->startOfDay();
        $labels = collect(range(0, 6))
            ->map(fn ($i) => $since->copy()->addDays($i)->format('Y-m-d'))
            ->values();

        $videoPlays = $this->dailyCounts(
            VideoEvent::query()->where('event_type', 'play'),
            'created_at',
            $labels
        );
        $videoShares = $this->dailyCounts(
            VideoEvent::query()->where('event_type', 'share'),
            'created_at',
            $labels
        );

        $audioType = (new Audio())->getMorphClass();
        $bookType = (new Book())->getMorphClass();

        $audioPlays = $this->dailyCounts(
            ContentEvent::query()->where('content_type', $audioType)->where('event_type', 'play'),
            'created_at',
            $labels
        );
        $bookReads = $this->dailyCounts(
            ContentEvent::query()->where('content_type', $bookType)->where('event_type', 'read'),
            'created_at',
            $labels
        );
        $subscriberAdds = $this->dailyCounts(
            Subscriber::query(),
            'subscribed_at',
            $labels
        );

        return response()->json([
            'labels' => $labels,
            'series' => [
                'videoPlays' => $videoPlays,
                'videoShares' => $videoShares,
                'audioPlays' => $audioPlays,
                'bookReads' => $bookReads,
                'subscriberAdds' => $subscriberAdds,
            ],
            'totals' => [
                'videoViews' => Video::query()->sum('view_count'),
                'videoPlays' => VideoEvent::query()->where('event_type', 'play')->count(),
                'videoImpressions' => VideoEvent::query()->where('event_type', 'impression')->count(),
                'videoShares' => VideoEvent::query()->where('event_type', 'share')->count(),
                'videoWatchMinutes' => (int) (VideoEvent::query()->where('event_type', 'watch')->sum('watch_seconds') / 60),
                'audioPlays' => ContentEvent::query()->where('content_type', $audioType)->where('event_type', 'play')->count(),
                'audioDownloads' => ContentEvent::query()->where('content_type', $audioType)->where('event_type', 'download')->count(),
                'audioShares' => ContentEvent::query()->where('content_type', $audioType)->where('event_type', 'share')->count(),
                'bookReads' => ContentEvent::query()->where('content_type', $bookType)->where('event_type', 'read')->count(),
                'bookDownloads' => ContentEvent::query()->where('content_type', $bookType)->where('event_type', 'download')->count(),
                'bookShares' => ContentEvent::query()->where('content_type', $bookType)->where('event_type', 'share')->count(),
                'likes' => \App\Models\ContentLike::query()->count(),
                'comments' => \App\Models\ContentComment::query()->count(),
                'subscribers' => Subscriber::query()->count(),
            ],
        ]);
    }

    private function dailyCounts($query, string $dateColumn, $labels)
    {
        $start = Carbon::parse($labels->first())->startOfDay();
        $end = Carbon::parse($labels->last())->endOfDay();

        $rows = $query
            ->whereBetween($dateColumn, [$start, $end])
            ->select(DB::raw("DATE($dateColumn) as day"), DB::raw('COUNT(*) as total'))
            ->groupBy(DB::raw("DATE($dateColumn)"))
            ->pluck('total', 'day');

        return $labels->map(fn ($day) => (int) ($rows[$day] ?? 0))->values();
    }
}








