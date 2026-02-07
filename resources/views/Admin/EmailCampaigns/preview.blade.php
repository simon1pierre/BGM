@extends('layouts.admin.app')
@section('contents')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Campaign Preview</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.campaigns.index') }}">Campaigns</a></li>
                <li class="breadcrumb-item">Preview</li>
            </ul>
        </div>
        <div class="page-header-right ms-auto">
            <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="btn btn-light">Back to Edit</a>
        </div>
    </div>

    <div class="main-content">
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    <strong>Subject:</strong> {{ $campaign->subject }}
                </div>
                <div class="mb-3">
                    <strong>Preheader:</strong> {{ $campaign->preheader ?? '—' }}
                </div>
                <div class="border rounded bg-white" style="height: 720px; overflow: hidden;">
                    <iframe
                        src="{{ route('admin.campaigns.preview.raw', $campaign) }}"
                        style="width: 100%; height: 100%; border: 0;"
                    ></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
