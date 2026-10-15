@php $in = $m->direction === 'inbound'; @endphp
<div style="display:flex; justify-content:{{ $in ? 'flex-start' : 'flex-end' }};">
    <a href="{{ route('admin.conversations.show', $m) }}" style="text-decoration:none; color:inherit; max-width:85%; padding:.6rem .9rem; border-radius:12px; font-size:.8rem; line-height:1.5; white-space:pre-wrap; background:{{ $in ? 'rgba(16,185,129,0.12)' : 'rgba(59,130,246,0.12)' }}; border:1px solid {{ $current ? 'var(--amber)' : ($in ? 'rgba(16,185,129,0.25)' : 'rgba(59,130,246,0.25)') }};">{{ \Illuminate\Support\Str::limit($m->content, 400) }}<div class="muted" style="font-size:.66rem; margin-top:.3rem;">#{{ $m->id }} · {{ $m->created_at->format('d M H:i') }}</div></a>
</div>
