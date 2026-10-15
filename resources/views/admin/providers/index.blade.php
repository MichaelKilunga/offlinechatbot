@extends("layouts.admin", ["title" => "Legal Aid Providers"])

@section("content")
    <div class="stats-grid">
        <div class="stat-card"><div class="stat-label">Providers</div><div class="stat-value">{{ number_format($counts['all']) }}</div><div class="stat-sub">across {{ $counts['regions'] }} regions</div></div>
        <a href="{{ route('admin.providers.index', ['missing' => 'contact']) }}" class="stat-card"><div class="stat-label">No phone or email</div><div class="stat-value">{{ $counts['no_contact'] }}</div><div class="stat-sub">citizens can't reach these</div></a>
        <a href="{{ route('admin.providers.index', ['missing' => 'district']) }}" class="stat-card"><div class="stat-label">No district</div><div class="stat-value">{{ $counts['no_district'] }}</div><div class="stat-sub">weaker location matching</div></a>
        <div class="stat-card"><div class="stat-label">Last sync / edit</div><div class="stat-value" style="font-size:1.1rem; margin-top:.4rem;">{{ $counts['last_sync'] ? \Carbon\Carbon::parse($counts['last_sync'])->diffForHumans() : 'never' }}</div></div>
    </div>

    <div class="grid-side">
        <div class="stack">
            <div class="card">
                <div class="card-header"><div class="card-title">🔄 Sync from mslac.or.tz</div></div>
                <div class="card-body">
                    <form action="{{ route('admin.providers.sync') }}" method="POST" onsubmit="this.querySelector('button').disabled = true; this.querySelector('button').textContent = 'Syncing… this can take a minute';">
                        @csrf
                        <div class="form-group"><label class="form-label">Pages to fetch</label><input type="number" name="pages" class="form-input" value="16" min="1" max="50"><div class="form-hint">Runs the scraper now and updates providers by name. Existing records are refreshed, nothing is deleted.</div></div>
                        <button class="btn btn-blue btn-full">🔄 Run sync now</button>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><div class="card-title">➕ Add provider</div></div>
                <div class="card-body">
                    <form action="{{ route('admin.providers.store') }}" method="POST">
                        @csrf
                        @include('admin.providers._fields', ['provider' => null])
                        <button class="btn btn-primary btn-full">💾 Save provider</button>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header"><div class="card-title">📤 Import / export CSV</div></div>
                <div class="card-body">
                    <form action="{{ route('admin.providers.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group"><input type="file" name="csv_file" class="form-input" accept=".csv,.txt" required><div class="form-hint">Header row: name, region, district, location, phone, email, language. Matched by name.</div></div>
                        <button class="btn btn-ghost btn-full btn-sm">⬆ Import CSV</button>
                    </form>
                    <a href="{{ route('admin.providers.export') }}" class="btn btn-ghost btn-full btn-sm" style="margin-top:.6rem;">⬇ Export all as CSV</a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div><div class="card-title">🏢 Providers</div><div class="card-subtitle">{{ $providers->total() }} match</div></div>
                <form method="GET" class="form-inline">
                    <div class="form-group"><input type="text" name="q" class="form-input" placeholder="Name, address, phone…" value="{{ request('q') }}" style="width:200px;"></div>
                    <div class="form-group">
                        <select name="region" class="form-select">
                            <option value="">All regions</option>
                            @foreach($regions as $r)<option value="{{ $r }}" {{ request('region') === $r ? 'selected' : '' }}>{{ $r }}</option>@endforeach
                        </select>
                    </div>
                    <input type="hidden" name="missing" value="{{ request('missing') }}">
                    <button class="btn btn-ghost btn-sm">Filter</button>
                    @if(request()->query())<a href="{{ route('admin.providers.index') }}" class="btn btn-ghost btn-sm">Clear</a>@endif
                </form>
            </div>
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead><tr><th>Name</th><th>Region / District</th><th>Location</th><th>Contact</th><th></th></tr></thead>
                    <tbody>
                        @forelse($providers as $p)
                            <tr>
                                <td style="font-weight:600; color:var(--gray-200); max-width:240px;">{{ $p->name }}</td>
                                <td class="small">{{ $p->region ?? '—' }}<div class="muted">{{ $p->district ?? 'no district' }}</div></td>
                                <td class="muted small" style="max-width:280px;">{{ \Illuminate\Support\Str::limit($p->location, 120) }}</td>
                                <td class="small">
                                    @if($p->phone)<div>📞 {{ $p->phone }}</div>@endif
                                    @if($p->email)<div>✉️ {{ $p->email }}</div>@endif
                                    @if(!$p->phone && !$p->email)<span class="badge badge-red">no contact</span>@endif
                                </td>
                                <td><div class="btn-row">
                                    <a href="{{ route('admin.providers.edit', $p) }}" class="btn btn-ghost btn-xs">✏️</a>
                                    <form action="{{ route('admin.providers.destroy', $p) }}" method="POST" onsubmit="return confirm('Delete this provider?')">@csrf @method('DELETE')<button class="btn btn-danger btn-xs">🗑</button></form>
                                </div></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="empty-state"><div class="big">🏢</div>No providers match. Run a sync or add one manually.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">{{ $providers->links('vendor.pagination.admin-dark') }}</div>
        </div>
    </div>
@endsection
