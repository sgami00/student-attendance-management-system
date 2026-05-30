<x-layout>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Your Managed Classes</h1>
        <a href="{{ route('classes.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700">+ Create Class</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($classes as $class)
            <div class="bg-white p-6 rounded shadow border-t-4 border-indigo-600">
                <h3 class="text-xl font-bold mb-2">{{ $class->name }}</h3>
                <p class="text-gray-500 text-sm mb-4">Code: {{ $class->code }} | Enrolled: {{ $class->students_count }}</p>
                <div class="flex space-x-2">
                    <a href="{{ route('attendance.create', $class->id) }}" class="bg-green-500 text-white text-xs px-3 py-2 rounded hover:bg-green-600">Take Attendance</a>
                    <a href="{{ route('classes.show', $class->id) }}" class="bg-gray-200 text-gray-700 text-xs px-3 py-2 rounded hover:bg-gray-300">View Students</a>
                </div>
            </div>
        @endforeach
    </div>
</x-layout>