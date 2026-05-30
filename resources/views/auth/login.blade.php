<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-200 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded shadow-md w-96">
        <h2 class="text-2xl font-bold mb-6 text-center">Teacher Sign In</h2>
        <form action="/login" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Email Address</label>
                <input type="email" name="email" class="w-full border rounded p-2" required>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">Password</label>
                <input type="password" name="password" class="w-full border rounded p-2" required>
            </div>
            <button class="w-full bg-indigo-600 text-white p-2 rounded hover:bg-indigo-700">Login</button>
        </form>
    </div>
</body>
</html>