<x-layout>

<style>
    body { background: #eef2ff; }
    .mesh-bg { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: -1; background: #eef2ff; }
    .mesh-bg .blob { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.5; animation: floatBlob linear infinite; }
    .blob-1 { width: 600px; height: 600px; background: radial-gradient(circle, #a5b4fc, #6366f1); top: -150px; left: -100px; animation-duration: 18s; }
    .blob-2 { width: 500px; height: 500px; background: radial-gradient(circle, #c4b5fd, #8b5cf6); top: 200px; right: -100px; animation-duration: 22s; animation-delay: -6s; }
    .blob-3 { width: 400px; height: 400px; background: radial-gradient(circle, #bae6fd, #38bdf8); bottom: 0px; left: 30%; animation-duration: 26s; animation-delay: -12s; }
    @keyframes floatBlob {
        0%   { transform: translate(0px, 0px) scale(1); }
        25%  { transform: translate(40px, -30px) scale(1.05); }
        50%  { transform: translate(-20px, 50px) scale(0.95); }
        75%  { transform: translate(-40px, -20px) scale(1.03); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
</style>

<div class="mesh-bg">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
</div>

{{-- Header --}}
<div class="relative rounded-2xl overflow-hidden mb-8 shadow-lg"
    style="background: linear-gradient(135deg, #4338ca 0%, #6366f1 50%, #818cf8 100%);">
    <div class="absolute top-0 right-0 w-64 h-64 rounded-full opacity-10" style="background: white; transform: translate(30%, -30%);"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 rounded-full opacity-10" style="background: white; transform: translate(-30%, 30%);"></div>
    <div class="relative px-8 py-8 flex justify-between items-center">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                    <i class="fa-solid fa-users text-white text-xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white tracking-tight">All My Students</h1>
            </div>
            <p class="text-indigo-200 text-sm mt-1 ml-14">Overall list of students across all your classes.</p>
        </div>
        <a href="{{ route('dashboard') }}"
            class="bg-white text-indigo-700 font-bold text-sm px-5 py-2.5 rounded-xl hover:bg-indigo-50 transition shadow-md flex items-center gap-2">
            <i class="fa-solid fa-arrow-left"></i>
            Back to Dashboard
        </a>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
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
            <p class="text-xs text-gray-500 font-medium">Total Unique Students</p>
            <p class="text-2xl font-bold text-gray-800">{{ $allStudents->count() }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 flex items-center gap-3">
        <div class="bg-amber-100 p-2.5 rounded-lg">
            <i class="fa-solid fa-calendar-check text-amber-600 text-lg"></i>
        </div>
        <div>
            <p class="text-xs text-gray-500 font-medium">Today</p>
            <p class="text-2xl font-bold text-gray-800">{{ now()->format('M d, Y') }}</p>
        </div>
    </div>
</div>

{{-- Search + Filter --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6 flex flex-col md:flex-row gap-3">
    <input type="text" id="searchInput" placeholder="Search student name or ID..."
        class="flex-1 border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
    <select id="classFilter" class="border border-gray-200 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
        <option value="">All Classes</option>
        @foreach($classes as $class)
            <option value="{{ $class->id }}">{{ $class->name }} ({{ $class->code }})</option>
        @endforeach
    </select>
</div>

{{-- Students Per Class --}}
@foreach($classes as $class)
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 overflow-hidden class-section" data-class-id="{{ $class->id }}">
    <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center"
        style="background: linear-gradient(90deg, #eef2ff, #fff);">
        <div class="flex items-center gap-3">
            <div class="bg-indigo-100 p-2 rounded-lg">
                <i class="fa-solid fa-chalkboard text-indigo-600"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-800">{{ $class->name }}</h2>
                <span class="font-mono text-xs bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded">{{ $class->code }}</span>
            </div>
        </div>
        <span class="bg-indigo-100 text-indigo-700 text-xs font-semibold px-3 py-1 rounded-full">
            {{ $class->students->count() }} student(s)
        </span>
    </div>

    @if($class->students->isEmpty())
        <div class="p-6 text-center text-gray-400 italic text-sm">No students enrolled in this class.</div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-500 text-xs font-semibold uppercase tracking-wide">
                        <th class="px-6 py-3">ID Number</th>
                        <th class="px-6 py-3">Full Name</th>
                        <th class="px-6 py-3">Email</th>
                        <th class="px-6 py-3 text-center">Attendance Log</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($class->students as $student)
                    <tr class="border-t border-gray-50 hover:bg-indigo-50 transition student-row"
                        data-name="{{ strtolower($student->name) }}"
                        data-id="{{ strtolower($student->student_id_number) }}">
                        <td class="px-6 py-3 font-mono text-gray-500 text-xs">{{ $student->student_id_number }}</td>
                        <td class="px-6 py-3 font-semibold text-gray-800">{{ $student->name }}</td>
                        <td class="px-6 py-3 text-gray-500">{{ $student->email }}</td>
                        <td class="px-6 py-3 text-center">
                            <a href="{{ route('students.log', $student->id) }}"
                                class="bg-indigo-100 text-indigo-700 hover:bg-indigo-600 hover:text-white text-xs px-3 py-1.5 rounded font-semibold transition-colors">
                                📋 View Log
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endforeach

{{-- Empty state --}}
@if($classes->isEmpty())
<div class="bg-white p-12 rounded-2xl shadow text-center border border-dashed border-gray-300">
    <i class="fa-solid fa-users-slash text-gray-300 text-5xl mb-4"></i>
    <p class="text-gray-500 italic">You have no classes yet. Create a class first from the dashboard.</p>
</div>
@endif

<script>
    const searchInput  = document.getElementById('searchInput');
    const classFilter  = document.getElementById('classFilter');

    function applyFilters() {
        const search    = searchInput.value.toLowerCase();
        const classId   = classFilter.value;

        document.querySelectorAll('.class-section').forEach(section => {
            const sectionClassId = section.dataset.classId;
            const matchesClass   = !classId || sectionClassId === classId;

            if (!matchesClass) {
                section.style.display = 'none';
                return;
            }

            section.style.display = '';

            let visibleRows = 0;
            section.querySelectorAll('.student-row').forEach(row => {
                const name = row.dataset.name;
                const id   = row.dataset.id;
                const matchesSearch = !search || name.includes(search) || id.includes(search);
                row.style.display = matchesSearch ? '' : 'none';
                if (matchesSearch) visibleRows++;
            });
        });
    }

    searchInput.addEventListener('input', applyFilters);
    classFilter.addEventListener('change', applyFilters);
</script>

</x-layout>