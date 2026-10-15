@extends("layouts.admin", ["title" => "Community Moderation"])

@section("content")
    <div class="stats-grid">
        <a href="{{ route('admin.community.index') }}" class="stat-card"><div class="stat-label">Posts</div><div class="stat-value">{{ $counts['posts'] }}</div></a>
        <a href="{{ route('admin.community.index', ['status' => 'pending']) }}" class="stat-card" style="{{ $counts['pending'] ? 'border-color:rgba(245,158,11,0.4);' : '' }}"><div class="stat-label">Hidden / pending</div><div class="stat-value">{{ $counts['pending'] }}</div></a>
        <a href="{{ route('admin.community.index', ['status' => 'pinned']) }}" class="stat-card"><div class="stat-label">Pinned</div><div class="stat-value">{{ $counts['pinned'] }}</div></a>
        <div class="stat-card"><div class="stat-label">Threads</div><div class="stat-value">{{ $counts['threads'] }}</div></div>
    </div>

    <div class="grid-side" style="grid-template-columns: 1fr 360px;">
        <div class="card">
            <div class="card-header">
                <div class="card-title">🗣️ Posts</div>
                <form method="GET" class="form-inline">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <div class="form-group"><input type="text" name="q" class="form-input" placeholder="Search content…" value="{{ request('q') }}" style="width:200px;"></div>
                    <div class="form-group">
                        <select name="thread" class="form-select">
                            <option value="">All threads</option>
                            @foreach($threads as $t)<option value="{{ $t->id }}" {{ (string) request('thread') === (string) $t->id ? 'selected' : '' }}>{{ $t->title }}</option>@endforeach
                        </select>
                    </div>
                    <button class="btn btn-ghost btn-sm">Filter</button>
                </form>
            </div>
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead><tr><th>Post</th><th>Author</th><th>Thread</th><th>Status</th><th>Actions</th></tr></thead>
                    <tbody>
                        @forelse($posts as $post)
                            <tr style="{{ $post->is_approved ? '' : 'background:rgba(245,158,11,0.05);' }}">
                                <td style="max-width:420px;"><div style="white-space:pre-wrap;">{{ \Illuminate\Support\Str::limit($post->content, 300) }}</div><div class="muted" style="font-size:.7rem; margin-top:.3rem;">#{{ $post->id }} · {{ $post->created_at->format('d M Y H:i') }}@if($post->parent_id) · reply to #{{ $post->parent_id }}@endif</div></td>
                                <td class="small">@if($post->user)<a href="{{ route('admin.users.show', $post->user) }}">{{ $post->user->name ?? $post->user->phone_number ?? $post->user->email }}</a>@if($post->user->is_banned) <span class="badge badge-red">banned</span>@endif @else <span class="muted">deleted</span> @endif</td>
                                <td class="small">{{ $post->thread?->title ?? '—' }}</td>
                                <td>
                                    {!! $post->is_approved ? '<span class="badge badge-green">Visible</span>' : '<span class="badge badge-amber">Hidden</span>' !!}
                                    @if($post->is_pinned)<span class="badge badge-blue">📌 Pinned</span>@endif
                                </td>
                                <td><div class="btn-row">
                                    <form action="{{ route('admin.community.posts.approve', $post) }}" method="POST">@csrf @method('PATCH')<button class="btn btn-xs {{ $post->is_approved ? 'btn-ghost' : 'btn-teal' }}">{{ $post->is_approved ? 'Hide' : '✓ Approve' }}</button></form>
                                    <form action="{{ route('admin.community.posts.pin', $post) }}" method="POST">@csrf @method('PATCH')<button class="btn btn-ghost btn-xs">{{ $post->is_pinned ? 'Unpin' : '📌 Pin' }}</button></form>
                                    <form action="{{ route('admin.community.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete this post and its replies?')">@csrf @method('DELETE')<button class="btn btn-danger btn-xs">🗑</button></form>
                                </div></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="empty-state"><div class="big">🗣️</div>No posts match.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">{{ $posts->links('vendor.pagination.admin-dark') }}</div>
        </div>

        <div class="card">
            <div class="card-header"><div class="card-title">Threads</div></div>
            <div class="card-body" style="padding:.5rem 1.2rem;">
                @forelse($threads as $t)
                    <div style="padding:.7rem 0; border-bottom:1px solid rgba(255,255,255,0.05);">
                        <div style="display:flex; justify-content:space-between; gap:.5rem; align-items:flex-start;">
                            <div>
                                <a href="{{ route('admin.community.index', ['thread' => $t->id]) }}" style="font-weight:600; color:var(--gray-100); text-decoration:none;">{{ $t->title }}</a>
                                <div class="muted" style="font-size:.7rem;">{{ $t->posts_count }} posts · {{ $t->members_count }} members · {{ $t->is_private ? 'private' : 'public' }}{{ $t->is_system ? ' · system' : '' }} · by {{ $t->creator?->name ?? $t->creator?->phone_number ?? '—' }}</div>
                            </div>
                            <div class="btn-row">
                                <a href="{{ route('community.show', $t) }}" target="_blank" class="btn btn-ghost btn-xs">↗</a>
                                @unless($t->is_system)
                                    <form action="{{ route('admin.community.threads.destroy', $t) }}" method="POST" onsubmit="return confirm('Delete thread \'{{ addslashes($t->title) }}\' and all its posts?')">@csrf @method('DELETE')<button class="btn btn-danger btn-xs">🗑</button></form>
                                @endunless
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="muted small" style="padding:1rem 0;">No threads yet.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
