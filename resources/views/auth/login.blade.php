<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – StudentAttendanceSystem</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #dde8ff 0%, #eef0fb 40%, #f9dff5 100%);
            min-height: 100vh;
        }

        .card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-radius: 20px;
            box-shadow: 0 8px 40px rgba(80, 80, 160, 0.10);
        }

        .input-wrap {
            display: flex;
            align-items: center;
            border: 1.5px solid #e2e4f0;
            border-radius: 10px;
            padding: 0 14px;
            background: #f8f9fe;
            transition: border-color 0.2s;
        }

        .input-wrap:focus-within {
            border-color: #5b5fcf;
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

        .input-wrap input::placeholder {
            color: #b0b4cc;
        }

        .btn-login {
            width: 100%;
            background: #5b5fcf;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 13px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            letter-spacing: 0.01em;
        }

        .btn-login:hover {
            background: #4648b8;
            transform: translateY(-1px);
        }

        /* Student portal button — outlined style */
        .btn-student {
            width: 100%;
            background: transparent;
            color: #5b5fcf;
            border: 1.5px solid #5b5fcf;
            border-radius: 10px;
            padding: 12px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, color 0.2s, transform 0.1s;
            letter-spacing: 0.01em;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-student:hover {
            background: #5b5fcf;
            color: #fff;
            transform: translateY(-1px);
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
            <div class="bg-indigo-600 text-white rounded-xl p-2.5 shadow-md">
                <i class="fa-solid fa-qrcode text-2xl"></i>
            </div>
            <span class="text-3xl font-bold text-gray-900 tracking-tight">StudentAttendanceSystem</span>
        </div>
        <p class="text-xs font-semibold tracking-[0.18em] text-gray-400 uppercase">Teacher Portal</p>
    </div>

    {{-- Card --}}
    <div class="card w-full max-w-md px-8 py-8">

        <h2 class="text-2xl font-bold text-gray-900 mb-1">Welcome 👋</h2>
        <p class="text-sm text-gray-500 mb-6">Sign in to manage your classes and attendance.</p>

        {{-- Error Message --}}
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Teacher Login Form --}}
        <form action="/login" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                <div class="input-wrap">
                    <i class="fa-regular fa-envelope"></i>
                    <input type="email" name="email" placeholder="teacher@school.edu"
                           value="{{ old('email') }}" required autocomplete="email">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" id="password-field"
                           placeholder="••••••••" required autocomplete="current-password">
                    <button type="button" onclick="togglePassword()"
                            class="text-gray-400 hover:text-gray-600 ml-2 transition-colors" tabindex="-1">
                        <i class="fa-regular fa-eye" id="eye-icon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-login mt-2">
                <i class="fa-solid fa-arrow-right-to-bracket mr-2"></i> Login
            </button>
        </form>

        {{-- Divider --}}
        <div class="divider my-5">OR</div>

        {{-- Student Portal Button --}}
        <a href="{{ route('student.login') }}" class="btn-student">
            <i class="fa-solid fa-graduation-cap"></i>
            Student Portal
        </a>

        <p class="text-center text-xs text-gray-400 mt-3">
            View your attendance records as a student
        </p>

    </div>

    {{-- Footer --}}
    <p class="text-xs text-gray-400 mt-6">
        &copy; {{ date('Y') }} Student Attendance Management System
    </p>

<script>
    function togglePassword() {
        const field = document.getElementById('password-field');
        const icon  = document.getElementById('eye-icon');
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>

</body>
</html>