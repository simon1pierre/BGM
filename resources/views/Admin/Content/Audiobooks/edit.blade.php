@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Edit Audiobook</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.audiobooks.index') }}">Audiobooks</a></li>
                    <li class="breadcrumb-item">Edit</li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            @php
                $translations = [
                    'en' => $audiobook->translationFor('en'),
                    'fr' => $audiobook->translationFor('fr'),
                    'rw' => $audiobook->translationFor('rw'),
                ];
            @endphp
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.audiobooks.update', $audiobook) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Title</label>
                                <input type="text" name="title" value="{{ old('title', $audiobook->title) }}" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Publish Date</label>
                                <input type="date" name="published_at" value="{{ old('published_at', optional($audiobook->published_at)->toDateString()) }}" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Audiobook File</label>
                                <input type="file" name="audio_file" class="form-control" accept="audio/*">
                                @if ($audiobook->audio_file)
                                    <div class="mt-2">
                                        <audio controls class="w-100">
                                            <source src="{{ asset('storage/'.$audiobook->audio_file) }}" type="audio/mpeg">
                                        </audio>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Thumbnail (optional)</label>
                                <input type="file" name="thumbnail" class="form-control" accept="image/*">
                                @if ($audiobook->thumbnail)
                                    <div class="mt-2 text-muted fs-12">Current: {{ $audiobook->thumbnail }}</div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Category (optional)</label>
                                <select name="category_id" class="form-select">
                                    <option value="">None</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id', $audiobook->category_id) == $category->id)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Linked Book (optional)</label>
                                <select name="book_id" class="form-select">
                                    <option value="">None</option>
                                    @foreach ($books as $book)
                                        <option value="{{ $book->id }}" @selected(old('book_id', $audiobook->book_id) == $book->id)>{{ $book->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Narrator</label>
                                <input type="text" name="narrator" value="{{ old('narrator', $audiobook->narrator) }}" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Series</label>
                                <input type="text" name="series" value="{{ old('series', $audiobook->series) }}" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Duration</label>
                                <input type="text" name="duration" value="{{ old('duration', $audiobook->duration) }}" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="form-control" rows="4">{{ old('description', $audiobook->description) }}</textarea>
                            </div>
                            <div class="col-md-12">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Title (EN)</label>
                                        <input type="text" name="title_en" value="{{ old('title_en', $translations['en']?->title) }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Title (FR)</label>
                                        <input type="text" name="title_fr" value="{{ old('title_fr', $translations['fr']?->title) }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Title (RW)</label>
                                        <input type="text" name="title_rw" value="{{ old('title_rw', $translations['rw']?->title) }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Description (EN)</label>
                                        <textarea name="description_en" class="form-control" rows="3">{{ old('description_en', $translations['en']?->description) }}</textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Description (FR)</label>
                                        <textarea name="description_fr" class="form-control" rows="3">{{ old('description_fr', $translations['fr']?->description) }}</textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Description (RW)</label>
                                        <textarea name="description_rw" class="form-control" rows="3">{{ old('description_rw', $translations['rw']?->description) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-center gap-4">
                                <div>
                                    <input type="hidden" name="is_published" value="0">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" name="is_published" value="1" @checked(old('is_published', $audiobook->is_published))>
                                        <label class="form-check-label">Published</label>
                                    </div>
                                </div>
                                <div>
                                    <input type="hidden" name="featured" value="0">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" name="featured" value="1" @checked(old('featured', $audiobook->featured))>
                                        <label class="form-check-label">Featured</label>
                                    </div>
                                </div>
                                <div>
                                    <input type="hidden" name="recommended" value="0">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" name="recommended" value="1" @checked(old('recommended', $audiobook->recommended))>
                                        <label class="form-check-label">Recommended</label>
                                    </div>
                                </div>
                                <div>
                                    <input type="hidden" name="is_prayer_audio" value="0">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" name="is_prayer_audio" value="1" @checked(old('is_prayer_audio', $audiobook->is_prayer_audio))>
                                        <label class="form-check-label">Prayer Audio</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button class="btn btn-primary">Update Audiobook</button>
                            <a href="{{ route('admin.audiobooks.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
