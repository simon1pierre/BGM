<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\ContentCategory;
use App\Models\Subscriber;
use App\Models\UserActivityLog;
use App\Models\video;
use App\Models\book;
use App\Models\audio;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $latestVideos = video::query()
            ->with(['category.translations', 'translations'])
            ->where('is_published', true)
            ->orderByDesc('featured')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        $featuredVideo = video::query()
            ->with(['category.translations', 'translations'])
            ->where('is_published', true)
            ->orderByDesc('featured')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->first();

        $featuredBook = book::query()
            ->with(['category.translations', 'translations'])
            ->where('is_published', true)
            ->orderByDesc('featured')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->first();

        $featuredAudio = audio::query()
            ->with(['category.translations', 'translations'])
            ->where('is_published', true)
            ->orderByDesc('featured')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->first();

        $recommendedBooks = book::query()
            ->with(['category.translations', 'translations'])
            ->where('is_published', true)
            ->where('recommended', true)
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        $recommendedAudios = audio::query()
            ->with(['category.translations', 'translations'])
            ->where('is_published', true)
            ->where('recommended', true)
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        return view('home', compact('latestVideos', 'featuredVideo', 'featuredBook', 'featuredAudio', 'recommendedBooks', 'recommendedAudios'));
    }

    public function videos(Request $request)
    {
        $categories = ContentCategory::query()
            ->whereIn('type', ['video', 'all'])
            ->where('is_active', true)
            ->with('translations')
            ->withCount(['videos as videos_count' => function ($query) {
                $query->where('is_published', true);
            }])
            ->orderBy('name')
            ->get();

        $activeCategory = $request->query('category');
        $onlyFeatured = $request->boolean('featured');
        $search = trim((string) $request->query('q'));

        $locale = app()->getLocale();

        $videosQuery = video::query()
            ->with(['category.translations', 'translations'])
            ->withCount(['likes', 'comments' => function ($query) {
                $query->where('is_approved', true);
            }])
            ->with(['comments' => function ($query) {
                $query->where('is_approved', true)
                    ->orderByDesc('created_at')
                    ->limit(2);
            }])
            ->where('is_published', true)
            ->when($search, function ($query) use ($search, $locale) {
                $query->where(function ($inner) use ($search, $locale) {
                    $inner->where('title', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%')
                        ->orWhere('speaker', 'like', '%'.$search.'%')
                        ->orWhere('series', 'like', '%'.$search.'%')
                        ->orWhereHas('translations', function ($translation) use ($search, $locale) {
                            $translation->where('locale', $locale)
                                ->where(function ($sub) use ($search) {
                                    $sub->where('title', 'like', '%'.$search.'%')
                                        ->orWhere('description', 'like', '%'.$search.'%');
                                });
                        });
                });
            })
            ->when($onlyFeatured, function ($query) {
                $query->where('featured', true);
            })
            ->when($activeCategory, function ($query) use ($activeCategory) {
                $query->where('category_id', $activeCategory);
            })
            ->orderByDesc('published_at')
            ->orderByDesc('created_at');

        $allCount = video::query()
            ->where('is_published', true)
            ->count();
        $featuredCount = video::query()
            ->where('is_published', true)
            ->where('featured', true)
            ->count();

        $videos = $videosQuery
            ->paginate(9)
            ->withQueryString();

        $recommendedVideos = video::query()
            ->with(['category.translations', 'translations'])
            ->where('is_published', true)
            ->whereNotIn('id', $videos->pluck('id'))
            ->orderByDesc('featured')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        return view('videos.index', compact('videos', 'categories', 'activeCategory', 'onlyFeatured', 'allCount', 'featuredCount', 'search', 'recommendedVideos'));
    }

    public function books(Request $request)
    {
        $categories = ContentCategory::query()
            ->whereIn('type', ['document', 'all'])
            ->where('is_active', true)
            ->with('translations')
            ->withCount(['documents as documents_count' => function ($query) {
                $query->where('is_published', true);
            }])
            ->orderBy('name')
            ->get();

        $activeCategory = $request->query('category');
        $search = trim((string) $request->query('q'));

        $locale = app()->getLocale();

        $booksQuery = book::query()
            ->with(['category.translations', 'translations'])
            ->withCount(['likes', 'comments' => function ($query) {
                $query->where('is_approved', true);
            }])
            ->where('is_published', true)
            ->when($search, function ($query) use ($search, $locale) {
                $query->where(function ($inner) use ($search, $locale) {
                    $inner->where('title', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%')
                        ->orWhere('author', 'like', '%'.$search.'%')
                        ->orWhere('series', 'like', '%'.$search.'%')
                        ->orWhereHas('translations', function ($translation) use ($search, $locale) {
                            $translation->where('locale', $locale)
                                ->where(function ($sub) use ($search) {
                                    $sub->where('title', 'like', '%'.$search.'%')
                                        ->orWhere('description', 'like', '%'.$search.'%');
                                });
                        });
                });
            })
            ->when($activeCategory, function ($query) use ($activeCategory) {
                $query->where('category_id', $activeCategory);
            })
            ->orderByDesc('published_at')
            ->orderByDesc('created_at');

        $allCount = book::query()
            ->where('is_published', true)
            ->count();

        $books = $booksQuery
            ->paginate(9)
            ->withQueryString();

        return view('books.index', compact('books', 'categories', 'activeCategory', 'allCount', 'search'));
    }

    public function bookShow(book $book)
    {
        if (!$book->is_published) {
            abort(404);
        }

        $book->load('category');
        $book->load('translations');
        $book->category?->load('translations');
        $book->loadCount(['likes', 'comments' => function ($query) {
            $query->where('is_approved', true);
        }]);
        $book->load(['comments' => function ($query) {
            $query->where('is_approved', true)
                ->orderByDesc('created_at')
                ->limit(5);
        }]);

        $relatedBooks = book::query()
            ->with(['category.translations', 'translations'])
            ->where('is_published', true)
            ->where('id', '!=', $book->id)
            ->when($book->category_id, function ($query) use ($book) {
                $query->where('category_id', $book->category_id);
            })
            ->orderByDesc('featured')
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('books.show', compact('book', 'relatedBooks'));
    }

    public function audios(Request $request)
    {
        $categories = ContentCategory::query()
            ->whereIn('type', ['audio', 'all'])
            ->where('is_active', true)
            ->with('translations')
            ->withCount(['audios as audios_count' => function ($query) {
                $query->where('is_published', true);
            }])
            ->orderBy('name')
            ->get();

        $activeCategory = $request->query('category');
        $search = trim((string) $request->query('q'));

        $locale = app()->getLocale();

        $audiosQuery = audio::query()
            ->with(['category.translations', 'translations'])
            ->withCount(['likes', 'comments' => function ($query) {
                $query->where('is_approved', true);
            }])
            ->where('is_published', true)
            ->when($search, function ($query) use ($search, $locale) {
                $query->where(function ($inner) use ($search, $locale) {
                    $inner->where('title', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%')
                        ->orWhere('speaker', 'like', '%'.$search.'%')
                        ->orWhere('series', 'like', '%'.$search.'%')
                        ->orWhereHas('translations', function ($translation) use ($search, $locale) {
                            $translation->where('locale', $locale)
                                ->where(function ($sub) use ($search) {
                                    $sub->where('title', 'like', '%'.$search.'%')
                                        ->orWhere('description', 'like', '%'.$search.'%');
                                });
                        });
                });
            })
            ->when($activeCategory, function ($query) use ($activeCategory) {
                $query->where('category_id', $activeCategory);
            })
            ->orderByDesc('published_at')
            ->orderByDesc('created_at');

        $allCount = audio::query()
            ->where('is_published', true)
            ->count();

        $audios = $audiosQuery
            ->paginate(9)
            ->withQueryString();

        return view('audios.index', compact('audios', 'categories', 'activeCategory', 'allCount', 'search'));
    }

    public function audioShow(audio $audio)
    {
        if (!$audio->is_published) {
            abort(404);
        }

        $audio->load('category');
        $audio->load('translations');
        $audio->category?->load('translations');
        $audio->loadCount(['likes', 'comments' => function ($query) {
            $query->where('is_approved', true);
        }]);
        $audio->load(['comments' => function ($query) {
            $query->where('is_approved', true)
                ->orderByDesc('created_at')
                ->limit(5);
        }]);

        $relatedAudios = audio::query()
            ->with(['category.translations', 'translations'])
            ->where('is_published', true)
            ->where('id', '!=', $audio->id)
            ->when($audio->category_id, function ($query) use ($audio) {
                $query->where('category_id', $audio->category_id);
            })
            ->orderByDesc('featured')
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('audios.show', compact('audio', 'relatedAudios'));
    }

    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
        ]);

        $subscriber = Subscriber::updateOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['name'] ?? null,
                'is_active' => true,
                'subscribed_at' => now(),
            ]
        );

        UserActivityLog::create([
            'actor_user_id' => null,
            'action' => 'subscriber_created',
            'meta' => [
                'email' => $subscriber->email,
                'name' => $subscriber->name,
                'ip' => $request->ip(),
            ],
        ]);

        return redirect()->back()->with('status', __('messages.home.subscribe_thanks'));
    }
}
