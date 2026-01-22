<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Platform Admin - {{ $title ?? 'Dashboard' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>
</head>
<body class="bg-gray-50 flex flex-col min-h-screen">

    <!-- Nav -->
    <nav class="bg-white border-b border-gray-200 px-4 py-2.5 fixed left-0 right-0 top-0 z-50">
        <div class="flex flex-wrap justify-between items-center">
            <div class="flex justify-start items-center">
                <a href="{{ route('admin.dashboard') }}" class="flex mr-4">
                    <span class="self-center text-2xl font-semibold whitespace-nowrap">EduChat Admin</span>
                </a>
            </div>
            <div class="flex items-center lg:order-2">
                <div class="hidden lg:flex items-center space-x-4">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                    <a href="{{ route('admin.curriculum.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Curriculum</a>
                    <a href="{{ route('admin.templates.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Templates</a>
                    <a href="{{ route('admin.settings.index') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">Settings</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="p-4 pt-20 flex-grow max-w-7xl mx-auto w-full">
        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-white border-t border-gray-200 p-4 mt-auto">
        <div class="text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} EduChat SMS Learning
        </div>
    </footer>
</body>
</html>
