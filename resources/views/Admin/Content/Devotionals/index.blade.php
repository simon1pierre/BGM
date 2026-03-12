@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Devotionals</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Devotionals</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <a href="{{ route('admin.devotionals.create') }}" class="btn btn-primary">
                    <i class="feather-plus me-2"></i>
                    Add Devotional
                </a>
            </div>
        </div>

        <div class="main-content">
            @if (session('status'))
                <div class="alert alert-success mb-4">{{ session('status') }}</div>
            @endif

            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Search</label>
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Title, author, verse...">
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
                            <label class="form-label fw-semibold">Featured</label>
                            <select name="featured" class="form-select">
                                <option value="">All</option>
                                <option value="yes" @selected(request('featured') === 'yes')>Yes</option>
                                <option value="no" @selected(request('featured') === 'no')>No</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Deleted</label>
                            <select name="deleted" class="form-select">
                                <option value="">Exclude</option>
                                <option value="with" @selected(request('deleted') === 'with')>Include</option>
                                <option value="only" @selected(request('deleted') === 'only')>Only deleted</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                            <a href="{{ route('admin.devotionals.index') }}" class="btn btn-light">Reset</a>
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
                                    <th>Reference</th>
                                    <th>Author</th>
                                    <th>Status</th>
                                    <th>Published</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($devotionals as $devotional)
                                    <tr @class(['table-danger' => $devotional->trashed()])>
                                        <td>
                                            <div class="fw-semibold">{{ $devotional->title }}</div>
                                            @if ($devotional->excerpt)
                                                <div class="text-muted fs-12">{{ \Illuminate\Support\Str::limit($devotional->excerpt, 80) }}</div>
                                            @endif
                                        </td>
                                        <td>{{ $devotional->scripture_reference ?: '-' }}</td>
                                        <td>{{ $devotional->author ?: '-' }}</td>
                                        <td>
                                            <span class="badge {{ $devotional->is_published ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $devotional->is_published ? 'Published' : 'Draft' }}
                                            </span>
                                            @if ($devotional->featured)
                                                <span class="badge bg-warning text-dark">Highlighted</span>
                                            @endif
                                            @if ($devotional->trashed())
                                                <span class="badge bg-danger">Deleted</span>
                                            @endif
                                        </td>
                                        <td>{{ optional($devotional->published_at)->format('Y-m-d') ?: '-' }}</td>
                                        <td class="text-end">
                                            <div class="d-inline-flex align-items-center gap-2 flex-nowrap">
                                                @if ($devotional->trashed())
                                                    <form method="POST" action="{{ route('admin.devotionals.restore', $devotional->id) }}">
                                                        @csrf
                                                        <button class="btn btn-sm btn-success">Restore</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.devotionals.force-delete', $devotional->id) }}" onsubmit="return confirm('Permanently delete this devotional?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger">Delete Permanently</button>
                                                    </form>
                                                @else
                                                    <a href="{{ route('admin.devotionals.show', $devotional) }}" class="btn btn-sm btn-outline-primary">View</a>
                                                    <form method="POST" action="{{ route('admin.devotionals.toggle-published', $devotional) }}">
                                                        @csrf
                                                        <button class="btn btn-sm btn-light">{{ $devotional->is_published ? 'Draft' : 'Publish' }}</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('admin.devotionals.toggle-featured', $devotional) }}">
                                                        @csrf
                                                        <button class="btn btn-sm {{ $devotional->featured ? 'btn-warning' : 'btn-outline-warning' }}">{{ $devotional->featured ? 'Unhighlight' : 'Highlight' }}</button>
                                                    </form>
                                                    <a href="{{ route('admin.devotionals.edit', $devotional) }}" class="btn btn-sm btn-light">Edit</a>
                                                    <form method="POST" action="{{ route('admin.devotionals.destroy', $devotional) }}" onsubmit="return confirm('Delete this devotional?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">No devotionals found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $devotionals->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection








