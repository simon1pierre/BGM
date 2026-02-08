@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Edit Video</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.videos.index') }}">Videos</a></li>
                    <li class="breadcrumb-item">Edit</li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            @php
                $translations = [
                    'en' => $video->translationFor('en'),
                    'fr' => $video->translationFor('fr'),
                    'rw' => $video->translationFor('rw'),
                ];
            @endphp
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.videos.update', $video) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Title</label>
                                <input type="text" name="title" value="{{ old('title', $video->title) }}" class="form-control" required>
                                @error('title') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Publish Date</label>
                                <input type="date" name="published_at" value="{{ old('published_at', optional($video->published_at)->toDateString()) }}" class="form-control">
                                @error('published_at') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">YouTube URL</label>
                                <input type="url" name="youtube_url" value="{{ old('youtube_url', $video->youtube_url) }}" class="form-control" required>
                                @error('youtube_url') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Category</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">Select category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id', $video->category_id) == $category->id)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Speaker</label>
                                <input type="text" name="speaker" value="{{ old('speaker', $video->speaker) }}" class="form-control">
                                @error('speaker') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Series</label>
                                <input type="text" name="series" value="{{ old('series', $video->series) }}" class="form-control">
                                @error('series') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="form-control" rows="4">{{ old('description', $video->description) }}</textarea>
                                @error('description') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-12">
                                <div class="card border border-dashed">
                                    <div class="card-body">
                                        <div class="fw-semibold mb-3">Translations (EN / FR / RW)</div>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Title (EN)</label>
                                                <input type="text" name="title_en" value="{{ old('title_en', $translations['en']?->title) }}" class="form-control" required>
                                                @error('title_en') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                                                <label class="form-label fw-semibold mt-3">Description (EN)</label>
                                                <textarea name="description_en" class="form-control" rows="3">{{ old('description_en', $translations['en']?->description) }}</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Title (FR)</label>
                                                <input type="text" name="title_fr" value="{{ old('title_fr', $translations['fr']?->title) }}" class="form-control" required>
                                                @error('title_fr') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                                                <label class="form-label fw-semibold mt-3">Description (FR)</label>
                                                <textarea name="description_fr" class="form-control" rows="3">{{ old('description_fr', $translations['fr']?->description) }}</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Title (RW)</label>
                                                <input type="text" name="title_rw" value="{{ old('title_rw', $translations['rw']?->title) }}" class="form-control" required>
                                                @error('title_rw') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                                                <label class="form-label fw-semibold mt-3">Description (RW)</label>
                                                <textarea name="description_rw" class="form-control" rows="3">{{ old('description_rw', $translations['rw']?->description) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Thumbnail</label>
                                <input type="file" name="thumbnail" class="form-control" accept="image/*">
                                @if ($video->thumbnail_url)
                                    <div class="mt-2">
                                        <img src="{{ $video->thumbnail_url }}" alt="thumbnail" class="rounded" style="max-width: 220px; width: 100%;">
                                    </div>
                                @endif
                                @error('thumbnail') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 d-flex align-items-center gap-4">
                                <div>
                                    <input type="hidden" name="is_published" value="0">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" name="is_published" value="1" @checked(old('is_published', $video->is_published))>
                                        <label class="form-check-label">Published</label>
                                    </div>
                                </div>
                                <div>
                                    <input type="hidden" name="featured" value="0">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" name="featured" value="1" @checked(old('featured', $video->featured))>
                                        <label class="form-check-label">Featured</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card border border-dashed">
                                    <div class="card-body">
                                        <div class="form-check mb-3">
                                            <input type="hidden" name="notify_subscribers" value="0">
                                            <input class="form-check-input" type="checkbox" name="notify_subscribers" value="1" id="notifySubscribersVideoEdit">
                                            <label class="form-check-label fw-semibold" for="notifySubscribersVideoEdit">Notify subscribers about this video update</label>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Notify Target</label>
                                                <select name="notify_target" class="form-select">
                                                    <option value="all">All active subscribers</option>
                                                    <option value="custom">Custom email list</option>
                                                </select>
                                            </div>
                                            <div class="col-md-8">
                                                <label class="form-label fw-semibold">Custom Emails (comma separated)</label>
                                                <input type="text" name="notify_emails" class="form-control" placeholder="user1@example.com, user2@example.com">
                                                @error('notify_emails') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Extra Message</label>
                                                <textarea name="notify_message" class="form-control" rows="3" placeholder="Add a short encouraging note...">{{ old('notify_message') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card border border-dashed">
                                    <div class="card-body">
                                        <div class="fw-semibold mb-2">Playlist (Videos)</div>
                                        <div class="row g-3">
                                            <div class="col-md-8">
                                                <label class="form-label fw-semibold">Add to Existing Playlists</label>
                                                <div class="border rounded p-2" style="max-height: 200px; overflow-y: auto;">
                                                    @forelse ($playlists as $playlist)
                                                        <label class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="playlist_ids[]" value="{{ $playlist->id }}"
                                                                @checked(in_array($playlist->id, $selectedPlaylists ?? []))>
                                                            <span class="form-check-label">{{ $playlist->title }}</span>
                                                        </label>
                                                    @empty
                                                        <div class="text-muted fs-12">No video playlists yet.</div>
                                                    @endforelse
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Or Create New Playlist</label>
                                                <input type="text" name="new_playlist_title" class="form-control" placeholder="New playlist title">
                                                @error('new_playlist_title') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button class="btn btn-primary">Update Video</button>
                            <a href="{{ route('admin.videos.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
