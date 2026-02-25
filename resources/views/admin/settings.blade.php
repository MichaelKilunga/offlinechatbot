@extends("layouts.admin", ["title" => "System Settings"])

@section("content")
    <div class="mx-auto max-w-2xl">
        <div class="rounded-lg bg-white p-8 shadow">
            <h2 class="mb-6 text-2xl font-bold">Global Application Settings</h2>

            <form action="{{ route("admin.settings.update") }}" class="space-y-6" method="POST">
                @csrf

                <!-- Default Language -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Primary SMS Language</label>
                    <select
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        name="primary_language">
                        <option {{ ($settings["primary_language"] ?? "sw") == "sw" ? "selected" : "" }} value="sw">
                            Swahili (Kiswahili)</option>
                        <option {{ ($settings["primary_language"] ?? "sw") == "en" ? "selected" : "" }} value="en">
                            English</option>
                    </select>
                    <p class="mt-2 text-sm text-gray-500">Determines which prompt template and curriculum subset to
                        prioritize.</p>
                </div>

                <!-- Bot Personality -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Bot Name (Internal/Branding)</label>
                    <input
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        name="bot_name" type="text" value="{{ $settings["bot_name"] ?? env("APP_NAME") }}">
                </div>

                <hr>

                <div class="flex justify-end pt-4">
                    <button
                        class="rounded-md bg-blue-600 px-6 py-2 font-semibold text-white shadow transition hover:bg-blue-700"
                        type="submit">
                        Save Configuration
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-8 rounded-lg border border-blue-200 bg-blue-50 p-4">
            <h4 class="mb-2 font-bold text-blue-800">How this works</h4>
            <ul class="list-inside list-disc space-y-1 text-sm text-blue-700">
                <li>Primary language affects keyword matching priority.</li>
                <li>Templates must be created for the selected language to work correctly.</li>
                <li>Curriculum content uploaded for other languages will still be searchable if specific keywords match.
                </li>
            </ul>
        </div>
    </div>
@endsection
