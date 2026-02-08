@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Add Category</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                    <li class="breadcrumb-item">Create</li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.categories.store') }}">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Name</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                                @error('name') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Slug (optional)</label>
                                <input type="text" name="slug" value="{{ old('slug') }}" class="form-control">
                                @error('slug') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Type</label>
                                <select name="type" class="form-select" required>
                                    <option value="">Select type</option>
                                    <option value="video" @selected(old('type') === 'video')>Video</option>
                                    <option value="audio" @selected(old('type') === 'audio')>Audio</option>
                                    <option value="document" @selected(old('type') === 'document')>Document</option>
                                    <option value="all" @selected(old('type') === 'all')>All content</option>
                                </select>
                                @error('type') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="form-check mt-4">
                                    <input type="hidden" name="is_active" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                                    <label class="form-check-label">Active</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                                @error('description') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-12">
                                <div class="card border border-dashed">
                                    <div class="card-body">
                                        <div class="fw-semibold mb-3">Translations (EN / FR / RW)</div>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Name (EN)</label>
                                                <input type="text" name="name_en" value="{{ old('name_en') }}" class="form-control" required>
                                                @error('name_en') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                                                <label class="form-label fw-semibold mt-3">Description (EN)</label>
                                                <textarea name="description_en" class="form-control" rows="3">{{ old('description_en') }}</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Name (FR)</label>
                                                <input type="text" name="name_fr" value="{{ old('name_fr') }}" class="form-control" required>
                                                @error('name_fr') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                                                <label class="form-label fw-semibold mt-3">Description (FR)</label>
                                                <textarea name="description_fr" class="form-control" rows="3">{{ old('description_fr') }}</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Name (RW)</label>
                                                <input type="text" name="name_rw" value="{{ old('name_rw') }}" class="form-control" required>
                                                @error('name_rw') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                                                <label class="form-label fw-semibold mt-3">Description (RW)</label>
                                                <textarea name="description_rw" class="form-control" rows="3">{{ old('description_rw') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button class="btn btn-primary">Save Category</button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
