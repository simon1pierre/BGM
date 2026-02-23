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
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Title</label>
                                <input type="text" name="title" value="{{ old('title') }}" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Primary Audiobook File (optional)</label>
                                <input type="file" name="audio_file" class="form-control" accept="audio/*">
                                <div class="fs-12 text-muted mt-1">You can skip this if you upload audiobook parts below.</div>
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
                            <div class="col-md-12">
                                <div class="border rounded-3 p-3 bg-light">
                                    <h6 class="mb-2">Multi-Part Audio (Optional)</h6>
                                    <p class="text-muted fs-12 mb-3">Upload by language. Part titles are automatically taken from each file name.</p>
                                    <ul class="nav nav-pills mb-3" role="tablist">
                                        <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#createPartLangRw" type="button">Kinyarwanda</button></li>
                                        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#createPartLangEn" type="button">English</button></li>
                                        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#createPartLangFr" type="button">French</button></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="createPartLangRw">
                                            <label class="form-label fw-semibold">Kinyarwanda Parts</label>
                                            <input type="file" name="part_files_rw[]" class="form-control" accept="audio/*" multiple data-upload-monitor data-upload-max-files="300" data-upload-warn-mb="1500" data-upload-summary-target="#createPartsSummaryRw">
                                            <div id="createPartsSummaryRw" class="fs-12 text-muted mt-1">No files selected.</div>
                                        </div>
                                        <div class="tab-pane fade" id="createPartLangEn">
                                            <label class="form-label fw-semibold">English Parts</label>
                                            <input type="file" name="part_files_en[]" class="form-control" accept="audio/*" multiple data-upload-monitor data-upload-max-files="300" data-upload-warn-mb="1500" data-upload-summary-target="#createPartsSummaryEn">
                                            <div id="createPartsSummaryEn" class="fs-12 text-muted mt-1">No files selected.</div>
                                        </div>
                                        <div class="tab-pane fade" id="createPartLangFr">
                                            <label class="form-label fw-semibold">French Parts</label>
                                            <input type="file" name="part_files_fr[]" class="form-control" accept="audio/*" multiple data-upload-monitor data-upload-max-files="300" data-upload-warn-mb="1500" data-upload-summary-target="#createPartsSummaryFr">
                                            <div id="createPartsSummaryFr" class="fs-12 text-muted mt-1">No files selected.</div>
                                        </div>
                                    </div>
                                    <div class="row g-3 mt-1">
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Start Number</label>
                                            <input type="number" name="part_order_start" value="{{ old('part_order_start', 1) }}" min="1" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label fw-semibold">Duration (optional)</label>
                                            <input type="text" name="part_duration" value="{{ old('part_duration') }}" class="form-control" placeholder="e.g. 12:30">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-light btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#audiobookCreateAdvanced" aria-expanded="false" aria-controls="audiobookCreateAdvanced">
                                    Advanced Options
                                </button>
                            </div>
                            <div class="col-md-12 collapse" id="audiobookCreateAdvanced">
                                <div class="card border border-dashed">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Publish Date</label>
                                                <input type="date" name="published_at" value="{{ old('published_at') }}" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Category (optional)</label>
                                                <select name="category_id" class="form-select">
                                                    <option value="">None</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Thumbnail (optional)</label>
                                                <input type="file" name="thumbnail" class="form-control" accept="image/*">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Narrator</label>
                                                <input type="text" name="narrator" value="{{ old('narrator') }}" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Series</label>
                                                <input type="text" name="series" value="{{ old('series') }}" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Duration</label>
                                                <input type="text" name="duration" value="{{ old('duration') }}" class="form-control" placeholder="e.g. 45:30">
                                            </div>
                                            <div class="col-md-12">
                                                <label class="form-label fw-semibold">Description</label>
                                                <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                                            </div>
                                        </div>
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
