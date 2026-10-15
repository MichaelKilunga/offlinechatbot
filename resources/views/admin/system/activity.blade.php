@extends("layouts.admin", ["title" => "Activity Log"])

@section("content")
    <div class="card">
        <div class="card-header">
            <div><div class="card-title">📜 Admin activity</div><div class="card-subtitle">Every change made from this panel, newest first</div></div>
            <form method="GET" class="form-inline">
                <div class="form-group">
                    <select name="action" class="form-select">
                        <option value="">All areas</option>
                        @foreach($actions as $a)<option value="{{ $a }}" {{ request('action') === $a ? 'selected' : '' }}>{{ ucfirst($a) }}</option>@endforeach
                    </select>
                </div>
                <div class="form-group"><input type="text" name="q" class="form-input" placeholder="Search description…" value="{{ request('q') }}" style="width:220px;"></div>
                <button class="btn btn-ghost btn-sm">Filter</button>
                @if(request()->query())<a href="{{ route('admin.system.activity') }}" class="btn btn-ghost btn-sm">Clear</a>@endif
            </form>
        </div>
        <div style="overflow-x:auto;">
            <table class="data-table">
                <thead><tr><th>When</th><th>Admin</th><th>Action</th><th>Description</th><th>Details</th></tr></thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td class="small muted" style="white-space:nowrap;">{{ $log->created_at->format('d M Y') }}<br>{{ $log->created_at->format('H:i:s') }}</td>
                            <td class="small">{{ $log->admin }}<div class="muted" style="font-size:.68rem;">{{ $log->ip }}</div></td>
                            <td><span class="badge badge-gray mono">{{ $log->action }}</span></td>
                            <td>{{ $log->description }}
                                @if($log->target_type)<div class="muted" style="font-size:.7rem;">{{ $log->target_type }} #{{ $log->target_id }}</div>@endif
                            </td>
                            <td class="mono" style="font-size:.7rem; max-width:360px; color:var(--gray-500); word-break:break-all;">
                                @if($log->meta){{ \Illuminate\Support\Str::limit(json_encode($log->meta, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), 300) }}@else — @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty-state"><div class="big">📜</div>No activity recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">{{ $logs->links('vendor.pagination.admin-dark') }}</div>
    </div>
@endsection
