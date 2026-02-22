<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\ContentCategory;
use App\Models\UserActivityLog;
use App\Models\book;
use App\Jobs\SendContentNotificationJob;
use App\Models\ContentNotification;
use App\Http\Controllers\Concerns\HandlesTranslations;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class DocumentController extends Controller
{
    use HandlesTranslations;

    public function index()
    {
        return view('Admin.Content.Documents.index');
    }

    public function create()
    {
        $categories = ContentCategory::query()
            ->whereIn('type', ['document', 'all'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('Admin.Content.Documents.create', compact('categories'));
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
            'document_file' => ['required', 'mimetypes:application/pdf', 'max:20480'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
            'author' => ['nullable', 'string', 'max:255'],
            'series' => ['nullable', 'string', 'max:255'],
            'category_id' => [
                'required',
                Rule::exists('content_categories', 'id')->whereIn('type', ['document', 'all']),
            ],
            'published_at' => ['nullable', 'date'],
            'featured' => ['nullable', 'boolean'],
            'recommended' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'notify_subscribers' => ['nullable', 'boolean'],
            'notify_target' => ['nullable', 'in:all,custom'],
            'notify_emails' => ['nullable', 'string'],
            'notify_message' => ['nullable', 'string'],
        ]);

        $documentPath = $request->file('document_file')->store('content/documents', 'public');
        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('content/documents/covers', 'public');
        }

        $document = book::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'file_path' => $documentPath,
            'cover_image' => $coverPath,
            'author' => $validated['author'] ?? null,
            'series' => $validated['series'] ?? null,
            'category_id' => $validated['category_id'],
            'published_at' => $validated['published_at'] ?? null,
            'featured' => $request->boolean('featured'),
            'recommended' => $request->boolean('recommended'),
            'is_published' => $request->boolean('is_published'),
        ]);

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'document_created',
            'meta' => [
                'id' => $document->id,
                'title' => $document->title,
            ],
        ]);

        $this->syncTranslations($document, $request, ['title', 'description']);

        $this->maybeNotifySubscribers($request, [
            'type' => 'document',
            'title' => $document->title,
            'description' => $document->description,
            'category' => $document->category?->name,
            'thumbnail' => $document->cover_image ? asset('storage/'.$document->cover_image) : null,
            'cta_url' => asset('storage/'.$document->file_path),
            'cta_text' => 'Download PDF',
            'subject' => 'New Document: '.$document->title,
            'extra_text' => $request->input('notify_message'),
        ]);

        return redirect()->route('admin.documents.index')->with('status', 'Document created.');
    }

    public function edit(book $document)
    {
        $categories = ContentCategory::query()
            ->whereIn('type', ['document', 'all'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('Admin.Content.Documents.edit', compact('document', 'categories'));
    }

    public function preview(book $document)
    {
        return view('Admin.Content.Documents.preview', compact('document'));
    }

    public function update(Request $request, book $document)
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
            'document_file' => ['nullable', 'mimetypes:application/pdf', 'max:20480'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
            'author' => ['nullable', 'string', 'max:255'],
            'series' => ['nullable', 'string', 'max:255'],
            'category_id' => [
                'required',
                Rule::exists('content_categories', 'id')->whereIn('type', ['document', 'all']),
            ],
            'published_at' => ['nullable', 'date'],
            'featured' => ['nullable', 'boolean'],
            'recommended' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'notify_subscribers' => ['nullable', 'boolean'],
            'notify_target' => ['nullable', 'in:all,custom'],
            'notify_emails' => ['nullable', 'string'],
            'notify_message' => ['nullable', 'string'],
        ]);

        $documentPath = $document->file_path;
        if ($request->hasFile('document_file')) {
            $documentPath = $request->file('document_file')->store('content/documents', 'public');
        }

        $coverPath = $document->cover_image;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('content/documents/covers', 'public');
        }

        $document->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'file_path' => $documentPath,
            'cover_image' => $coverPath,
            'author' => $validated['author'] ?? null,
            'series' => $validated['series'] ?? null,
            'category_id' => $validated['category_id'],
            'published_at' => $validated['published_at'] ?? null,
            'featured' => $request->boolean('featured'),
            'recommended' => $request->boolean('recommended'),
            'is_published' => $request->boolean('is_published'),
        ]);

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'document_updated',
            'meta' => [
                'id' => $document->id,
                'title' => $document->title,
            ],
        ]);

        $this->syncTranslations($document, $request, ['title', 'description']);

        $this->maybeNotifySubscribers($request, [
            'type' => 'document',
            'title' => $document->title,
            'description' => $document->description,
            'category' => $document->category?->name,
            'thumbnail' => $document->cover_image ? asset('storage/'.$document->cover_image) : null,
            'cta_url' => asset('storage/'.$document->file_path),
            'cta_text' => 'Download PDF',
            'subject' => 'Updated Document: '.$document->title,
            'extra_text' => $request->input('notify_message'),
        ]);

        return redirect()->route('admin.documents.index')->with('status', 'Document updated.');
    }

    public function destroy(Request $request, book $document)
    {
        $document->delete();

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'document_deleted',
            'meta' => [
                'id' => $document->id,
                'title' => $document->title,
            ],
        ]);

        return redirect()->route('admin.documents.index')->with('status', 'Document deleted.');
    }

    public function restore(Request $request, int $document)
    {
        $record = book::withTrashed()->findOrFail($document);
        $record->restore();

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'document_restored',
            'meta' => [
                'id' => $record->id,
                'title' => $record->title,
            ],
        ]);

        return redirect()->route('admin.documents.index')->with('status', 'Document restored.');
    }

    public function forceDelete(Request $request, int $document)
    {
        $record = book::withTrashed()->findOrFail($document);
        $title = $record->title;
        $record->forceDelete();

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'document_force_deleted',
            'meta' => [
                'id' => $document,
                'title' => $title,
            ],
        ]);

        return redirect()->route('admin.documents.index')->with('status', 'Document permanently deleted.');
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
