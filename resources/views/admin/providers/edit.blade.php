@extends("layouts.admin", ["title" => "Edit Provider"])

@section("content")
    <div style="max-width:720px; margin:0 auto;">
        <div class="card">
            <div class="card-header">
                <div class="card-title">✏️ {{ $provider->name }}</div>
                <a href="{{ route('admin.providers.index') }}" class="btn btn-ghost btn-sm">← Back</a>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.providers.update', $provider) }}" method="POST">
                    @csrf @method('PUT')
                    @include('admin.providers._fields', ['provider' => $provider])
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span class="muted small">Updated {{ $provider->updated_at->diffForHumans() }}</span>
                        <button class="btn btn-primary">💾 Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
