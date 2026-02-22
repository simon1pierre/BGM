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
                                <label class="form-label fw-semibold">Primary Audiobook File</label>
                                <input type="file" name="audio_file" class="form-control" accept="audio/*">
                                @if ($audiobook->audio_file)
                                    <div class="mt-2">
                                        <audio controls class="w-100">
                                            <source src="{{ asset('storage/'.$audiobook->audio_file) }}" type="audio/mpeg">
                                        </audio>
                                    </div>
                                @endif
                                <div class="fs-12 text-muted mt-1">Used as default playback if no part is selected.</div>
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
                                <label class="form-label fw-semibold">Linked Book</label>
                                <select name="book_id" class="form-select" required>
                                    @foreach ($books as $book)
                                        <option value="{{ $book->id }}" @selected(old('book_id', $audiobook->book_id) == $book->id)>{{ $book->title }}</option>
                                    @endforeach
                                </select>
                                <div class="fs-12 text-muted mt-1">Each audiobook must belong to one book.</div>
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

                    <hr class="my-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="mb-0">Audiobook Parts</h6>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-light text-dark">{{ $audiobook->parts->count() }} part(s)</span>
                            @if ($audiobook->parts->count() > 1)
                                <form id="partReorderForm" method="POST" action="{{ route('admin.audiobooks.parts.reorder', $audiobook) }}" class="d-inline">
                                    @csrf
                                    <div id="partReorderInputs"></div>
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Save Parts Order</button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <div class="table-responsive mb-3">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">Order</th>
                                    <th>Title</th>
                                    <th>Duration</th>
                                    <th style="width: 220px;">Preview</th>
                                    <th style="width: 90px;">Status</th>
                                    <th class="text-end" style="width: 230px;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="partsTableBody">
                                @forelse ($audiobook->parts as $part)
                                    <tr data-part-id="{{ $part->id }}">
                                        <td><span class="part-order">{{ $part->sort_order }}</span></td>
                                        <td>{{ $part->title }}</td>
                                        <td>{{ $part->duration ?: '-' }}</td>
                                        <td>
                                            <audio controls class="w-100">
                                                <source src="{{ asset('storage/'.$part->audio_file) }}" type="audio/mpeg">
                                            </audio>
                                        </td>
                                        <td>
                                            @if ($part->is_published)
                                                <span class="badge bg-soft-success text-success">Live</span>
                                            @else
                                                <span class="badge bg-soft-warning text-warning">Draft</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <button type="button" class="btn btn-sm btn-light part-move-up" title="Move Up">↑</button>
                                            <button type="button" class="btn btn-sm btn-light part-move-down" title="Move Down">↓</button>
                                            <form method="POST" action="{{ route('admin.audiobooks.parts.destroy', [$audiobook, $part->id]) }}" class="d-inline" data-confirm="Remove this audiobook part?" data-confirm-action="Remove">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">Remove</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-muted text-center">No parts yet. Add parts below.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <form method="POST" action="{{ route('admin.audiobooks.parts.store', $audiobook) }}" enctype="multipart/form-data" class="border rounded-3 p-3 bg-light">
                        @csrf
                        <h6 class="mb-2">Quick Multi-Part Upload</h6>
                        <p class="text-muted fs-12 mb-3">Fast way: choose many files and click upload.</p>
                        <div class="row g-3">
                            <div class="col-md-7">
                                <label class="form-label fw-semibold">Audio Files (many)</label>
                                <input type="file" name="part_files[]" class="form-control" accept="audio/*" multiple required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Prefix</label>
                                <input type="text" name="part_title_prefix" class="form-control" value="Part">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Start Number</label>
                                <input type="number" name="part_order_start" min="1" class="form-control" value="{{ ($audiobook->parts->max('sort_order') ?? 0) + 1 }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Duration (optional)</label>
                                <input type="text" name="duration" class="form-control" placeholder="e.g. 11:48">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <div class="form-check">
                                    <input type="hidden" name="is_published" value="0">
                                    <input class="form-check-input" type="checkbox" id="bulkPartPublished" name="is_published" value="1" checked>
                                    <label class="form-check-label" for="bulkPartPublished">Published</label>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <button class="btn btn-primary w-100">Upload Parts</button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-3 border rounded-3 p-3">
                        <button class="btn btn-sm btn-light mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#singlePartForm" aria-expanded="false" aria-controls="singlePartForm">
                            Add Single Part (Advanced)
                        </button>
                        <div id="singlePartForm" class="collapse">
                            <form method="POST" action="{{ route('admin.audiobooks.parts.store', $audiobook) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Part Title</label>
                                        <input type="text" name="title" class="form-control" placeholder="e.g. Part 12 - The Conflict">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Order</label>
                                        <input type="number" name="sort_order" min="1" class="form-control" value="{{ ($audiobook->parts->max('sort_order') ?? 0) + 1 }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold">Duration</label>
                                        <input type="text" name="duration" class="form-control" placeholder="e.g. 11:48">
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end">
                                        <div class="form-check">
                                            <input type="hidden" name="is_published" value="0">
                                            <input class="form-check-input" type="checkbox" id="singlePartPublished" name="is_published" value="1" checked>
                                            <label class="form-check-label" for="singlePartPublished">Published</label>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label fw-semibold">Audio File</label>
                                        <input type="file" name="audio_file" class="form-control" accept="audio/*" required>
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button class="btn btn-outline-primary w-100">Add Single Part</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    (() => {
        const tbody = document.getElementById('partsTableBody');
        const inputContainer = document.getElementById('partReorderInputs');
        if (!tbody || !inputContainer) return;

        const rebuildOrderPayload = () => {
            const rows = Array.from(tbody.querySelectorAll('tr[data-part-id]'));
            inputContainer.innerHTML = '';

            rows.forEach((row, index) => {
                const id = row.getAttribute('data-part-id');
                const orderNode = row.querySelector('.part-order');
                if (orderNode) orderNode.textContent = String(index + 1);

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'ordered_ids[]';
                input.value = id || '';
                inputContainer.appendChild(input);
            });
        };

        tbody.querySelectorAll('.part-move-up').forEach((btn) => {
            btn.addEventListener('click', (event) => {
                const row = event.currentTarget.closest('tr[data-part-id]');
                if (!row) return;
                const prev = row.previousElementSibling;
                if (!prev) return;
                tbody.insertBefore(row, prev);
                rebuildOrderPayload();
            });
        });

        tbody.querySelectorAll('.part-move-down').forEach((btn) => {
            btn.addEventListener('click', (event) => {
                const row = event.currentTarget.closest('tr[data-part-id]');
                if (!row) return;
                const next = row.nextElementSibling;
                if (!next) return;
                tbody.insertBefore(next, row);
                rebuildOrderPayload();
            });
        });

        rebuildOrderPayload();
    })();
</script>
@endpush
