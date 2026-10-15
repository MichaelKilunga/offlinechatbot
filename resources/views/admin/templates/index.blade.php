@extends("layouts.admin", ["title" => "Prompt Templates"])

@section("content")
    <div class="alert alert-info">
        <span class="alert-icon">ℹ️</span>
        <div>
            <strong>Live now:</strong>
            Swahili → {{ $activeByLanguage['sw']?->name ?? 'built-in default' }} ·
            English → {{ $activeByLanguage['en']?->name ?? 'built-in default' }}.
            Only one template can be active per language. Strict constraints (plain text, word limit, legal-aid routing) are always appended by the engine.
            <a href="{{ route('admin.templates.preview') }}" style="color:inherit; font-weight:600;">Open the playground →</a>
        </div>
    </div>

    <div class="grid-side">

        <!-- Create Template -->
        <div class="card" style="position:sticky; top:80px;">
            <div class="card-header"><div class="card-title">🧩 New template</div></div>
            <div class="card-body">
                <form action="{{ route('admin.templates.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Template name</label>
                        <input type="text" name="name" class="form-input" placeholder="e.g. Swahili v2 (shorter)" value="{{ old('name') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prompt template</label>
                        <textarea name="template" class="form-textarea" rows="8" required
                            placeholder="Wewe ni msaidizi wa kisheria nchini Tanzania. Muktadha: {context}. Swali: {user_input}">{{ old('template') }}</textarea>
                        <div class="form-hint">Placeholders: <span class="mono">{context}</span> (legal entry + nearby providers + official contacts), <span class="mono">{user_input}</span> (the question, required).</div>
                    </div>
                    <div class="grid-2" style="gap:1rem;">
                        <div class="form-group">
                            <label class="form-label">Temperature</label>
                            <input type="number" name="temperature" class="form-input" step="0.1" min="0" max="2" value="{{ old('temperature', 0.7) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Max tokens</label>
                            <input type="number" name="max_tokens" class="form-input" value="{{ old('max_tokens', 300) }}">
                        </div>
                    </div>
                    <div class="form-hint" style="margin-top:-.6rem; margin-bottom:1rem;">Note: the engine currently uses the global temperature and token cap from Settings. These values are kept per template for reference.</div>
                    <div class="form-group">
                        <label class="form-label">Tone</label>
                        <input type="text" name="tone" class="form-input" value="{{ old('tone', 'legal_educational') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Language</label>
                        <select name="language" class="form-select">
                            <option value="sw" {{ old('language', 'sw') === 'sw' ? 'selected' : '' }}>🇹🇿 Swahili</option>
                            <option value="en" {{ old('language') === 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-full">💾 Save as inactive draft</button>
                </form>
            </div>
        </div>

        <!-- Templates List -->
        <div class="stack">
            @forelse($templates as $tpl)
                <div class="card" style="border-left:3px solid {{ $tpl->is_active ? 'var(--teal)' : 'rgba(255,255,255,0.1)' }};">
                    <div class="card-header">
                        <div>
                            <div style="font-family:'Space Grotesk',sans-serif; font-size:1rem; font-weight:700; color:var(--gray-100);">{{ $tpl->name }}</div>
                            <div style="display:flex; gap:.5rem; margin-top:.4rem; flex-wrap:wrap;">
                                {!! $tpl->language === 'sw' ? '<span class="badge badge-amber">🇹🇿 Swahili</span>' : '<span class="badge badge-blue">🇬🇧 English</span>' !!}
                                <span class="badge badge-gray">{{ $tpl->tone }}</span>
                                <span class="badge badge-gray">T={{ $tpl->temperature }}</span>
                                <span class="badge badge-gray">{{ $tpl->max_tokens }} tokens</span>
                                @if($tpl->is_active)<span class="badge badge-green">✓ Live</span>@else<span class="badge badge-gray">Draft</span>@endif
                                @unless(str_contains($tpl->template, '{context}'))<span class="badge badge-red">no {context}</span>@endunless
                                <span class="muted small" style="align-self:center;">edited {{ $tpl->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="btn-row">
                            <a href="{{ route('admin.templates.preview', ['language' => $tpl->language]) }}" class="btn btn-ghost btn-sm">🧪 Test</a>
                            <a href="{{ route('admin.templates.edit', $tpl) }}" class="btn btn-ghost btn-sm">✏️ Edit</a>
                            <form action="{{ route('admin.templates.toggle', $tpl) }}" method="POST" onsubmit="return confirm('{{ $tpl->is_active ? 'Deactivate this template? The built-in default will be used until another is activated.' : 'Make this the live ' . $tpl->language . ' template? Any other active ' . $tpl->language . ' template will be deactivated.' }}')">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-sm {{ $tpl->is_active ? 'btn-danger' : 'btn-teal' }}">{{ $tpl->is_active ? '⏸ Deactivate' : '▶ Make live' }}</button>
                            </form>
                            <form action="{{ route('admin.templates.destroy', $tpl) }}" method="POST" onsubmit="return confirm('Delete this template permanently?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">🗑</button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <pre class="pre" style="max-height:220px;">{{ $tpl->template }}</pre>
                    </div>
                </div>
            @empty
                <div class="card">
                    <div class="empty-state">
                        <div class="big">🧩</div>
                        <div style="font-size:1rem; font-weight:600; margin-bottom:.5rem;">No templates yet</div>
                        <div style="font-size:.875rem;">The engine is using its built-in defaults. Create a template to customise the persona.</div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
@endsection
