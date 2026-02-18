<div class="row g-4">
    <div class="col-md-8">
        <label class="form-label fw-semibold">Title</label>
        <input type="text" name="title" class="form-control" required value="{{ old('title', $event->title ?? '') }}">
        @error('title') <div class="text-danger fs-12">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Event Type</label>
        <select name="event_type" class="form-select" required>
            <option value="prayer_meeting" @selected(old('event_type', $event->event_type ?? 'prayer_meeting') === 'prayer_meeting')>Prayer Meeting</option>
            <option value="service" @selected(old('event_type', $event->event_type ?? '') === 'service')>Service</option>
            <option value="conference" @selected(old('event_type', $event->event_type ?? '') === 'conference')>Conference</option>
            <option value="other" @selected(old('event_type', $event->event_type ?? '') === 'other')>Other</option>
        </select>
        @error('event_type') <div class="text-danger fs-12">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Timezone</label>
        <input type="text" name="timezone" class="form-control" required value="{{ old('timezone', $event->timezone ?? 'UTC') }}" placeholder="Africa/Kigali">
        @error('timezone') <div class="text-danger fs-12">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Starts At</label>
        <input type="datetime-local" name="starts_at" class="form-control" required value="{{ old('starts_at', isset($event) && $event->starts_at ? $event->starts_at->format('Y-m-d\\TH:i') : '') }}">
        @error('starts_at') <div class="text-danger fs-12">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Ends At</label>
        <input type="datetime-local" name="ends_at" class="form-control" value="{{ old('ends_at', isset($event) && $event->ends_at ? $event->ends_at->format('Y-m-d\\TH:i') : '') }}">
        @error('ends_at') <div class="text-danger fs-12">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Venue</label>
        <input type="text" name="venue" class="form-control" value="{{ old('venue', $event->venue ?? '') }}">
        @error('venue') <div class="text-danger fs-12">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Location</label>
        <input type="text" name="location" class="form-control" value="{{ old('location', $event->location ?? '') }}">
        @error('location') <div class="text-danger fs-12">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-12">
        <label class="form-label fw-semibold">Live Platform</label>
        <select name="live_platform" class="form-select">
            <option value="">None</option>
            <option value="zoom" @selected(old('live_platform', $event->live_platform ?? '') === 'zoom')>Zoom</option>
            <option value="youtube" @selected(old('live_platform', $event->live_platform ?? '') === 'youtube')>YouTube Live</option>
            <option value="other" @selected(old('live_platform', $event->live_platform ?? '') === 'other')>Other</option>
        </select>
        @error('live_platform') <div class="text-danger fs-12">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-12">
        <label class="form-label fw-semibold">Live URL (Zoom or YouTube Live link)</label>
        <input type="url" name="live_url" class="form-control" value="{{ old('live_url', $event->live_url ?? '') }}">
        @error('live_url') <div class="text-danger fs-12">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-12">
        <label class="form-label fw-semibold">Registration URL</label>
        <input type="url" name="registration_url" class="form-control" value="{{ old('registration_url', $event->registration_url ?? '') }}">
        @error('registration_url') <div class="text-danger fs-12">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-12">
        <label class="form-label fw-semibold">Description</label>
        <textarea name="description" class="form-control" rows="6">{{ old('description', $event->description ?? '') }}</textarea>
        @error('description') <div class="text-danger fs-12">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Image</label>
        <input type="file" name="image" class="form-control" accept="image/*">
        @error('image') <div class="text-danger fs-12">{{ $message }}</div> @enderror
        @if (!empty($event?->image_path))
            <div class="mt-2">
                <img src="{{ asset('storage/'.$event->image_path) }}" alt="Event image" style="max-height:120px;border-radius:8px;">
            </div>
        @endif
    </div>
    <div class="col-md-6 d-flex align-items-center gap-4">
        <div class="form-check mt-4">
            <input type="hidden" name="is_published" value="0">
            <input type="checkbox" class="form-check-input" name="is_published" value="1" @checked(old('is_published', $event->is_published ?? true))>
            <label class="form-check-label">Published</label>
        </div>
        <div class="form-check mt-4">
            <input type="hidden" name="is_featured" value="0">
            <input type="checkbox" class="form-check-input" name="is_featured" value="1" @checked(old('is_featured', $event->is_featured ?? false))>
            <label class="form-check-label">Featured</label>
        </div>
    </div>
</div>
