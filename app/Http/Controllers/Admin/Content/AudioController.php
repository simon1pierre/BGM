<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\UserActivityLog;
use App\Models\audio;
use Illuminate\Http\Request;

class AudioController extends Controller
{
    public function index()
    {
        return view('Admin.Content.Audios.index');
    }

    public function create()
    {
        return view('Admin.Content.Audios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'audio_file' => ['required', 'mimetypes:audio/mpeg,audio/mp3,audio/mp4,audio/x-wav,audio/ogg', 'max:20480'],
            'duration' => ['nullable', 'string', 'max:50'],
            'speaker' => ['nullable', 'string', 'max:255'],
            'series' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'featured' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $audioPath = $request->file('audio_file')->store('content/audios', 'public');

        $audio = audio::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'audio_file' => $audioPath,
            'duration' => $validated['duration'] ?? null,
            'speaker' => $validated['speaker'] ?? null,
            'series' => $validated['series'] ?? null,
            'published_at' => $validated['published_at'] ?? null,
            'featured' => $request->boolean('featured'),
            'is_published' => $request->boolean('is_published'),
        ]);

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'audio_created',
            'meta' => [
                'id' => $audio->id,
                'title' => $audio->title,
            ],
        ]);

        return redirect()->route('admin.audios.index')->with('status', 'Audio created.');
    }

    public function edit(audio $audio)
    {
        return view('Admin.Content.Audios.edit', compact('audio'));
    }

    public function preview(audio $audio)
    {
        return view('Admin.Content.Audios.preview', compact('audio'));
    }

    public function update(Request $request, audio $audio)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'audio_file' => ['nullable', 'mimetypes:audio/mpeg,audio/mp4,audio/x-wav,audio/ogg', 'max:20480'],
            'duration' => ['nullable', 'string', 'max:50'],
            'speaker' => ['nullable', 'string', 'max:255'],
            'series' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'featured' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $audioPath = $audio->audio_file;
        if ($request->hasFile('audio_file')) {
            $audioPath = $request->file('audio_file')->store('content/audios', 'public');
        }

        $audio->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'audio_file' => $audioPath,
            'duration' => $validated['duration'] ?? null,
            'speaker' => $validated['speaker'] ?? null,
            'series' => $validated['series'] ?? null,
            'published_at' => $validated['published_at'] ?? null,
            'featured' => $request->boolean('featured'),
            'is_published' => $request->boolean('is_published'),
        ]);

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'audio_updated',
            'meta' => [
                'id' => $audio->id,
                'title' => $audio->title,
            ],
        ]);

        return redirect()->route('admin.audios.index')->with('status', 'Audio updated.');
    }

    public function destroy(Request $request, audio $audio)
    {
        $audio->delete();

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'audio_deleted',
            'meta' => [
                'id' => $audio->id,
                'title' => $audio->title,
            ],
        ]);

        return redirect()->route('admin.audios.index')->with('status', 'Audio deleted.');
    }

    public function restore(Request $request, int $audio)
    {
        $record = audio::withTrashed()->findOrFail($audio);
        $record->restore();

        UserActivityLog::create([
            'actor_user_id' => $request->user()->id ?? null,
            'action' => 'audio_restored',
            'meta' => [
                'id' => $record->id,
                'title' => $record->title,
            ],
        ]);

        return redirect()->route('admin.audios.index')->with('status', 'Audio restored.');
    }
}
