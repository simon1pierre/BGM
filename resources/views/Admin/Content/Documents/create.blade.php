@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Add Document</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Books</a></li>
                    <li class="breadcrumb-item">Create</li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Title</label>
                                <input type="text" name="title" value="{{ old('title') }}" class="form-control" required>
                                @error('title') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Publish Date</label>
                                <input type="date" name="published_at" value="{{ old('published_at') }}" class="form-control">
                                @error('published_at') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">PDF File</label>
                                <input type="file" name="document_file" class="form-control" accept="application/pdf" required>
                                @error('document_file') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Category</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">Select category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Author</label>
                                <input type="text" name="author" value="{{ old('author') }}" class="form-control">
                                @error('author') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Series</label>
                                <input type="text" name="series" value="{{ old('series') }}" class="form-control" placeholder="e.g. Foundations of Faith">
                                @error('series') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                                @error('description') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-12">
                                <div class="card border border-dashed">
                                    <div class="card-body">
                                        <div class="form-check mb-3">
                                            <input type="hidden" name="create_audiobook" value="0">
                                            <input class="form-check-input" type="checkbox" name="create_audiobook" value="1" id="createAudiobookOnBook" @checked(old('create_audiobook'))>
                                            <label class="form-check-label fw-semibold" for="createAudiobookOnBook">Create audiobook parts now (optional)</label>
                                        </div>
                                        <p class="text-muted fs-12 mb-3">You can skip this section and upload audiobook parts later from the book edit page.</p>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Audiobook Parts Title</label>
                                                <input type="text" name="audiobook_title" value="{{ old('audiobook_title') }}" class="form-control" placeholder="Enter audiobook parts title">
                                                @error('audiobook_title') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Audiobook Parts (many files)</label>
                                                <input type="file" name="audiobook_part_files[]" class="form-control" accept="audio/*" multiple>
                                                @error('audiobook_part_files') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                                                @error('audiobook_part_files.*') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="col-md-12 d-flex align-items-center gap-4">
                                                <div>
                                                    <input type="hidden" name="audiobook_is_published" value="0">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="audiobook_is_published" value="1" @checked(old('audiobook_is_published', true))>
                                                        <label class="form-check-label">Audiobook Published</label>
                                                    </div>
                                                </div>
                                                <div>
                                                    <input type="hidden" name="audiobook_is_prayer_audio" value="0">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="audiobook_is_prayer_audio" value="1" @checked(old('audiobook_is_prayer_audio'))>
                                                        <label class="form-check-label">Prayer Audio</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card border border-dashed">
                                    <div class="card-body">
                                        <div class="fw-semibold mb-3">Translations (EN / FR / RW)</div>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Title (EN)</label>
                                                <input type="text" name="title_en" value="{{ old('title_en') }}" class="form-control" required>
                                                @error('title_en') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                                                <label class="form-label fw-semibold mt-3">Description (EN)</label>
                                                <textarea name="description_en" class="form-control" rows="3">{{ old('description_en') }}</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Title (FR)</label>
                                                <input type="text" name="title_fr" value="{{ old('title_fr') }}" class="form-control" required>
                                                @error('title_fr') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                                                <label class="form-label fw-semibold mt-3">Description (FR)</label>
                                                <textarea name="description_fr" class="form-control" rows="3">{{ old('description_fr') }}</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Title (RW)</label>
                                                <input type="text" name="title_rw" value="{{ old('title_rw') }}" class="form-control" required>
                                                @error('title_rw') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                                                <label class="form-label fw-semibold mt-3">Description (RW)</label>
                                                <textarea name="description_rw" class="form-control" rows="3">{{ old('description_rw') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Cover Image</label>
                                <input type="file" name="cover_image" class="form-control" accept="image/*">
                                @error('cover_image') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 d-flex align-items-center gap-4">
                                <div>
                                    <input type="hidden" name="is_published" value="0">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" name="is_published" value="1" checked>
                                        <label class="form-check-label">Published</label>
                                    </div>
                                </div>
                                <div>
                                    <input type="hidden" name="featured" value="0">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" name="featured" value="1">
                                        <label class="form-check-label">Featured</label>
                                    </div>
                                </div>
                                <div>
                                    <input type="hidden" name="recommended" value="0">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" name="recommended" value="1">
                                        <label class="form-check-label">Recommended</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card border border-dashed">
                                    <div class="card-body">
                                        <div class="form-check mb-3">
                                            <input type="hidden" name="notify_subscribers" value="0">
                                            <input class="form-check-input" type="checkbox" name="notify_subscribers" value="1" id="notifySubscribersDocument">
                                            <label class="form-check-label fw-semibold" for="notifySubscribersDocument">Notify subscribers about this document</label>
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
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button class="btn btn-primary">Save Document</button>
                            <a href="{{ route('admin.documents.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


