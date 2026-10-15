@extends("layouts.admin", ["title" => "Prompt Playground"])

@section("content")
    <div class="grid-side" style="grid-template-columns: 380px 1fr;">
        <div class="card" style="position:sticky; top:80px;">
            <div class="card-header"><div><div class="card-title">🧪 Try a question</div><div class="card-subtitle">See exactly what the engine builds and, optionally, what the model answers</div></div></div>
            <div class="card-body">
                <form action="{{ route('admin.templates.preview.run') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Citizen's question</label>
                        <textarea name="question" class="form-textarea" rows="5" required>{{ request('question', $question) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Language</label>
                        <select name="language" class="form-select">
                            <option value="sw" {{ request('language', $language) === 'sw' ? 'selected' : '' }}>🇹🇿 Swahili</option>
                            <option value="en" {{ request('language', $language) === 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                        </select>
                        <div class="form-hint">In production the language comes from the Primary Language setting (or auto-detection).</div>
                    </div>
                    <div style="display:flex; flex-direction:column; gap:.5rem;">
                        <button class="btn btn-ghost btn-full" name="send" value="0">👁 Build prompt only (free)</button>
                        <button class="btn btn-primary btn-full" name="send" value="1" onclick="return confirm('This sends a real request to the model and uses tokens. Continue?')">🚀 Build and send to model</button>
                    </div>
                    <div class="form-hint" style="margin-top:.8rem;">Test runs are not stored as conversations and never go out by SMS. They are recorded in the activity log.</div>
                </form>
            </div>
        </div>

        <div class="stack">
            @if($reply)
                <div class="card" style="border-color:rgba(20,184,166,0.35);">
                    <div class="card-header">
                        <div class="card-title">Model reply</div>
                        <div class="btn-row">
                            @if($reply['model'])<span class="badge badge-amber">{{ $reply['model'] }}</span>@endif
                            <span class="badge badge-gray">{{ $reply['ms'] }} ms</span>
                            @if($reply['tokens'])<span class="badge badge-teal">{{ number_format($reply['tokens']['totalTokenCount'] ?? 0) }} tokens ({{ $reply['tokens']['promptTokenCount'] ?? 0 }} in / {{ $reply['tokens']['candidatesTokenCount'] ?? 0 }} out)</span>@endif
                        </div>
                    </div>
                    <div class="card-body">
                        <pre class="pre" style="max-height:none; color:var(--gray-100); font-family:'Inter',sans-serif; font-size:.9rem;">{{ $reply['text'] }}</pre>
                        @php $words = str_word_count(strip_tags($reply['text'])); $hasStar = str_contains($reply['text'], '*'); @endphp
                        <div style="display:flex; gap:.5rem; margin-top:.8rem; flex-wrap:wrap;">
                            <span class="badge badge-gray">{{ $words }} words</span>
                            <span class="badge badge-gray">{{ mb_strlen($reply['text']) }} chars ≈ {{ (int) ceil(mb_strlen($reply['text']) / 160) }} SMS parts</span>
                            <span class="badge {{ $hasStar ? 'badge-red' : 'badge-green' }}">{{ $hasStar ? 'contains asterisks' : 'plain text ✓' }}</span>
                        </div>
                    </div>
                </div>
            @endif

            @if($prompt)
                <div class="card">
                    <div class="card-header">
                        <div><div class="card-title">Assembled prompt</div><div class="card-subtitle">Template + legal context + nearby providers + official contacts + strict constraints</div></div>
                        <span class="badge badge-gray">{{ mb_strlen($prompt) }} chars</span>
                    </div>
                    <div class="card-body"><pre class="pre" style="max-height:720px;">{{ $prompt }}</pre></div>
                </div>
            @else
                <div class="card"><div class="empty-state"><div class="big">🧪</div>Type a question and build the prompt to inspect what the engine sends.</div></div>
            @endif
        </div>
    </div>
@endsection
