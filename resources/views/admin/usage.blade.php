@extends("layouts.admin", ["title" => "Usage & Insights"])

@section("content")
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.2rem; flex-wrap:wrap; gap:.8rem;">
        <div class="tabs">
            @foreach([7, 14, 30, 90] as $d)
                <a href="{{ route('admin.usage.index', ['days' => $d]) }}" class="tab {{ $days === $d ? 'active' : '' }}">Last {{ $d }} days</a>
            @endforeach
        </div>
        <div class="muted small">All time: {{ number_format($summary['all_calls']) }} calls · {{ number_format($summary['all_time']) }} tokens</div>
    </div>

    <div class="stats-grid">
        <div class="stat-card"><div class="stat-label">Model calls</div><div class="stat-value">{{ number_format($summary['calls']) }}</div><div class="stat-sub">in {{ $days }} days</div></div>
        <div class="stat-card"><div class="stat-label">Total tokens</div><div class="stat-value">{{ number_format($summary['total']) }}</div><div class="stat-sub">{{ number_format($summary['prompt']) }} in · {{ number_format($summary['completion']) }} out</div></div>
        <div class="stat-card"><div class="stat-label">Avg per call</div><div class="stat-value">{{ number_format($summary['avg']) }}</div><div class="stat-sub">max {{ number_format($summary['max']) }}</div></div>
        <div class="stat-card">
            <div class="stat-label">Estimated cost</div>
            <div class="stat-value">{{ $summary['has_prices'] ? '$' . number_format($summary['cost'], 4) : '—' }}</div>
            <div class="stat-sub">{!! $summary['has_prices'] ? 'from prices in Settings' : '<a href="' . route('admin.settings.index') . '" style="color:var(--blue-light);">Set token prices in Settings</a>' !!}</div>
        </div>
    </div>

    @php $maxTok = max(1, $daily->max('total')); $maxCalls = max(1, $daily->max('calls')); $maxHour = max(1, $hourly->max('count')); @endphp
    <div class="grid-2" style="margin-bottom:1.5rem;">
        <div class="card">
            <div class="card-header"><div><div class="card-title">Tokens per day</div><div class="card-subtitle">Prompt + completion</div></div></div>
            <div class="card-body">
                <div class="bars">
                    @foreach($daily as $d)
                        <div class="bar-col"><div class="bar-tip">{{ $d['label'] }}: {{ number_format($d['total']) }} tokens ({{ $d['calls'] }} calls)</div><div class="bar teal" style="height:{{ round($d['total'] / $maxTok * 100) }}%;"></div></div>
                    @endforeach
                </div>
                @if($days <= 14)<div class="bar-labels">@foreach($daily as $d)<span>{{ substr($d['label'], 4) }}</span>@endforeach</div>@endif
            </div>
        </div>
        <div class="card">
            <div class="card-header"><div><div class="card-title">Questions by hour of day</div><div class="card-subtitle">When citizens message, server time</div></div></div>
            <div class="card-body">
                <div class="bars">
                    @foreach($hourly as $h)
                        <div class="bar-col"><div class="bar-tip">{{ sprintf('%02d:00', $h['hour']) }}: {{ $h['count'] }}</div><div class="bar" style="height:{{ round($h['count'] / $maxHour * 100) }}%;"></div></div>
                    @endforeach
                </div>
                <div class="bar-labels">@foreach($hourly as $h)<span>{{ $h['hour'] % 3 === 0 ? $h['hour'] : '' }}</span>@endforeach</div>
            </div>
        </div>
    </div>

    <div class="grid-3">
        <div class="card">
            <div class="card-header"><div class="card-title">By model</div></div>
            <table class="data-table">
                <thead><tr><th>Model</th><th>Calls</th><th>Tokens</th><th>Avg</th></tr></thead>
                <tbody>
                    @forelse($byModel as $m)
                        <tr><td class="mono small">{{ $m['model'] }}</td><td>{{ $m['calls'] }}</td><td>{{ number_format($m['tokens']) }}</td><td>{{ number_format($m['avg']) }}</td></tr>
                    @empty
                        <tr><td colspan="4" class="empty-state">No model calls in this window.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card">
            <div class="card-header"><div class="card-title">Most active users</div></div>
            <table class="data-table">
                <thead><tr><th>User</th><th>Questions</th></tr></thead>
                <tbody>
                    @forelse($topUsers as $row)
                        <tr>
                            <td>@if($row->user)<a href="{{ route('admin.users.show', $row->user) }}">{{ $row->user->phone_number ?? $row->user->email }}</a>@if($row->user->is_banned) <span class="badge badge-red">banned</span>@endif @else <span class="muted">deleted</span>@endif</td>
                            <td>{{ $row->c }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" class="empty-state">No questions in this window.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card">
            <div class="card-header"><div><div class="card-title">What people ask about</div><div class="card-subtitle">Most frequent words in questions</div></div></div>
            <div class="card-body" style="display:flex; flex-wrap:wrap; gap:.4rem;">
                @forelse($words as $word => $count)
                    <a href="{{ route('admin.conversations.index', ['q' => $word, 'direction' => 'inbound']) }}" class="badge badge-gray" style="text-decoration:none;">{{ $word }} <span class="muted">{{ $count }}</span></a>
                @empty
                    <span class="muted small">Not enough data yet.</span>
                @endforelse
            </div>
        </div>
    </div>

    <div class="card" style="margin-top:1.5rem;">
        <div class="card-header"><div class="card-title">Daily breakdown</div></div>
        <div style="overflow-x:auto; max-height:420px; overflow-y:auto;">
            <table class="data-table">
                <thead><tr><th>Date</th><th>Calls</th><th>Prompt tokens</th><th>Completion tokens</th><th>Total</th></tr></thead>
                <tbody>
                    @foreach($daily->reverse() as $d)
                        <tr><td>{{ $d['date'] }}</td><td>{{ $d['calls'] }}</td><td>{{ number_format($d['prompt']) }}</td><td>{{ number_format($d['completion']) }}</td><td style="font-weight:600;">{{ number_format($d['total']) }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
