@extends("layouts.admin", ["title" => "Health & Tools"])

@section("content")
    <div class="grid-side" style="grid-template-columns: 1fr 380px;">
        <div class="stack">
            <div class="card">
                <div class="card-header">
                    <div><div class="card-title">🩺 Health checks</div><div class="card-subtitle">{{ collect($checks)->where('ok', false)->count() }} issue(s)</div></div>
                </div>
                <div class="card-body" style="padding:.5rem 1.5rem;">
                    @foreach($checks as $c)
                        <div class="check">
                            <span class="check-dot {{ $c['ok'] ? 'ok' : 'bad' }}"></span>
                            <div style="flex:1;">{{ $c['label'] }}<div class="muted" style="font-size:.72rem;">{{ $c['detail'] }}</div></div>
                            <span class="badge {{ $c['ok'] ? 'badge-green' : 'badge-red' }}">{{ $c['ok'] ? 'OK' : 'Attention' }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="grid-2">
                <div class="card">
                    <div class="card-header"><div class="card-title">Last activity</div></div>
                    <div class="card-body">
                        <dl class="kv" style="grid-template-columns: 130px 1fr;">
                            <dt>Last inbound</dt><dd>{{ $lastInbound ? $lastInbound->created_at->format('d M Y H:i:s') . ' (' . $lastInbound->created_at->diffForHumans() . ')' : 'never' }}</dd>
                            <dt>Last model call</dt><dd>{{ $lastModelCall ? $lastModelCall->created_at->format('d M Y H:i:s') . ' · ' . $lastModelCall->model : 'never' }}</dd>
                            <dt>Queue pending</dt><dd>{{ $queue['pending'] ?? 'n/a' }}@if($queue['oldest_pending']) <span class="muted small">(oldest {{ \Carbon\Carbon::createFromTimestamp($queue['oldest_pending'])->diffForHumans() }})</span>@endif</dd>
                            <dt>Queue failed</dt><dd style="{{ $queue['failed'] ? 'color:#fca5a5; font-weight:600;' : '' }}">{{ $queue['failed'] ?? 'n/a' }}</dd>
                        </dl>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header"><div class="card-title">Runtime</div></div>
                    <div class="card-body">
                        <dl class="kv" style="grid-template-columns: 130px 1fr;">
                            @foreach($runtime as $k => $v)<dt>{{ $k }}</dt><dd class="mono small">{{ $v }}</dd>@endforeach
                        </dl>
                    </div>
                </div>
            </div>

            @if($failedJobs->isNotEmpty())
                <div class="card" style="border-color:rgba(239,68,68,0.3);">
                    <div class="card-header"><div class="card-title">❌ Failed jobs (latest 10)</div></div>
                    <div style="overflow-x:auto;">
                        <table class="data-table">
                            <thead><tr><th>When</th><th>Queue</th><th>Error</th></tr></thead>
                            <tbody>
                                @foreach($failedJobs as $j)
                                    <tr>
                                        <td class="small" style="white-space:nowrap;">{{ $j->failed_at }}</td>
                                        <td class="small">{{ $j->queue }}</td>
                                        <td class="mono small" style="max-width:600px; color:#fca5a5;">{{ \Illuminate\Support\Str::limit(strtok($j->exception, "\n"), 220) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <div class="card">
                <div class="card-header"><div><div class="card-title">📄 Application log</div><div class="card-subtitle">Last lines of storage/logs/laravel.log</div></div></div>
                <div class="card-body"><pre class="pre" style="max-height:520px; font-size:.72rem;">{{ $logTail }}</pre></div>
            </div>
        </div>

        <div class="stack">
            <div class="card">
                <div class="card-header"><div class="card-title">🧰 Tools</div></div>
                <div class="card-body" style="display:flex; flex-direction:column; gap:.6rem;">
                    <form action="{{ route('admin.system.tool', 'clear-cache') }}" method="POST">@csrf<button class="btn btn-ghost btn-full btn-sm">🧹 Clear caches (app, view, config)</button></form>
                    <form action="{{ route('admin.system.tool', 'run-queue') }}" method="POST">@csrf<button class="btn btn-ghost btn-full btn-sm">▶ Process pending queue jobs now</button></form>
                    <form action="{{ route('admin.system.tool', 'retry-failed') }}" method="POST" onsubmit="return confirm('Retry all failed jobs?')">@csrf<button class="btn btn-teal btn-full btn-sm">↺ Retry all failed jobs</button></form>
                    <form action="{{ route('admin.system.tool', 'flush-failed') }}" method="POST" onsubmit="return confirm('Delete the failed-job list? They will not be retried.')">@csrf<button class="btn btn-danger btn-full btn-sm">✕ Clear failed-job list</button></form>
                    <div class="divider" style="margin:.6rem 0;"></div>
                    <form action="{{ route('admin.system.tool', 'purge-old-messages') }}" method="POST" onsubmit="return confirm('Permanently delete all messages older than ' + this.days.value + ' days? Export first if you need them.')">
                        @csrf
                        <label class="form-label">Purge messages older than (days)</label>
                        <div style="display:flex; gap:.5rem;">
                            <input type="number" name="days" class="form-input sm" value="180" min="7" style="width:100px;">
                            <button class="btn btn-danger btn-sm" style="flex:1;">🗑 Purge</button>
                        </div>
                        <div class="form-hint">Model logs attached to those messages are removed too. <a href="{{ route('admin.conversations.export') }}" style="color:var(--blue-light);">Export everything first</a>.</div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><div class="card-title">🗄️ Database</div></div>
                <table class="data-table">
                    <tbody>
                        @foreach($tables as $label => $count)
                            <tr><td>{{ $label }}</td><td style="text-align:right; font-weight:600;">{{ number_format($count) }}</td></tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
