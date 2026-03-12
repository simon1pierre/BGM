@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Video Series</h5>
                </div>
            </div>
            <div class="page-header-right ms-auto">
                <a href="{{ route('admin.video-series.create') }}" class="btn btn-primary">Add Series</a>
            </div>
        </div>

        <div class="main-content">
            <div class="card">
                <div class="card-body">
                    <form method="GET" class="row g-2 mb-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Search title or description">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="active" @selected(request('status') === 'active')>Active</option>
                                <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="deleted" class="form-select">
                                <option value="">Without Deleted</option>
                                <option value="with" @selected(request('deleted') === 'with')>With Deleted</option>
                                <option value="only" @selected(request('deleted') === 'only')>Deleted Only</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-light w-100">Filter</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Videos</th>
                                    <th>Order</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($series as $row)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $row->title }}</div>
                                            <div class="text-muted fs-12">{{ \Illuminate\Support\Str::limit($row->description, 90) }}</div>
                                        </td>
                                        <td>{{ $row->videos_count }}</td>
                                        <td>{{ $row->sort_order }}</td>
                                        <td>
                                            <span class="badge {{ $row->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $row->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            @if ($row->trashed())
                                                <form action="{{ route('admin.video-series.restore', $row->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button class="btn btn-sm btn-success">Restore</button>
                                                </form>
                                                <form action="{{ route('admin.video-series.force-delete', $row->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">Delete Permanently</button>
                                                </form>
                                            @else
                                                <a href="{{ route('admin.video-series.edit', $row) }}" class="btn btn-sm btn-primary">Edit</a>
                                                <form action="{{ route('admin.video-series.destroy', $row) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No series found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $series->links('pagination.admin') }}
                </div>
            </div>
        </div>
    </div>
@endsection



