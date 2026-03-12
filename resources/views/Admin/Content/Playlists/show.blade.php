@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Playlist Details</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.playlists.index') }}">Playlists</a></li>
                    <li class="breadcrumb-item">{{ $playlist->title }}</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <a href="{{ route('admin.playlists.edit', $playlist) }}" class="btn btn-primary">Edit Playlist</a>
            </div>
        </div>

        <div class="main-content">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="fw-semibold">Title</div>
                            <div class="text-muted">{{ $playlist->title }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="fw-semibold">Type</div>
                            <div class="text-muted text-capitalize">{{ $playlist->type }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="fw-semibold">Status</div>
                            <div class="text-muted">{{ $playlist->is_published ? 'Published' : 'Draft' }}</div>
                        </div>
                        <div class="col-md-12">
                            <div class="fw-semibold">Description</div>
                            <div class="text-muted">{{ $playlist->description ?? '—' }}</div>
                        </div>
                        @if ($playlist->cover_image)
                            <div class="col-md-12">
                                <div class="fw-semibold mb-2">Cover</div>
                                <img src="{{ asset('storage/'.$playlist->cover_image) }}" alt="cover" class="rounded" style="max-width: 320px; width: 100%;">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">Playlist Items</h5>
                    <span class="text-muted fs-12">Showing {{ $items->count() }} of {{ $items->total() }}</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Item</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $item)
                                    @php
                                        $model = $playlist->type === 'video'
                                            ? \App\Models\Video::find($item->item_id)
                                            : \App\Models\Audio::find($item->item_id);
                                    @endphp
                                    <tr>
                                        <td>{{ $item->sort_order }}</td>
                                        <td>{{ $model?->title ?? 'Missing item' }}</td>
                                        <td class="text-capitalize">{{ $playlist->type }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No items in this playlist.</td>
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


