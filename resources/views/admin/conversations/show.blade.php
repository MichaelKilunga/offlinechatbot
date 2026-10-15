@extends("layouts.admin", ["title" => "Message #" . $message->id])

@section("content")
    <div style="margin-bottom:1rem; display:flex; gap:.5rem;">
        <a href="{{ route('admin.conversations.index') }}" class="btn btn-ghost btn-sm">← Message log</a>
        @if($message->user)<a href="{{ route('admin.users.show', $message->user) }}" class="btn btn-ghost btn-sm">👤 {{ $message->user->phone_number ?? $message->user->email }}</a>@endif
    </div>

    <div class="grid-2">
        <div class="stack">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{!! $message->direction === 'inbound' ? '<span class="badge badge-green">⬇ Inbound</span>' : '<span class="badge badge-blue">⬆ Outbound</span>' !!} &nbsp; Message #{{ $message->id }}</div>
                    <span class="muted small">{{ $message->created_at->format('d M Y H:i:s') }}</span>
                </div>
                <div class="card-body"><pre class="pre" style="max-height:none;">{{ $message->content }}</pre></div>
                <div class="card-footer">
                    <form action="{{ route('admin.conversations.destroy', $message) }}" method="POST" onsubmit="return confirm('Delete this message?')">@csrf @method('DELETE')<button class="btn btn-danger btn-sm">🗑 Delete message</button></form>
                </div>
            </div>

            @if($message->aiLog)
                <div class="card">
                    <div class="card-header"><div class="card-title">Model call</div><span class="badge badge-amber">{{ $message->aiLog->model }}</span></div>
                    <div class="card-body">
                        <dl class="kv" style="margin-bottom:1rem;">
                            <dt>Prompt tokens</dt><dd>{{ number_format($message->aiLog->prompt_tokens) }}</dd>
                            <dt>Completion tokens</dt><dd>{{ number_format($message->aiLog->completion_tokens) }}</dd>
                            <dt>Total tokens</dt><dd>{{ number_format($message->aiLog->total_tokens) }}</dd>
                            <dt>Logged at</dt><dd>{{ $message->aiLog->created_at->format('d M Y H:i:s') }}</dd>
                        </dl>
                        <div class="form-label">Question sent</div>
                        <pre class="pre" style="max-height:200px; margin-bottom:1rem;">{{ $message->aiLog->prompt }}</pre>
                        <div class="form-label">Raw model response</div>
                        <pre class="pre" style="max-height:300px;">{{ $message->aiLog->response }}</pre>
                        <div class="form-hint">Only the citizen's question is logged, not the full assembled prompt. Use the Prompt Playground to see the full prompt the engine would build for this question.</div>
                        <a href="{{ route('admin.templates.preview') }}?question={{ urlencode($message->aiLog->prompt) }}" class="btn btn-ghost btn-sm" style="margin-top:.8rem;">🧪 Rebuild in playground</a>
                    </div>
                </div>
            @endif
        </div>

        <div class="card">
            <div class="card-header"><div class="card-title">Conversation context</div><div class="card-subtitle">6 messages before and after</div></div>
            <div class="card-body" style="display:flex; flex-direction:column; gap:.6rem;">
                @foreach($before as $m)
                    @include('admin.conversations._bubble', ['m' => $m, 'current' => false])
                @endforeach
                @include('admin.conversations._bubble', ['m' => $message, 'current' => true])
                @foreach($after as $m)
                    @include('admin.conversations._bubble', ['m' => $m, 'current' => false])
                @endforeach
            </div>
        </div>
    </div>
@endsection
