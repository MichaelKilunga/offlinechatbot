@extends("layouts.admin", ["title" => "Prompt Templates"])

@section("content")
    <div class="flex flex-col gap-6 md:flex-row">
        <!-- Create Template -->
        <div class="md:w-1/3">
            <div class="sticky top-24 rounded-lg bg-white p-6 shadow">
                <h2 class="mb-4 text-xl font-bold">Create Template</h2>
                <form action="{{ route("admin.templates.store") }}" class="space-y-4" method="POST">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Name</label>
                        <input
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            name="name" placeholder="e.g., Default Swahili" required type="text">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Template</label>
                        <textarea
                            class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            name="template" placeholder="You are a teacher. Use context: {context}. User says: {user_input}" required
                            rows="8"></textarea>
                        <p class="mt-1 text-xs text-gray-500">Placeholders: {context}, {user_input}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Temp</label>
                            <input
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                name="temperature" step="0.1" type="number" value="0.7">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Max Tokens</label>
                            <input
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                name="max_tokens" type="number" value="200">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tone</label>
                        <input
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            name="tone" type="text" value="educational">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Language</label>
                        <select
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            name="language">
                            <option value="sw">Swahili</option>
                            <option value="en">English</option>
                        </select>
                    </div>
                    <button class="w-full rounded-md bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700"
                        type="submit">Save Template</button>
                </form>
            </div>
        </div>

        <!-- Templates List -->
        <div class="space-y-4 md:w-2/3">
            @forelse($templates as $tpl)
                <div
                    class="{{ $tpl->is_active ? "border-green-500" : "border-gray-300" }} rounded-lg border-l-4 bg-white p-6 shadow">
                    <div class="mb-4 flex items-start justify-between">
                        <div>
                            <h3 class="text-lg font-bold">{{ $tpl->name }}</h3>
                            <div class="mt-1 flex space-x-2">
                                <span
                                    class="rounded bg-blue-100 px-2 py-0.5 text-xs font-semibold uppercase text-blue-800">{{ $tpl->language }}</span>
                                <span
                                    class="rounded bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-800">{{ $tpl->tone }}</span>
                            </div>
                        </div>
                        <form action="{{ route("admin.templates.toggle", $tpl) }}" method="POST">
                            @csrf
                            @method("PATCH")
                            <button
                                class="{{ $tpl->is_active ? "bg-red-100 text-red-700" : "bg-green-100 text-green-700" }} rounded-md px-3 py-1 text-xs font-medium"
                                type="submit">
                                {{ $tpl->is_active ? "Deactivate" : "Activate" }}
                            </button>
                        </form>
                    </div>
                    <div class="overflow-x-auto whitespace-pre-wrap rounded bg-gray-50 p-3 font-mono text-sm text-gray-700">
                        {{ $tpl->template }}
                    </div>
                </div>
            @empty
                <div class="rounded-lg bg-white p-12 text-center text-gray-500 shadow">
                    No templates yet. Create your first teacher prompt!
                </div>
            @endforelse
        </div>
    </div>
@endsection
