@php
    $editing = isset($devotional);
    $devotional = $devotional ?? null;
    $rawValue = function (string $field, $default = '') use ($editing, $devotional) {
        if (!$editing) {
            return $default;
        }

        return $devotional->getRawOriginal($field) ?? $default;
    };
@endphp

<div class="row g-4">
    <div class="col-md-8">
        <label class="form-label fw-semibold">Title</label>
        <input type="text" name="title" value="{{ old('title', $rawValue('title')) }}" class="form-control" required>
        @error('title') <div class="text-danger fs-12">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Publish Date</label>
        <input type="date" name="published_at" value="{{ old('published_at', optional($devotional->published_at ?? null)->format('Y-m-d')) }}" class="form-control">
        @error('published_at') <div class="text-danger fs-12">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Cover Image (optional)</label>
        <input type="file" name="cover_image" class="form-control" accept="image/*">
        @if ($editing && !empty($devotional->cover_image))
            <div class="mt-2">
                <img src="{{ $devotional->cover_image_url }}" alt="Cover" style="width: 110px; height: 145px; object-fit: cover; border-radius: 8px;">
            </div>
        @endif
        @error('cover_image') <div class="text-danger fs-12">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Author</label>
        <input type="text" name="author" value="{{ old('author', $devotional->author ?? '') }}" class="form-control">
        @error('author') <div class="text-danger fs-12">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Bible Reference</label>
        <input type="text" name="scripture_reference" value="{{ old('scripture_reference', $devotional->scripture_reference ?? '') }}" class="form-control" placeholder="e.g. John 3:16">
        @error('scripture_reference') <div class="text-danger fs-12">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Summary</label>
        <textarea name="excerpt" class="form-control" rows="4">{{ old('excerpt', $rawValue('excerpt')) }}</textarea>
        @error('excerpt') <div class="text-danger fs-12">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Sort Order</label>
        <input type="number" min="0" name="sort_order" value="{{ old('sort_order', $devotional->sort_order ?? 0) }}" class="form-control">
        @error('sort_order') <div class="text-danger fs-12">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-12">
        <label class="form-label fw-semibold">Main Content</label>
        <textarea name="body" class="form-control" rows="10" required>{{ old('body', $rawValue('body')) }}</textarea>
        @error('body') <div class="text-danger fs-12">{{ $message }}</div> @enderror
    </div>

    <div class="col-md-12">
        <div class="card border border-dashed">
            <div class="card-body">
                <div class="fw-semibold mb-3">Translations (EN / FR / RW)</div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Title (EN)</label>
                        <input type="text" name="title_en" value="{{ old('title_en', $translations['en']->title ?? '') }}" class="form-control" required>
                        @error('title_en') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                        <label class="form-label fw-semibold mt-3">Summary (EN)</label>
                        <textarea name="excerpt_en" class="form-control" rows="3">{{ old('excerpt_en', $translations['en']->excerpt ?? '') }}</textarea>
                        @error('excerpt_en') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                        <label class="form-label fw-semibold mt-3">Content (EN)</label>
                        <textarea name="body_en" class="form-control" rows="8" required>{{ old('body_en', $translations['en']->body ?? '') }}</textarea>
                        @error('body_en') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Title (FR)</label>
                        <input type="text" name="title_fr" value="{{ old('title_fr', $translations['fr']->title ?? '') }}" class="form-control" required>
                        @error('title_fr') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                        <label class="form-label fw-semibold mt-3">Summary (FR)</label>
                        <textarea name="excerpt_fr" class="form-control" rows="3">{{ old('excerpt_fr', $translations['fr']->excerpt ?? '') }}</textarea>
                        @error('excerpt_fr') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                        <label class="form-label fw-semibold mt-3">Content (FR)</label>
                        <textarea name="body_fr" class="form-control" rows="8" required>{{ old('body_fr', $translations['fr']->body ?? '') }}</textarea>
                        @error('body_fr') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Title (RW)</label>
                        <input type="text" name="title_rw" value="{{ old('title_rw', $translations['rw']->title ?? '') }}" class="form-control" required>
                        @error('title_rw') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                        <label class="form-label fw-semibold mt-3">Summary (RW)</label>
                        <textarea name="excerpt_rw" class="form-control" rows="3">{{ old('excerpt_rw', $translations['rw']->excerpt ?? '') }}</textarea>
                        @error('excerpt_rw') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                        <label class="form-label fw-semibold mt-3">Content (RW)</label>
                        <textarea name="body_rw" class="form-control" rows="8" required>{{ old('body_rw', $translations['rw']->body ?? '') }}</textarea>
                        @error('body_rw') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 d-flex align-items-center gap-4">
        <div>
            <input type="hidden" name="is_published" value="0">
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="is_published" value="1" @checked(old('is_published', $devotional->is_published ?? true))>
                <label class="form-check-label">Published</label>
            </div>
        </div>
        <div>
            <input type="hidden" name="featured" value="0">
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="featured" value="1" @checked(old('featured', $devotional->featured ?? false))>
                <label class="form-check-label">Featured</label>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card border border-dashed">
            <div class="card-body">
                <div class="form-check mb-3">
                    <input type="hidden" name="notify_subscribers" value="0">
                    <input class="form-check-input" type="checkbox" name="notify_subscribers" value="1" id="notifySubscribersDevotional" @checked(old('notify_subscribers'))>
                    <label class="form-check-label fw-semibold" for="notifySubscribersDevotional">Notify subscribers about this devotional</label>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Notify Target</label>
                        <select name="notify_target" class="form-select">
                            <option value="all" @selected(old('notify_target', 'all') === 'all')>All active subscribers</option>
                            <option value="custom" @selected(old('notify_target') === 'custom')>Custom email list</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Custom Emails (comma separated)</label>
                        <input type="text" name="notify_emails" value="{{ old('notify_emails') }}" class="form-control" placeholder="user1@example.com, user2@example.com">
                        @error('notify_emails') <div class="text-danger fs-12">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-semibold">Extra Message</label>
                        <textarea name="notify_message" class="form-control" rows="3" placeholder="Add short encouragement or context...">{{ old('notify_message') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-4 d-flex gap-2">
    <button class="btn btn-primary">{{ $editing ? 'Update Devotional' : 'Save Devotional' }}</button>
    <a href="{{ route('admin.devotionals.index') }}" class="btn btn-light">Cancel</a>
</div>
