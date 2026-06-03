<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Student Attendance System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #eef2ff;
            margin: 0;
            min-height: 100vh;
        }

        .mesh-bg {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 0;
            overflow: hidden;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.55;
            animation: floatBlob linear infinite;
        }

        .blob-1 {
            width: 600px; height: 600px;
            background: radial-gradient(circle, #a5b4fc, #6366f1);
            top: -150px; left: -100px;
            animation-duration: 18s;
        }
        .blob-2 {
            width: 500px; height: 500px;
            background: radial-gradient(circle, #c4b5fd, #8b5cf6);
            top: 200px; right: -100px;
            animation-duration: 22s;
            animation-delay: -6s;
        }
        .blob-3 {
            width: 400px; height: 400px;
            background: radial-gradient(circle, #bae6fd, #38bdf8);
            bottom: 0px; left: 30%;
            animation-duration: 26s;
            animation-delay: -12s;
        }
        .blob-4 {
            width: 350px; height: 350px;
            background: radial-gradient(circle, #fbcfe8, #f472b6);
            bottom: 100px; right: 20%;
            animation-duration: 20s;
            animation-delay: -4s;
        }

        @keyframes floatBlob {
            0%   { transform: translate(0px, 0px) scale(1); }
            25%  { transform: translate(40px, -30px) scale(1.05); }
            50%  { transform: translate(-20px, 50px) scale(0.95); }
            75%  { transform: translate(-40px, -20px) scale(1.03); }
            100% { transform: translate(0px, 0px) scale(1); }
        }

        .login-card {
            position: relative;
            z-index: 1;
        }

        .input-field {
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .input-field:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,0.15);
            outline: none;
        }
        .input-field.error {
            border-color: #ef4444;
        }
        .input-field.error:focus {
            box-shadow: 0 0 0 3px rgba(239,68,68,0.15);
        }
    </style>
</head>
<body class="flex flex-col items-center justify-center min-h-screen">

    {{-- Animated mesh background --}}
    <div class="mesh-bg">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
        <div class="blob blob-4"></div>
    </div>

    {{-- Header --}}
    <div class="text-center mb-8 login-card">
        <div class="flex items-center justify-center gap-3 mb-2">
            <div class="bg-indigo-600 p-2.5 rounded-xl shadow">
                <i class="fa-solid fa-qrcode text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 tracking-tight">
                StudentAttendanceSystem
            </h1>
        </div>
        <p class="text-sm text-gray-500 font-medium tracking-widest uppercase">Teacher Portal</p>
    </div>

    {{-- Login Card --}}
    <div class="login-card bg-white bg-opacity-80 backdrop-blur-md p-8 rounded-2xl shadow-xl w-full max-w-sm border border-white border-opacity-60">

        <h2 class="text-xl font-bold mb-1 text-gray-800">Welcome👋</h2>
        <p class="text-sm text-gray-500 mb-6">Sign in to manage your classes and attendance.</p>

        <form action="/login" method="POST" class="space-y-4">
            @csrf

            {{-- Email --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Email Address
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <i class="fa-solid fa-envelope text-sm"></i>
                    </span>
                    <input type="email" name="email"
                        value="{{ old('email') }}"
                        placeholder="teacher@school.edu"
                        class="input-field w-full border rounded-xl pl-9 pr-3 py-2.5 text-sm {{ $errors->has('email') ? 'error border-red-400' : 'border-gray-300' }}"
                        required>
                </div>
                @error('email')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">
                    Password
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </span>
                    <input type="password" name="password"
                        id="passwordInput"
                        placeholder="••••••••"
                        class="input-field w-full border rounded-xl pl-9 pr-10 py-2.5 text-sm {{ $errors->has('password') ? 'error border-red-400' : 'border-gray-300' }}"
                        required>
                    <button type="button" onclick="togglePassword()"
                        class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-eye text-sm" id="eyeIcon"></i>
                    </button>
                </div>
                @error('password')
                    <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Submit --}}
            <button type="submit"
                class="w-full bg-indigo-600 text-white font-bold py-2.5 rounded-xl hover:bg-indigo-700 transition shadow-md mt-2 flex items-center justify-center gap-2">
                <i class="fa-solid fa-right-to-bracket"></i>
                Login
            </button>
        </form>
    </div>

    <p class="login-card text-xs text-gray-500 mt-6">© {{ date('Y') }} Student Attendance Management System</p>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>

</body>
</html>