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
                    <li class="breadcrumb-item"><a href="{{ route('admin.documents.index') }}">Books</a></li>
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
                                <label class="form-label fw-semibold">Linked Book</label>
                                <input type="hidden" name="book_id" value="{{ $audiobook->book_id }}">
                                <div class="form-control bg-light">
                                    {{ $books->firstWhere('id', $audiobook->book_id)?->title ?? 'Book not found' }}
                                </div>
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
                                <button class="btn btn-light btn-sm mt-4" type="button" data-bs-toggle="collapse" data-bs-target="#audiobookEditAdvanced" aria-expanded="false" aria-controls="audiobookEditAdvanced">
                                    Advanced Options
                                </button>
                            </div>
                            <div class="col-md-12 collapse" id="audiobookEditAdvanced">
                                <div class="card border border-dashed">
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Publish Date</label>
                                                <input type="date" name="published_at" value="{{ old('published_at', optional($audiobook->published_at)->toDateString()) }}" class="form-control">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Thumbnail (optional)</label>
                                                <input type="file" name="thumbnail" class="form-control" accept="image/*">
                                                @if ($audiobook->thumbnail)
                                                    <div class="mt-2 text-muted fs-12">Current: {{ $audiobook->thumbnail }}</div>
                                                @endif
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Duration</label>
                                                <input type="text" name="duration" value="{{ old('duration', $audiobook->duration) }}" class="form-control">
                                            </div>
                                            <div class="col-md-12">
                                                <div class="alert alert-light border">Category is inherited from linked book: <strong>{{ $books->firstWhere('id', $audiobook->book_id)?->category?->name ?? '-' }}</strong></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                            <a href="{{ route('admin.documents.edit', $audiobook->book_id) }}" class="btn btn-light">Back to Book</a>
                        </div>
                    </form>

                    <hr class="my-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h6 class="mb-0">Audiobook Parts</h6>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-light text-dark">{{ $audiobook->parts->count() }} part(s)</span>
                            <form id="partBulkDeleteForm" method="POST" action="{{ route('admin.audiobooks.parts.destroy-many', $audiobook) }}" class="d-inline" data-confirm="Delete selected audiobook parts?" data-confirm-action="Delete Selected">
                                @csrf
                                @method('DELETE')
                                <div id="partBulkDeleteInputs"></div>
                                <button id="partBulkDeleteBtn" type="submit" class="btn btn-sm btn-outline-danger" disabled>Delete Selected</button>
                            </form>
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
                                    <th style="width: 45px;">
                                        <input type="checkbox" id="partSelectAll" class="form-check-input" title="Select all parts">
                                    </th>
                                    <th style="width: 80px;">Order</th>
                                    <th>Title</th>
                                    <th>Language</th>
                                    <th>Duration</th>
                                    <th style="width: 220px;">Preview</th>
                                    <th style="width: 90px;">Status</th>
                                    <th class="text-end" style="width: 230px;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="partsTableBody">
                                @forelse ($audiobook->parts as $part)
                                    @php($editId = 'editPart'.$part->id)
                                    <tr data-part-id="{{ $part->id }}">
                                        <td>
                                            <input type="checkbox" class="form-check-input part-select" value="{{ $part->id }}" aria-label="Select part {{ $part->id }}">
                                        </td>
                                        <td><span class="part-order">{{ $part->sort_order }}</span></td>
                                        <td>{{ $part->title }}</td>
                                        <td><span class="badge bg-soft-info text-info">{{ $part->language_label }}</span></td>
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
                                            <div class="d-flex justify-content-end align-items-center gap-1 flex-nowrap" style="white-space: nowrap;">
                                            <button type="button" class="btn btn-sm btn-light part-move-up" title="Move Up">&uarr;</button>
                                            <button type="button" class="btn btn-sm btn-light part-move-down" title="Move Down">&darr;</button>
                                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#{{ $editId }}" aria-expanded="false" aria-controls="{{ $editId }}">Edit</button>
                                            <form method="POST" action="{{ route('admin.audiobooks.parts.destroy', [$audiobook, $part->id]) }}" class="d-inline" data-confirm="Remove this audiobook part?" data-confirm-action="Remove">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger">Remove</button>
                                            </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="8" class="p-0 border-0">
                                            <div id="{{ $editId }}" class="collapse border-top bg-light-subtle p-3">
                                                <form method="POST" action="{{ route('admin.audiobooks.parts.update', [$audiobook, $part->id]) }}" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="row g-3 align-items-end">
                                                        <div class="col-md-4">
                                                            <label class="form-label fw-semibold">Part Title</label>
                                                            <input type="text" name="title" class="form-control" value="{{ $part->title }}" required>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label fw-semibold">Order</label>
                                                            <input type="number" name="sort_order" min="1" class="form-control" value="{{ $part->sort_order }}" required>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label fw-semibold">Language</label>
                                                            <select name="language" class="form-select" required>
                                                                <option value="rw" @selected($part->language === 'rw')>Kinyarwanda</option>
                                                                <option value="en" @selected($part->language === 'en')>English</option>
                                                                <option value="fr" @selected($part->language === 'fr')>French</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label class="form-label fw-semibold">Duration</label>
                                                            <input type="text" name="duration" class="form-control" value="{{ $part->duration }}" placeholder="e.g. 11:48">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="hidden" name="is_published" value="0">
                                                            <div class="form-check mt-4">
                                                                <input class="form-check-input" type="checkbox" name="is_published" value="1" id="partPublished{{ $part->id }}" @checked($part->is_published)>
                                                                <label class="form-check-label" for="partPublished{{ $part->id }}">Published</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-9">
                                                            <label class="form-label fw-semibold">Replace Audio File (optional)</label>
                                                            <input type="file" name="audio_file" class="form-control" accept="audio/*">
                                                            <div class="fs-12 text-muted mt-1">Current file: {{ basename((string) $part->audio_file) }}</div>
                                                        </div>
                                                        <div class="col-md-3 d-grid">
                                                            <button class="btn btn-primary">Save Part Changes</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-muted text-center">No parts yet. Add parts below.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <form method="POST" action="{{ route('admin.audiobooks.parts.store', $audiobook) }}" enctype="multipart/form-data" class="border rounded-3 p-3 bg-light">
                        @csrf
                        <h6 class="mb-2">Quick Multi-Part Upload</h6>
                        <p class="text-muted fs-12 mb-3">Fast way: choose files by language and upload. Each part title is taken from the original file name.</p>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <ul class="nav nav-pills mb-3" role="tablist">
                                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#editPartLangRw" type="button">Kinyarwanda</button></li>
                                    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#editPartLangEn" type="button">English</button></li>
                                    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#editPartLangFr" type="button">French</button></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="editPartLangRw">
                                        <label class="form-label fw-semibold">Kinyarwanda Parts</label>
                                        <input type="file" name="part_files_rw[]" class="form-control" accept="audio/*" multiple data-upload-monitor data-upload-max-files="300" data-upload-warn-mb="1500" data-upload-summary-target="#editPartsSummaryRw">
                                        <div id="editPartsSummaryRw" class="fs-12 text-muted mt-1">No files selected.</div>
                                    </div>
                                    <div class="tab-pane fade" id="editPartLangEn">
                                        <label class="form-label fw-semibold">English Parts</label>
                                        <input type="file" name="part_files_en[]" class="form-control" accept="audio/*" multiple data-upload-monitor data-upload-max-files="300" data-upload-warn-mb="1500" data-upload-summary-target="#editPartsSummaryEn">
                                        <div id="editPartsSummaryEn" class="fs-12 text-muted mt-1">No files selected.</div>
                                    </div>
                                    <div class="tab-pane fade" id="editPartLangFr">
                                        <label class="form-label fw-semibold">French Parts</label>
                                        <input type="file" name="part_files_fr[]" class="form-control" accept="audio/*" multiple data-upload-monitor data-upload-max-files="300" data-upload-warn-mb="1500" data-upload-summary-target="#editPartsSummaryFr">
                                        <div id="editPartsSummaryFr" class="fs-12 text-muted mt-1">No files selected.</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Start Number</label>
                                <input type="number" name="part_order_start" min="1" class="form-control" value="{{ ($audiobook->parts->max('sort_order') ?? 0) + 1 }}">
                            </div>
                            <div class="col-md-2">
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
                                        <div class="fs-12 text-muted mt-1">Leave empty to use the uploaded file name.</div>
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
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold">Language</label>
                                        <select name="part_language" class="form-select">
                                            <option value="rw">Kinyarwanda</option>
                                            <option value="en">English</option>
                                            <option value="fr">French</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
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
        const selectAll = document.getElementById('partSelectAll');
        const bulkDeleteInputs = document.getElementById('partBulkDeleteInputs');
        const bulkDeleteBtn = document.getElementById('partBulkDeleteBtn');
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

        const rebuildBulkDeletePayload = () => {
            if (!bulkDeleteInputs || !bulkDeleteBtn) return;
            const checked = Array.from(tbody.querySelectorAll('.part-select:checked'));
            bulkDeleteInputs.innerHTML = '';
            checked.forEach((checkbox) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'part_ids[]';
                input.value = checkbox.value;
                bulkDeleteInputs.appendChild(input);
            });
            bulkDeleteBtn.disabled = checked.length === 0;
            if (selectAll) {
                const all = Array.from(tbody.querySelectorAll('.part-select'));
                selectAll.checked = all.length > 0 && checked.length === all.length;
            }
        };

        const getEditRow = (mainRow) => {
            const next = mainRow.nextElementSibling;
            return next && !next.hasAttribute('data-part-id') ? next : null;
        };

        const getPrevMainRow = (mainRow) => {
            let node = mainRow.previousElementSibling;
            while (node && !node.hasAttribute('data-part-id')) {
                node = node.previousElementSibling;
            }
            return node;
        };

        const getNextMainRow = (mainRow) => {
            let node = mainRow.nextElementSibling;
            while (node && !node.hasAttribute('data-part-id')) {
                node = node.nextElementSibling;
            }
            return node;
        };

        tbody.querySelectorAll('.part-move-up').forEach((btn) => {
            btn.addEventListener('click', (event) => {
                const mainRow = event.currentTarget.closest('tr[data-part-id]');
                if (!mainRow) return;

                const prevMain = getPrevMainRow(mainRow);
                if (!prevMain) return;

                const editRow = getEditRow(mainRow);
                const fragment = document.createDocumentFragment();
                fragment.appendChild(mainRow);
                if (editRow) fragment.appendChild(editRow);

                tbody.insertBefore(fragment, prevMain);
                rebuildOrderPayload();
            });
        });

        tbody.querySelectorAll('.part-move-down').forEach((btn) => {
            btn.addEventListener('click', (event) => {
                const mainRow = event.currentTarget.closest('tr[data-part-id]');
                if (!mainRow) return;

                const nextMain = getNextMainRow(mainRow);
                if (!nextMain) return;

                const nextMainEditRow = getEditRow(nextMain);
                const insertBeforeNode = nextMainEditRow ? nextMainEditRow.nextElementSibling : nextMain.nextElementSibling;

                const editRow = getEditRow(mainRow);
                const fragment = document.createDocumentFragment();
                fragment.appendChild(mainRow);
                if (editRow) fragment.appendChild(editRow);

                tbody.insertBefore(fragment, insertBeforeNode);
                rebuildOrderPayload();
            });
        });

        selectAll?.addEventListener('change', () => {
            const all = tbody.querySelectorAll('.part-select');
            all.forEach((checkbox) => {
                checkbox.checked = selectAll.checked;
            });
            rebuildBulkDeletePayload();
        });

        tbody.querySelectorAll('.part-select').forEach((checkbox) => {
            checkbox.addEventListener('change', rebuildBulkDeletePayload);
        });

        rebuildOrderPayload();
        rebuildBulkDeletePayload();
    })();
</script>
@endpush


