@extends("layouts.admin", ["title" => "Curriculum Management"])

@section("content")
    <div class="flex flex-col gap-6 md:flex-row">
        <!-- Import Column -->
        <div class="md:w-1/3">
            <div class="sticky top-24 rounded-lg bg-white p-6 shadow">
                <h2 class="mb-4 text-xl font-bold">Import Curriculum</h2>
                <form action="{{ route("admin.curriculum.import") }}" class="space-y-4" enctype="multipart/form-data"
                    method="POST">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">CSV File</label>
                        <input
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:rounded-full file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100"
                            name="csv_file" required type="file">
                        <p class="mt-1 text-xs text-gray-500">Columns: title, content, summary, tags</p>
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
                        type="submit">Import Data</button>
                </form>
            </div>
        </div>

        <!-- List Column -->
        <div class="md:w-2/3">
            <div class="overflow-hidden rounded-lg bg-white shadow">
                <div class="border-b px-6 py-4">
                    <h2 class="text-xl font-bold">Curriculum Entries</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Lang</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Tags</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($curriculums as $item)
                                <tr>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $item->title }}</td>
                                    <td class="px-6 py-4 text-sm uppercase text-gray-500">{{ $item->language }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $item->tags ?? "-" }}</td>
                                    <td class="px-6 py-4 text-sm font-medium">
                                        <form action="{{ route("admin.curriculum.destroy", $item) }}" method="POST"
                                            onsubmit="return confirm('Delete this entry?')">
                                            @csrf
                                            @method("DELETE")
                                            <button class="text-red-600 hover:text-red-900" type="submit">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="px-6 py-4 text-center text-gray-500" colspan="4">No curriculum found.
                                        Import some!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="border-t px-6 py-4">
                    {{ $curriculums->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
