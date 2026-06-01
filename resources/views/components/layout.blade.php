<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <nav class="bg-indigo-600 text-white p-4 shadow-md">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <div class="flex space-x-6 font-semibold">
                <a href="/dashboard" class="hover:text-indigo-200">Dashboard</a>
                <a href="/analytics" class="hover:text-indigo-200">Analytics Dashboard</a>
            </div>
            @auth
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-sm">Logout</button>
            </form>
            @endauth
        </div>
    </nav>
    <main class="max-w-6xl mx-auto mt-8 px-4">
        {{ $slot }}
    </main>
</body>
</html>