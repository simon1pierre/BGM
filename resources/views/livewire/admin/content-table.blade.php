<div>
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Search</label>
                    <input type="text" wire:model.debounce.500ms="search" class="form-control" placeholder="Title, speaker, series, or author">
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Status</label>
                    <select wire:model="status" class="form-select">
                        <option value="all">All</option>
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Featured</label>
                    <select wire:model="featured" class="form-select">
                        <option value="all">All</option>
                        <option value="yes">Featured</option>
                        <option value="no">Not featured</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Deleted</label>
                    <select wire:model="deleted" class="form-select">
                        <option value="exclude">Exclude</option>
                        <option value="with">Include</option>
                        <option value="only">Only</option>
                    </select>
                </div>
                @if ($type === 'audiobooks')
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Prayer</label>
                        <select wire:model="prayer" class="form-select">
                            <option value="all">All</option>
                            <option value="yes">Prayer</option>
                            <option value="no">Non-prayer</option>
                        </select>
                    </div>
                @endif
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Category</label>
                    <select wire:model="categoryId" class="form-select">
                        <option value="">All</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Per Page</label>
                    <select wire:model="perPage" class="form-select">
                        <option value="2">2</option>
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold d-block">&nbsp;</label>
                    <button type="button" class="btn btn-primary w-100" wire:click="$refresh">
                        Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold d-block">&nbsp;</label>
                    <button type="button" class="btn btn-light w-100 no-print" onclick="printAdminReport('{{ ucfirst($type) }} Report')">
                        Print Report
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>
                                <button type="button" wire:click="sortBy('title')" class="btn btn-link p-0 text-decoration-none">
                                    Title
                                </button>
                            </th>
                            <th>Category</th>
                            <th>
                                @if ($type === 'documents')
                                    Author
                                @elseif ($type === 'audiobooks')
                                    Narrator
                                @else
                                    Speaker
                                @endif
                            </th>
                            <th>Status</th>
                            <th>Featured</th>
                            <th>
                                @if ($type === 'videos')
                                    Views
                                @else
                                    Downloads
                                @endif
                            </th>
                            <th>Published</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        @if ($type === 'videos' && $item->thumbnail_url)
                                            <img src="{{ $item->thumbnail_url }}" alt="thumbnail" class="rounded" style="width: 52px; height: 36px; object-fit: cover;">
                                        @endif
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $item->title }}</div>
                                            <div class="fs-12 text-muted text-truncate">
                                                {{ $item->series ?? $item->category?->name ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item->category?->name ?? '-' }}</td>
                                <td>{{ $item->speaker ?? $item->narrator ?? $item->author ?? '-' }}</td>
                                <td>
                                    @if ($item->trashed())
                                        <span class="badge bg-soft-secondary text-muted">Deleted</span>
                                    @elseif ($item->is_published)
                                        <span class="badge bg-soft-success text-success">Published</span>
                                    @else
                                        <span class="badge bg-soft-warning text-warning">Draft</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->featured)
                                        <span class="badge bg-soft-primary text-primary">Featured</span>
                                    @else
                                        <span class="badge bg-soft-secondary text-muted">No</span>
                                    @endif
                                    @if ($type === 'audiobooks' && $item->is_prayer_audio)
                                        <span class="badge bg-soft-info text-info">Prayer</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($type === 'videos')
                                        {{ $item->view_count ?? 0 }}
                                    @elseif ($type === 'audiobooks')
                                        {{ $item->play_count ?? 0 }}
                                    @else
                                        {{ $item->download_count ?? 0 }}
                                    @endif
                                </td>
                                <td class="text-muted fs-12">{{ $item->published_at?->toDateString() ?? '-' }}</td>
                                <td class="text-end">
                                    @if ($item->trashed())
                                        <form method="POST" action="{{ route('admin.'.$type.'.restore', $item->id) }}" class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-success">Restore</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.'.$type.'.force-delete', $item->id) }}" class="d-inline" data-confirm="Permanently delete this item? This cannot be undone." data-confirm-action="Permanent Delete">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Permanent Delete</button>
                                        </form>
                                    @else
                                        <a href="{{ route('admin.'.$type.'.preview', $item->id) }}" class="btn btn-sm btn-light">Preview</a>
                                        <a href="{{ route('admin.'.$type.'.edit', $item->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                        <form method="POST" action="{{ route('admin.'.$type.'.destroy', $item->id) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No content found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $items->links() }}
            </div>
        </div>
    </div>
</div>
