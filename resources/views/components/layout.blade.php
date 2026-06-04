<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Attendance Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-900 font-sans min-h-screen">

    <nav class="bg-indigo-700 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                
                <div class="flex-shrink-0 flex items-center gap-2">
                    <i class="fa-solid fa-qrcode text-2xl"></i>
                    <span class="font-bold text-xl tracking-tight">StudentAttendanceSystem</span>
                </div>

                <div class="flex space-x-8 font-medium">
                    <a href="/dashboard" class="hover:text-indigo-200 transition-colors border-b-2 border-transparent hover:border-indigo-200 pb-1">Dashboard</a>
                    <a href="/analytics" class="hover:text-indigo-200 transition-colors border-b-2 border-transparent hover:border-indigo-200 pb-1">Analytics Dashboard</a>
                </div>

                @auth
                <div class="flex items-center gap-4">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        
                        <button type="submit" class="bg-indigo-800 hover:bg-red-600 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 shadow-md">
                            Logout
                        </button>
                    </form>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        {{ $slot }}
    </main>

    <footer class="mt-auto py-6 text-center text-gray-500 text-sm">
        <p>&copy; {{ date('Y') }} Student Attendance Management System. All rights reserved.</p>
    </footer>

</body>
</html>