@extends('layouts.admin.app')
@section('contents')
<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Edit Campaign</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.campaigns.index') }}">Campaigns</a></li>
                <li class="breadcrumb-item">Edit</li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.campaigns.update', $campaign) }}" class="row g-3">
                    @csrf
                    @method('PUT')

                    <div class="col-12">
                        <label class="form-label fw-semibold">Subject</label>
                        <input type="text" name="subject" value="{{ old('subject', $campaign->subject) }}" class="form-control">
                        @error('subject')<div class="text-danger fs-12">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Message</label>
                        <textarea name="message" rows="8" class="form-control">{{ old('message', $campaign->message) }}</textarea>
                        @error('message')<div class="text-danger fs-12">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="status" class="form-select">
                            <option value="draft" @selected(old('status', $campaign->status) === 'draft')>Draft</option>
                            <option value="scheduled" @selected(old('status', $campaign->status) === 'scheduled')>Scheduled</option>
                            <option value="sent" @selected(old('status', $campaign->status) === 'sent')>Send Now</option>
                        </select>
                        @error('status')<div class="text-danger fs-12">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Scheduled At (Africa/Kigali)</label>
                        <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at', optional($campaign->scheduled_at)->format('Y-m-d\\TH:i')) }}" class="form-control">
                        @error('scheduled_at')<div class="text-danger fs-12">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary">Save</button>
                        <a href="{{ route('admin.campaigns.index') }}" class="btn btn-light">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
