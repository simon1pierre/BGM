<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\Playlist;
use App\Models\PlaylistItem;
use App\Models\UserActivityLog;
use App\Models\video;
use App\Models\audio;
use App\Http\Controllers\Concerns\HandlesTranslations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PlaylistController extends Controller
{
    use HandlesTranslations;

    public function index(Request $request)
    {
        $playlists = Playlist::query()
            ->withCount('items');

        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));
            $playlists->where(function ($query) use ($search): void {
                $query->where('title', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('type')) {
            $playlists->where('type', (string) $request->query('type'));
        }

        if ($request->filled('status')) {
            $status = (string) $request->query('status');
            if ($status === 'published') {
                $playlists->where('is_published', true);
            } elseif ($status === 'draft') {
                $playlists->where('is_published', false);
            }
        }

        if ($request->filled('deleted')) {
            $deleted = (string) $request->query('deleted');
            if ($deleted === 'with') {
                $playlists->withTrashed();
            } elseif ($deleted === 'only') {
                $playlists->onlyTrashed();
            }
        }

        $playlists = $playlists
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('Admin.Content.Playlists.index', compact('playlists'));
    }

    public function create()
    {
        $videos = video::query()->orderByDesc('created_at')->get();
        $audios = audio::query()->orderByDesc('created_at')->get();

        return view('Admin.Content.Playlists.create', compact('videos', 'audios'));
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
            'type' => ['required', 'in:video,audio'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
            'is_published' => ['nullable', 'boolean'],
            'featured' => ['nullable', 'boolean'],
            'items' => ['nullable', 'array'],
            'items.*' => ['integer'],
            'orders' => ['nullable', 'array'],
        ]);

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('playlists/covers', 'public');
        }

        $playlist = Playlist::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'cover_image' => $coverPath,
            'is_published' => $request->boolean('is_published'),
            'featured' => $request->boolean('featured'),
            'created_by' => $request->user()?->id,
        ]);

        $this->syncItems($playlist, $request->input('items', []), $request->input('orders', []));

        UserActivityLog::create([
            'actor_user_id' => $request->user()?->id,
            'action' => 'playlist_created',
            'meta' => [
                'id' => $playlist->id,
                'title' => $playlist->title,
            ],
        ]);

        $this->syncTranslations($playlist, $request, ['title', 'description']);

        return redirect()->route('admin.playlists.index')->with('status', 'Playlist created.');
    }

    public function edit(Playlist $playlist)
    {
        $videos = video::query()->orderByDesc('created_at')->get();
        $audios = audio::query()->orderByDesc('created_at')->get();
        $selected = $playlist->items->pluck('item_id')->all();
        $orders = $playlist->items->pluck('sort_order', 'item_id')->all();

        return view('Admin.Content.Playlists.edit', compact('playlist', 'videos', 'audios', 'selected', 'orders'));
    }

    public function show(Playlist $playlist)
    {
        $items = PlaylistItem::query()
            ->where('playlist_id', $playlist->id)
            ->orderBy('sort_order')
            ->paginate(15);

        return view('Admin.Content.Playlists.show', compact('playlist', 'items'));
    }

    public function update(Request $request, Playlist $playlist)
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
            'type' => ['required', 'in:video,audio'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
            'is_published' => ['nullable', 'boolean'],
            'featured' => ['nullable', 'boolean'],
            'items' => ['nullable', 'array'],
            'items.*' => ['integer'],
            'orders' => ['nullable', 'array'],
        ]);

        $coverPath = $playlist->cover_image;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('playlists/covers', 'public');
        }

        $playlist->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'type' => $validated['type'],
            'cover_image' => $coverPath,
            'is_published' => $request->boolean('is_published'),
            'featured' => $request->boolean('featured'),
        ]);

        $this->syncItems($playlist, $request->input('items', []), $request->input('orders', []));

        UserActivityLog::create([
            'actor_user_id' => $request->user()?->id,
            'action' => 'playlist_updated',
            'meta' => [
                'id' => $playlist->id,
                'title' => $playlist->title,
            ],
        ]);

        $this->syncTranslations($playlist, $request, ['title', 'description']);

        return redirect()->route('admin.playlists.index')->with('status', 'Playlist updated.');
    }

    public function destroy(Request $request, Playlist $playlist)
    {
        $playlist->delete();

        UserActivityLog::create([
            'actor_user_id' => $request->user()?->id,
            'action' => 'playlist_deleted',
            'meta' => [
                'id' => $playlist->id,
                'title' => $playlist->title,
            ],
        ]);

        return redirect()->back()->with('status', 'Playlist deleted.');
    }

    public function restore(Request $request, int $playlist)
    {
        $record = Playlist::withTrashed()->findOrFail($playlist);
        $record->restore();

        UserActivityLog::create([
            'actor_user_id' => $request->user()?->id,
            'action' => 'playlist_restored',
            'meta' => [
                'id' => $record->id,
                'title' => $record->title,
            ],
        ]);

        return redirect()->back()->with('status', 'Playlist restored.');
    }

    public function forceDelete(Request $request, int $playlist)
    {
        $record = Playlist::withTrashed()->findOrFail($playlist);
        $title = $record->title;
        $record->forceDelete();

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'playlist_force_deleted',
            'meta' => [
                'id' => $playlist,
                'title' => $title,
            ],
        ]);

        return redirect()->back()->with('status', 'Playlist permanently deleted.');
    }

    private function syncItems(Playlist $playlist, array $items, array $orders): void
    {
        $items = array_values(array_unique(array_map('intval', $items)));
        if (count($items) > 0) {
            $table = $playlist->type === 'video' ? 'videos' : 'audios';
            $validCount = DB::table($table)->whereIn('id', $items)->count();
            if ($validCount !== count($items)) {
                throw ValidationException::withMessages([
                    'items' => 'One or more selected items are invalid for this playlist type.',
                ]);
            }
        }

        $playlist->items()->delete();
        $sort = 1;

        foreach ($items as $itemId) {
            $order = isset($orders[$itemId]) ? (int) $orders[$itemId] : $sort;
            PlaylistItem::create([
                'playlist_id' => $playlist->id,
                'item_type' => $playlist->type,
                'item_id' => $itemId,
                'sort_order' => $order,
            ]);
            $sort++;
        }
    }
}
