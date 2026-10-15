@extends("layouts.admin", ["title" => "Edit Legal Entry"])

@section("content")
    <div style="max-width:820px; margin:0 auto;">
        <div class="card">
            <div class="card-header">
                <div class="card-title">✏️ {{ $curriculum->title }}</div>
                <a href="{{ route('admin.curriculum.index') }}" class="btn btn-ghost btn-sm">← Back</a>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.curriculum.update', $curriculum) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="form-group"><label class="form-label">Title</label><input type="text" name="title" class="form-input" value="{{ old('title', $curriculum->title) }}" required></div>
                    <div class="form-group"><label class="form-label">Content</label><textarea name="content" class="form-textarea" rows="12" required>{{ old('content', $curriculum->content) }}</textarea></div>
                    <div class="form-group"><label class="form-label">Summary</label><input type="text" name="summary" class="form-input" value="{{ old('summary', $curriculum->summary) }}"></div>
                    <div class="grid-2">
                        <div class="form-group"><label class="form-label">Tags (comma separated)</label><input type="text" name="tags" class="form-input" value="{{ old('tags', $curriculum->tags) }}"></div>
                        <div class="form-group"><label class="form-label">Language</label>
                            <select name="language" class="form-select">
                                <option value="sw" {{ old('language', $curriculum->language) === 'sw' ? 'selected' : '' }}>🇹🇿 Swahili</option>
                                <option value="en" {{ old('language', $curriculum->language) === 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Match keywords (comma separated)</label>
                        <input type="text" name="keywords" class="form-input" value="{{ old('keywords', implode(', ', (array) $curriculum->keywords)) }}">
                        <div class="form-hint">The engine injects this entry when a question contains any of these words (title words and tags are always added). Saved as: <span class="mono">{{ implode(', ', (array) $curriculum->keywords) }}</span></div>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span class="muted small">Created {{ $curriculum->created_at->format('d M Y') }} · Updated {{ $curriculum->updated_at->diffForHumans() }} · {{ $curriculum->is_active ? 'Active' : 'Inactive' }}</span>
                        <button class="btn btn-primary">💾 Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
