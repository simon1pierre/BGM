<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\DownloadsLog;
use App\Models\audio;
use App\Models\book;
use App\Models\video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ContentDownloadController extends Controller
{
    public function audio(Request $request, audio $audio): StreamedResponse
    {
        if (!Storage::disk('public')->exists($audio->audio_file)) {
            abort(404);
        }

        $this->recordDownload('audio', $audio->id, $request->ip());
        $audio->increment('download_count');

        return Storage::disk('public')->download(
            $audio->audio_file,
            $this->sanitizeDownloadName($audio->title, 'mp3')
        );
    }

    public function document(Request $request, book $document): StreamedResponse
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404);
        }

        $this->recordDownload('book', $document->id, $request->ip());
        $document->increment('download_count');

        return Storage::disk('public')->download(
            $document->file_path,
            $this->sanitizeDownloadName($document->title, 'pdf')
        );
    }

    public function videoView(video $video)
    {
        $video->increment('view_count');

        return response()->noContent();
    }

    private function recordDownload(string $type, int $id, ?string $ip): void
    {
        DownloadsLog::create([
            'item_type' => $type,
            'item_id' => $id,
            'ip_address' => $ip,
            'downloaded_at' => now(),
        ]);
    }

    private function sanitizeDownloadName(string $title, string $extension): string
    {
        $name = preg_replace('/[^A-Za-z0-9\-\s]/', '', $title);
        $name = trim(preg_replace('/\s+/', ' ', $name));
        $name = $name !== '' ? $name : 'download';

        return $name.'.'.$extension;
    }
}
