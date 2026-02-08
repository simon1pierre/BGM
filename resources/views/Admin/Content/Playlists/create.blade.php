@extends('layouts.admin.app')
@section('contents')
    <div class="nxl-content">
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Add Playlist</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.playlists.index') }}">Playlists</a></li>
                    <li class="breadcrumb-item">Create</li>
                </ul>
            </div>
        </div>

        <div class="main-content">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.playlists.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-8">
                                <label class="form-label fw-semibold">Title</label>
                                <input type="text" name="title" value="{{ old('title') }}" class="form-control" required>
                                @error('title') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Type</label>
                                <select name="type" id="playlistType" class="form-select" required>
                                    <option value="video" @selected(old('type') === 'video')>Video</option>
                                    <option value="audio" @selected(old('type') === 'audio')>Audio</option>
                                </select>
                                @error('type') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                            </div>
                            <div class="col-md-12">
                                <div class="card border border-dashed">
                                    <div class="card-body">
                                        <div class="fw-semibold mb-3">Translations (EN / FR / RW)</div>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Title (EN)</label>
                                                <input type="text" name="title_en" value="{{ old('title_en') }}" class="form-control" required>
                                                @error('title_en') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                                                <label class="form-label fw-semibold mt-3">Description (EN)</label>
                                                <textarea name="description_en" class="form-control" rows="3">{{ old('description_en') }}</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Title (FR)</label>
                                                <input type="text" name="title_fr" value="{{ old('title_fr') }}" class="form-control" required>
                                                @error('title_fr') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                                                <label class="form-label fw-semibold mt-3">Description (FR)</label>
                                                <textarea name="description_fr" class="form-control" rows="3">{{ old('description_fr') }}</textarea>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Title (RW)</label>
                                                <input type="text" name="title_rw" value="{{ old('title_rw') }}" class="form-control" required>
                                                @error('title_rw') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                                                <label class="form-label fw-semibold mt-3">Description (RW)</label>
                                                <textarea name="description_rw" class="form-control" rows="3">{{ old('description_rw') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Cover Image</label>
                                <input type="file" name="cover_image" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-6 d-flex align-items-center gap-4">
                                <div>
                                    <input type="hidden" name="is_published" value="0">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" name="is_published" value="1" checked>
                                        <label class="form-check-label">Published</label>
                                    </div>
                                </div>
                                <div>
                                    <input type="hidden" name="featured" value="0">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" name="featured" value="1">
                                        <label class="form-check-label">Featured</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h6 class="fw-semibold">Select Items</h6>
                            @error('items') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                            <div class="border rounded p-3" style="max-height: 360px; overflow-y: auto;">
                                <div id="videoItems">
                                    <div class="row">
                                        @foreach ($videos as $video)
                                            <div class="col-md-6">
                                                <label class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="items[]" value="{{ $video->id }}">
                                                    <span class="form-check-label">{{ $video->title }}</span>
                                                </label>
                                                <input type="number" class="form-control form-control-sm mt-1" name="orders[{{ $video->id }}]" placeholder="Order">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div id="audioItems" style="display:none;">
                                    <div class="row">
                                        @foreach ($audios as $audio)
                                            <div class="col-md-6">
                                                <label class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="items[]" value="{{ $audio->id }}">
                                                    <span class="form-check-label">{{ $audio->title }}</span>
                                                </label>
                                                <input type="number" class="form-control form-control-sm mt-1" name="orders[{{ $audio->id }}]" placeholder="Order">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button class="btn btn-primary">Save Playlist</button>
                            <a href="{{ route('admin.playlists.index') }}" class="btn btn-light">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const typeSelect = document.getElementById('playlistType');
        const videoItems = document.getElementById('videoItems');
        const audioItems = document.getElementById('audioItems');
        function toggleItems() {
            const type = typeSelect.value;
            videoItems.style.display = type === 'video' ? 'block' : 'none';
            audioItems.style.display = type === 'audio' ? 'block' : 'none';
        }
        typeSelect.addEventListener('change', toggleItems);
        toggleItems();
    </script>
@endsection
