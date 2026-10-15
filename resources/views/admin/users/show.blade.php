@extends("layouts.admin", ["title" => "User " . ($user->phone_number ?? $user->email ?? $user->id)])

@section("content")
    <div style="margin-bottom:1rem;"><a href="{{ route('admin.users.index') }}" class="btn btn-ghost btn-sm">← All users</a></div>

    <div class="grid-side" style="grid-template-columns: 320px 1fr;">
        <div class="stack">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{ $user->phone_number ?? $user->email ?? ('User #' . $user->id) }}</div>
                    @if($user->is_banned)
                        <span class="badge badge-red">🚫 Banned</span>
                    @elseif($user->abuse_count > 0)
                        <span class="badge badge-amber">⚠️ {{ $user->abuse_count }} strikes</span>
                    @else
                        <span class="badge badge-green">OK</span>
                    @endif
                </div>
                <div class="card-body">
                    <dl class="kv" style="grid-template-columns: 110px 1fr;">
                        <dt>Name</dt><dd>{{ $user->name ?? '—' }}</dd>
                        <dt>Email</dt><dd>{{ $user->email ?? '—' }}</dd>
                        <dt>Role</dt><dd>{{ $user->role ?? 'user' }}{{ $user->is_admin ? ' (admin)' : '' }}</dd>
                        <dt>Joined</dt><dd>{{ $user->created_at->format('d M Y H:i') }}</dd>
                        <dt>First message</dt><dd>{{ $totals['first'] ? \Carbon\Carbon::parse($totals['first'])->format('d M Y H:i') : '—' }}</dd>
                        <dt>Last message</dt><dd>{{ $totals['last'] ? \Carbon\Carbon::parse($totals['last'])->diffForHumans() : '—' }}</dd>
                        <dt>Messages</dt><dd>{{ $totals['messages'] }} ({{ $totals['inbound'] }} questions)</dd>
                        <dt>Tokens used</dt><dd>{{ number_format($totals['tokens']) }}</dd>
                        <dt>Strikes</dt><dd>{{ $user->abuse_count }} / {{ \App\Services\ModerationService::banThreshold() }} before ban</dd>
                    </dl>
                </div>
                <div class="card-footer" style="display:flex; flex-direction:column; gap:.5rem;">
                    <form action="{{ route('admin.users.ban', $user) }}" method="POST" onsubmit="return confirm('{{ $user->is_banned ? 'Unban' : 'Ban' }} this user?')">
                        @csrf @method('PATCH')
                        @unless($user->is_banned)
                            <input type="text" name="reason" class="form-input sm" placeholder="Reason (optional, kept in activity log)" style="margin-bottom:.5rem;">
                        @endunless
                        <button class="btn btn-full btn-sm {{ $user->is_banned ? 'btn-teal' : 'btn-danger' }}">{{ $user->is_banned ? '✓ Unban user' : '🚫 Ban user' }}</button>
                    </form>
                    @if($user->abuse_count > 0)
                        <form action="{{ route('admin.users.reset', $user) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="btn btn-ghost btn-full btn-sm">↺ Reset strikes to 0</button>
                        </form>
                    @endif
                    <a href="{{ route('admin.conversations.export', ['phone' => $user->phone_number]) }}" class="btn btn-ghost btn-full btn-sm">⬇ Export conversation (CSV)</a>
                    @unless($user->is_admin)
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Permanently delete this user and ALL their messages? This cannot be undone.')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-full btn-sm">🗑 Delete user &amp; history</button>
                        </form>
                    @endunless
                </div>
            </div>

            @if($activity->isNotEmpty())
                <div class="card">
                    <div class="card-header"><div class="card-title">Admin actions on this user</div></div>
                    <div class="card-body" style="padding:.5rem 1.2rem;">
                        @foreach($activity as $log)
                            <div style="padding:.5rem 0; border-bottom:1px solid rgba(255,255,255,0.05); font-size:.78rem;">
                                <div>{{ $log->description }}</div>
                                <div class="muted" style="font-size:.7rem;">{{ $log->admin }} · {{ $log->created_at->format('d M Y H:i') }}@if(!empty($log->meta['reason'])) · reason: {{ $log->meta['reason'] }}@endif</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="card">
            <div class="card-header">
                <div><div class="card-title">Conversation</div><div class="card-subtitle">Newest first</div></div>
            </div>
            <div class="card-body" style="display:flex; flex-direction:column; gap:.8rem;">
                @forelse($messages as $m)
                    @php $in = $m->direction === 'inbound'; @endphp
                    <div style="display:flex; justify-content:{{ $in ? 'flex-start' : 'flex-end' }};">
                        <div style="max-width:78%; padding:.7rem 1rem; border-radius:14px; font-size:.85rem; line-height:1.5; background:{{ $in ? 'rgba(16,185,129,0.12)' : 'rgba(59,130,246,0.12)' }}; border:1px solid {{ $in ? 'rgba(16,185,129,0.25)' : 'rgba(59,130,246,0.25)' }}; white-space:pre-wrap;">{{ $m->content }}
                            <div class="muted" style="font-size:.68rem; margin-top:.4rem; display:flex; gap:.6rem; align-items:center; white-space:normal;">
                                <span>{{ $m->created_at->format('d M Y H:i:s') }}</span>
                                @if($m->aiLog)<span>· {{ $m->aiLog->model }} · {{ number_format($m->aiLog->total_tokens) }} tok</span>@endif
                                <a href="{{ route('admin.conversations.show', $m) }}" style="color:var(--blue-light); text-decoration:none;">details</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state"><div class="big">📭</div>No messages yet.</div>
                @endforelse
            </div>
            <div class="card-footer">{{ $messages->links('vendor.pagination.admin-dark') }}</div>
        </div>
    </div>
@endsection
