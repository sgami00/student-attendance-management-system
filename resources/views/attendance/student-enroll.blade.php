<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enroll in a Class – {{ $student->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #d4f0e8 0%, #eef8f3 60%, #d9f0ff 100%);
            min-height: 100vh;
            font-family: ui-sans-serif, system-ui, sans-serif;
        }
    </style>
</head>
<body class="px-4 py-8">

    {{--- Navbar ---}}
    <nav class="max-w-3xl mx-auto flex items-center justify-between mb-8">
        <div class="flex items-center gap-2">
            <div class="bg-emerald-500 text-white rounded-xl p-2 shadow">
                <i class="fa-solid fa-graduation-cap text-lg"></i>
            </div>
            <span class="font-bold text-gray-800 text-lg">Student Portal</span>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('student.attendance') }}"
               class="text-sm text-gray-500 hover:text-emerald-600 font-semibold border border-gray-200 px-4 py-1.5 rounded-lg bg-white hover:bg-emerald-50 transition">
                <i class="fa-solid fa-chart-bar mr-1"></i> My Attendance
            </a>
            <form action="{{ route('student.logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="text-sm text-gray-500 hover:text-red-600 font-semibold border border-gray-200 px-4 py-1.5 rounded-lg bg-white hover:bg-red-50 transition">
                    <i class="fa-solid fa-right-from-bracket mr-1"></i> Sign Out
                </button>
            </form>
        </div>
    </nav>

    <div class="max-w-3xl mx-auto space-y-5">

        {{-- Page Header --}}
        <div class="bg-white rounded-2xl shadow p-6 border-l-4 border-emerald-500">
            <div class="flex items-center gap-3">
                <div class="bg-emerald-100 text-emerald-600 rounded-xl p-3">
                    <i class="fa-solid fa-door-open text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Enroll in a Class</h1>
                    <p class="text-sm text-gray-500 mt-0.5">
                        Hi, <span class="font-semibold text-gray-700">{{ $student->name }}</span>!
                        Enter the class code given by your teacher to enroll.
                    </p>
                </div>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3 rounded-xl text-sm font-medium shadow-sm">
                <i class="fa-solid fa-circle-check text-emerald-500 text-base"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('warning'))
            <div class="flex items-center gap-3 bg-yellow-50 border border-yellow-200 text-yellow-800 px-5 py-3 rounded-xl text-sm font-medium shadow-sm">
                <i class="fa-solid fa-triangle-exclamation text-yellow-500 text-base"></i>
                {{ session('warning') }}
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-5 py-3 rounded-xl text-sm font-medium shadow-sm">
                <i class="fa-solid fa-circle-xmark text-red-400 text-base"></i>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-5 py-3 rounded-xl text-sm font-medium shadow-sm">
                <i class="fa-solid fa-circle-xmark text-red-400 text-base"></i>
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Enrollment Form --}}
        <div class="bg-white rounded-2xl shadow p-6">
            <form action="{{ route('student.enroll.submit') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="class_code" class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Class Code
                    </label>
                    <div class="flex gap-3">
                        <input
                            type="text"
                            name="class_code"
                            id="class_code"
                            value="{{ old('class_code') }}"
                            placeholder="e.g. CS101-A"
                            required
                            autocomplete="off"
                            class="flex-1 border border-gray-300 rounded-xl px-4 py-3 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition uppercase"
                            style="text-transform: uppercase;"
                        >
                        <button type="submit"
                                class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold px-6 py-3 rounded-xl text-sm transition shadow hover:shadow-md flex items-center gap-2">
                            <i class="fa-solid fa-plus"></i> Enroll
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">
                        <i class="fa-solid fa-circle-info mr-1"></i>
                        Class codes are case-insensitive. Ask your teacher for the code.
                    </p>
                </div>
            </form>
        </div>

        {{-- Currently Enrolled Classes --}}
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-gray-800">My Enrolled Classes</h2>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $enrolledClasses->count() }} class(es)</p>
                </div>
                <i class="fa-solid fa-book-open text-emerald-300 text-xl"></i>
            </div>

            @if($enrolledClasses->isEmpty())
                <div class="p-10 text-center text-gray-400 italic">
                    <i class="fa-regular fa-folder-open text-3xl mb-3 block"></i>
                    You are not enrolled in any class yet. Enter a class code above!
                </div>
            @else
                <ul class="divide-y divide-gray-100">
                    @foreach($enrolledClasses as $class)
                        <li class="flex items-center justify-between px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex items-center gap-3">
                                <div class="bg-emerald-100 text-emerald-600 rounded-lg p-2.5">
                                    <i class="fa-solid fa-chalkboard-user text-sm"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-800 text-sm">{{ $class->name }}</div>
                                    <span class="font-mono text-xs bg-gray-100 px-1.5 py-0.5 rounded text-gray-500">
                                        {{ $class->code }}
                                    </span>
                                    @if($class->teacher)
                                        <span class="text-xs text-gray-400 ml-2">
                                            <i class="fa-solid fa-user mr-1"></i>{{ $class->teacher->name }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <span class="text-xs bg-emerald-100 text-emerald-700 font-semibold px-3 py-1 rounded-full">
                                Enrolled
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

    </div>

    <p class="text-center text-xs text-gray-400 mt-8">
        &copy; {{ date('Y') }} Student Attendance Management System
    </p>

</body>
</html>