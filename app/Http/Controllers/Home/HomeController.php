<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\ContentCategory;
use App\Models\ContactMessage;
use App\Models\Event;
use App\Models\Setting;
use App\Models\Subscriber;
use App\Models\UserActivityLog;
use App\Models\video;
use App\Models\book;
use App\Models\audio;
use App\Models\audiobook;
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

        $featuredAudiobooks = audiobook::query()
            ->with(['category.translations', 'translations', 'linkedBook.translations'])
            ->where('is_published', true)
            ->where('featured', true)
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->limit(4)
            ->get();

        $upcomingEvents = Event::query()
            ->where('is_published', true)
            ->where(function ($query) {
                $query->where('ends_at', '>=', now())
                    ->orWhere(function ($sub) {
                        $sub->whereNull('ends_at')
                            ->where('starts_at', '>=', now());
                    });
            })
            ->orderByDesc('is_featured')
            ->orderBy('starts_at')
            ->limit(3)
            ->get();

        return view('home', compact('latestVideos', 'featuredVideo', 'featuredBook', 'featuredAudio', 'recommendedBooks', 'recommendedAudios', 'featuredAudiobooks', 'upcomingEvents'));
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

    public function bookShow(Request $request, book $book)
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
        $prayerFilter = $this->resolvePrayerFilter($request->query('prayer'));
        $hasLinkedAudiobooks = audiobook::query()
            ->where('is_published', true)
            ->where('book_id', $book->id)
            ->exists();

        $linkedAudiobooks = audiobook::query()
            ->where('is_published', true)
            ->where('book_id', $book->id)
            ->when(!is_null($prayerFilter), function ($query) use ($prayerFilter) {
                $query->where('is_prayer_audio', $prayerFilter);
            })
            ->orderByDesc('is_prayer_audio')
            ->orderByDesc('featured')
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('books.show', compact('book', 'relatedBooks', 'linkedAudiobooks', 'prayerFilter', 'hasLinkedAudiobooks'));
    }

    public function bookReader(book $book)
    {
        if (!$book->is_published) {
            abort(404);
        }

        $book->load('category');
        $book->load('translations');
        $book->category?->load('translations');

        $linkedAudiobooks = audiobook::query()
            ->where('is_published', true)
            ->where('book_id', $book->id)
            ->orderByDesc('is_prayer_audio')
            ->orderByDesc('featured')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->get();

        return view('books.reader', compact('book', 'linkedAudiobooks'));
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

    public function audiobooks(Request $request)
    {
        $categories = ContentCategory::query()
            ->whereIn('type', ['audio', 'all'])
            ->where('is_active', true)
            ->with('translations')
            ->withCount(['audiobooks as audiobooks_count' => function ($query) {
                $query->where('is_published', true);
            }])
            ->orderBy('name')
            ->get();

        $activeCategory = $request->query('category');
        $search = trim((string) $request->query('q'));
        $prayerFilter = $this->resolvePrayerFilter($request->query('prayer'));
        $locale = app()->getLocale();

        $audiobooksQuery = audiobook::query()
            ->with(['category.translations', 'translations', 'linkedBook.translations'])
            ->where('is_published', true)
            ->when($search, function ($query) use ($search, $locale) {
                $query->where(function ($inner) use ($search, $locale) {
                    $inner->where('title', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%')
                        ->orWhere('narrator', 'like', '%'.$search.'%')
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
            ->when(!is_null($prayerFilter), function ($query) use ($prayerFilter) {
                $query->where('is_prayer_audio', $prayerFilter);
            })
            ->when($activeCategory, function ($query) use ($activeCategory) {
                $query->where('category_id', $activeCategory);
            })
            ->orderByDesc('featured')
            ->orderByDesc('published_at')
            ->orderByDesc('created_at');

        $allCount = audiobook::query()
            ->where('is_published', true)
            ->count();

        $audiobooks = $audiobooksQuery
            ->paginate(9)
            ->withQueryString();

        return view('audiobooks.index', compact('audiobooks', 'categories', 'activeCategory', 'allCount', 'search', 'prayerFilter'));
    }

    public function audiobookShow(audiobook $audiobook)
    {
        if (!$audiobook->is_published) {
            abort(404);
        }

        $audiobook->load(['category.translations', 'translations', 'linkedBook.translations']);

        $relatedAudiobooks = audiobook::query()
            ->with(['category.translations', 'translations', 'linkedBook.translations'])
            ->where('is_published', true)
            ->where('id', '!=', $audiobook->id)
            ->when($audiobook->category_id, function ($query) use ($audiobook) {
                $query->where('category_id', $audiobook->category_id);
            })
            ->orderByDesc('featured')
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('audiobooks.show', compact('audiobook', 'relatedAudiobooks'));
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

    public function about()
    {
        $stats = [
            'videos' => video::query()->where('is_published', true)->count(),
            'audios' => audio::query()->where('is_published', true)->count(),
            'books' => book::query()->where('is_published', true)->count(),
            'subscribers' => Subscriber::query()->where('is_active', true)->count(),
        ];

        return view('pages.about', compact('stats'));
    }

    public function resources()
    {
        $settings = Setting::currentOrDefault();
        $limit = max(3, (int) ($settings->home_featured_video_limit ?? 3));

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

        $videos = video::query()
            ->with(['category.translations', 'translations'])
            ->where('is_published', true)
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();

        $books = book::query()
            ->with(['category.translations', 'translations'])
            ->where('is_published', true)
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        $audios = audio::query()
            ->with(['category.translations', 'translations'])
            ->where('is_published', true)
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        return view('pages.resources', compact('featuredVideo', 'featuredBook', 'featuredAudio', 'videos', 'books', 'audios'));
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        ContactMessage::create([
            'name' => $validated['name'] ?? null,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'] ?? null,
            'message' => $validated['message'],
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
            'locale' => app()->getLocale(),
            'page_url' => $request->headers->get('referer'),
        ]);

        return redirect()->route('contact')->with('status', __('messages.contact.thanks'));
    }

    public function events()
    {
        $search = trim((string) request('q'));
        $type = request('type');
        $platform = request('platform');
        $period = request('period', 'upcoming');

        $query = Event::query()
            ->where('is_published', true)
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('title', 'like', '%'.$search.'%')
                        ->orWhere('description', 'like', '%'.$search.'%')
                        ->orWhere('venue', 'like', '%'.$search.'%')
                        ->orWhere('location', 'like', '%'.$search.'%');
                });
            })
            ->when($type, function ($q) use ($type) {
                $q->where('event_type', $type);
            })
            ->when($platform, function ($q) use ($platform) {
                if ($platform === 'none') {
                    $q->whereNull('live_platform');
                    return;
                }
                $q->where('live_platform', $platform);
            });

        if ($period === 'past') {
            $query->where(function ($q) {
                $q->where('ends_at', '<', now())
                    ->orWhere(function ($sub) {
                        $sub->whereNull('ends_at')
                            ->where('starts_at', '<', now());
                    });
            })->orderByDesc('starts_at');
        } else {
            $query->where(function ($q) {
                $q->where('ends_at', '>=', now())
                    ->orWhere(function ($sub) {
                        $sub->whereNull('ends_at')
                            ->where('starts_at', '>=', now());
                    });
            })->orderBy('starts_at');
        }

        $upcomingEvents = $query
            ->orderByDesc('is_featured')
            ->paginate(9)
            ->withQueryString();

        return view('pages.events', compact('upcomingEvents'));
    }

    public function eventShow(Event $event)
    {
        if (!$event->is_published) {
            abort(404);
        }

        $relatedEvents = Event::query()
            ->where('is_published', true)
            ->where('id', '!=', $event->id)
            ->when($event->event_type, function ($query) use ($event) {
                $query->where('event_type', $event->event_type);
            })
            ->orderByDesc('is_featured')
            ->orderBy('starts_at')
            ->limit(3)
            ->get();

        return view('pages.events-show', compact('event', 'relatedEvents'));
    }

    public function give()
    {
        return view('pages.give');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function terms()
    {
        return view('pages.terms');
    }

    private function resolvePrayerFilter($rawValue): ?bool
    {
        if ($rawValue === '1') {
            return true;
        }

        if ($rawValue === '0') {
            return false;
        }

        return null;
    }
}
