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
                <div class="d-flex flex-wrap justify-content-end gap-2 mb-3 no-print">
                    <button type="button" class="btn btn-success btn-sm" onclick="document.getElementById('trashBulkRestoreForm').requestSubmit();">
                        Restore Selected
                    </button>
                    <button
                        type="button"
                        class="btn btn-outline-danger btn-sm"
                        data-confirm-trigger
                        data-confirm-target="trashBulkDeleteForm"
                        data-confirm-message="Permanently delete selected items? This cannot be undone."
                        data-confirm-action="Permanent Delete"
                    >
                        Permanent Delete Selected
                    </button>
                </div>

                <form id="trashBulkRestoreForm" method="POST" action="{{ route('admin.trash.bulk-restore') }}" class="d-none">
                    @csrf
                    <input type="hidden" name="module_filter" value="{{ $module }}">
                    <input type="hidden" name="search_query" value="{{ $search }}">
                    <div id="bulkRestoreInputs"></div>
                </form>

                <form id="trashBulkDeleteForm" method="POST" action="{{ route('admin.trash.bulk-force-delete') }}" class="d-none">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="module_filter" value="{{ $module }}">
                    <input type="hidden" name="search_query" value="{{ $search }}">
                    <div id="bulkDeleteInputs"></div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th style="width:42px;">
                                    <input type="checkbox" id="trashCheckAll" class="form-check-input">
                                </th>
                                <th>Module</th>
                                <th>Record</th>
                                <th>Details</th>
                                <th>Deleted At</th>
                                <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse ($items as $item)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="form-check-input trash-row-check" value="{{ $item['module_key'] }}:{{ $item['id'] }}">
                                    </td>
                                    <td><span class="badge bg-soft-secondary text-muted">{{ $item['module_label'] }}</span></td>
                                    <td class="fw-semibold">{{ $item['title'] }}</td>
                                    <td class="text-muted fs-12">{{ $item['subtitle'] ?: '—' }}</td>
                                    <td class="text-muted fs-12">{{ $item['deleted_at']?->diffForHumans() }}</td>
                                    <td class="text-end">
                                        <form method="POST" action="{{ route('admin.trash.restore', ['module' => $item['module_key'], 'id' => $item['id']]) }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="module_filter" value="{{ $module }}">
                                            <input type="hidden" name="search_query" value="{{ $search }}">
                                            <button class="btn btn-sm btn-success">Restore</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.trash.force-delete', ['module' => $item['module_key'], 'id' => $item['id']]) }}" class="d-inline" data-confirm="Permanently delete this item? This cannot be undone." data-confirm-action="Permanent Delete">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="module_filter" value="{{ $module }}">
                                            <input type="hidden" name="search_query" value="{{ $search }}">
                                            <button class="btn btn-sm btn-outline-danger">Permanent Delete</button>
                                        </form>
                                        <a href="{{ $item['manage_url'] }}" class="btn btn-sm btn-primary">Open Module</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No deleted records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (() => {
        const checkAll = document.getElementById('trashCheckAll');
        const rowChecks = () => Array.from(document.querySelectorAll('.trash-row-check'));
        const restoreContainer = document.getElementById('bulkRestoreInputs');
        const deleteContainer = document.getElementById('bulkDeleteInputs');

        const selected = () => rowChecks().filter((el) => el.checked).map((el) => el.value);
        const syncHiddenInputs = () => {
            if (!restoreContainer || !deleteContainer) return;
            const values = selected();
            const html = values.map((value) => `<input type="hidden" name="items[]" value="${value}">`).join('');
            restoreContainer.innerHTML = html;
            deleteContainer.innerHTML = html;
        };

        checkAll?.addEventListener('change', () => {
            rowChecks().forEach((el) => { el.checked = checkAll.checked; });
            syncHiddenInputs();
        });

        rowChecks().forEach((el) => {
            el.addEventListener('change', () => {
                if (checkAll) {
                    const checks = rowChecks();
                    checkAll.checked = checks.length > 0 && checks.every((item) => item.checked);
                }
                syncHiddenInputs();
            });
        });
    })();
</script>
@endsection


