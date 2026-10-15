@extends("layouts.admin", ["title" => "Legal Database"])

@section("content")
    <div class="grid-side">

        <div class="stack">
            <!-- Add entry -->
            <div class="card">
                <div class="card-header"><div class="card-title">✍️ Add entry manually</div></div>
                <div class="card-body">
                    <form action="{{ route('admin.curriculum.store') }}" method="POST">
                        @csrf
                        <div class="form-group"><label class="form-label">Title</label><input type="text" name="title" class="form-input" value="{{ old('title') }}" required placeholder="e.g. Haki za Mtuhumiwa Wakati wa Kukamatwa"></div>
                        <div class="form-group"><label class="form-label">Content</label><textarea name="content" class="form-textarea" rows="6" required placeholder="Full legal explanation the engine will quote…">{{ old('content') }}</textarea></div>
                        <div class="form-group"><label class="form-label">Summary (optional)</label><input type="text" name="summary" class="form-input" value="{{ old('summary') }}"></div>
                        <div class="form-group"><label class="form-label">Tags (comma separated)</label><input type="text" name="tags" class="form-input" value="{{ old('tags') }}" placeholder="jinai, polisi, dhamana"></div>
                        <div class="form-group"><label class="form-label">Extra match keywords (comma separated)</label><input type="text" name="keywords" class="form-input" value="{{ old('keywords') }}" placeholder="kukamatwa, mtuhumiwa, kimya"><div class="form-hint">Title words and tags are matched automatically. Add the words citizens actually type.</div></div>
                        <div class="form-group"><label class="form-label">Language</label>
                            <select name="language" class="form-select">
                                <option value="sw" {{ old('language', 'sw') === 'sw' ? 'selected' : '' }}>🇹🇿 Swahili</option>
                                <option value="en" {{ old('language') === 'en' ? 'selected' : '' }}>🇬🇧 English</option>
                            </select>
                        </div>
                        <button class="btn btn-primary btn-full">💾 Save entry</button>
                    </form>
                </div>
            </div>

            <!-- Import -->
            <div class="card">
                <div class="card-header"><div class="card-title">📤 Import file</div></div>
                <div class="card-body">
                    <form action="{{ route('admin.curriculum.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">CSV, TXT or JSON</label>
                            <input type="file" name="doc_file" required accept=".csv,.txt,.json" class="form-input">
                            <div class="form-hint">CSV columns: title, content, summary, tags.<br>JSON keys: title, content, summary, tags, keywords[], language.<br>TXT splits by blank lines.</div>
                        </div>
                        <div class="form-group"><label class="form-label">Default language</label>
                            <select name="language" class="form-select"><option value="sw">🇹🇿 Swahili</option><option value="en">🇬🇧 English</option></select>
                        </div>
                        <button class="btn btn-blue btn-full">⬆ Import</button>
                    </form>
                    <div class="divider"></div>
                    <div class="form-label">Backup / export</div>
                    <div class="btn-row">
                        <a href="{{ route('admin.curriculum.export') }}" class="btn btn-ghost btn-sm">⬇ All (JSON)</a>
                        <a href="{{ route('admin.curriculum.export', ['language' => 'sw']) }}" class="btn btn-ghost btn-sm">⬇ Swahili</a>
                        <a href="{{ route('admin.curriculum.export', ['language' => 'en']) }}" class="btn btn-ghost btn-sm">⬇ English</a>
                    </div>
                    <div class="form-hint">Exports re-import cleanly through the JSON importer.</div>
                </div>
            </div>
        </div>

        <!-- Entries Table -->
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">⚖️ Legal database entries</div>
                    <div class="card-subtitle">{{ $counts['all'] }} total · {{ $counts['sw'] }} Swahili · {{ $counts['en'] }} English · {{ $counts['inactive'] }} inactive</div>
                </div>
                <form method="GET" class="form-inline">
                    <div class="form-group"><input type="text" name="q" class="form-input" placeholder="Search title, content, tags…" value="{{ request('q') }}" style="width:220px;"></div>
                    <div class="form-group">
                        <select name="language" class="form-select">
                            <option value="">All languages</option>
                            <option value="sw" {{ request('language') === 'sw' ? 'selected' : '' }}>Swahili</option>
                            <option value="en" {{ request('language') === 'en' ? 'selected' : '' }}>English</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="status" class="form-select">
                            <option value="">Any status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <button class="btn btn-ghost btn-sm">Filter</button>
                </form>
            </div>

            <form action="{{ route('admin.curriculum.bulk') }}" method="POST" id="bulkForm" onsubmit="return confirmBulk()">
                @csrf
                <div style="overflow-x:auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th><input type="checkbox" onclick="document.querySelectorAll('.rowcheck').forEach(c => c.checked = this.checked)"></th>
                                <th>Title</th>
                                <th>Lang</th>
                                <th>Keywords</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($curriculums as $item)
                                <tr style="{{ $item->is_active ? '' : 'opacity:.55;' }}">
                                    <td><input type="checkbox" class="rowcheck" name="ids[]" value="{{ $item->id }}" form="bulkForm"></td>
                                    <td style="max-width:300px;">
                                        <div style="font-weight:600; color:var(--gray-200);">{{ $item->title }}</div>
                                        <div class="muted small" style="overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">{{ $item->summary ?: \Illuminate\Support\Str::limit($item->content, 140) }}</div>
                                    </td>
                                    <td>{!! $item->language === 'sw' ? '<span class="badge badge-amber">SW</span>' : '<span class="badge badge-blue">EN</span>' !!}</td>
                                    <td class="muted small" style="max-width:220px;">{{ implode(', ', array_slice((array) $item->keywords, 0, 8)) }}{{ count((array) $item->keywords) > 8 ? '…' : '' }}</td>
                                    <td>{!! $item->is_active ? '<span class="badge badge-green">Active</span>' : '<span class="badge badge-gray">Inactive</span>' !!}</td>
                                    <td>
                                        <div class="btn-row">
                                            <a href="{{ route('admin.curriculum.edit', $item) }}" class="btn btn-ghost btn-xs">✏️ Edit</a>
                                            <button type="submit" form="toggle-{{ $item->id }}" class="btn btn-ghost btn-xs">{{ $item->is_active ? '⏸ Disable' : '▶ Enable' }}</button>
                                            <button type="submit" form="delete-{{ $item->id }}" class="btn btn-danger btn-xs" onclick="return confirm('Delete this legal database entry?')">🗑</button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="empty-state"><div class="big">📭</div>No entries match. Add one or import a file.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem;">
                    <div class="form-inline">
                        <select name="action" class="form-select sm" style="width:auto;">
                            <option value="activate">Activate selected</option>
                            <option value="deactivate">Deactivate selected</option>
                            <option value="delete">Delete selected</option>
                        </select>
                        <button class="btn btn-ghost btn-sm">Apply to selected</button>
                    </div>
                    {{ $curriculums->links('vendor.pagination.admin-dark') }}
                </div>
            </form>

            @foreach($curriculums as $item)
                <form id="toggle-{{ $item->id }}" action="{{ route('admin.curriculum.toggle', $item) }}" method="POST" style="display:none;">@csrf @method('PATCH')</form>
                <form id="delete-{{ $item->id }}" action="{{ route('admin.curriculum.destroy', $item) }}" method="POST" style="display:none;">@csrf @method('DELETE')</form>
            @endforeach
        </div>
    </div>

    @push('scripts')
    <script>
        function confirmBulk() {
            const n = document.querySelectorAll('.rowcheck:checked').length;
            if (!n) { alert('Select at least one entry first.'); return false; }
            const action = document.querySelector('#bulkForm select[name=action]').value;
            return confirm(`${action} ${n} entr${n === 1 ? 'y' : 'ies'}?`);
        }
    </script>
    @endpush
@endsection
