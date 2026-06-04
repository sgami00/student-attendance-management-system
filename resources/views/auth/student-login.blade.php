<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal – StudentAttendanceSystem</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #d4f0e8 0%, #eef8f3 40%, #d9f0ff 100%);
            min-height: 100vh;
        }

        .card {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            box-shadow: 0 8px 40px rgba(20, 140, 100, 0.10);
        }

        .input-wrap {
            display: flex;
            align-items: center;
            border: 1.5px solid #e2e4f0;
            border-radius: 10px;
            padding: 0 14px;
            background: #f6fdf9;
            transition: border-color 0.2s;
        }

        .input-wrap:focus-within {
            border-color: #10b981;
            background: #fff;
        }

        .input-wrap i {
            color: #b0b4cc;
            font-size: 0.9rem;
            margin-right: 10px;
        }

        .input-wrap input {
            border: none;
            background: transparent;
            outline: none;
            padding: 12px 0;
            font-size: 0.92rem;
            width: 100%;
            color: #333;
        }

        .input-wrap input::placeholder { color: #b0b4cc; }

        .btn-primary {
            width: 100%;
            background: #10b981;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 13px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
        }

        .btn-primary:hover {
            background: #059669;
            transform: translateY(-1px);
        }

        .btn-back {
            width: 100%;
            background: transparent;
            color: #6b7280;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            padding: 12px;
            font-size: 0.92rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-back:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #c5c8dc;
            font-size: 0.78rem;
            font-weight: 500;
            letter-spacing: 0.05em;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e6e8f3;
        }
    </style>
</head>
<body class="flex flex-col items-center justify-center min-h-screen px-4">

    {{-- Brand Header --}}
    <div class="text-center mb-6">
        <div class="flex items-center justify-center gap-3 mb-2">
            <div class="bg-emerald-500 text-white rounded-xl p-2.5 shadow-md">
                <i class="fa-solid fa-graduation-cap text-2xl"></i>
            </div>
            <span class="text-3xl font-bold text-gray-900 tracking-tight">StudentAttendanceSystem</span>
        </div>
        <p class="text-xs font-semibold tracking-[0.18em] text-gray-400 uppercase">Student Portal</p>
    </div>

    {{-- Card --}}
    <div class="card w-full max-w-md px-8 py-8">

        <h2 class="text-2xl font-bold text-gray-900 mb-1">Student Sign In 🎓</h2>
        <p class="text-sm text-gray-500 mb-6">Enter your Student ID to view your attendance records.</p>

        {{-- Error --}}
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        {{-- Student Login Form --}}
        <form action="{{ route('student.login.submit') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Student ID Number</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-id-card"></i>
                    <input type="text" name="student_id_number"
                           placeholder="e.g. 2024-00123"
                           value="{{ old('student_id_number') }}" required autocomplete="off">
                </div>
                @error('student_id_number')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-primary mt-2">
                <i class="fa-solid fa-magnifying-glass mr-2"></i> View My Attendance
            </button>
        </form>

        {{-- Divider --}}
        <div class="divider my-5">OR</div>

        {{-- Back to Teacher Login --}}
        <a href="{{ route('login') }}" class="btn-back">
            <i class="fa-solid fa-chalkboard-teacher"></i>
            Back to Teacher Login
        </a>

    </div>

    {{-- Footer --}}
    <p class="text-xs text-gray-400 mt-6">
        &copy; {{ date('Y') }} Student Attendance Management System
    </p>

</body>
</html>