<x-layout>
    <div class="bg-white p-6 rounded shadow mb-6">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $class->name }}</h1>
                <p class="text-sm text-gray-500">Class Code: <span class="font-mono bg-gray-100 px-1 py-0.5 rounded">{{ $class->code }}</span></p>
            </div>
            <div class="space-x-2">
                <a href="{{ route('classes.edit', $class->id) }}" class="bg-yellow-500 text-white text-sm px-4 py-2 rounded hover:bg-yellow-600">Edit Class Details</a>
                <a href="/dashboard" class="bg-gray-200 text-gray-700 text-sm px-4 py-2 rounded hover:bg-gray-300">Back</a>
            </div>
        </div>

        <hr class="my-4">

        <h2 class="text-lg font-semibold text-gray-700 mb-3">Enrolled Students ({{ $class->students->count() }})</h2>
        
        @if($class->students->isEmpty())
            <p class="text-gray-500 italic p-4 bg-gray-50 rounded border">No students are currently enrolled in this class session.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b text-gray-600 text-sm font-semibold">
                            <th class="p-3">ID Number</th>
                            <th class="p-3">Student Name</th>
                            <th class="p-3">Email Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($class->students as $student)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 font-mono text-sm text-gray-600">{{ $student->student_id_number }}</td>
                                <td class="p-3 font-medium text-gray-800">{{ $student->name }}</td>
                                <td class="p-3 text-gray-600 text-sm">{{ $student->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-layout>