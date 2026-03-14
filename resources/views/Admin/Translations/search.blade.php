@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Translation Search</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Translations</li>
                    <li class="breadcrumb-item">Search</li>
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
                <div class="card-body">
                    <form method="GET" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Search</label>
                            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Type a word or sentence to fix">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Locale</label>
                            <select name="locale" class="form-select">
                                @foreach (['all' => 'All', 'rw' => 'Kinyarwanda', 'en' => 'English', 'fr' => 'French'] as $key => $label)
                                    <option value="{{ $key }}" {{ $locale === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Source</label>
                            <select name="source" class="form-select">
                                @foreach (['all' => 'All', 'content' => 'Content', 'settings' => 'Site Settings', 'lang' => 'Language Files'] as $key => $label)
                                    <option value="{{ $key }}" {{ $source === $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button class="btn btn-primary w-100">Search</button>
                        </div>
                        <div class="col-md-1">
                            <a href="{{ route('admin.translations.search') }}" class="btn btn-light w-100">Reset</a>
                        </div>
                        <div class="col-12">
                            <div class="text-muted fs-12">Search shows matches in content translations (titles, descriptions, excerpts, bodies) and site settings translations.</div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="card-title">Language File Translations</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Locale</th>
                                    <th>File</th>
                                    <th>Key</th>
                                    <th>Value</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($langResults as $row)
                                    <tr>
                                        <td class="text-uppercase">{{ $row['locale'] }}</td>
                                        <td>{{ $row['file'] }}.php</td>
                                        <td class="text-muted fs-12">{{ $row['key'] }}</td>
                                        <td class="fs-12">{{ \Illuminate\Support\Str::limit($row['value'], 120) }}</td>
                                        <td class="text-end">
                                            <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#langEdit{{ $loop->index }}" aria-expanded="false">
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="collapse" id="langEdit{{ $loop->index }}">
                                        <td colspan="5" class="bg-light">
                                            <form method="POST" action="{{ route('admin.translations.search.lang.update') }}" class="row g-2">
                                                @csrf
                                                <input type="hidden" name="locale" value="{{ $row['locale'] }}">
                                                <input type="hidden" name="file" value="{{ $row['file'] }}">
                                                <input type="hidden" name="key" value="{{ $row['key'] }}">
                                                <div class="col-md-10">
                                                    <label class="form-label fs-12">{{ $row['file'] }}.php → {{ $row['key'] }}</label>
                                                    <textarea name="value" class="form-control form-control-sm" rows="2">{{ $row['value'] }}</textarea>
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button class="btn btn-sm btn-success w-100">Save</button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted">No language file matches yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $langResults->links('pagination.admin') }}
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header"><h5 class="card-title">Content Translations</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Content</th>
                                    <th>Locale</th>
                                    <th>Title</th>
                                    <th>Excerpt</th>
                                    <th>Description</th>
                                    <th>Body</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($contentResults as $row)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ class_basename($row->content_type) }} #{{ $row->content_id }}</div>
                                            <div class="text-muted fs-12">Translation #{{ $row->id }}</div>
                                        </td>
                                        <td class="text-uppercase">{{ $row->locale }}</td>
                                        <td class="fs-12">{{ \Illuminate\Support\Str::limit($row->title, 80) ?: '-' }}</td>
                                        <td class="fs-12">{{ \Illuminate\Support\Str::limit($row->excerpt, 80) ?: '-' }}</td>
                                        <td class="fs-12">{{ \Illuminate\Support\Str::limit($row->description, 80) ?: '-' }}</td>
                                        <td class="fs-12">{{ \Illuminate\Support\Str::limit($row->body, 80) ?: '-' }}</td>
                                        <td class="text-end">
                                            <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#contentEdit{{ $row->id }}" aria-expanded="false" aria-controls="contentEdit{{ $row->id }}">
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="collapse" id="contentEdit{{ $row->id }}">
                                        <td colspan="7" class="bg-light">
                                            <form method="POST" action="{{ route('admin.translations.search.content.update', $row->id) }}" class="row g-2">
                                                @csrf
                                                <div class="col-md-3">
                                                    <label class="form-label fs-12">Title</label>
                                                    <input type="text" name="title" value="{{ $row->title }}" class="form-control form-control-sm">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label fs-12">Excerpt</label>
                                                    <textarea name="excerpt" class="form-control form-control-sm" rows="2">{{ $row->excerpt }}</textarea>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label fs-12">Description</label>
                                                    <textarea name="description" class="form-control form-control-sm" rows="2">{{ $row->description }}</textarea>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label fs-12">Body</label>
                                                    <textarea name="body" class="form-control form-control-sm" rows="2">{{ $row->body }}</textarea>
                                                </div>
                                                <div class="col-12 text-end">
                                                    <button class="btn btn-sm btn-success">Save Changes</button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted">No content translation matches yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $contentResults->links('pagination.admin') }}
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><h5 class="card-title">Site Settings Translations</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Locale</th>
                                    <th>Field</th>
                                    <th>Value</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($settingsResults as $row)
                                    <tr>
                                        <td class="text-uppercase">{{ $row['locale'] }}</td>
                                        <td class="fw-semibold">{{ str_replace('_', ' ', $row['field']) }}</td>
                                        <td class="fs-12">{{ \Illuminate\Support\Str::limit($row['value'], 120) }}</td>
                                        <td class="text-end">
                                            <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#settingEdit{{ $row['id'] }}{{ $loop->index }}" aria-expanded="false">
                                                Edit
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="collapse" id="settingEdit{{ $row['id'] }}{{ $loop->index }}">
                                        <td colspan="4" class="bg-light">
                                            <form method="POST" action="{{ route('admin.translations.search.setting.update', $row['id']) }}" class="row g-2">
                                                @csrf
                                                <input type="hidden" name="field" value="{{ $row['field'] }}">
                                                <div class="col-md-10">
                                                    <label class="form-label fs-12">{{ str_replace('_', ' ', $row['field']) }}</label>
                                                    <textarea name="value" class="form-control form-control-sm" rows="2">{{ $row['value'] }}</textarea>
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button class="btn btn-sm btn-success w-100">Save</button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted">No settings translation matches yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $settingsResults->links('pagination.admin') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
