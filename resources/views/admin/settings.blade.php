@extends("layouts.admin", ["title" => "System Settings"])

@section("content")
    <div style="max-width:680px;">
        <div class="card">
            <div class="card-header">
                <div class="card-title">⚙️ Global Application Settings</div>
                <div class="card-subtitle">Controls AI behaviour and platform configuration</div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.update') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Primary SMS Language</label>
                        <select name="primary_language" class="form-select">
                            <option value="sw" {{ ($settings['primary_language'] ?? 'sw') == 'sw' ? 'selected' : '' }}>
                                🇹🇿 Swahili (Kiswahili)
                            </option>
                            <option value="en" {{ ($settings['primary_language'] ?? 'sw') == 'en' ? 'selected' : '' }}>
                                🇬🇧 English
                            </option>
                        </select>
                        <div class="form-hint">Determines which prompt template and curriculum subset to prioritize for AI responses.</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Bot Name (Branding)</label>
                        <input type="text" name="bot_name" class="form-input"
                               value="{{ $settings['bot_name'] ?? env('APP_NAME') }}"
                               placeholder="HuruLearn">
                        <div class="form-hint">Used in admin branding only. Does not affect SMS responses.</div>
                    </div>

                    <div style="height:1px; background:rgba(255,255,255,0.07); margin:1.5rem 0;"></div>

                    <div style="display:flex; justify-content:flex-end;">
                        <button type="submit" class="btn btn-primary">💾 Save Configuration</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card" style="margin-top:1.5rem; border-color:rgba(59,130,246,0.2);">
            <div class="card-header" style="border-color:rgba(59,130,246,0.15);">
                <div class="card-title" style="color:var(--blue-light);">ℹ️ How Settings Work</div>
            </div>
            <div class="card-body">
                <ul style="list-style:none; display:flex; flex-direction:column; gap:.8rem;">
                    <li style="display:flex; gap:.75rem; align-items:flex-start; font-size:.875rem; color:var(--gray-400);">
                        <span style="color:var(--amber); font-weight:700; flex-shrink:0;">✦</span>
                        Primary language affects keyword matching priority across the AI engine.
                    </li>
                    <li style="display:flex; gap:.75rem; align-items:flex-start; font-size:.875rem; color:var(--gray-400);">
                        <span style="color:var(--amber); font-weight:700; flex-shrink:0;">✦</span>
                        Templates must be created for the selected language to work correctly.
                    </li>
                    <li style="display:flex; gap:.75rem; align-items:flex-start; font-size:.875rem; color:var(--gray-400);">
                        <span style="color:var(--amber); font-weight:700; flex-shrink:0;">✦</span>
                        Curriculum content for other languages is still searchable when keywords match.
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
