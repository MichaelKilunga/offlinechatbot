@extends("layouts.admin", ["title" => "Conversations"])

@section("content")
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">💬 Message log</div>
                <div class="card-subtitle">{{ number_format($messages->total()) }} of {{ number_format($total) }} messages match</div>
            </div>
            <a href="{{ route('admin.conversations.export', request()->query()) }}" class="btn btn-teal btn-sm">⬇ Export matching as CSV</a>
        </div>
        <div class="card-body" style="padding:1rem 1.5rem; border-bottom:1px solid rgba(255,255,255,0.07);">
            <form method="GET" class="form-inline">
                <div class="form-group"><label class="form-label">Phone</label><input type="text" name="phone" class="form-input" value="{{ request('phone') }}" style="width:150px;"></div>
                <div class="form-group"><label class="form-label">Text contains</label><input type="text" name="q" class="form-input" value="{{ request('q') }}" style="width:200px;"></div>
                <div class="form-group"><label class="form-label">Direction</label>
                    <select name="direction" class="form-select">
                        <option value="">Both</option>
                        <option value="inbound" {{ request('direction') === 'inbound' ? 'selected' : '' }}>Inbound</option>
                        <option value="outbound" {{ request('direction') === 'outbound' ? 'selected' : '' }}>Outbound</option>
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="">Any</option>
                        <option value="ai" {{ request('type') === 'ai' ? 'selected' : '' }}>Model replies</option>
                        <option value="moderated" {{ request('type') === 'moderated' ? 'selected' : '' }}>Warnings &amp; bans</option>
                        <option value="no_ai" {{ request('type') === 'no_ai' ? 'selected' : '' }}>Outbound without model log</option>
                    </select>
                </div>
                <div class="form-group"><label class="form-label">From</label><input type="date" name="from" class="form-input" value="{{ request('from') }}"></div>
                <div class="form-group"><label class="form-label">To</label><input type="date" name="to" class="form-input" value="{{ request('to') }}"></div>
                <button class="btn btn-ghost btn-sm">Filter</button>
                @if(request()->query())<a href="{{ route('admin.conversations.index') }}" class="btn btn-ghost btn-sm">Clear</a>@endif
            </form>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr><th>Time</th><th>User</th><th>Dir</th><th>Content</th><th>Tokens</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($messages as $m)
                        @php $isModerated = str_starts_with($m->content, 'ONYO') || str_starts_with($m->content, 'WARNING') || str_starts_with($m->content, 'HUDUMA IMEFUNGIWA') || str_starts_with($m->content, 'SERVICE BANNED'); @endphp
                        <tr>
                            <td class="muted small" style="white-space:nowrap;">{{ $m->created_at->format('d M Y') }}<br>{{ $m->created_at->format('H:i:s') }}</td>
                            <td style="white-space:nowrap;">
                                @if($m->user)<a href="{{ route('admin.users.show', $m->user) }}">{{ $m->user->phone_number ?? $m->user->email }}</a>@else<span class="muted">deleted</span>@endif
                            </td>
                            <td>{!! $m->direction === 'inbound' ? '<span class="badge badge-green">⬇ In</span>' : '<span class="badge badge-blue">⬆ Out</span>' !!}</td>
                            <td style="max-width:520px; {{ $isModerated ? 'color:var(--danger); font-style:italic;' : '' }}">
                                <div style="overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">{{ $m->content }}</div>
                            </td>
                            <td class="small" style="white-space:nowrap;">
                                @if($m->aiLog)
                                    <div style="color:var(--amber-light); font-weight:600;">{{ number_format($m->aiLog->total_tokens) }}</div>
                                    <div class="muted" style="font-size:.68rem;">{{ $m->aiLog->prompt_tokens }} in / {{ $m->aiLog->completion_tokens }} out</div>
                                @else — @endif
                            </td>
                            <td><div class="btn-row">
                                <a href="{{ route('admin.conversations.show', $m) }}" class="btn btn-ghost btn-xs">Details</a>
                                <form action="{{ route('admin.conversations.destroy', $m) }}" method="POST" onsubmit="return confirm('Delete this message?')">@csrf @method('DELETE')<button class="btn btn-danger btn-xs">✕</button></form>
                            </div></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty-state"><div class="big">📭</div>No messages match these filters.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $messages->links('vendor.pagination.admin-dark') }}</div>
    </div>
@endsection
