{{-- File: resources/views/classes/show.blade.php --}}
<x-layout>
    <div class="bg-white p-6 rounded shadow mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $class->name }} Details</h1>
                <p class="text-sm text-gray-500">Class Code: <span class="font-mono bg-gray-100 px-1 py-0.5 rounded">{{ $class->code }}</span></p>
            </div>
            <a href="/dashboard" class="bg-gray-200 text-gray-700 text-sm px-4 py-2 rounded hover:bg-gray-300 font-semibold transition border">Back to Dashboard</a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-1 bg-white p-6 rounded shadow border-t-4 border-indigo-600 h-fit">
            <h3 class="text-lg font-bold text-gray-800 mb-1">Add New Student</h3>
            <p class="text-xs text-gray-500 mb-4">Enter the information to enroll in this class and generate a System QR pass code.</p>

            <form action="{{ route('students.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="school_class_id" value="{{ $class->id }}">

                <div>
                    <label class="block text-sm font-medium text-gray-700">Student ID Number</label>
                    <input type="text" name="student_id_number" placeholder="E.g. 201152" class="w-full border rounded p-2 mt-1 text-sm font-mono" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Student Full Name</label>
                    <input type="text" name="name" placeholder="Firstname Lastname" class="w-full border rounded p-2 mt-1 text-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" name="email" placeholder="student@domain.com" class="w-full border rounded p-2 mt-1 text-sm" required>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white font-semibold py-2.5 rounded text-sm hover:bg-indigo-700 transition shadow-sm">
                    Enroll Student & Generate QR
                </button>
            </form>
        </div>

        <div class="lg:col-span-2 bg-white p-6 rounded shadow">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Enrolled Student Roster ({{ $class->students->count() }})</h2>
            
            @if($class->students->isEmpty())
                <p class="text-gray-500 italic p-4 bg-gray-50 rounded border text-center">No students currently enrolled in this class module.</p>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse table-auto">
                        <thead>
                            <tr class="bg-gray-50 border-b text-gray-600 text-sm font-semibold">
                                <th class="p-3">ID Number</th>
                                <th class="p-3">Student Name</th>
                                <th class="p-3">Email Address</th>
                                <th class="p-3 text-center">System QR Pass</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($class->students as $student)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3 font-mono text-sm text-gray-600">{{ $student->student_id_number }}</td>
                                    <td class="p-3 font-medium text-gray-800">{{ $student->name }}</td>
                                    <td class="p-3 text-gray-600 text-sm">{{ $student->email }}</td>
                                    <td class="p-3 text-center">
                                        <div class="bg-white p-1.5 border rounded shadow-sm inline-block mx-auto">
                                            @php
                                                $cleanStudentId = trim($student->student_id_number);
                                                $qrPayload = urlencode($class->id . '|' . $cleanStudentId);
                                                $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . $qrPayload;
                                            @endphp
                                            <img src="{{ $qrUrl }}" alt="QR Code" class="w-[60px] h-[60px] block mx-auto">
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="bg-white p-6 rounded shadow mt-6 border-t-4 border-amber-500">
        <h2 class="text-xl font-bold text-gray-800 mb-1">Attendance Session History Logs</h2>
        <p class="text-xs text-gray-500 mb-4">This is where you can view the history of your attendance sheets. You can change or permanently delete them here.</p>

        @if($class->attendances && $class->attendances->isEmpty())
            <p class="text-gray-500 italic p-4 bg-gray-50 rounded border text-center">
No attendance records have been saved for this class yet.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse table-auto">
                    <thead>
                        <tr class="bg-gray-100 border-b text-gray-600 text-sm font-semibold">
                            <th class="p-3">Attendance Date</th>
                            <th class="p-3">Student Name</th>
                            <th class="p-3">ID Number</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-center">Actions Matrix</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($class->attendances as $record)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 font-medium text-gray-800">{{ $record->attendance_date }}</td>
                                <td class="p-3 text-gray-700">{{ $record->student_name }}</td>
                                <td class="p-3 font-mono text-xs text-gray-500">{{ $record->student_id_number }}</td>
                                <td class="p-3">
                                    @if($record->status === 'present')
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded-full font-semibold uppercase">Present</span>
                                    @elseif($record->status === 'late')
                                        <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-0.5 rounded-full font-semibold uppercase">Late</span>
                                    @else
                                        <span class="bg-red-100 text-red-800 text-xs px-2 py-0.5 rounded-full font-semibold uppercase">Absent</span>
                                    @endif
                                </td>
                                <td class="p-3 text-center space-x-1 whitespace-nowrap">
                                    <a href="{{ route('attendance.edit', $record->id) }}" class="bg-blue-600 text-white text-xs px-3 py-1.5 rounded hover:bg-blue-700 font-semibold inline-block shadow-sm">
                                        Edit
                                    </a>

                                    <form action="{{ route('attendance.destroy', $record->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete the attendance record of {{ $record->student_name }} for today?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 text-white text-xs px-3 py-1.5 rounded hover:bg-red-700 font-semibold shadow-sm">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-layout>