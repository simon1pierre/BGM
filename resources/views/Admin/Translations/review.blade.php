@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Translations</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Translations</li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if (session('status_error'))
                <div class="alert alert-danger">{{ session('status_error') }}</div>
            @endif

            <div class="card mb-4">
                <div class="card-body py-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                        <div>
                            <h6 class="mb-1">Simple Workflow</h6>
                            <div class="text-muted fs-12">1) Open Needs Review, 2) Approve good translation, 3) Edit manually only when needed.</div>
                        </div>
                        <a href="{{ route('admin.translations.review', ['status' => 'needs_review']) }}" class="btn btn-primary btn-sm">Open Needs Review</a>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-xxl-3 col-md-6">
                    <div class="card"><div class="card-body"><div class="fs-12 text-muted">Needs Review</div><div class="fs-3 fw-bold text-warning">{{ number_format($summary['needs_review']) }}</div></div></div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card"><div class="card-body"><div class="fs-12 text-muted">Approved</div><div class="fs-3 fw-bold text-success">{{ number_format($summary['approved']) }}</div></div></div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card"><div class="card-body"><div class="fs-12 text-muted">Manual</div><div class="fs-3 fw-bold">{{ number_format($summary['manual']) }}</div></div></div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="card"><div class="card-body"><div class="fs-12 text-muted">System</div><div class="fs-3 fw-bold">{{ number_format($summary['system']) }}</div></div></div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body py-2">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link {{ $status === 'all' ? 'active' : '' }}" href="{{ route('admin.translations.review', array_merge(request()->except('page'), ['status' => 'all'])) }}">All</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $status === 'needs_review' ? 'active' : '' }}" href="{{ route('admin.translations.review', array_merge(request()->except('page'), ['status' => 'needs_review'])) }}">Needs Review</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $status === 'approved' ? 'active' : '' }}" href="{{ route('admin.translations.review', array_merge(request()->except('page'), ['status' => 'approved'])) }}">Approved</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $translatedBy === 'manual' ? 'active' : '' }}" href="{{ route('admin.translations.review', array_merge(request()->except('page'), ['translated_by' => 'manual', 'status' => 'all'])) }}">Manual</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Locale</label>
                            <select name="locale" class="form-select">
                                @foreach (['all' => 'All', 'rw' => 'Kinyarwanda', 'en' => 'English', 'fr' => 'Francais'] as $key => $label)
                                    <option value="{{ $key }}" {{ $locale === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                @foreach (['all' => 'All', 'needs_review' => 'Needs Review', 'approved' => 'Approved'] as $key => $label)
                                    <option value="{{ $key }}" {{ $status === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Search title, description, content id">
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-primary w-100">Apply</button>
                        </div>
                        <div class="col-md-1">
                            <a href="{{ route('admin.translations.review') }}" class="btn btn-light w-100">Reset</a>
                        </div>

                        <div class="col-12">
                            <a class="text-primary fs-12" data-bs-toggle="collapse" href="#advancedFilters" role="button" aria-expanded="false" aria-controls="advancedFilters">Advanced Filters</a>
                        </div>
                        <div class="collapse col-12" id="advancedFilters">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label">Type</label>
                                    <select name="type" class="form-select">
                                        @foreach (['all' => 'All', 'video' => 'Video', 'audio' => 'Audio', 'audiobook' => 'Audiobook', 'book' => 'Book'] as $key => $label)
                                            <option value="{{ $key }}" {{ $type === $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Origin</label>
                                    <select name="translated_by" class="form-select">
                                        @foreach (['all' => 'All', 'system' => 'System', 'manual' => 'Manual'] as $key => $label)
                                            <option value="{{ $key }}" {{ $translatedBy === $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="card-title">Review Items</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Content</th>
                                    <th>Status</th>
                                    <th>Score</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rows as $row)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold text-capitalize">{{ $row->content_kind }} #{{ $row->content_id }}</div>
                                            <div class="text-muted fs-12">Translation #{{ $row->id }} • {{ strtoupper($row->locale) }}</div>
                                        </td>
                                        <td>
                                            <span class="badge {{ $row->translation_status === 'approved' ? 'bg-success' : 'bg-warning text-dark' }}">
                                                {{ $row->translation_status === 'approved' ? 'Approved' : 'Needs Review' }}
                                            </span>
                                            <div class="text-muted fs-12">{{ ucfirst($row->translated_by) }}</div>
                                        </td>
                                        <td>{{ is_null($row->quality_score) ? '-' : number_format((float) $row->quality_score, 1).'%' }}</td>
                                        <td class="fs-12">{{ $row->title ?: '-' }}</td>
                                        <td class="fs-12">{{ \Illuminate\Support\Str::limit($row->description, 120) ?: '-' }}</td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <form method="POST" action="{{ route('admin.translations.approve', $row->id) }}">
                                                    @csrf
                                                    <button class="btn btn-sm btn-success w-100">Approve</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.translations.reject', $row->id) }}">
                                                    @csrf
                                                    <button class="btn btn-sm btn-outline-warning w-100">Send Back</button>
                                                </form>
                                                <button class="btn btn-sm btn-outline-primary w-100" type="button" data-bs-toggle="collapse" data-bs-target="#manualForm{{ $row->id }}" aria-expanded="false" aria-controls="manualForm{{ $row->id }}">
                                                    Edit Manually
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="collapse" id="manualForm{{ $row->id }}">
                                        <td colspan="6" class="bg-light">
                                            <form method="POST" action="{{ route('admin.translations.manual-save', $row->id) }}" class="row g-2">
                                                @csrf
                                                <div class="col-md-4">
                                                    <input type="text" name="title" class="form-control form-control-sm" placeholder="Manual title">
                                                </div>
                                                <div class="col-md-6">
                                                    <textarea name="description" class="form-control form-control-sm" rows="2" placeholder="Manual description"></textarea>
                                                </div>
                                                <div class="col-md-2">
                                                    <button class="btn btn-sm btn-primary w-100">Save + Approve</button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted">No translations found for selected filters.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $rows->links('pagination.admin') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection








