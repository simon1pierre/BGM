<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\ContentComment;
use App\Models\ContentLike;
use App\Models\video;
use App\Models\book;
use App\Models\audio;
use Illuminate\Http\Request;

class ContentEngagementController extends Controller
{
    public function likeVideo(Request $request, video $video)
    {
        $deviceHash = $this->deviceHash($request);

        $like = ContentLike::query()
            ->where('content_type', $video->getMorphClass())
            ->where('content_id', $video->id)
            ->where('device_hash', $deviceHash)
            ->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            ContentLike::create([
                'content_type' => $video->getMorphClass(),
                'content_id' => $video->id,
                'device_hash' => $deviceHash,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'session_id' => $request->session()->getId(),
            ]);
            $liked = true;
        }

        $likesCount = ContentLike::query()
            ->where('content_type', $video->getMorphClass())
            ->where('content_id', $video->id)
            ->count();

        return response()->json([
            'liked' => $liked,
            'likes_count' => $likesCount,
        ]);
    }

    public function commentVideo(Request $request, video $video)
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:190'],
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $comment = ContentComment::create([
            'content_type' => $video->getMorphClass(),
            'content_id' => $video->id,
            'name' => $validated['name'] ?? null,
            'email' => $validated['email'] ?? null,
            'body' => $validated['body'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'session_id' => $request->session()->getId(),
            'is_approved' => true,
        ]);

        $commentsCount = ContentComment::query()
            ->where('content_type', $video->getMorphClass())
            ->where('content_id', $video->id)
            ->where('is_approved', true)
            ->count();

        return response()->json([
            'comment' => [
                'id' => $comment->id,
                'name' => $comment->name ?: 'Anonymous',
                'body' => $comment->body,
                'created_at' => $comment->created_at?->toDateTimeString(),
            ],
            'comments_count' => $commentsCount,
        ]);
    }

    public function likeBook(Request $request, book $book)
    {
        $deviceHash = $this->deviceHash($request);

        $like = ContentLike::query()
            ->where('content_type', $book->getMorphClass())
            ->where('content_id', $book->id)
            ->where('device_hash', $deviceHash)
            ->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            ContentLike::create([
                'content_type' => $book->getMorphClass(),
                'content_id' => $book->id,
                'device_hash' => $deviceHash,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'session_id' => $request->session()->getId(),
            ]);
            $liked = true;
        }

        $likesCount = ContentLike::query()
            ->where('content_type', $book->getMorphClass())
            ->where('content_id', $book->id)
            ->count();

        return response()->json([
            'liked' => $liked,
            'likes_count' => $likesCount,
        ]);
    }

    public function commentBook(Request $request, book $book)
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:190'],
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $comment = ContentComment::create([
            'content_type' => $book->getMorphClass(),
            'content_id' => $book->id,
            'name' => $validated['name'] ?? null,
            'email' => $validated['email'] ?? null,
            'body' => $validated['body'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'session_id' => $request->session()->getId(),
            'is_approved' => true,
        ]);

        $commentsCount = ContentComment::query()
            ->where('content_type', $book->getMorphClass())
            ->where('content_id', $book->id)
            ->where('is_approved', true)
            ->count();

        return response()->json([
            'comment' => [
                'id' => $comment->id,
                'name' => $comment->name ?: 'Anonymous',
                'body' => $comment->body,
                'created_at' => $comment->created_at?->toDateTimeString(),
            ],
            'comments_count' => $commentsCount,
        ]);
    }

    public function likeAudio(Request $request, audio $audio)
    {
        $deviceHash = $this->deviceHash($request);

        $like = ContentLike::query()
            ->where('content_type', $audio->getMorphClass())
            ->where('content_id', $audio->id)
            ->where('device_hash', $deviceHash)
            ->first();

        if ($like) {
            $like->delete();
            $liked = false;
        } else {
            ContentLike::create([
                'content_type' => $audio->getMorphClass(),
                'content_id' => $audio->id,
                'device_hash' => $deviceHash,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'session_id' => $request->session()->getId(),
            ]);
            $liked = true;
        }

        $likesCount = ContentLike::query()
            ->where('content_type', $audio->getMorphClass())
            ->where('content_id', $audio->id)
            ->count();

        return response()->json([
            'liked' => $liked,
            'likes_count' => $likesCount,
        ]);
    }

    public function commentAudio(Request $request, audio $audio)
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:190'],
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $comment = ContentComment::create([
            'content_type' => $audio->getMorphClass(),
            'content_id' => $audio->id,
            'name' => $validated['name'] ?? null,
            'email' => $validated['email'] ?? null,
            'body' => $validated['body'],
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'session_id' => $request->session()->getId(),
            'is_approved' => true,
        ]);

        $commentsCount = ContentComment::query()
            ->where('content_type', $audio->getMorphClass())
            ->where('content_id', $audio->id)
            ->where('is_approved', true)
            ->count();

        return response()->json([
            'comment' => [
                'id' => $comment->id,
                'name' => $comment->name ?: 'Anonymous',
                'body' => $comment->body,
                'created_at' => $comment->created_at?->toDateTimeString(),
            ],
            'comments_count' => $commentsCount,
        ]);
    }

    private function deviceHash(Request $request): string
    {
        $parts = [
            (string) $request->ip(),
            (string) $request->userAgent(),
            (string) $request->input('platform'),
            (string) $request->input('screen_width'),
            (string) $request->input('screen_height'),
            (string) $request->input('language'),
            (string) $request->input('timezone'),
        ];

        return hash('sha256', implode('|', $parts));
    }
}
