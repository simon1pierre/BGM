@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Edit Document</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Documents</a></li>
                    <li class="breadcrumb-item">Edit</li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            @php
                $translations = [
                    'en' => $document->translationFor('en'),
                    'fr' => $document->translationFor('fr'),
                    'rw' => $document->translationFor('rw'),
                ];
            @endphp
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.documents.update', $document) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Title</label>
                                <input type="text" name="title" value="{{ old('title', $document->title) }}" class="form-control" required>
                                @error('title') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Publish Date</label>
                                <input type="date" name="published_at" value="{{ old('published_at', optional($document->published_at)->toDateString()) }}" class="form-control">
                                @error('published_at') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">PDF File</label>
                                <input type="file" name="document_file" class="form-control" accept="application/pdf">
                                @if ($document->file_path)
                                    <div class="fs-12 text-muted mt-2">Current: {{ $document->file_path }}</div>
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/'.$document->file_path) }}" target="_blank" class="btn btn-sm btn-light">
                                            Preview PDF
                                        </a>
                                    </div>
                                @endif
                                @error('document_file') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Category</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">Select category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected(old('category_id', $document->category_id) == $category->id)>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Author</label>
                                <input type="text" name="author" value="{{ old('author', $document->author) }}" class="form-control">
                                @error('author') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Series</label>
                                <input type="text" name="series" value="{{ old('series', $document->series) }}" class="form-control" placeholder="e.g. Foundations of Faith">
                                @error('series') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="form-control" rows="4">{{ old('description', $document->description) }}</textarea>
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
                                <label class="form-label fw-semibold">Cover Image</label>
                                <input type="file" name="cover_image" class="form-control" accept="image/*">
                                @if ($document->cover_image)
                                    <div class="fs-12 text-muted mt-2">Current: {{ $document->cover_image }}</div>
                                @endif
                                @error('cover_image') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 d-flex align-items-center gap-4">
                                <div>
                                    <input type="hidden" name="is_published" value="0">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" name="is_published" value="1" @checked(old('is_published', $document->is_published))>
                                        <label class="form-check-label">Published</label>
                                    </div>
                                </div>
                                <div>
                                    <input type="hidden" name="featured" value="0">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" name="featured" value="1" @checked(old('featured', $document->featured))>
                                        <label class="form-check-label">Featured</label>
                                    </div>
                                </div>
                                <div>
                                    <input type="hidden" name="recommended" value="0">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" name="recommended" value="1" @checked(old('recommended', $document->recommended))>
                                        <label class="form-check-label">Recommended</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card border border-dashed">
                                    <div class="card-body">
                                        <div class="form-check mb-3">
                                            <input type="hidden" name="notify_subscribers" value="0">
                                            <input class="form-check-input" type="checkbox" name="notify_subscribers" value="1" id="notifySubscribersDocumentEdit">
                                            <label class="form-check-label fw-semibold" for="notifySubscribersDocumentEdit">Notify subscribers about this document update</label>
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
                            <button class="btn btn-primary">Update Document</button>
                            <a href="{{ route('admin.documents.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>

                    <hr class="my-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="mb-0">Audiobooks For This Book</h6>
                        <span class="badge bg-light text-dark">{{ $linkedAudiobooks->count() }} audiobook(s)</span>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Parts</th>
                                    <th>Status</th>
                                    <th>Published</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($linkedAudiobooks as $linkedAudiobook)
                                    <tr>
                                        <td>{{ $linkedAudiobook->title }}</td>
                                        <td>{{ $linkedAudiobook->published_parts_count }}</td>
                                        <td>
                                            @if ($linkedAudiobook->is_prayer_audio)
                                                <span class="badge bg-soft-info text-info">Prayer</span>
                                            @else
                                                <span class="badge bg-soft-secondary text-muted">Normal</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($linkedAudiobook->is_published)
                                                <span class="badge bg-soft-success text-success">Published</span>
                                            @else
                                                <span class="badge bg-soft-warning text-warning">Draft</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.audiobooks.edit', $linkedAudiobook) }}" class="btn btn-sm btn-primary">Manage Parts</a>
                                            <a href="{{ route('admin.audiobooks.preview', $linkedAudiobook) }}" class="btn btn-sm btn-light">Preview</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-muted text-center">No audiobooks assigned to this book yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="border rounded-3 p-3 bg-light mb-4">
                        <h6 class="mb-2">Upload Audiobook Parts (Simple)</h6>
                        <p class="text-muted fs-12 mb-3">Upload files directly. If no audiobook is selected, the system creates one automatically for this book.</p>
                        <form method="POST" action="{{ route('admin.documents.audiobook-parts.store', $document) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Target Audiobook (optional)</label>
                                    <select name="audiobook_id" class="form-select">
                                        <option value="">Auto create for this book</option>
                                        @foreach ($linkedAudiobooks as $linkedAudiobook)
                                            <option value="{{ $linkedAudiobook->id }}">{{ $linkedAudiobook->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">New Audiobook Title (optional)</label>
                                    <input type="text" name="title" class="form-control" placeholder="Leave empty to auto-generate">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold d-block">Upload Many Parts</label>
                                    <ul class="nav nav-pills mb-2" role="tablist">
                                        <li class="nav-item"><button class="nav-link active px-2 py-1" data-bs-toggle="pill" data-bs-target="#quickLangRw" type="button">RW</button></li>
                                        <li class="nav-item"><button class="nav-link px-2 py-1" data-bs-toggle="pill" data-bs-target="#quickLangEn" type="button">EN</button></li>
                                        <li class="nav-item"><button class="nav-link px-2 py-1" data-bs-toggle="pill" data-bs-target="#quickLangFr" type="button">FR</button></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="quickLangRw">
                                            <input type="file" name="part_files_rw[]" class="form-control" accept="audio/*" multiple data-upload-monitor data-upload-max-files="300" data-upload-warn-mb="1500" data-upload-summary-target="#quickPartsSummaryRw">
                                            <div id="quickPartsSummaryRw" class="fs-12 text-muted mt-1">No files selected.</div>
                                        </div>
                                        <div class="tab-pane fade" id="quickLangEn">
                                            <input type="file" name="part_files_en[]" class="form-control" accept="audio/*" multiple data-upload-monitor data-upload-max-files="300" data-upload-warn-mb="1500" data-upload-summary-target="#quickPartsSummaryEn">
                                            <div id="quickPartsSummaryEn" class="fs-12 text-muted mt-1">No files selected.</div>
                                        </div>
                                        <div class="tab-pane fade" id="quickLangFr">
                                            <input type="file" name="part_files_fr[]" class="form-control" accept="audio/*" multiple data-upload-monitor data-upload-max-files="300" data-upload-warn-mb="1500" data-upload-summary-target="#quickPartsSummaryFr">
                                            <div id="quickPartsSummaryFr" class="fs-12 text-muted mt-1">No files selected.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Or Upload One Part</label>
                                    <input type="file" name="single_part_file" class="form-control" accept="audio/*" data-upload-monitor data-upload-max-files="1" data-upload-warn-mb="256" data-upload-summary-target="#quickSinglePartSummary">
                                    <div id="quickSinglePartSummary" class="fs-12 text-muted mt-1">No file selected.</div>
                                    <select name="single_part_language" class="form-select mt-2">
                                        <option value="rw">Kinyarwanda</option>
                                        <option value="en">English</option>
                                        <option value="fr">French</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Start</label>
                                    <input type="number" name="part_order_start" min="1" class="form-control" value="1">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Duration</label>
                                    <input type="text" name="part_duration" class="form-control" placeholder="e.g. 11:48">
                                </div>
                                <div class="col-md-12">
                                    <button class="btn btn-light btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#quickPartsAdvanced" aria-expanded="false" aria-controls="quickPartsAdvanced">
                                        Advanced Options (new audiobook only)
                                    </button>
                                </div>
                                <div class="col-md-12 collapse" id="quickPartsAdvanced">
                                    <div class="card border border-dashed">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Narrator (new only)</label>
                                                    <input type="text" name="narrator" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Category (new only)</label>
                                                    <select name="category_id" class="form-select">
                                                        <option value="">None</option>
                                                        @foreach ($audioCategories as $audioCategory)
                                                            <option value="{{ $audioCategory->id }}">{{ $audioCategory->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <div class="form-check">
                                        <input type="hidden" name="is_published" value="0">
                                        <input class="form-check-input" type="checkbox" id="quickBookPartPublished" name="is_published" value="1" checked>
                                        <label class="form-check-label" for="quickBookPartPublished">Published</label>
                                    </div>
                                </div>
                                <div class="col-md-12 d-flex align-items-end">
                                    <button class="btn btn-primary w-100">Upload Parts For This Book</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="border rounded-3 p-3 bg-light d-none">
                        <h6 class="mb-2">Add Audiobook To This Book</h6>
                        <p class="text-muted fs-12 mb-3">This upload is automatically assigned to: <strong>{{ $document->title }}</strong>.</p>
                        <form method="POST" action="{{ route('admin.documents.audiobooks.store', $document) }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Audiobook Title</label>
                                    <input type="text" name="title" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Narrator</label>
                                    <input type="text" name="narrator" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Category</label>
                                    <select name="category_id" class="form-select">
                                        <option value="">None</option>
                                        @foreach ($audioCategories as $audioCategory)
                                            <option value="{{ $audioCategory->id }}">{{ $audioCategory->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-semibold">Description</label>
                                    <textarea name="description" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Primary Audio (optional)</label>
                                    <input type="file" name="audio_file" class="form-control" accept="audio/*">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Thumbnail (optional)</label>
                                    <input type="file" name="thumbnail" class="form-control" accept="image/*">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold d-block">Upload Many Parts</label>
                                    <ul class="nav nav-pills mb-2" role="tablist">
                                        <li class="nav-item"><button class="nav-link active px-2 py-1" data-bs-toggle="pill" data-bs-target="#addLangRw" type="button">RW</button></li>
                                        <li class="nav-item"><button class="nav-link px-2 py-1" data-bs-toggle="pill" data-bs-target="#addLangEn" type="button">EN</button></li>
                                        <li class="nav-item"><button class="nav-link px-2 py-1" data-bs-toggle="pill" data-bs-target="#addLangFr" type="button">FR</button></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="addLangRw">
                                            <input type="file" name="part_files_rw[]" class="form-control" accept="audio/*" multiple data-upload-monitor data-upload-max-files="300" data-upload-warn-mb="1500" data-upload-summary-target="#addAudiobookPartsSummaryRw">
                                            <div id="addAudiobookPartsSummaryRw" class="fs-12 text-muted mt-1">No files selected.</div>
                                        </div>
                                        <div class="tab-pane fade" id="addLangEn">
                                            <input type="file" name="part_files_en[]" class="form-control" accept="audio/*" multiple data-upload-monitor data-upload-max-files="300" data-upload-warn-mb="1500" data-upload-summary-target="#addAudiobookPartsSummaryEn">
                                            <div id="addAudiobookPartsSummaryEn" class="fs-12 text-muted mt-1">No files selected.</div>
                                        </div>
                                        <div class="tab-pane fade" id="addLangFr">
                                            <input type="file" name="part_files_fr[]" class="form-control" accept="audio/*" multiple data-upload-monitor data-upload-max-files="300" data-upload-warn-mb="1500" data-upload-summary-target="#addAudiobookPartsSummaryFr">
                                            <div id="addAudiobookPartsSummaryFr" class="fs-12 text-muted mt-1">No files selected.</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Start</label>
                                    <input type="number" name="part_order_start" min="1" class="form-control" value="1">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Duration</label>
                                    <input type="text" name="part_duration" class="form-control" placeholder="11:48">
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-light btn-sm mt-4" type="button" data-bs-toggle="collapse" data-bs-target="#addBookAudiobookAdvanced" aria-expanded="false" aria-controls="addBookAudiobookAdvanced">
                                        Advanced Options
                                    </button>
                                </div>
                                <div class="col-md-12 collapse" id="addBookAudiobookAdvanced">
                                    <div class="card border border-dashed">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Series</label>
                                                    <input type="text" name="series" class="form-control">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Published At</label>
                                                    <input type="date" name="published_at" class="form-control">
                                                </div>
                                                <div class="col-md-12 d-flex align-items-end gap-3">
                                                    <div class="form-check">
                                                        <input type="hidden" name="is_published" value="0">
                                                        <input class="form-check-input" type="checkbox" id="bookAudiobookPublished" name="is_published" value="1" checked>
                                                        <label class="form-check-label" for="bookAudiobookPublished">Published</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="hidden" name="featured" value="0">
                                                        <input class="form-check-input" type="checkbox" id="bookAudiobookFeatured" name="featured" value="1">
                                                        <label class="form-check-label" for="bookAudiobookFeatured">Featured</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input type="hidden" name="is_prayer_audio" value="0">
                                                        <input class="form-check-input" type="checkbox" id="bookAudiobookPrayer" name="is_prayer_audio" value="1">
                                                        <label class="form-check-label" for="bookAudiobookPrayer">Prayer Audio</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <button class="btn btn-primary w-100">Upload Audiobook To This Book</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
