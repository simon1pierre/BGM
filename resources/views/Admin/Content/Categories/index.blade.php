@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Content Categories</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Categories</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto d-flex align-items-center gap-2">
                <button type="button" class="btn btn-light no-print" onclick="printAdminReport('Categories Report')">
                    <i class="feather-printer me-2"></i>
                    Print Report
                </button>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="feather-plus me-2"></i>
                    Add Category
                </a>
            </div>
        </div>

        <div class="main-content">
            @if (session('status'))
                <div class="alert alert-success mb-4">{{ session('status') }}</div>
            @endif

            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Type</label>
                            <select name="type" class="form-select">
                                <option value="">All</option>
                                <option value="video" @selected(request('type') === 'video')>Video</option>
                                <option value="audio" @selected(request('type') === 'audio')>Audio</option>
                                <option value="document" @selected(request('type') === 'document')>Document</option>
                                <option value="all" @selected(request('type') === 'all')>All content</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All</option>
                                <option value="active" @selected(request('status') === 'active')>Active</option>
                                <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Deleted</label>
                            <select name="deleted" class="form-select">
                                <option value="">Exclude</option>
                                <option value="with" @selected(request('deleted') === 'with')>Include</option>
                                <option value="only" @selected(request('deleted') === 'only')>Only</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Per Page</label>
                            <select name="per_page" class="form-select">
                                <option value="5" @selected(request('per_page') == 5)>5</option>
                                <option value="10" @selected(request('per_page') == 10)>10</option>
                                <option value="25" @selected(request('per_page') == 25)>25</option>
                                <option value="50" @selected(request('per_page') == 50)>50</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Videos</th>
                                    <th>Audios</th>
                                    <th>Documents</th>
                                    <th>Slug</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($categories as $category)
                                    @php
                                        $videoCount = (int) ($category->video_count ?? 0);
                                        $audioCount = (int) ($category->audio_count ?? 0);
                                        $documentCount = (int) ($category->document_count ?? 0);
                                        $totalCount = $videoCount + $audioCount + $documentCount;
                                    @endphp
                                    <tr class="category-row" data-href="{{ route('admin.categories.show', $category) }}" style="cursor: pointer;">
                                        <td class="fw-semibold text-dark">{{ $category->name }}</td>
                                        <td class="text-capitalize">{{ $category->type }}</td>
                                        <td>
                                            @if ($category->trashed())
                                                <span class="badge bg-soft-secondary text-muted">Deleted</span>
                                            @elseif ($category->is_active)
                                                <span class="badge bg-soft-success text-success">Active</span>
                                            @else
                                                <span class="badge bg-soft-warning text-warning">Inactive</span>
                                            @endif
                                        </td>
                                        <td>{{ $totalCount }}</td>
                                        <td>{{ $videoCount }}</td>
                                        <td>{{ $audioCount }}</td>
                                        <td>{{ $documentCount }}</td>
                                        <td class="text-muted fs-12">{{ $category->slug }}</td>
                                        <td class="text-end">
                                            @if ($category->trashed())
                                                <form action="{{ route('admin.categories.restore', $category->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button class="btn btn-sm btn-success">Restore</button>
                                                </form>
                                                <form action="{{ route('admin.categories.force-delete', $category->id) }}" method="POST" class="d-inline" data-confirm="Permanently delete this category? This cannot be undone." data-confirm-action="Permanent Delete">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger">Permanent Delete</button>
                                                </form>
                                            @else
                                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-primary">Edit</a>
                                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">No categories found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('click', function (event) {
            const row = event.target.closest('.category-row');
            if (!row) return;
            if (event.target.closest('a, button, form')) return;
            const href = row.dataset.href;
            if (href) {
                window.location.href = href;
            }
        });
    </script>
@endpush


