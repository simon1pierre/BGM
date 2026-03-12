<?php

namespace App\Http\Controllers\Admin\Analytics;

use App\Http\Controllers\Controller;
use App\Models\AudiencePageEvent;
use App\Models\ContentComment;
use App\Models\ContentEvent;
use App\Models\ContentLike;
use App\Models\Audio;
use App\Models\Book;
use App\Models\Video;
use App\Models\VideoEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        [$from, $to] = $this->resolveDateRange($request);
        [$prevFrom, $prevTo] = $this->previousRange($from, $to);

        $videoTotals = [
            'views' => Video::query()->sum('view_count'),
            'plays' => VideoEvent::query()->where('event_type', 'play')->count(),
            'impressions' => VideoEvent::query()->where('event_type', 'impression')->count(),
            'shares' => VideoEvent::query()->where('event_type', 'share')->count(),
            'watch_minutes' => (int) (VideoEvent::query()->where('event_type', 'watch')->sum('watch_seconds') / 60),
        ];

        $audioType = (new Audio())->getMorphClass();
        $bookType = (new Book())->getMorphClass();

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

        $periodCurrent = [
            'video_plays' => VideoEvent::query()
                ->where('event_type', 'play')
                ->whereBetween('created_at', [$from, $to])
                ->count(),
            'audio_plays' => ContentEvent::query()
                ->where('content_type', $audioType)
                ->where('event_type', 'play')
                ->whereBetween('created_at', [$from, $to])
                ->count(),
            'book_reads' => ContentEvent::query()
                ->where('content_type', $bookType)
                ->where('event_type', 'read')
                ->whereBetween('created_at', [$from, $to])
                ->count(),
            'page_views' => AudiencePageEvent::query()
                ->where('event_type', 'page_view')
                ->whereBetween('created_at', [$from, $to])
                ->count(),
        ];

        $periodPrevious = [
            'video_plays' => VideoEvent::query()
                ->where('event_type', 'play')
                ->whereBetween('created_at', [$prevFrom, $prevTo])
                ->count(),
            'audio_plays' => ContentEvent::query()
                ->where('content_type', $audioType)
                ->where('event_type', 'play')
                ->whereBetween('created_at', [$prevFrom, $prevTo])
                ->count(),
            'book_reads' => ContentEvent::query()
                ->where('content_type', $bookType)
                ->where('event_type', 'read')
                ->whereBetween('created_at', [$prevFrom, $prevTo])
                ->count(),
            'page_views' => AudiencePageEvent::query()
                ->where('event_type', 'page_view')
                ->whereBetween('created_at', [$prevFrom, $prevTo])
                ->count(),
        ];

        $periodDelta = [
            'video_plays' => $this->deltaPercent($periodCurrent['video_plays'], $periodPrevious['video_plays']),
            'audio_plays' => $this->deltaPercent($periodCurrent['audio_plays'], $periodPrevious['audio_plays']),
            'book_reads' => $this->deltaPercent($periodCurrent['book_reads'], $periodPrevious['book_reads']),
            'page_views' => $this->deltaPercent($periodCurrent['page_views'], $periodPrevious['page_views']),
        ];

        $trend = $this->contentTrendSeries($from, $to, $audioType, $bookType);

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
            'funnels',
            'from',
            'to',
            'periodCurrent',
            'periodDelta',
            'trend'
        ));
    }

    public function events(Request $request)
    {
        $type = $request->query('type', 'all');
        $event = $request->query('event', 'all');

        $videoQuery = VideoEvent::query()
            ->select('id', 'video_id as content_id', DB::raw("'video' as content_type"), 'event_type', 'ip_address', 'user_agent', 'referrer', 'page_url', 'device_type', 'created_at');

        $contentQuery = ContentEvent::query()
            ->select(
                'id',
                'content_id',
                DB::raw("
                    CASE
                        WHEN LOWER(content_type) LIKE '%audiobook' THEN 'audiobook'
                        WHEN LOWER(content_type) LIKE '%audio' THEN 'audio'
                        WHEN LOWER(content_type) LIKE '%book' THEN 'book'
                        WHEN LOWER(content_type) LIKE '%video' THEN 'video'
                        ELSE LOWER(content_type)
                    END AS content_type
                "),
                'event_type',
                'ip_address',
                'user_agent',
                'referrer',
                'page_url',
                'device_type',
                'created_at'
            );

        $audienceQuery = AudiencePageEvent::query()
            ->select(
                'id',
                DB::raw('NULL as content_id'),
                DB::raw("'audience' as content_type"),
                'event_type',
                'ip_address',
                'user_agent',
                'referrer',
                'page_url',
                'device_type',
                'created_at'
            );

        $events = $videoQuery->unionAll($contentQuery)->unionAll($audienceQuery);

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
        [$from, $to] = $this->resolveDateRange($request);
        [$prevFrom, $prevTo] = $this->previousRange($from, $to);
        $bookType = (new Book())->getMorphClass();
        $visitorKeyExpr = "COALESCE(NULLIF(ce.visitor_id, ''), ce.device_hash)";
        $readerSessionExpr = "COALESCE(NULLIF(ce.reader_session_id, ''), ce.session_id)";

        $basePageEvents = AudiencePageEvent::query()
            ->whereBetween('created_at', [$from, $to]);

        $uniqueVisitors = (clone $basePageEvents)
            ->select(DB::raw('COUNT(DISTINCT COALESCE(NULLIF(visitor_id, \'\'), device_hash)) as total'))
            ->value('total') ?? 0;

        $sessions = (clone $basePageEvents)
            ->whereNotNull('session_id')
            ->distinct('session_id')
            ->count('session_id');

        $pageViews = (clone $basePageEvents)
            ->where('event_type', 'page_view')
            ->count();

        $avgSessionDuration = (int) ((clone $basePageEvents)
            ->where('event_type', 'session_end')
            ->avg('engaged_seconds') ?? 0);

        $sessionPageCounts = (clone $basePageEvents)
            ->select('session_id', DB::raw('COUNT(*) as page_views'))
            ->where('event_type', 'page_view')
            ->whereNotNull('session_id')
            ->groupBy('session_id');

        $sessionCountForBounce = DB::query()->fromSub($sessionPageCounts, 'session_counts')->count();
        $bounceSessions = DB::query()->fromSub($sessionPageCounts, 'session_counts')
            ->where('page_views', '<=', 1)
            ->count();
        $bounceRate = $sessionCountForBounce > 0
            ? round(($bounceSessions / $sessionCountForBounce) * 100, 1)
            : 0.0;

        $visitorSessionCounts = (clone $basePageEvents)
            ->select(DB::raw('COALESCE(NULLIF(visitor_id, \'\'), device_hash) as visitor_key'), DB::raw('COUNT(DISTINCT session_id) as sessions_count'))
            ->whereNotNull('session_id')
            ->groupBy('visitor_key');

        $returningVisitors = DB::query()->fromSub($visitorSessionCounts, 'visitor_sessions')
            ->where('sessions_count', '>', 1)
            ->count();

        $topPages = (clone $basePageEvents)
            ->select('page_url', DB::raw('COUNT(*) as total'))
            ->where('event_type', 'page_view')
            ->whereNotNull('page_url')
            ->groupBy('page_url')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $topReferrers = (clone $basePageEvents)
            ->select('referrer', DB::raw('COUNT(*) as total'))
            ->where('event_type', 'page_view')
            ->whereNotNull('referrer')
            ->groupBy('referrer')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $devices = (clone $basePageEvents)
            ->select('device_type', DB::raw('COUNT(*) as total'))
            ->groupBy('device_type')
            ->orderByDesc('total')
            ->get();

        $countries = (clone $basePageEvents)
            ->select(DB::raw("COALESCE(geo_country, 'Unknown') as geo_country"), DB::raw('COUNT(*) as total'))
            ->groupBy('geo_country')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $rows = (clone $basePageEvents)
            ->select(
                DB::raw('COALESCE(NULLIF(visitor_id, \'\'), device_hash) as visitor_key'),
                DB::raw('MAX(device_type) as device_type'),
                DB::raw('MAX(geo_country) as geo_country'),
                DB::raw('MAX(language) as language'),
                DB::raw('MAX(created_at) as last_seen'),
                DB::raw('COUNT(*) as total_events'),
                DB::raw('COUNT(DISTINCT session_id) as total_sessions')
            )
            ->groupBy('visitor_key')
            ->orderByDesc('last_seen')
            ->paginate(20)
            ->withQueryString();

        $summary = [
            'unique_visitors' => (int) $uniqueVisitors,
            'sessions' => (int) $sessions,
            'page_views' => (int) $pageViews,
            'avg_session_seconds' => (int) $avgSessionDuration,
            'bounce_rate' => $bounceRate,
            'returning_visitors' => (int) $returningVisitors,
        ];

        $previousSummary = $this->audienceSummaryBetween($prevFrom, $prevTo);
        $summaryDelta = [
            'unique_visitors' => $this->deltaPercent($summary['unique_visitors'], $previousSummary['unique_visitors']),
            'sessions' => $this->deltaPercent($summary['sessions'], $previousSummary['sessions']),
            'page_views' => $this->deltaPercent($summary['page_views'], $previousSummary['page_views']),
            'avg_session_seconds' => $this->deltaPercent($summary['avg_session_seconds'], $previousSummary['avg_session_seconds']),
            'bounce_rate' => $this->deltaPercent($summary['bounce_rate'], $previousSummary['bounce_rate']),
            'returning_visitors' => $this->deltaPercent($summary['returning_visitors'], $previousSummary['returning_visitors']),
        ];

        $audienceTrend = $this->audienceTrendSeries($from, $to);

        $readingProgressBase = ContentEvent::query()
            ->from('content_events as ce')
            ->where('ce.content_type', $bookType)
            ->where('ce.event_type', 'read_progress')
            ->whereBetween('ce.created_at', [$from, $to]);

        $trackedReaders = (int) ((clone $readingProgressBase)
            ->select(DB::raw("COUNT(DISTINCT {$visitorKeyExpr}) as total"))
            ->value('total') ?? 0);

        $trackedReaderSessions = (int) ((clone $readingProgressBase)
            ->select(DB::raw("COUNT(DISTINCT {$readerSessionExpr}) as total"))
            ->value('total') ?? 0);

        $sessionProgress = (clone $readingProgressBase)
            ->whereNotNull('ce.progress_percent')
            ->whereRaw("{$readerSessionExpr} IS NOT NULL")
            ->select(
                DB::raw("{$readerSessionExpr} as reader_session_key"),
                DB::raw('MAX(ce.progress_percent) as max_progress')
            )
            ->groupBy('reader_session_key');

        $avgReadCompletion = round((float) (DB::query()
            ->fromSub($sessionProgress, 'reader_session_progress')
            ->avg('max_progress') ?? 0), 1);

        $readerBookProgress = (clone $readingProgressBase)
            ->join('books as b', 'b.id', '=', 'ce.content_id')
            ->select(
                DB::raw("{$visitorKeyExpr} as visitor_key"),
                'ce.content_id as book_id',
                DB::raw('MAX(b.title) as book_title'),
                DB::raw('MAX(ce.progress_percent) as max_progress'),
                DB::raw('MAX(ce.page_number) as max_page_number'),
                DB::raw('MAX(ce.total_pages) as total_pages'),
                DB::raw('MAX(ce.created_at) as last_seen')
            )
            ->groupBy(DB::raw($visitorKeyExpr), 'ce.content_id')
            ->orderByDesc('last_seen')
            ->limit(20)
            ->get();

        $readingProgressSummary = [
            'tracked_readers' => $trackedReaders,
            'tracked_reader_sessions' => $trackedReaderSessions,
            'avg_completion_percent' => $avgReadCompletion,
        ];

        return view('Admin.Analytics.audiences', compact(
            'rows',
            'summary',
            'summaryDelta',
            'topPages',
            'topReferrers',
            'devices',
            'countries',
            'from',
            'to',
            'audienceTrend',
            'readingProgressSummary',
            'readerBookProgress'
        ));
    }

    public function content()
    {
        $topVideos = Video::query()->orderByDesc('view_count')->limit(10)->get();
        $topAudios = ContentEvent::query()
            ->select('content_id', DB::raw('COUNT(*) as total'))
            ->where('content_type', (new Audio())->getMorphClass())
            ->where('event_type', 'play')
            ->groupBy('content_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                $row->audio = Audio::find($row->content_id);
                return $row;
            });
        $topBooks = ContentEvent::query()
            ->select('content_id', DB::raw('COUNT(*) as total'))
            ->where('content_type', (new Book())->getMorphClass())
            ->where('event_type', 'read')
            ->groupBy('content_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                $row->book = Book::find($row->content_id);
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
        $videoReferrers = VideoEvent::query()
            ->select('referrer', DB::raw('COUNT(*) as total'))
            ->whereNotNull('referrer')
            ->groupBy('referrer');

        $contentReferrers = ContentEvent::query()
            ->select('referrer', DB::raw('COUNT(*) as total'))
            ->whereNotNull('referrer')
            ->groupBy('referrer');

        $audienceReferrers = AudiencePageEvent::query()
            ->select('referrer', DB::raw('COUNT(*) as total'))
            ->whereNotNull('referrer')
            ->groupBy('referrer');

        return DB::query()->fromSub(
            $videoReferrers->unionAll($contentReferrers)->unionAll($audienceReferrers),
            'referrers'
        )
            ->select('referrer', DB::raw('SUM(total) as total'))
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
            if ($row->content_type === (new Video())->getMorphClass()) {
                $row->title = Video::find($row->content_id)?->title;
            } elseif ($row->content_type === (new Audio())->getMorphClass()) {
                $row->title = Audio::find($row->content_id)?->title;
            } elseif ($row->content_type === (new Book())->getMorphClass()) {
                $row->title = Book::find($row->content_id)?->title;
            } else {
                $row->title = 'Unknown';
            }
            return $row;
        });
    }

    private function previousRange(Carbon $from, Carbon $to): array
    {
        $days = $from->diffInDays($to) + 1;
        $prevTo = $from->copy()->subDay()->endOfDay();
        $prevFrom = $prevTo->copy()->subDays($days - 1)->startOfDay();

        return [$prevFrom, $prevTo];
    }

    private function deltaPercent(float|int $current, float|int $previous): array
    {
        $currentValue = (float) $current;
        $previousValue = (float) $previous;

        if ($previousValue == 0.0) {
            if ($currentValue == 0.0) {
                return ['value' => 0.0, 'direction' => 'flat'];
            }

            return ['value' => 100.0, 'direction' => 'up'];
        }

        $value = round((($currentValue - $previousValue) / $previousValue) * 100, 1);
        $direction = $value > 0 ? 'up' : ($value < 0 ? 'down' : 'flat');

        return ['value' => $value, 'direction' => $direction];
    }

    private function audienceSummaryBetween(Carbon $from, Carbon $to): array
    {
        $base = AudiencePageEvent::query()->whereBetween('created_at', [$from, $to]);

        $uniqueVisitors = (clone $base)
            ->select(DB::raw('COUNT(DISTINCT COALESCE(NULLIF(visitor_id, \'\'), device_hash)) as total'))
            ->value('total') ?? 0;

        $sessions = (clone $base)
            ->whereNotNull('session_id')
            ->distinct('session_id')
            ->count('session_id');

        $pageViews = (clone $base)
            ->where('event_type', 'page_view')
            ->count();

        $avgSessionDuration = (int) ((clone $base)
            ->where('event_type', 'session_end')
            ->avg('engaged_seconds') ?? 0);

        $sessionPageCounts = (clone $base)
            ->select('session_id', DB::raw('COUNT(*) as page_views'))
            ->where('event_type', 'page_view')
            ->whereNotNull('session_id')
            ->groupBy('session_id');

        $sessionCountForBounce = DB::query()->fromSub($sessionPageCounts, 'session_counts')->count();
        $bounceSessions = DB::query()->fromSub($sessionPageCounts, 'session_counts')
            ->where('page_views', '<=', 1)
            ->count();

        $bounceRate = $sessionCountForBounce > 0
            ? round(($bounceSessions / $sessionCountForBounce) * 100, 1)
            : 0.0;

        $visitorSessionCounts = (clone $base)
            ->select(DB::raw('COALESCE(NULLIF(visitor_id, \'\'), device_hash) as visitor_key'), DB::raw('COUNT(DISTINCT session_id) as sessions_count'))
            ->whereNotNull('session_id')
            ->groupBy('visitor_key');

        $returningVisitors = DB::query()->fromSub($visitorSessionCounts, 'visitor_sessions')
            ->where('sessions_count', '>', 1)
            ->count();

        return [
            'unique_visitors' => (int) $uniqueVisitors,
            'sessions' => (int) $sessions,
            'page_views' => (int) $pageViews,
            'avg_session_seconds' => (int) $avgSessionDuration,
            'bounce_rate' => $bounceRate,
            'returning_visitors' => (int) $returningVisitors,
        ];
    }

    private function audienceTrendSeries(Carbon $from, Carbon $to): array
    {
        $labels = [];
        $cursor = $from->copy()->startOfDay();
        $end = $to->copy()->startOfDay();
        while ($cursor->lte($end)) {
            $labels[] = $cursor->format('M d');
            $cursor->addDay();
        }

        $pageViewRows = AudiencePageEvent::query()
            ->select(DB::raw('DATE(created_at) as day'), DB::raw('COUNT(*) as total'))
            ->where('event_type', 'page_view')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $uniqueRows = AudiencePageEvent::query()
            ->select(DB::raw('DATE(created_at) as day'), DB::raw('COUNT(DISTINCT COALESCE(NULLIF(visitor_id, \'\'), device_hash)) as total'))
            ->whereBetween('created_at', [$from, $to])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $sessionRows = AudiencePageEvent::query()
            ->select(DB::raw('DATE(created_at) as day'), DB::raw('COUNT(DISTINCT session_id) as total'))
            ->whereNotNull('session_id')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $pageViews = [];
        $uniqueVisitors = [];
        $sessions = [];
        $cursor = $from->copy()->startOfDay();
        while ($cursor->lte($end)) {
            $key = $cursor->toDateString();
            $pageViews[] = (int) ($pageViewRows[$key]->total ?? 0);
            $uniqueVisitors[] = (int) ($uniqueRows[$key]->total ?? 0);
            $sessions[] = (int) ($sessionRows[$key]->total ?? 0);
            $cursor->addDay();
        }

        return [
            'labels' => $labels,
            'page_views' => $pageViews,
            'unique_visitors' => $uniqueVisitors,
            'sessions' => $sessions,
        ];
    }

    private function contentTrendSeries(Carbon $from, Carbon $to, string $audioType, string $bookType): array
    {
        $labels = [];
        $cursor = $from->copy()->startOfDay();
        $end = $to->copy()->startOfDay();
        while ($cursor->lte($end)) {
            $labels[] = $cursor->format('M d');
            $cursor->addDay();
        }

        $videoRows = VideoEvent::query()
            ->select(DB::raw('DATE(created_at) as day'), DB::raw('COUNT(*) as total'))
            ->where('event_type', 'play')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $audioRows = ContentEvent::query()
            ->select(DB::raw('DATE(created_at) as day'), DB::raw('COUNT(*) as total'))
            ->where('content_type', $audioType)
            ->where('event_type', 'play')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $bookRows = ContentEvent::query()
            ->select(DB::raw('DATE(created_at) as day'), DB::raw('COUNT(*) as total'))
            ->where('content_type', $bookType)
            ->where('event_type', 'read')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $audienceRows = AudiencePageEvent::query()
            ->select(DB::raw('DATE(created_at) as day'), DB::raw('COUNT(*) as total'))
            ->where('event_type', 'page_view')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $videoPlays = [];
        $audioPlays = [];
        $bookReads = [];
        $pageViews = [];
        $cursor = $from->copy()->startOfDay();
        while ($cursor->lte($end)) {
            $key = $cursor->toDateString();
            $videoPlays[] = (int) ($videoRows[$key]->total ?? 0);
            $audioPlays[] = (int) ($audioRows[$key]->total ?? 0);
            $bookReads[] = (int) ($bookRows[$key]->total ?? 0);
            $pageViews[] = (int) ($audienceRows[$key]->total ?? 0);
            $cursor->addDay();
        }

        return [
            'labels' => $labels,
            'video_plays' => $videoPlays,
            'audio_plays' => $audioPlays,
            'book_reads' => $bookReads,
            'page_views' => $pageViews,
        ];
    }

    private function resolveDateRange(Request $request): array
    {
        $fromInput = $request->query('from');
        $toInput = $request->query('to');

        try {
            $from = $fromInput ? Carbon::parse($fromInput)->startOfDay() : now()->subDays(29)->startOfDay();
        } catch (\Throwable $e) {
            $from = now()->subDays(29)->startOfDay();
        }

        try {
            $to = $toInput ? Carbon::parse($toInput)->endOfDay() : now()->endOfDay();
        } catch (\Throwable $e) {
            $to = now()->endOfDay();
        }

        if ($from->greaterThan($to)) {
            [$from, $to] = [$to->copy()->startOfDay(), $from->copy()->endOfDay()];
        }

        return [$from, $to];
    }
}








