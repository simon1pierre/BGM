@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Edit Category</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                    <li class="breadcrumb-item">Edit</li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            @php
                $translations = [
                    'en' => $category->translationFor('en'),
                    'fr' => $category->translationFor('fr'),
                    'rw' => $category->translationFor('rw'),
                ];
            @endphp
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                        @csrf
                        @method('PUT')
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Name</label>
                                <input type="text" name="name" value="{{ old('name', $category->name) }}" class="form-control" required>
                                @error('name') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Slug (optional)</label>
                                <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="form-control">
                                @error('slug') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Type</label>
                                <select name="type" class="form-select" required>
                                    <option value="">Select type</option>
                                    <option value="video" @selected(old('type', $category->type) === 'video')>Video</option>
                                    <option value="audio" @selected(old('type', $category->type) === 'audio')>Audio</option>
                                    <option value="document" @selected(old('type', $category->type) === 'document')>Document</option>
                                    <option value="all" @selected(old('type', $category->type) === 'all')>All content</option>
                                </select>
                                @error('type') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="form-check mt-4">
                                    <input type="hidden" name="is_active" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active))>
                                    <label class="form-check-label">Active</label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="form-control" rows="4">{{ old('description', $category->description) }}</textarea>
                                @error('description') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-12">
                                <div class="card border border-dashed">
                                    <div class="card-body">
                                        <div class="fw-semibold mb-3">Translations (EN / FR / RW)</div>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Name (EN)</label>
                                                <input type="text" name="name_en" value="{{ old('name_en', $translations['en']?->title) }}" class="form-control" required>
                                                @error('name_en') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                                                <label class="form-label fw-semibold mt-3">Description (EN)</label>
                                                <textarea name="description_en" class="form-control" rows="3">{{ old('description_en', $translations['en']?->description) }}</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Name (FR)</label>
                                                <input type="text" name="name_fr" value="{{ old('name_fr', $translations['fr']?->title) }}" class="form-control" required>
                                                @error('name_fr') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                                                <label class="form-label fw-semibold mt-3">Description (FR)</label>
                                                <textarea name="description_fr" class="form-control" rows="3">{{ old('description_fr', $translations['fr']?->description) }}</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Name (RW)</label>
                                                <input type="text" name="name_rw" value="{{ old('name_rw', $translations['rw']?->title) }}" class="form-control" required>
                                                @error('name_rw') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                                                <label class="form-label fw-semibold mt-3">Description (RW)</label>
                                                <textarea name="description_rw" class="form-control" rows="3">{{ old('description_rw', $translations['rw']?->description) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button class="btn btn-primary">Update Category</button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection








