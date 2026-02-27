<?php

namespace App\Http\Controllers\Admin\Trash;

use App\Http\Controllers\Controller;
use App\Models\audio;
use App\Models\audiobook;
use App\Models\book;
use App\Models\ContactMessage;
use App\Models\ContentCategory;
use App\Models\EmailCampaign;
use App\Models\Event;
use App\Models\MinistryLeader;
use App\Models\Playlist;
use App\Models\Subscriber;
use App\Models\User;
use App\Models\UserActivityLog;
use App\Models\VideoSeries;
use App\Models\video;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class TrashController extends Controller
{
    public function index(Request $request)
    {
        $module = (string) $request->query('module', 'all');
        $search = trim((string) $request->query('q', ''));

        $modules = $this->modules();
        if ($module !== 'all' && !array_key_exists($module, $modules)) {
            $module = 'all';
        }

        $counts = [];
        foreach (array_keys($modules) as $key) {
            $counts[$key] = $this->queryFor($key, $search)->count();
        }

        $items = collect();
        if ($module === 'all') {
            foreach (array_keys($modules) as $key) {
                $items = $items->merge($this->collectRows($key, $search, 20));
            }

            $items = $items
                ->sortByDesc('deleted_at')
                ->values()
                ->take(120);
        } else {
            $items = $this->collectRows($module, $search, 120);
        }

        return view('Admin.Trash.index', compact('modules', 'counts', 'items', 'module', 'search'));
    }

    public function restore(Request $request, string $module, int $id)
    {
        $record = $this->resolveTrashedRecord($module, $id);
        $record->restore();

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'trash_restored',
            'meta' => [
                'module' => $module,
                'item_id' => $id,
            ],
        ]);

        return redirect()->route('admin.trash.index', [
            'module' => $request->input('module_filter', 'all'),
            'q' => $request->input('search_query', ''),
        ])->with('status', 'Item restored from trash.');
    }

    public function forceDelete(Request $request, string $module, int $id)
    {
        $record = $this->resolveTrashedRecord($module, $id);
        $record->forceDelete();

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'trash_force_deleted',
            'meta' => [
                'module' => $module,
                'item_id' => $id,
            ],
        ]);

        return redirect()->route('admin.trash.index', [
            'module' => $request->input('module_filter', 'all'),
            'q' => $request->input('search_query', ''),
        ])->with('status', 'Item permanently deleted.');
    }

    public function bulkRestore(Request $request)
    {
        $items = $this->validatedBulkItems($request);
        $restored = 0;

        foreach ($items as $item) {
            [$module, $id] = explode(':', $item, 2);
            $record = $this->resolveTrashedRecord($module, (int) $id);
            if ($record->trashed()) {
                $record->restore();
                $restored++;
            }
        }

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'trash_bulk_restored',
            'meta' => [
                'count' => $restored,
            ],
        ]);

        return redirect()->route('admin.trash.index', [
            'module' => $request->input('module_filter', 'all'),
            'q' => $request->input('search_query', ''),
        ])->with('status', $restored > 0 ? "{$restored} item(s) restored." : 'No items restored.');
    }

    public function bulkForceDelete(Request $request)
    {
        $items = $this->validatedBulkItems($request);
        $deleted = 0;

        foreach ($items as $item) {
            [$module, $id] = explode(':', $item, 2);
            $record = $this->resolveTrashedRecord($module, (int) $id);
            $record->forceDelete();
            $deleted++;
        }

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'trash_bulk_force_deleted',
            'meta' => [
                'count' => $deleted,
            ],
        ]);

        return redirect()->route('admin.trash.index', [
            'module' => $request->input('module_filter', 'all'),
            'q' => $request->input('search_query', ''),
        ])->with('status', $deleted > 0 ? "{$deleted} item(s) permanently deleted." : 'No items deleted.');
    }

    private function modules(): array
    {
        return [
            'videos' => ['label' => 'Videos', 'route' => route('admin.videos.index', ['deleted' => 'only'])],
            'audios' => ['label' => 'Audios', 'route' => route('admin.audios.index', ['deleted' => 'only'])],
            'audiobooks' => ['label' => 'Audiobooks', 'route' => route('admin.audiobooks.index', ['deleted' => 'only'])],
            'documents' => ['label' => 'Documents', 'route' => route('admin.documents.index', ['deleted' => 'only'])],
            'categories' => ['label' => 'Categories', 'route' => route('admin.categories.index', ['deleted' => 'only'])],
            'playlists' => ['label' => 'Playlists', 'route' => route('admin.playlists.index', ['deleted' => 'only'])],
            'video_series' => ['label' => 'Video Series', 'route' => route('admin.video-series.index', ['deleted' => 'only'])],
            'events' => ['label' => 'Events', 'route' => route('admin.events.index', ['deleted' => 'only'])],
            'users' => ['label' => 'Users', 'route' => route('admin.users.index', ['deleted' => 'only'])],
            'subscribers' => ['label' => 'Subscribers', 'route' => route('admin.subscribers.index', ['deleted' => 'only'])],
            'contacts' => ['label' => 'Contacts', 'route' => route('admin.contacts.index', ['deleted' => 'only'])],
            'campaigns' => ['label' => 'Campaigns', 'route' => route('admin.campaigns.index', ['deleted' => 'only'])],
            'ministry' => ['label' => 'Ministry Team', 'route' => route('admin.ministry-leaders.index', ['deleted' => 'only'])],
        ];
    }

    private function queryFor(string $module, string $search): Builder
    {
        return match ($module) {
            'videos' => $this->filter(video::onlyTrashed(), $search, ['title', 'description', 'speaker', 'series']),
            'audios' => $this->filter(audio::onlyTrashed(), $search, ['title', 'description', 'speaker', 'series']),
            'audiobooks' => $this->filter(audiobook::onlyTrashed(), $search, ['title', 'description', 'narrator', 'series']),
            'documents' => $this->filter(book::onlyTrashed(), $search, ['title', 'description', 'author', 'series']),
            'categories' => $this->filter(ContentCategory::onlyTrashed(), $search, ['name', 'slug']),
            'playlists' => $this->filter(Playlist::onlyTrashed(), $search, ['title', 'description']),
            'video_series' => $this->filter(VideoSeries::onlyTrashed(), $search, ['title', 'description']),
            'events' => $this->filter(Event::onlyTrashed(), $search, ['title', 'description', 'location', 'venue']),
            'users' => $this->filter(User::onlyTrashed(), $search, ['name', 'email', 'user_name', 'first_name', 'last_name']),
            'subscribers' => $this->filter(Subscriber::onlyTrashed(), $search, ['email', 'name']),
            'contacts' => $this->filter(ContactMessage::onlyTrashed(), $search, ['name', 'email', 'subject', 'message']),
            'campaigns' => $this->filter(EmailCampaign::onlyTrashed(), $search, ['subject', 'message']),
            'ministry' => $this->filter(MinistryLeader::onlyTrashed(), $search, ['name', 'position', 'email', 'phone', 'country']),
            default => video::onlyTrashed(),
        };
    }

    private function filter(Builder $query, string $search, array $columns): Builder
    {
        if ($search === '') {
            return $query;
        }

        $query->where(function (Builder $builder) use ($search, $columns): void {
            foreach ($columns as $column) {
                $builder->orWhere($column, 'like', '%'.$search.'%');
            }
        });

        return $query;
    }

    private function collectRows(string $module, string $search, int $limit): Collection
    {
        $modules = $this->modules();
        $rows = $this->queryFor($module, $search)
            ->orderByDesc('deleted_at')
            ->limit($limit)
            ->get();

        return $rows->map(function ($row) use ($module, $modules) {
            return [
                'id' => $row->id,
                'module_key' => $module,
                'module_label' => $modules[$module]['label'],
                'title' => $this->resolveTitle($module, $row),
                'subtitle' => $this->resolveSubtitle($module, $row),
                'deleted_at' => $row->deleted_at,
                'manage_url' => $modules[$module]['route'],
            ];
        });
    }

    private function resolveTrashedRecord(string $module, int $id): Model
    {
        return match ($module) {
            'videos' => video::withTrashed()->findOrFail($id),
            'audios' => audio::withTrashed()->findOrFail($id),
            'audiobooks' => audiobook::withTrashed()->findOrFail($id),
            'documents' => book::withTrashed()->findOrFail($id),
            'categories' => ContentCategory::withTrashed()->findOrFail($id),
            'playlists' => Playlist::withTrashed()->findOrFail($id),
            'video_series' => VideoSeries::withTrashed()->findOrFail($id),
            'events' => Event::withTrashed()->findOrFail($id),
            'users' => User::withTrashed()->findOrFail($id),
            'subscribers' => Subscriber::withTrashed()->findOrFail($id),
            'contacts' => ContactMessage::withTrashed()->findOrFail($id),
            'campaigns' => EmailCampaign::withTrashed()->findOrFail($id),
            'ministry' => MinistryLeader::withTrashed()->findOrFail($id),
            default => abort(404),
        };
    }

    private function validatedBulkItems(Request $request): array
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*' => ['required', 'string', 'regex:/^[a-z_]+:\d+$/'],
            'module_filter' => ['nullable', 'string'],
            'search_query' => ['nullable', 'string'],
        ]);

        return array_values(array_unique($validated['items']));
    }

    private function resolveTitle(string $module, mixed $row): string
    {
        return match ($module) {
            'users' => (string) ($row->name ?: trim(($row->first_name ?? '').' '.($row->last_name ?? '')) ?: $row->email),
            'subscribers' => (string) ($row->name ?: $row->email),
            'contacts' => (string) ($row->subject ?: $row->email ?: 'Contact message'),
            'campaigns' => (string) ($row->subject ?: 'Campaign'),
            'categories' => (string) ($row->name ?: 'Category'),
            'playlists' => (string) ($row->title ?: 'Playlist'),
            'video_series' => (string) ($row->title ?: 'Video series'),
            'ministry' => (string) ($row->name ?: 'Ministry profile'),
            default => (string) ($row->title ?: 'Untitled'),
        };
    }

    private function resolveSubtitle(string $module, mixed $row): string
    {
        return match ($module) {
            'users' => (string) ($row->email ?? ''),
            'subscribers' => (string) ($row->email ?? ''),
            'contacts' => (string) ($row->email ?? ''),
            'campaigns' => (string) ($row->status ?? ''),
            'categories' => (string) ($row->type ?? ''),
            'playlists' => (string) ($row->type ?? ''),
            'video_series' => '',
            'events' => (string) ($row->event_type ?? ''),
            'ministry' => (string) ($row->position ?? ''),
            default => '',
        };
    }
}
