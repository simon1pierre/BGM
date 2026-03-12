<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\HandlesTranslations;
use App\Models\UserActivityLog;
use App\Models\VideoSeries;
use Illuminate\Http\Request;

class VideoSeriesController extends Controller
{
    use HandlesTranslations;

    public function index(Request $request)
    {
        $query = VideoSeries::query()->with('translations')->withCount('videos');

        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));
            $query->where(function ($builder) use ($search): void {
                $builder->where('title', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', (string) $request->query('status') === 'active');
        }

        if ($request->filled('deleted')) {
            $deleted = (string) $request->query('deleted');
            if ($deleted === 'with') {
                $query->withTrashed();
            } elseif ($deleted === 'only') {
                $query->onlyTrashed();
            }
        }

        $series = $query
            ->orderBy('sort_order')
            ->orderBy('title')
            ->paginate(15)
            ->withQueryString();

        return view('Admin.Content.VideoSeries.index', compact('series'));
    }

    public function create()
    {
        return view('Admin.Content.VideoSeries.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'title_en' => ['required', 'string', 'max:255'],
            'title_fr' => ['required', 'string', 'max:255'],
            'title_rw' => ['required', 'string', 'max:255'],
            'description_en' => ['nullable', 'string'],
            'description_fr' => ['nullable', 'string'],
            'description_rw' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $record = VideoSeries::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active', true),
        ]);

        UserActivityLog::create([
            'actor_user_id' => $request->user()?->id,
            'action' => 'video_series_created',
            'meta' => ['id' => $record->id, 'title' => $record->title],
        ]);

        $this->syncTranslations($record, $request, ['title', 'description']);

        return redirect()->route('admin.video-series.index')->with('status', 'Video series created.');
    }

    public function edit(VideoSeries $videoSeries)
    {
        $translations = [
            'en' => $videoSeries->translationFor('en'),
            'fr' => $videoSeries->translationFor('fr'),
            'rw' => $videoSeries->translationFor('rw'),
        ];

        return view('Admin.Content.VideoSeries.edit', compact('videoSeries', 'translations'));
    }

    public function update(Request $request, VideoSeries $videoSeries)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'title_en' => ['required', 'string', 'max:255'],
            'title_fr' => ['required', 'string', 'max:255'],
            'title_rw' => ['required', 'string', 'max:255'],
            'description_en' => ['nullable', 'string'],
            'description_fr' => ['nullable', 'string'],
            'description_rw' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $videoSeries->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
        ]);

        UserActivityLog::create([
            'actor_user_id' => $request->user()?->id,
            'action' => 'video_series_updated',
            'meta' => ['id' => $videoSeries->id, 'title' => $videoSeries->title],
        ]);

        $this->syncTranslations($videoSeries, $request, ['title', 'description']);

        return redirect()->route('admin.video-series.index')->with('status', 'Video series updated.');
    }

    public function destroy(Request $request, VideoSeries $videoSeries)
    {
        $videoSeries->delete();

        UserActivityLog::create([
            'actor_user_id' => $request->user()?->id,
            'action' => 'video_series_deleted',
            'meta' => ['id' => $videoSeries->id, 'title' => $videoSeries->title],
        ]);

        return redirect()->back()->with('status', 'Video series deleted.');
    }

    public function restore(Request $request, int $videoSeries)
    {
        $record = VideoSeries::withTrashed()->findOrFail($videoSeries);
        $record->restore();

        UserActivityLog::create([
            'actor_user_id' => $request->user()?->id,
            'action' => 'video_series_restored',
            'meta' => ['id' => $record->id, 'title' => $record->title],
        ]);

        return redirect()->back()->with('status', 'Video series restored.');
    }

    public function forceDelete(Request $request, int $videoSeries)
    {
        $record = VideoSeries::withTrashed()->findOrFail($videoSeries);
        $title = $record->title;
        $record->forceDelete();

        UserActivityLog::create([
            'actor_user_id' => $request->user()?->id,
            'action' => 'video_series_force_deleted',
            'meta' => ['id' => $videoSeries, 'title' => $title],
        ]);

        return redirect()->back()->with('status', 'Video series permanently deleted.');
    }
}


