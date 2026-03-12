@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Audio Playlists</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Audio Playlists</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto d-flex align-items-center gap-2">
                <button type="button" class="btn btn-light no-print" onclick="printAdminReport('Playlists Report')">
                    <i class="feather-printer me-2"></i>
                    Print Report
                </button>
                <a href="{{ route('admin.playlists.create') }}" class="btn btn-primary">
                    <i class="feather-plus me-2"></i>
                    Add Playlist
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
                            <label class="form-label fw-semibold">Search</label>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                class="form-control"
                                placeholder="Title or description"
                            >
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All</option>
                                <option value="published" @selected(request('status') === 'published')>Published</option>
                                <option value="draft" @selected(request('status') === 'draft')>Draft</option>
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
                        <div class="col-md-2 d-grid">
                            <button class="btn btn-primary">Filter</button>
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
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Items</th>
                                    <th>Created</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($playlists as $playlist)
                                    <tr>
                                        <td class="fw-semibold text-dark">
                                            <a href="{{ route('admin.playlists.show', $playlist) }}" class="text-decoration-none">
                                                {{ $playlist->title }}
                                            </a>
                                        </td>
                                        <td>
                                            @if ($playlist->trashed())
                                                <span class="badge bg-soft-secondary text-muted">Deleted</span>
                                            @elseif ($playlist->is_published)
                                                <span class="badge bg-soft-success text-success">Published</span>
                                            @else
                                                <span class="badge bg-soft-warning text-warning">Draft</span>
                                            @endif
                                        </td>
                                        <td>{{ $playlist->items_count }}</td>
                                        <td class="text-muted fs-12">{{ $playlist->created_at?->toDateString() }}</td>
                                        <td class="text-end">
                                            @if ($playlist->trashed())
                                                <form action="{{ route('admin.playlists.restore', $playlist->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button class="btn btn-sm btn-success">Restore</button>
                                                </form>
                                                <form action="{{ route('admin.playlists.force-delete', $playlist->id) }}" method="POST" class="d-inline" data-confirm="Permanently delete this playlist? This cannot be undone." data-confirm-action="Permanent Delete">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger">Permanent Delete</button>
                                                </form>
                                            @else
                                                <a href="{{ route('admin.playlists.edit', $playlist) }}" class="btn btn-sm btn-primary">Edit</a>
                                                <form action="{{ route('admin.playlists.destroy', $playlist) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No playlists found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $playlists->links('pagination.admin') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


