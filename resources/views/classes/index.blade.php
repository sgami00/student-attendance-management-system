{{-- File: resources/views/classes/index.blade.php --}}
<x-layout>

<style>
    body {
        background: #eef2ff;
        background-image: none;
    }

    .mesh-bg {
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        z-index: -1;
        overflow: hidden;
        background: #eef2ff;
    }

    .mesh-bg .blob {
        position: absolute;
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.5;
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
</style>

{{-- Animated mesh background --}}
<div class="mesh-bg">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
    <div class="blob blob-4"></div>
</div>

    {{-- Hero Header --}}
    <div class="relative rounded-2xl overflow-hidden mb-8 shadow-lg"
        style="background: linear-gradient(135deg, #4338ca 0%, #6366f1 50%, #818cf8 100%);">
        {{-- Decorative circles --}}
        <div class="absolute top-0 right-0 w-64 h-64 rounded-full opacity-10"
            style="background: white; transform: translate(30%, -30%);"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 rounded-full opacity-10"
            style="background: white; transform: translate(-30%, 30%);"></div>

        <div class="relative px-8 py-8 flex justify-between items-center">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                        <i class="fa-solid fa-chalkboard-teacher text-white text-xl"></i>
                    </div>
                    <h1 class="text-3xl font-bold text-white tracking-tight">Teacher Dashboard</h1>
                </div>
                <p class="text-indigo-200 text-sm mt-1 ml-14">Manage your active classes and automated QR attendance logs.</p>
            </div>
            <a href="{{ route('classes.create') }}"
                class="bg-white text-indigo-700 font-bold text-sm px-5 py-2.5 rounded-xl hover:bg-indigo-50 transition shadow-md flex items-center gap-2 whitespace-nowrap">
                <i class="fa-solid fa-plus"></i>
                Create New Class
            </a>
        </div>
    </div>

    {{-- Stats row --}}
    @if(!$classes->isEmpty())
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex items-center gap-3">
            <div class="bg-indigo-100 p-2.5 rounded-lg">
                <i class="fa-solid fa-layer-group text-indigo-600 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Total Classes</p>
                <p class="text-2xl font-bold text-gray-800">{{ $classes->count() }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex items-center gap-3">
            <div class="bg-emerald-100 p-2.5 rounded-lg">
                <i class="fa-solid fa-users text-emerald-600 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Total Students</p>
                <p class="text-2xl font-bold text-gray-800">{{ $classes->sum(fn($c) => $c->students->count()) }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex items-center gap-3">
            <div class="bg-amber-100 p-2.5 rounded-lg">
                <i class="fa-solid fa-calendar-check text-amber-600 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Active Today</p>
                <p class="text-2xl font-bold text-gray-800">{{ now()->format('M d') }}</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex items-center gap-3">
            <div class="bg-purple-100 p-2.5 rounded-lg">
                <i class="fa-solid fa-qrcode text-purple-600 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">QR Enabled</p>
                <p class="text-2xl font-bold text-gray-800">All</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Classes Grid --}}
    @if($classes->isEmpty())
        <div class="bg-white p-12 rounded-2xl shadow text-center border border-dashed border-gray-300">
            <i class="fa-solid fa-folder-open text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500 italic">No classes found. Click <strong>"+ Create New Class"</strong> to begin.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($classes as $class)
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 overflow-hidden flex flex-col">
                    {{-- Card top color bar --}}
                    <div class="h-1.5 w-full" style="background: linear-gradient(90deg, #4338ca, #818cf8);"></div>

                    <div class="p-6 flex flex-col flex-1">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">{{ $class->name }}</h3>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="bg-indigo-50 text-indigo-700 font-mono text-xs px-2 py-0.5 rounded font-semibold">{{ $class->code }}</span>
                                    <span class="text-gray-400 text-xs">·</span>
                                    <span class="text-gray-500 text-xs"><i class="fa-solid fa-users mr-1"></i>{{ $class->students->count() }} enrolled</span>
                                </div>
                            </div>

                            <div class="flex space-x-1 bg-gray-50 p-1 rounded-lg border border-gray-100">
                                <a href="{{ route('classes.edit', $class->id) }}"
                                    class="text-gray-400 hover:text-blue-600 p-1.5 rounded-md transition hover:bg-blue-50" title="Edit Class">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                    </svg>
                                </a>
                                <form action="{{ route('classes.destroy', $class->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete {{ $class->name }}? All data will be permanently lost.');"
                                    class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-gray-400 hover:text-red-600 p-1.5 rounded-md transition hover:bg-red-50" title="Delete Class">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-auto pt-4 border-t border-gray-100 flex space-x-2">
                            <a href="{{ route('attendance.create', $class->id) }}"
                                class="flex-1 bg-emerald-600 text-white text-sm font-semibold px-4 py-2.5 rounded-xl hover:bg-emerald-700 transition shadow-sm text-center flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-clipboard-check text-xs"></i>
                                Take Attendance
                            </a>
                            <a href="{{ route('classes.show', $class->id) }}"
                                class="flex-1 bg-gray-100 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-xl hover:bg-indigo-50 hover:text-indigo-700 transition border text-center flex items-center justify-center gap-1.5">
                                <i class="fa-solid fa-users text-xs"></i>
                                View Students
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</x-layout>