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
use App\Models\video;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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

    private function modules(): array
    {
        return [
            'videos' => ['label' => 'Videos', 'route' => route('admin.videos.index', ['deleted' => 'only'])],
            'audios' => ['label' => 'Audios', 'route' => route('admin.audios.index', ['deleted' => 'only'])],
            'audiobooks' => ['label' => 'Audiobooks', 'route' => route('admin.audiobooks.index', ['deleted' => 'only'])],
            'documents' => ['label' => 'Documents', 'route' => route('admin.documents.index', ['deleted' => 'only'])],
            'categories' => ['label' => 'Categories', 'route' => route('admin.categories.index', ['deleted' => 'only'])],
            'playlists' => ['label' => 'Playlists', 'route' => route('admin.playlists.index', ['deleted' => 'only'])],
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
                'module_key' => $module,
                'module_label' => $modules[$module]['label'],
                'title' => $this->resolveTitle($module, $row),
                'subtitle' => $this->resolveSubtitle($module, $row),
                'deleted_at' => $row->deleted_at,
                'manage_url' => $modules[$module]['route'],
            ];
        });
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
            'events' => (string) ($row->event_type ?? ''),
            'ministry' => (string) ($row->position ?? ''),
            default => '',
        };
    }
}

