@extends("layouts.admin", ["title" => "Users"])

@section("content")
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">👤 Users</div>
                <div class="card-subtitle">Everyone who has messaged via SMS or web chat</div>
            </div>
            <div class="tabs">
                <a href="{{ route('admin.users.index') }}" class="tab {{ !request('status') ? 'active' : '' }}">All ({{ $counts['all'] }})</a>
                <a href="{{ route('admin.users.index', ['status' => 'flagged']) }}" class="tab {{ request('status') === 'flagged' ? 'active' : '' }}">With strikes ({{ $counts['flagged'] }})</a>
                <a href="{{ route('admin.users.index', ['status' => 'banned']) }}" class="tab {{ request('status') === 'banned' ? 'active' : '' }}">Banned ({{ $counts['banned'] }})</a>
                <a href="{{ route('admin.users.index', ['status' => 'clean']) }}" class="tab {{ request('status') === 'clean' ? 'active' : '' }}">Clean</a>
                <a href="{{ route('admin.users.index', ['status' => 'admins']) }}" class="tab {{ request('status') === 'admins' ? 'active' : '' }}">Admins ({{ $counts['admins'] }})</a>
            </div>
        </div>
        <div class="card-body" style="padding:1rem 1.5rem; border-bottom:1px solid rgba(255,255,255,0.07);">
            <form method="GET" class="form-inline">
                <input type="hidden" name="status" value="{{ request('status') }}">
                <div class="form-group"><input type="text" name="q" class="form-input" placeholder="Search phone, name, email…" value="{{ request('q') }}" style="width:260px;"></div>
                <div class="form-group">
                    <select name="sort" class="form-select">
                        <option value="messages_max_created_at" {{ $sort === 'messages_max_created_at' ? 'selected' : '' }}>Sort: last active</option>
                        <option value="messages_count" {{ $sort === 'messages_count' ? 'selected' : '' }}>Sort: most messages</option>
                        <option value="abuse_count" {{ $sort === 'abuse_count' ? 'selected' : '' }}>Sort: most strikes</option>
                        <option value="created_at" {{ $sort === 'created_at' ? 'selected' : '' }}>Sort: newest</option>
                    </select>
                </div>
                <button class="btn btn-ghost btn-sm">Apply</button>
                @if(request()->hasAny(['q']))<a href="{{ route('admin.users.index', ['status' => request('status')]) }}" class="btn btn-ghost btn-sm">Clear</a>@endif
            </form>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Status</th>
                        <th>Messages</th>
                        <th>Last active</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $u)
                        <tr>
                            <td>
                                <a href="{{ route('admin.users.show', $u) }}" style="font-weight:600;">{{ $u->phone_number ?? $u->email ?? ('User #' . $u->id) }}</a>
                                @if($u->name)<div class="muted small">{{ $u->name }}</div>@endif
                                @if($u->is_admin)<span class="badge badge-teal" style="margin-top:.2rem;">admin</span>@endif
                            </td>
                            <td>
                                @if($u->is_banned)
                                    <span class="badge badge-red">🚫 Banned</span>
                                @elseif($u->abuse_count > 0)
                                    <span class="badge badge-amber">⚠️ {{ $u->abuse_count }} strike{{ $u->abuse_count > 1 ? 's' : '' }}</span>
                                @else
                                    <span class="badge badge-green">OK</span>
                                @endif
                            </td>
                            <td>{{ $u->messages_count }} <span class="muted small">({{ $u->inbound_count }} questions)</span></td>
                            <td class="muted small">{{ $u->messages_max_created_at ? \Carbon\Carbon::parse($u->messages_max_created_at)->diffForHumans() : '—' }}</td>
                            <td class="muted small">{{ $u->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="btn-row">
                                    <a href="{{ route('admin.users.show', $u) }}" class="btn btn-ghost btn-xs">View</a>
                                    <form action="{{ route('admin.users.ban', $u) }}" method="POST" onsubmit="return confirm('{{ $u->is_banned ? 'Unban' : 'Ban' }} this user?')">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-xs {{ $u->is_banned ? 'btn-teal' : 'btn-danger' }}">{{ $u->is_banned ? 'Unban' : 'Ban' }}</button>
                                    </form>
                                    @if($u->abuse_count > 0)
                                        <form action="{{ route('admin.users.reset', $u) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-ghost btn-xs">Reset strikes</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="empty-state"><div class="big">👤</div>No users match.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $users->links('vendor.pagination.admin-dark') }}</div>
    </div>
@endsection
