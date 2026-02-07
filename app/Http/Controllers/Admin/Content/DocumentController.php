<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\UserActivityLog;
use App\Models\book;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        return view('Admin.Content.Documents.index');
    }

    public function create()
    {
        return view('Admin.Content.Documents.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'document_file' => ['required', 'mimetypes:application/pdf', 'max:20480'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
            'author' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'featured' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
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
            'category' => $validated['category'] ?? null,
            'published_at' => $validated['published_at'] ?? null,
            'featured' => $request->boolean('featured'),
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

        return redirect()->route('admin.documents.index')->with('status', 'Document created.');
    }

    public function edit(book $document)
    {
        return view('Admin.Content.Documents.edit', compact('document'));
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
            'document_file' => ['nullable', 'mimetypes:application/pdf', 'max:20480'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
            'author' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'featured' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
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
            'category' => $validated['category'] ?? null,
            'published_at' => $validated['published_at'] ?? null,
            'featured' => $request->boolean('featured'),
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
}
