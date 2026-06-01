{{-- File: resources/views/classes/index.blade.php o resources/views/dashboard.blade.php --}}
<x-layout>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Teacher Dashboard</h1>
            <p class="text-sm text-gray-500">Manage your active classes and automated QR attendance logs.</p>
        </div>
        <a href="{{ route('classes.create') }}" class="bg-indigo-600 text-white font-semibold text-sm px-4 py-2 rounded hover:bg-indigo-700 transition shadow">
            + Create New Class
        </a>
    </div>

    @if($classes->isEmpty())
        <div class="bg-white p-8 rounded shadow text-center border">
            <p class="text-gray-500 italic">No classes found. Click "+ Create New Class" to begin.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($classes as $class)
                <div class="bg-white p-6 rounded shadow relative border-t-4 border-indigo-600 flex flex-col justify-between min-h-[180px] hover:shadow-md transition-shadow duration-200">
                    <div>
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">{{ $class->name }}</h3>
                                <p class="text-sm text-gray-500">Code: <span class="font-mono bg-gray-100 px-1 rounded text-xs">{{ $class->code }}</span> | Enrolled: {{ $class->students->count() }}</p>
                            </div>
                            
                            <div class="flex space-x-1 bg-gray-50 p-1 rounded border border-gray-100">
                                <a href="{{ route('classes.edit', $class->id) }}" class="text-gray-400 hover:text-blue-600 p-1 rounded transition" title="Edit Class Details">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                    </svg>
                                </a>

                                <form action="{{ route('classes.destroy', $class->id) }}" method="POST" onsubmit="return confirm('Sigurado ka bang gusto mong burahin ang klaseng {{ $class->name }}? Lahat ng data nito pati records ng attendance ay permanenteng mawawala.');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-400 hover:text-red-600 p-1 rounded transition" title="Delete Class From System">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 flex space-x-2">
                        <a href="{{ route('attendance.create', $class->id) }}" class="bg-emerald-600 text-white text-sm font-semibold px-4 py-2 rounded hover:bg-emerald-700 transition shadow-sm">
                            Take Attendance
                        </a>
                        <a href="{{ route('classes.show', $class->id) }}" class="bg-gray-100 text-gray-700 text-sm font-semibold px-4 py-2 rounded hover:bg-gray-200 transition border">
                            View Students
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-layout>