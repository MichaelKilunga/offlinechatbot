<div class="form-group"><label class="form-label">Name</label><input type="text" name="name" class="form-input" value="{{ old('name', $provider?->name) }}" required></div>
<div class="grid-2" style="gap:1rem;">
    <div class="form-group"><label class="form-label">Region</label><input type="text" name="region" class="form-input" value="{{ old('region', $provider?->region) }}" placeholder="Dar es Salaam"></div>
    <div class="form-group"><label class="form-label">District</label><input type="text" name="district" class="form-input" value="{{ old('district', $provider?->district) }}" placeholder="KINONDONI"></div>
</div>
<div class="form-group"><label class="form-label">Location / address</label><textarea name="location" class="form-textarea" rows="3">{{ old('location', $provider?->location) }}</textarea><div class="form-hint">Include ward and street names: the engine matches citizens' location words against this text.</div></div>
<div class="grid-2" style="gap:1rem;">
    <div class="form-group"><label class="form-label">Phone</label><input type="text" name="phone" class="form-input" value="{{ old('phone', $provider?->phone) }}" placeholder="+2557…"></div>
    <div class="form-group"><label class="form-label">Email</label><input type="email" name="email" class="form-input" value="{{ old('email', $provider?->email) }}"></div>
</div>
<div class="form-group"><label class="form-label">Language</label>
    <select name="language" class="form-select">
        <option value="sw" {{ old('language', $provider?->language ?? 'sw') === 'sw' ? 'selected' : '' }}>Swahili</option>
        <option value="en" {{ old('language', $provider?->language) === 'en' ? 'selected' : '' }}>English</option>
    </select>
</div>
