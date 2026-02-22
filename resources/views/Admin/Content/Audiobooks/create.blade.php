@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Add Audiobook</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.audiobooks.index') }}">Audiobooks</a></li>
                    <li class="breadcrumb-item">Create</li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.audiobooks.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Title</label>
                                <input type="text" name="title" value="{{ old('title') }}" class="form-control" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Publish Date</label>
                                <input type="date" name="published_at" value="{{ old('published_at') }}" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Primary Audiobook File (optional)</label>
                                <input type="file" name="audio_file" class="form-control" accept="audio/*">
                                <div class="fs-12 text-muted mt-1">You can skip this if you upload audiobook parts below.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Thumbnail (optional)</label>
                                <input type="file" name="thumbnail" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Category (optional)</label>
                                <select name="category_id" class="form-select">
                                    <option value="">None</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Linked Book</label>
                                <select name="book_id" class="form-select" required>
                                    @foreach ($books as $book)
                                        <option value="{{ $book->id }}" @selected(old('book_id') == $book->id)>{{ $book->title }}</option>
                                    @endforeach
                                </select>
                                <div class="fs-12 text-muted mt-1">Each audiobook must belong to one book.</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Narrator</label>
                                <input type="text" name="narrator" value="{{ old('narrator') }}" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Series</label>
                                <input type="text" name="series" value="{{ old('series') }}" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Duration</label>
                                <input type="text" name="duration" value="{{ old('duration') }}" class="form-control" placeholder="e.g. 45:30">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                            </div>
                            <div class="col-md-12">
                                <div class="border rounded-3 p-3 bg-light">
                                    <h6 class="mb-3">Multi-Part Audio (Optional)</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label fw-semibold">Upload Many Parts</label>
                                            <input type="file" name="part_files[]" class="form-control" accept="audio/*" multiple>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Part Title Prefix</label>
                                            <input type="text" name="part_title_prefix" value="{{ old('part_title_prefix', 'Part') }}" class="form-control" placeholder="Part">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Start Number</label>
                                            <input type="number" name="part_order_start" value="{{ old('part_order_start', 1) }}" min="1" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-semibold">Duration (optional)</label>
                                            <input type="text" name="part_duration" value="{{ old('part_duration') }}" class="form-control" placeholder="e.g. 12:30">
                                        </div>
                                    </div>
                                    <div class="fs-12 text-muted mt-2">
                                        Example: "Part" + start 1 creates Part 1, Part 2, ... for all uploaded files.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Title (EN)</label>
                                        <input type="text" name="title_en" value="{{ old('title_en') }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Title (FR)</label>
                                        <input type="text" name="title_fr" value="{{ old('title_fr') }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Title (RW)</label>
                                        <input type="text" name="title_rw" value="{{ old('title_rw') }}" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Description (EN)</label>
                                        <textarea name="description_en" class="form-control" rows="3">{{ old('description_en') }}</textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Description (FR)</label>
                                        <textarea name="description_fr" class="form-control" rows="3">{{ old('description_fr') }}</textarea>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Description (RW)</label>
                                        <textarea name="description_rw" class="form-control" rows="3">{{ old('description_rw') }}</textarea>
                                    </div>
                                </div>
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
                                <div>
                                    <input type="hidden" name="is_prayer_audio" value="0">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" name="is_prayer_audio" value="1" @checked(old('is_prayer_audio'))>
                                        <label class="form-check-label">Prayer Audio</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button class="btn btn-primary">Save Audiobook</button>
                            <a href="{{ route('admin.audiobooks.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
