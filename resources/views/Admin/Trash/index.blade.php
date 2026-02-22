@extends('layouts.admin.app')
@section('contents')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Trash Management</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Trash Management</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto d-flex align-items-center gap-2">
            <button type="button" class="btn btn-light no-print" onclick="printAdminReport('Trash Report')">
                <i class="feather-printer me-2"></i>Print Report
            </button>
        </div>
    </div>

    <div class="main-content">
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Module</label>
                        <select name="module" class="form-select">
                            <option value="all" @selected($module === 'all')>All Modules</option>
                            @foreach ($modules as $key => $meta)
                                <option value="{{ $key }}" @selected($module === $key)>{{ $meta['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Search</label>
                        <input type="text" name="q" value="{{ $search }}" class="form-control" placeholder="Search deleted records...">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-3 mb-4">
            @foreach ($modules as $key => $meta)
                <div class="col-md-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="text-muted fs-12">{{ $meta['label'] }}</div>
                                    <div class="fs-4 fw-bold">{{ $counts[$key] ?? 0 }}</div>
                                </div>
                                <a href="{{ $meta['route'] }}" class="btn btn-sm btn-outline-primary">Open</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Module</th>
                                <th>Record</th>
                                <th>Details</th>
                                <th>Deleted At</th>
                                <th class="text-end">Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($items as $item)
                                <tr>
                                    <td><span class="badge bg-soft-secondary text-muted">{{ $item['module_label'] }}</span></td>
                                    <td class="fw-semibold">{{ $item['title'] }}</td>
                                    <td class="text-muted fs-12">{{ $item['subtitle'] ?: '—' }}</td>
                                    <td class="text-muted fs-12">{{ $item['deleted_at']?->diffForHumans() }}</td>
                                    <td class="text-end">
                                        <a href="{{ $item['manage_url'] }}" class="btn btn-sm btn-primary">Open Trash</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No deleted records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

