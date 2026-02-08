@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Playlists</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Playlists</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
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

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Type</th>
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
                                        <td class="text-capitalize">{{ $playlist->type }}</td>
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
                                        <td colspan="6" class="text-center text-muted">No playlists found.</td>
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
