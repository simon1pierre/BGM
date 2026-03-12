<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\ContentCategory;
use App\Models\UserActivityLog;
use App\Models\Book;
use App\Models\Audiobook;
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
            'document_file' => ['required', 'mimetypes:application/pdf', 'max:262144'],
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
            'create_audiobook' => ['nullable', 'boolean'],
            'audiobook_title' => ['required_if:create_audiobook,1', 'nullable', 'string', 'max:255'],
            'audiobook_part_files' => ['nullable', 'array'],
            'audiobook_part_files.*' => ['file', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:262144'],
            'audiobook_is_prayer_audio' => ['nullable', 'boolean'],
            'audiobook_is_published' => ['nullable', 'boolean'],
        ]);

        $documentPath = $request->file('document_file')->store('content/documents', 'public');
        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('content/documents/covers', 'public');
        }

        $document = Book::create([
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

        if ($request->boolean('create_audiobook')) {
            if (!$request->hasFile('audiobook_part_files')) {
                throw ValidationException::withMessages([
                    'audiobook_part_files' => 'Upload at least one audiobook part.',
                ]);
            }

            $parts = [];
            $order = 1;
            foreach ((array) $request->file('audiobook_part_files', []) as $file) {
                $stored = $file->store('content/audiobooks/parts', 'public');
                $baseName = pathinfo((string) $file->getClientOriginalName(), PATHINFO_FILENAME);
                $parts[] = [
                    'title' => trim($baseName) !== '' ? trim($baseName) : 'untitled',
                    'audio_file' => $stored,
                    'language' => 'rw',
                    'duration' => null,
                    'sort_order' => $order,
                    'is_published' => true,
                ];
                $order++;
            }

            $audioPath = null;
            if (count($parts) > 0) {
                $audioPath = $parts[0]['audio_file'];
            }

            $audiobook = Audiobook::create([
                'title' => trim((string) ($validated['audiobook_title'] ?? '')),
                'description' => null,
                'audio_file' => $audioPath,
                'thumbnail' => null,
                'duration' => null,
                'category_id' => $document->category_id,
                'book_id' => $document->id,
                'narrator' => null,
                'series' => null,
                'published_at' => $validated['published_at'] ?? null,
                'featured' => false,
                'recommended' => false,
                'is_prayer_audio' => $request->boolean('audiobook_is_prayer_audio'),
                'is_published' => $request->boolean('audiobook_is_published', true),
            ]);

            if (count($parts) > 0) {
                $audiobook->parts()->createMany($parts);
            }

            $request->merge([
                'title_en' => $audiobook->title,
                'title_fr' => $audiobook->title,
                'title_rw' => $audiobook->title,
            ]);
            $this->syncTranslations($audiobook, $request, ['title']);
        }

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

    public function edit(Book $document)
    {
        $categories = ContentCategory::query()
            ->whereIn('type', ['document', 'all'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $audioCategories = ContentCategory::query()
            ->whereIn('type', ['audio', 'all'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $linkedAudiobooks = Audiobook::query()
            ->withCount('publishedParts')
            ->where('book_id', $document->id)
            ->orderByDesc('created_at')
            ->get();

        return view('Admin.Content.Documents.edit', compact('document', 'categories', 'audioCategories', 'linkedAudiobooks'));
    }

    public function preview(Book $document)
    {
        return view('Admin.Content.Documents.preview', compact('document'));
    }

    public function update(Request $request, Book $document)
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
            'document_file' => ['nullable', 'mimetypes:application/pdf', 'max:262144'],
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

    public function destroy(Request $request, Book $document)
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

        return redirect()->back()->with('status', 'Document deleted.');
    }

    public function restore(Request $request, int $document)
    {
        $record = Book::withTrashed()->findOrFail($document);
        $record->restore();

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'document_restored',
            'meta' => [
                'id' => $record->id,
                'title' => $record->title,
            ],
        ]);

        return redirect()->back()->with('status', 'Document restored.');
    }

    public function forceDelete(Request $request, int $document)
    {
        $record = Book::withTrashed()->findOrFail($document);
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

        return redirect()->back()->with('status', 'Document permanently deleted.');
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










