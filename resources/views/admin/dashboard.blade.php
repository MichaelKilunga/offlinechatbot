@extends("layouts.admin", ["title" => "Dashboard"])

@section("content")
    <!-- Status strip -->
    <div class="card" style="margin-bottom:1.6rem;">
        <div class="card-body" style="display:flex; gap:1.5rem; flex-wrap:wrap; align-items:center; padding:1rem 1.5rem;">
            <div class="check" style="border:none; padding:0;"><span class="check-dot {{ $aiEnabled ? 'ok' : 'bad' }}"></span> Engine {{ $aiEnabled ? 'answering' : 'paused' }}</div>
            <div class="check" style="border:none; padding:0;"><span class="check-dot {{ $moderationEnabled ? 'ok' : 'bad' }}"></span> Moderation {{ $moderationEnabled ? 'on' : 'off' }}</div>
            <div class="check" style="border:none; padding:0;"><span class="check-dot {{ $geminiConfigured ? 'ok' : 'bad' }}"></span> Model key {{ $geminiConfigured ? 'configured' : 'missing' }}</div>
            <div class="check" style="border:none; padding:0;"><span class="check-dot {{ $smsConfigured ? 'ok' : 'bad' }}"></span> SMS gateway {{ $smsConfigured ? 'configured' : 'missing' }}</div>
            <div class="check" style="border:none; padding:0;"><span class="check-dot {{ $stats['queue_failed'] ? 'bad' : 'ok' }}"></span> Queue: {{ $stats['queue_pending'] }} pending, {{ $stats['queue_failed'] }} failed</div>
            <div class="muted small" style="margin-left:auto;">Last inbound: {{ $lastInbound ? \Carbon\Carbon::parse($lastInbound)->diffForHumans() : 'never' }}</div>
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <a href="{{ route('admin.users.index') }}" class="stat-card" style="border-color:rgba(59,130,246,0.25);">
            <div class="stat-label">Users</div>
            <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
            <div class="stat-sub">+{{ $stats['new_users_7d'] }} new · {{ $stats['active_users_7d'] }} active in 7 days</div>
            <div class="stat-icon">👤</div>
        </a>
        <a href="{{ route('admin.conversations.index') }}" class="stat-card" style="border-color:rgba(245,158,11,0.25);">
            <div class="stat-label">Messages</div>
            <div class="stat-value">{{ number_format($stats['total_messages']) }}</div>
            <div class="stat-sub">{{ $stats['messages_today'] }} today ({{ $stats['inbound_today'] }} questions)</div>
            <div class="stat-icon">💬</div>
        </a>
        <a href="{{ route('admin.usage.index') }}" class="stat-card" style="border-color:rgba(20,184,166,0.25);">
            <div class="stat-label">Tokens (7 days)</div>
            <div class="stat-value">{{ number_format($stats['tokens_7d']) }}</div>
            <div class="stat-sub">{{ number_format($stats['tokens_today']) }} today · {{ $stats['ai_calls_7d'] }} calls · avg {{ number_format($stats['avg_tokens']) }}</div>
            <div class="stat-icon">🔢</div>
        </a>
        <a href="{{ route('admin.users.index', ['status' => 'banned']) }}" class="stat-card" style="border-color:rgba(239,68,68,0.25);">
            <div class="stat-label">Moderation</div>
            <div class="stat-value">{{ $stats['banned_users'] }}</div>
            <div class="stat-sub">banned · {{ $stats['flagged_users'] }} with strikes</div>
            <div class="stat-icon">🚫</div>
        </a>
        <a href="{{ route('admin.curriculum.index') }}" class="stat-card">
            <div class="stat-label">Knowledge</div>
            <div class="stat-value">{{ $stats['curricula_active'] }}<span class="muted" style="font-size:1rem;">/{{ $stats['curricula'] }}</span></div>
            <div class="stat-sub">active legal entries · {{ number_format($stats['providers']) }} aid providers · {{ $stats['templates_active'] }} live templates</div>
            <div class="stat-icon">⚖️</div>
        </a>
        <a href="{{ route('admin.community.index', ['status' => 'pending']) }}" class="stat-card" style="{{ $stats['posts_pending'] ? 'border-color:rgba(245,158,11,0.4);' : '' }}">
            <div class="stat-label">Community</div>
            <div class="stat-value">{{ $stats['posts_pending'] }}</div>
            <div class="stat-sub">posts awaiting approval</div>
            <div class="stat-icon">🗣️</div>
        </a>
    </div>

    <!-- Charts -->
    <div class="grid-2" style="margin-bottom:1.6rem;">
        @php $maxMsg = max(1, $series->max('messages')); $maxTok = max(1, $series->max('tokens')); @endphp
        <div class="card">
            <div class="card-header">
                <div><div class="card-title">Questions received</div><div class="card-subtitle">Inbound messages per day, last 14 days</div></div>
                <div class="muted small">Total {{ number_format($series->sum('messages')) }}</div>
            </div>
            <div class="card-body">
                <div class="bars">
                    @foreach($series as $d)
                        <div class="bar-col">
                            <div class="bar-tip">{{ $d['label'] }}: {{ $d['messages'] }} questions</div>
                            <div class="bar" style="height:{{ round($d['messages'] / $maxMsg * 100) }}%;"></div>
                        </div>
                    @endforeach
                </div>
                <div class="bar-labels">@foreach($series as $d)<span>{{ substr($d['label'], 4) }}</span>@endforeach</div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div><div class="card-title">Token usage</div><div class="card-subtitle">Total tokens per day, last 14 days</div></div>
                <div class="muted small">Total {{ number_format($series->sum('tokens')) }}</div>
            </div>
            <div class="card-body">
                <div class="bars">
                    @foreach($series as $d)
                        <div class="bar-col">
                            <div class="bar-tip">{{ $d['label'] }}: {{ number_format($d['tokens']) }} tokens</div>
                            <div class="bar teal" style="height:{{ round($d['tokens'] / $maxTok * 100) }}%;"></div>
                        </div>
                    @endforeach
                </div>
                <div class="bar-labels">@foreach($series as $d)<span>{{ substr($d['label'], 4) }}</span>@endforeach</div>
            </div>
        </div>
    </div>

    <div class="grid-side" style="grid-template-columns: 1fr 320px;">
        <!-- Recent Interactions -->
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Recent Interactions</div>
                    <div class="card-subtitle">Live traffic from SMS and web chat</div>
                </div>
                <a href="{{ route('admin.conversations.index') }}" class="btn btn-ghost btn-sm">Open full log →</a>
            </div>
            <div class="card-body" style="padding:1rem 1.5rem; border-bottom:1px solid rgba(255,255,255,0.07);">
                <form method="GET" class="form-inline">
                    <div class="form-group"><input type="text" name="phone" class="form-input" placeholder="Phone" value="{{ request('phone') }}" style="width:150px;"></div>
                    <div class="form-group"><input type="text" name="q" class="form-input" placeholder="Contains text…" value="{{ request('q') }}" style="width:180px;"></div>
                    <div class="form-group">
                        <select name="direction" class="form-select">
                            <option value="">Both directions</option>
                            <option value="inbound" {{ request('direction') === 'inbound' ? 'selected' : '' }}>Inbound only</option>
                            <option value="outbound" {{ request('direction') === 'outbound' ? 'selected' : '' }}>Outbound only</option>
                        </select>
                    </div>
                    <div class="form-group"><input type="date" name="date" class="form-input" value="{{ request('date') }}"></div>
                    <button class="btn btn-ghost btn-sm">Filter</button>
                    @if(request()->hasAny(['phone','q','direction','date']))<a href="{{ route('admin.dashboard') }}" class="btn btn-ghost btn-sm">Clear</a>@endif
                </form>
            </div>
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>User</th>
                            <th>Dir</th>
                            <th>Content</th>
                            <th>Model</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($interactions as $msg)
                            @php $isModerated = str_starts_with($msg->content, 'ONYO') || str_starts_with($msg->content, 'WARNING') || str_contains($msg->content, 'BANNED') || str_starts_with($msg->content, 'HUDUMA IMEFUNGIWA'); @endphp
                            <tr>
                                <td style="white-space:nowrap; color:var(--gray-500); font-size:.78rem;">
                                    {{ $msg->created_at->format("d M H:i") }}
                                    <div style="color:var(--gray-600); font-size:.7rem;">{{ $msg->created_at->diffForHumans(null, true) }} ago</div>
                                </td>
                                <td style="font-weight:600; white-space:nowrap;">
                                    @if($msg->user)
                                        <a href="{{ route('admin.users.show', $msg->user) }}">{{ $msg->user->phone_number ?? $msg->user->email }}</a>
                                        @if($msg->user->is_banned)
                                            <div style="font-size:.65rem; color:var(--danger); font-weight:700; margin-top:.2rem;">🚩 BANNED</div>
                                        @elseif($msg->user->abuse_count > 0)
                                            <div style="font-size:.65rem; color:var(--warning); font-weight:700; margin-top:.2rem;">⚠️ Strike {{ $msg->user->abuse_count }}</div>
                                        @endif
                                    @else
                                        <span class="muted">deleted user</span>
                                    @endif
                                </td>
                                <td>
                                    @if($msg->direction === 'inbound')
                                        <span class="badge badge-green">⬇ In</span>
                                    @else
                                        <span class="badge badge-blue">⬆ Out</span>
                                    @endif
                                </td>
                                <td style="max-width:360px;">
                                    <a href="{{ route('admin.conversations.show', $msg) }}" style="color:inherit; display:block; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:340px; {{ $isModerated ? 'color:var(--danger); font-style:italic;' : '' }}" title="{{ $msg->content }}">{{ $msg->content }}</a>
                                </td>
                                <td style="white-space:nowrap;">
                                    @if($msg->aiLog)
                                        <div style="font-size:.75rem; font-weight:600; color:var(--amber-light);">{{ $msg->aiLog->model }}</div>
                                        <div style="font-size:.7rem; color:var(--gray-500);">{{ number_format($msg->aiLog->total_tokens ?? 0) }} tokens</div>
                                    @elseif($isModerated)
                                        <span class="badge badge-red">Moderated</span>
                                    @else
                                        <span style="color:var(--gray-600);">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="empty-state"><div class="big">📭</div>No interactions match.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $interactions->links('vendor.pagination.admin-dark') }}
            </div>
        </div>

        <!-- Recent admin activity -->
        <div class="stack">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Recent admin activity</div>
                    <a href="{{ route('admin.system.activity') }}" class="btn btn-ghost btn-xs">All →</a>
                </div>
                <div class="card-body" style="padding:.5rem 1.2rem;">
                    @forelse($recentActivity as $log)
                        <div style="padding:.6rem 0; border-bottom:1px solid rgba(255,255,255,0.05); font-size:.8rem;">
                            <div style="color:var(--gray-200);">{{ $log->description }}</div>
                            <div class="muted" style="font-size:.7rem; margin-top:.15rem;">{{ $log->admin }} · {{ $log->created_at->diffForHumans() }}</div>
                        </div>
                    @empty
                        <div class="muted small" style="padding:1rem 0;">No admin actions recorded yet.</div>
                    @endforelse
                </div>
            </div>
            <div class="card">
                <div class="card-header"><div class="card-title">Quick actions</div></div>
                <div class="card-body" style="display:flex; flex-direction:column; gap:.5rem;">
                    <a href="{{ route('admin.templates.preview') }}" class="btn btn-ghost btn-sm">🧪 Test a question in the playground</a>
                    <a href="{{ route('admin.settings.index') }}" class="btn btn-ghost btn-sm">{{ $aiEnabled ? '⏸ Pause the engine' : '▶ Resume the engine' }}</a>
                    <a href="{{ route('admin.curriculum.index') }}" class="btn btn-ghost btn-sm">⚖️ Add legal content</a>
                    <a href="{{ route('admin.conversations.export') }}" class="btn btn-ghost btn-sm">⬇ Export all conversations (CSV)</a>
                    <a href="{{ route('admin.system.index') }}" class="btn btn-ghost btn-sm">🩺 System health &amp; tools</a>
                </div>
            </div>
        </div>
    </div>
@endsection
