<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Concerns\HandlesTranslations;
use App\Http\Controllers\Controller;
use App\Models\Devotional;
use App\Models\ContentNotification;
use App\Models\UserActivityLog;
use App\Jobs\SendContentNotificationJob;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DevotionalController extends Controller
{
    use HandlesTranslations;

    public function index(Request $request)
    {
        $query = Devotional::query()->with('translations');

        if ($request->filled('search')) {
            $search = trim((string) $request->query('search'));
            $query->where(function ($builder) use ($search): void {
                $builder->where('title', 'like', '%'.$search.'%')
                    ->orWhere('excerpt', 'like', '%'.$search.'%')
                    ->orWhere('body', 'like', '%'.$search.'%')
                    ->orWhere('author', 'like', '%'.$search.'%')
                    ->orWhere('scripture_reference', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('status')) {
            $query->where('is_published', (string) $request->query('status') === 'published');
        }

        if ($request->filled('featured')) {
            $query->where('featured', (string) $request->query('featured') === 'yes');
        }

        if ($request->filled('deleted')) {
            $deleted = (string) $request->query('deleted');
            if ($deleted === 'with') {
                $query->withTrashed();
            } elseif ($deleted === 'only') {
                $query->onlyTrashed();
            }
        }

        $devotionals = $query
            ->orderByDesc('featured')
            ->orderBy('sort_order')
            ->orderByDesc('published_at')
            ->paginate(12)
            ->withQueryString();

        return view('Admin.Content.Devotionals.index', compact('devotionals'));
    }

    public function create()
    {
        return view('Admin.Content.Devotionals.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:4000'],
            'body' => ['required', 'string'],
            'title_en' => ['required', 'string', 'max:255'],
            'title_fr' => ['required', 'string', 'max:255'],
            'title_rw' => ['required', 'string', 'max:255'],
            'excerpt_en' => ['nullable', 'string', 'max:4000'],
            'excerpt_fr' => ['nullable', 'string', 'max:4000'],
            'excerpt_rw' => ['nullable', 'string', 'max:4000'],
            'body_en' => ['required', 'string'],
            'body_fr' => ['required', 'string'],
            'body_rw' => ['required', 'string'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
            'scripture_reference' => ['nullable', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'featured' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'notify_subscribers' => ['nullable', 'boolean'],
            'notify_target' => ['nullable', 'in:all,custom'],
            'notify_emails' => ['nullable', 'string'],
            'notify_message' => ['nullable', 'string'],
        ]);

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('content/devotionals/covers', 'public');
        }

        $devotional = Devotional::create([
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'] ?? null,
            'body' => $validated['body'],
            'cover_image' => $coverPath,
            'scripture_reference' => $validated['scripture_reference'] ?? null,
            'author' => $validated['author'] ?? null,
            'published_at' => $validated['published_at'] ?? null,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'featured' => $request->boolean('featured'),
            'is_published' => $request->boolean('is_published'),
        ]);

        $this->syncTranslations($devotional, $request, ['title', 'excerpt', 'body']);

        $this->maybeNotifySubscribers($request, [
            'type' => 'devotional',
            'title' => $devotional->title,
            'description' => $devotional->excerpt ?: \Illuminate\Support\Str::limit(strip_tags((string) $devotional->body), 180),
            'category' => 'Devotional',
            'thumbnail' => $devotional->cover_image_url,
            'cta_url' => route('devotionals.show', $devotional),
            'cta_text' => 'Read Devotional',
            'subject' => 'New Devotional: '.$devotional->title,
            'extra_text' => $request->input('notify_message'),
        ]);

        UserActivityLog::create([
            'actor_user_id' => $request->user()?->id,
            'action' => 'devotional_created',
            'meta' => ['id' => $devotional->id, 'title' => $devotional->title],
        ]);

        return redirect()->route('admin.devotionals.index')->with('status', 'Devotional created.');
    }

    public function edit(Devotional $devotional)
    {
        $translations = [
            'en' => $devotional->translationFor('en'),
            'fr' => $devotional->translationFor('fr'),
            'rw' => $devotional->translationFor('rw'),
        ];

        return view('Admin.Content.Devotionals.edit', compact('devotional', 'translations'));
    }

    public function show(Devotional $devotional)
    {
        $devotional->load('translations');

        return view('Admin.Content.Devotionals.show', compact('devotional'));
    }

    public function update(Request $request, Devotional $devotional)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:4000'],
            'body' => ['required', 'string'],
            'title_en' => ['required', 'string', 'max:255'],
            'title_fr' => ['required', 'string', 'max:255'],
            'title_rw' => ['required', 'string', 'max:255'],
            'excerpt_en' => ['nullable', 'string', 'max:4000'],
            'excerpt_fr' => ['nullable', 'string', 'max:4000'],
            'excerpt_rw' => ['nullable', 'string', 'max:4000'],
            'body_en' => ['required', 'string'],
            'body_fr' => ['required', 'string'],
            'body_rw' => ['required', 'string'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
            'scripture_reference' => ['nullable', 'string', 'max:255'],
            'author' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'featured' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'notify_subscribers' => ['nullable', 'boolean'],
            'notify_target' => ['nullable', 'in:all,custom'],
            'notify_emails' => ['nullable', 'string'],
            'notify_message' => ['nullable', 'string'],
        ]);

        $coverPath = $devotional->cover_image;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('content/devotionals/covers', 'public');
        }

        $devotional->update([
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'] ?? null,
            'body' => $validated['body'],
            'cover_image' => $coverPath,
            'scripture_reference' => $validated['scripture_reference'] ?? null,
            'author' => $validated['author'] ?? null,
            'published_at' => $validated['published_at'] ?? null,
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
            'featured' => $request->boolean('featured'),
            'is_published' => $request->boolean('is_published'),
        ]);

        $this->syncTranslations($devotional, $request, ['title', 'excerpt', 'body']);

        $this->maybeNotifySubscribers($request, [
            'type' => 'devotional',
            'title' => $devotional->title,
            'description' => $devotional->excerpt ?: \Illuminate\Support\Str::limit(strip_tags((string) $devotional->body), 180),
            'category' => 'Devotional',
            'thumbnail' => $devotional->cover_image_url,
            'cta_url' => route('devotionals.show', $devotional),
            'cta_text' => 'Read Devotional',
            'subject' => 'Updated Devotional: '.$devotional->title,
            'extra_text' => $request->input('notify_message'),
        ]);

        UserActivityLog::create([
            'actor_user_id' => $request->user()?->id,
            'action' => 'devotional_updated',
            'meta' => ['id' => $devotional->id, 'title' => $devotional->title],
        ]);

        return redirect()->route('admin.devotionals.index')->with('status', 'Devotional updated.');
    }

    public function destroy(Request $request, Devotional $devotional)
    {
        $devotional->delete();

        UserActivityLog::create([
            'actor_user_id' => $request->user()?->id,
            'action' => 'devotional_deleted',
            'meta' => ['id' => $devotional->id, 'title' => $devotional->title],
        ]);

        return redirect()->back()->with('status', 'Devotional deleted.');
    }

    public function restore(Request $request, int $devotional)
    {
        $record = Devotional::withTrashed()->findOrFail($devotional);
        $record->restore();

        UserActivityLog::create([
            'actor_user_id' => $request->user()?->id,
            'action' => 'devotional_restored',
            'meta' => ['id' => $record->id, 'title' => $record->title],
        ]);

        return redirect()->back()->with('status', 'Devotional restored.');
    }

    public function forceDelete(Request $request, int $devotional)
    {
        $record = Devotional::withTrashed()->findOrFail($devotional);
        $title = $record->title;
        $record->forceDelete();

        UserActivityLog::create([
            'actor_user_id' => $request->user()?->id,
            'action' => 'devotional_force_deleted',
            'meta' => ['id' => $devotional, 'title' => $title],
        ]);

        return redirect()->back()->with('status', 'Devotional permanently deleted.');
    }

    public function toggleFeatured(Request $request, Devotional $devotional)
    {
        $devotional->update(['featured' => !$devotional->featured]);

        UserActivityLog::create([
            'actor_user_id' => $request->user()?->id,
            'action' => 'devotional_featured_toggled',
            'meta' => ['id' => $devotional->id, 'featured' => $devotional->featured],
        ]);

        return redirect()->back()->with('status', $devotional->featured ? 'Devotional highlighted.' : 'Devotional un-highlighted.');
    }

    public function togglePublished(Request $request, Devotional $devotional)
    {
        $devotional->update(['is_published' => !$devotional->is_published]);

        UserActivityLog::create([
            'actor_user_id' => $request->user()?->id,
            'action' => 'devotional_published_toggled',
            'meta' => ['id' => $devotional->id, 'is_published' => $devotional->is_published],
        ]);

        return redirect()->back()->with('status', $devotional->is_published ? 'Devotional published.' : 'Devotional moved to draft.');
    }

    private function maybeNotifySubscribers(Request $request, array $payload): void
    {
        if (!$request->boolean('notify_subscribers')) {
            return;
        }

        $target = $request->input('notify_target', 'all');
        if ($target === 'custom') {
            $emails = $this->parseEmails((string) $request->input('notify_emails'));

            if (count($emails) === 0) {
                throw ValidationException::withMessages([
                    'notify_emails' => 'Please provide at least one valid email address.',
                ]);
            }

            ContentNotification::create([
                'payload' => $payload,
                'target_type' => 'custom',
                'target_emails' => $emails,
                'status' => 'sent',
                'sent_at' => now(),
                'created_by' => $request->user()?->id,
            ]);
            SendContentNotificationJob::dispatchSync($payload, $emails, false);
            return;
        }

        ContentNotification::create([
            'payload' => $payload,
            'target_type' => 'all',
            'target_emails' => null,
            'status' => 'sent',
            'sent_at' => now(),
            'created_by' => $request->user()?->id,
        ]);
        SendContentNotificationJob::dispatchSync($payload, [], true);
    }

    private function parseEmails(string $raw): array
    {
        return collect(explode(',', $raw))
            ->map(fn ($email) => trim($email))
            ->filter(fn ($email) => $email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL))
            ->unique()
            ->values()
            ->all();
    }
}
