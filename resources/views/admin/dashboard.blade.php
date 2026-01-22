@extends("layouts.admin", ["title" => "Dashboard"])

@section("content")
    <!-- Stats -->
    <div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-3">
        <div class="rounded-lg bg-white p-6 shadow">
            <h3 class="text-sm font-medium text-gray-500">Total Users</h3>
            <p class="text-3xl font-bold">{{ $totalUsers }}</p>
        </div>
        <div class="rounded-lg bg-white p-6 shadow">
            <h3 class="text-sm font-medium text-gray-500">Total Messages</h3>
            <p class="text-3xl font-bold">{{ $totalMessages }}</p>
        </div>
        <div class="rounded-lg bg-white p-6 shadow">
            <h3 class="text-sm font-medium text-gray-500">Messages Today</h3>
            <p class="text-3xl font-bold">{{ $messagesToday }}</p>
        </div>
    </div>

    <!-- Recent Logs -->
    <div class="overflow-hidden rounded-lg bg-white shadow">
        <div class="flex items-center justify-between border-b px-6 py-4">
            <h2 class="text-xl font-semibold">Recent Interactions</h2>
            <span class="text-sm text-gray-500">Live SMS Traffic</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Content
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">AI
                            Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach ($interactions as $msg)
                        <tr>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                {{ $msg->created_at->format("H:i:s") }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                {{ $msg->user->phone_number }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm">
                                <span
                                    class="{{ $msg->direction === "inbound" ? "bg-green-100 text-green-800" : "bg-blue-100 text-blue-800" }} inline-flex rounded-full px-2 text-xs font-semibold leading-5">
                                    {{ ucfirst($msg->direction) }}
                                </span>
                            </td>
                            <td class="max-w-xs truncate px-6 py-4 text-sm text-gray-500" title="{{ $msg->content }}">
                                {{ $msg->content }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                @if ($msg->aiLog)
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $msg->aiLog->model }}</span>
                                        <span class="text-xs text-gray-400">{{ $msg->aiLog->total_tokens ?? 0 }}
                                            tokens</span>
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="border-t px-6 py-4">
            {{ $interactions->links() }}
        </div>
    </div>
@endsection
