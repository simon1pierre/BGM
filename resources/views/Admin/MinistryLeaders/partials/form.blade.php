<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Name</label>
        <input
            type="text"
            name="name"
            value="{{ old('name', $ministryLeader->name ?? '') }}"
            class="form-control @error('name') is-invalid @enderror"
            required
        >
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Position / Title</label>
        <input
            type="text"
            name="position"
            value="{{ old('position', $ministryLeader->position ?? '') }}"
            class="form-control @error('position') is-invalid @enderror"
            placeholder="Senior Pastor, Prayer Coordinator..."
            required
        >
        @error('position')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Country</label>
        <input
            type="text"
            name="country"
            value="{{ old('country', $ministryLeader->country ?? '') }}"
            class="form-control @error('country') is-invalid @enderror"
            placeholder="Rwanda"
        >
        @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Role Type</label>
        <select name="role_type" class="form-select @error('role_type') is-invalid @enderror" required>
            <option value="leader" @selected(old('role_type', $ministryLeader->role_type ?? 'leader') === 'leader')>Leader</option>
            <option value="preacher" @selected(old('role_type', $ministryLeader->role_type ?? '') === 'preacher')>Preacher</option>
        </select>
        @error('role_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Email</label>
        <input
            type="email"
            name="email"
            value="{{ old('email', $ministryLeader->email ?? '') }}"
            class="form-control @error('email') is-invalid @enderror"
        >
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Phone / WhatsApp</label>
        <input
            type="text"
            name="phone"
            value="{{ old('phone', $ministryLeader->phone ?? '') }}"
            class="form-control @error('phone') is-invalid @enderror"
        >
        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Sort Order</label>
        <input
            type="number"
            name="sort_order"
            value="{{ old('sort_order', $ministryLeader->sort_order ?? 0) }}"
            class="form-control @error('sort_order') is-invalid @enderror"
            min="0"
            max="9999"
        >
        @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold d-block">Visible on Home</label>
        <div class="form-check form-switch mt-2">
            <input
                class="form-check-input"
                type="checkbox"
                role="switch"
                id="is_active"
                name="is_active"
                value="1"
                @checked(old('is_active', $ministryLeader->is_active ?? true))
            >
            <label class="form-check-label" for="is_active">Active</label>
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Photo</label>
        <input type="file" name="photo" accept="image/*" class="form-control @error('photo') is-invalid @enderror">
        @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    @if (!empty($ministryLeader?->photo_path))
        <div class="col-md-12">
            <div class="mt-2">
                <img src="{{ asset('storage/'.$ministryLeader->photo_path) }}" alt="{{ $ministryLeader->name }}" style="width:88px;height:88px;object-fit:cover;border-radius:999px;">
            </div>
        </div>
    @endif
</div>








