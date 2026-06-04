<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Attendance – {{ $student->name }}</title>
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

    {{-- Navbar --}}
    <nav class="max-w-3xl mx-auto flex items-center justify-between mb-8">
        <div class="flex items-center gap-2">
            <div class="bg-emerald-500 text-white rounded-xl p-2 shadow">
                <i class="fa-solid fa-graduation-cap text-lg"></i>
            </div>
            <span class="font-bold text-gray-800 text-lg">Student Portal</span>
        </div>
        <form action="{{ route('student.logout') }}" method="POST">
            @csrf
            <button type="submit"
                    class="text-sm text-gray-500 hover:text-red-600 font-semibold border border-gray-200 px-4 py-1.5 rounded-lg bg-white hover:bg-red-50 transition">
                <i class="fa-solid fa-right-from-bracket mr-1"></i> Sign Out
            </button>
        </form>
    </nav>

    <div class="max-w-3xl mx-auto space-y-5">

        {{-- Profile Card --}}
        <div class="bg-white rounded-2xl shadow p-6 border-l-4 border-emerald-500">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $student->name }}</h1>
                    <p class="text-sm text-gray-500 mt-0.5">
                        <span class="font-mono bg-gray-100 px-1.5 py-0.5 rounded text-xs">{{ $student->student_id_number }}</span>
                        &nbsp;·&nbsp; {{ $student->email }}
                    </p>
                </div>
                <div class="text-center sm:text-right">
                    <div class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-0.5">Attendance Rate</div>
                    <div class="text-4xl font-extrabold
                        @if($attendanceRate >= 75) text-emerald-500
                        @elseif($attendanceRate >= 50) text-yellow-500
                        @else text-red-500
                        @endif
                    ">{{ $attendanceRate }}%</div>
                </div>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl shadow p-5 text-center">
                <div class="text-xs font-semibold uppercase tracking-widest text-emerald-500 mb-2">Present</div>
                <div class="text-5xl font-extrabold text-emerald-500">{{ $presentCount }}</div>
                <div class="text-xs text-gray-400 mt-1">sessions</div>
            </div>
            <div class="bg-white rounded-2xl shadow p-5 text-center">
                <div class="text-xs font-semibold uppercase tracking-widest text-red-400 mb-2">Absent</div>
                <div class="text-5xl font-extrabold text-red-400">{{ $absentCount }}</div>
                <div class="text-xs text-gray-400 mt-1">sessions</div>
            </div>
            <div class="bg-white rounded-2xl shadow p-5 text-center">
                <div class="text-xs font-semibold uppercase tracking-widest text-yellow-500 mb-2">Late</div>
                <div class="text-5xl font-extrabold text-yellow-400">{{ $lateCount }}</div>
                <div class="text-xs text-gray-400 mt-1">sessions</div>
            </div>
        </div>

        {{-- Attendance Table --}}
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="p-5 border-b border-gray-100">
                <h2 class="text-lg font-bold text-gray-800">My Attendance History</h2>
                <p class="text-xs text-gray-400 mt-0.5">{{ $total }} record(s) · latest first</p>
            </div>

            @if($attendances->isEmpty())
                <div class="p-10 text-center text-gray-400 italic">
                    <i class="fa-regular fa-folder-open text-3xl mb-3 block"></i>
                    No attendance records found yet.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                                <th class="px-5 py-3 font-semibold">Date</th>
                                <th class="px-5 py-3 font-semibold">Class</th>
                                <th class="px-5 py-3 font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $record)
                                <tr class="border-t border-gray-100 hover:bg-gray-50 transition-colors">
                                    <td class="px-5 py-3">
                                        <div class="font-medium text-gray-800">
                                            {{ \Carbon\Carbon::parse($record->attendance_date)->format('M d, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            {{ \Carbon\Carbon::parse($record->attendance_date)->format('l') }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-3">
                                        <div class="text-gray-700">{{ $record->schoolClass->name ?? '—' }}</div>
                                        <span class="font-mono text-xs bg-gray-100 px-1.5 py-0.5 rounded text-gray-500">
                                            {{ $record->schoolClass->code ?? '' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3">
                                        @if($record->status === 'present')
                                            <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full uppercase">Present</span>
                                        @elseif($record->status === 'late')
                                            <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-3 py-1 rounded-full uppercase">Late</span>
                                        @else
                                            <span class="bg-red-100 text-red-600 text-xs font-semibold px-3 py-1 rounded-full uppercase">Absent</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>

    <p class="text-center text-xs text-gray-400 mt-8">
        &copy; {{ date('Y') }} Student Attendance Management System
    </p>

</body>
</html>