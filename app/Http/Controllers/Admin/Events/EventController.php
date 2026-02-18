<?php

namespace App\Http\Controllers\Admin\Events;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query();

        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%'.$search.'%')
                    ->orWhere('description', 'like', '%'.$search.'%')
                    ->orWhere('location', 'like', '%'.$search.'%')
                    ->orWhere('venue', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('status')) {
            $status = (string) $request->query('status');
            if ($status === 'published') {
                $query->where('is_published', true);
            } elseif ($status === 'draft') {
                $query->where('is_published', false);
            }
        }

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->string('event_type'));
        }

        if ($request->filled('featured')) {
            $featured = $request->string('featured');
            if ($featured === '1') {
                $query->where('is_featured', true);
            } elseif ($featured === '0') {
                $query->where('is_featured', false);
            }
        }

        if ($request->filled('period')) {
            $period = $request->string('period');
            if ($period === 'upcoming') {
                $query->where(function ($q) {
                    $q->where('ends_at', '>=', now())
                        ->orWhere(function ($sub) {
                            $sub->whereNull('ends_at')->where('starts_at', '>=', now());
                        });
                });
            } elseif ($period === 'past') {
                $query->where(function ($q) {
                    $q->where('ends_at', '<', now())
                        ->orWhere(function ($sub) {
                            $sub->whereNull('ends_at')->where('starts_at', '<', now());
                        });
                });
            }
        }

        if ($request->string('deleted') === 'with') {
            $query->withTrashed();
        } elseif ($request->string('deleted') === 'only') {
            $query->onlyTrashed();
        }

        $events = $query->orderBy('starts_at')->orderByDesc('created_at')->paginate(15)->withQueryString();

        return view('Admin.Events.index', compact('events'));
    }

    public function create()
    {
        return view('Admin.Events.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateEvent($request);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
        }

        $event = Event::create([
            ...$validated,
            'image_path' => $imagePath,
            'is_published' => $request->boolean('is_published'),
            'is_featured' => $request->boolean('is_featured'),
        ]);

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'event_created',
            'meta' => [
                'event_id' => $event->id,
                'title' => $event->title,
            ],
        ]);

        return redirect()->route('admin.events.index')->with('status', 'Event created.');
    }

    public function edit(Event $event)
    {
        return view('Admin.Events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $this->validateEvent($request);

        $imagePath = $event->image_path;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
        }

        $event->update([
            ...$validated,
            'image_path' => $imagePath,
            'is_published' => $request->boolean('is_published'),
            'is_featured' => $request->boolean('is_featured'),
        ]);

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'event_updated',
            'meta' => [
                'event_id' => $event->id,
                'title' => $event->title,
            ],
        ]);

        return redirect()->route('admin.events.index')->with('status', 'Event updated.');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'event_deleted',
            'meta' => [
                'event_id' => $event->id,
                'title' => $event->title,
            ],
        ]);

        return redirect()->route('admin.events.index')->with('status', 'Event deleted.');
    }

    public function restore(int $event)
    {
        $record = Event::withTrashed()->findOrFail($event);
        $record->restore();

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'event_restored',
            'meta' => [
                'event_id' => $record->id,
                'title' => $record->title,
            ],
        ]);

        return redirect()->route('admin.events.index')->with('status', 'Event restored.');
    }

    public function togglePublished(Event $event)
    {
        $event->update(['is_published' => !$event->is_published]);

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'event_publish_toggled',
            'meta' => [
                'event_id' => $event->id,
                'title' => $event->title,
                'is_published' => $event->is_published,
            ],
        ]);

        return back()->with('status', $event->is_published ? 'Event published.' : 'Event moved to draft.');
    }

    public function toggleFeatured(Event $event)
    {
        $event->update(['is_featured' => !$event->is_featured]);

        UserActivityLog::create([
            'actor_user_id' => Auth::id(),
            'action' => 'event_featured_toggled',
            'meta' => [
                'event_id' => $event->id,
                'title' => $event->title,
                'is_featured' => $event->is_featured,
            ],
        ]);

        return back()->with('status', $event->is_featured ? 'Event marked as featured.' : 'Event removed from featured.');
    }

    private function validateEvent(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'event_type' => ['required', 'in:prayer_meeting,service,conference,other'],
            'location' => ['nullable', 'string', 'max:255'],
            'venue' => ['nullable', 'string', 'max:255'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'timezone' => ['required', 'string', 'max:64'],
            'live_platform' => ['nullable', 'in:zoom,youtube,other'],
            'live_url' => ['nullable', 'url', 'max:255'],
            'registration_url' => ['nullable', 'url', 'max:255'],
            'image' => ['nullable', 'image', 'max:4096'],
            'is_published' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
        ]);
    }
}
