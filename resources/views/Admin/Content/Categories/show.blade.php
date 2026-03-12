@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Category Details</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                    <li class="breadcrumb-item">{{ $category->name }}</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary">Edit Category</a>
            </div>
        </div>

        <div class="main-content">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="fw-semibold">Name</div>
                            <div class="text-muted">{{ $category->name }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="fw-semibold">Type</div>
                            <div class="text-muted text-capitalize">{{ $category->type }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="fw-semibold">Status</div>
                            <div class="text-muted">{{ $category->is_active ? 'Active' : 'Inactive' }}</div>
                        </div>
                        <div class="col-md-12">
                            <div class="fw-semibold">Description</div>
                            <div class="text-muted">{{ $category->description ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xxl-4 col-md-6">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div>
                                    <div class="fs-4 fw-bold text-dark">{{ $stats['videos']['total'] }}</div>
                                    <div class="fs-12 text-muted">Videos</div>
                                </div>
                                <div class="text-muted fs-12">Views: {{ $stats['videos']['views'] }}</div>
                            </div>
                            <div class="fs-12 text-muted">Published: {{ $stats['videos']['published'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-md-6">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div>
                                    <div class="fs-4 fw-bold text-dark">{{ $stats['audios']['total'] }}</div>
                                    <div class="fs-12 text-muted">Audios</div>
                                </div>
                                <div class="text-muted fs-12">Downloads: {{ $stats['audios']['downloads'] }}</div>
                            </div>
                            <div class="fs-12 text-muted">Published: {{ $stats['audios']['published'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-md-6">
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between mb-3">
                                <div>
                                    <div class="fs-4 fw-bold text-dark">{{ $stats['documents']['total'] }}</div>
                                    <div class="fs-12 text-muted">Documents</div>
                                </div>
                                <div class="text-muted fs-12">Downloads: {{ $stats['documents']['downloads'] }}</div>
                            </div>
                            <div class="fs-12 text-muted">Published: {{ $stats['documents']['published'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="btn-group">
                        <a href="{{ route('admin.categories.show', [$category, 'tab' => 'videos']) }}" class="btn btn-sm {{ $tab === 'videos' ? 'btn-primary' : 'btn-light' }}">Videos</a>
                        <a href="{{ route('admin.categories.show', [$category, 'tab' => 'audios']) }}" class="btn btn-sm {{ $tab === 'audios' ? 'btn-primary' : 'btn-light' }}">Audios</a>
                        <a href="{{ route('admin.categories.show', [$category, 'tab' => 'documents']) }}" class="btn btn-sm {{ $tab === 'documents' ? 'btn-primary' : 'btn-light' }}">Documents</a>
                    </div>
                    <span class="text-muted fs-12">Showing {{ $items->count() }} of {{ $items->total() }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Published</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $item)
                                    <tr>
                                        <td>{{ $item->title }}</td>
                                        <td>
                                            @if ($item->is_published)
                                                <span class="badge bg-soft-success text-success">Published</span>
                                            @else
                                                <span class="badge bg-soft-warning text-warning">Draft</span>
                                            @endif
                                        </td>
                                        <td class="text-muted fs-12">{{ $item->published_at?->toDateString() ?? '—' }}</td>
                                        <td class="text-end">
                                            @php
                                                $routePrefix = $tab === 'audios' ? 'admin.audios' : ($tab === 'documents' ? 'admin.documents' : 'admin.videos');
                                            @endphp
                                            <a href="{{ route($routePrefix.'.preview', $item->id) }}" class="btn btn-sm btn-light">Preview</a>
                                            <a href="{{ route($routePrefix.'.edit', $item->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No content in this category yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $items->links('pagination.admin') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


