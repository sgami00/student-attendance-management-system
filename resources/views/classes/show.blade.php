<x-layout>
    <div class="bg-white p-6 rounded shadow mb-6 border-t-4 border-indigo-600">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ $class->name }}</h1>
                <p class="text-sm text-gray-500 mt-1">
                    Class Code: <span class="font-mono bg-gray-100 px-1.5 py-0.5 rounded text-xs">{{ $class->code }}</span>
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('attendance.create', $class->id) }}"
                   class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 font-semibold text-sm shadow transition-colors">
                    + Record Attendance
                </a>
                <a href="{{ route('classes.edit', $class->id) }}"
                   class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 font-semibold text-sm shadow transition-colors">
                    Edit Class
                </a>
            </div>
        </div>
    </div>

    {{-- Add Student Form --}}
    <div class="bg-white p-6 rounded shadow mb-6 border-t-4 border-green-500">
        <h2 class="text-xl font-bold text-gray-800 mb-1">Enroll New Student</h2>
        <p class="text-xs text-gray-500 mb-4">Fill in the details below to add a student to this class.</p>

        <form action="{{ route('students.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end">
            @csrf
            <input type="hidden" name="school_class_id" value="{{ $class->id }}">

            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Student ID Number</label>
                <input type="text" name="student_id_number" required
                       class="w-full border border-gray-300 rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                       placeholder="e.g. 2024-00123">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Full Name</label>
                <input type="text" name="name" required
                       class="w-full border border-gray-300 rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                       placeholder="e.g. Juan Dela Cruz">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1">Email Address</label>
                <input type="email" name="email" required
                       class="w-full border border-gray-300 rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                       placeholder="e.g. juan@school.edu">
            </div>
            <div>
                <button type="submit"
                        class="w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 font-semibold text-sm shadow transition-colors">
                    Enroll Student
                </button>
            </div>
        </form>
    </div>

    {{-- Students Table --}}
    <div class="bg-white p-6 rounded shadow mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-1">Enrolled Students</h2>
        <p class="text-xs text-gray-500 mb-4">
            {{ $class->students->count() }} student(s) currently enrolled. Click <strong>View Log</strong> to see a student's full attendance history.
        </p>
        
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
                            <th class="p-3 text-center">QR Pass</th>
                            {{-- ─── NEW COLUMN ─── --}}
                            <th class="p-3 text-center">Attendance Log</th>
                            <th class="p-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($class->students as $student)
                            <tr class="border-b hover:bg-gray-50" id="student-row-{{ $student->id }}">
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
                                {{-- ─── NEW: View Log Button ─── --}}
                                <td class="p-3 text-center">
                                    <a href="{{ route('students.log', $student->id) }}"
                                       class="bg-indigo-100 text-indigo-700 hover:bg-indigo-600 hover:text-white text-xs px-3 py-1.5 rounded font-semibold inline-block transition-colors shadow-sm">
                                        📋 View Log
                                    </a>
                                </td>
                                <td class="p-3 text-center space-x-1 whitespace-nowrap">
                                    <button onclick="openEditModal({{ $student->id }}, '{{ addslashes($student->name) }}', '{{ $student->email }}')"
                                            class="bg-blue-600 text-white text-xs px-3 py-1.5 rounded hover:bg-blue-700 font-semibold shadow-sm">
                                        Edit
                                    </button>
                                    <button onclick="deleteStudent({{ $student->id }})"
                                            class="bg-red-600 text-white text-xs px-3 py-1.5 rounded hover:bg-red-700 font-semibold shadow-sm">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Attendance Session History --}}
    <div class="bg-white p-6 rounded shadow mt-6 border-t-4 border-amber-500">
        <h2 class="text-xl font-bold text-gray-800 mb-1">Attendance Session History Logs</h2>
        <p class="text-xs text-gray-500 mb-4">View, edit, or delete attendance records for this class.</p>

        @if($class->attendances && $class->attendances->isEmpty())
            <p class="text-gray-500 italic p-4 bg-gray-50 rounded border text-center">
                No attendance records have been saved for this class yet.
            </p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse table-auto">
                    <thead>
                        <tr class="bg-gray-100 border-b text-gray-600 text-sm font-semibold">
                            <th class="p-3">Attendance Date</th>
                            <th class="p-3">Student Name</th>
                            <th class="p-3">ID Number</th>
                            <th class="p-3">Status</th>
                            <th class="p-3 text-center">Actions</th>
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
                                    <a href="{{ route('attendance.edit', $record->id) }}"
                                       class="bg-blue-600 text-white text-xs px-3 py-1.5 rounded hover:bg-blue-700 font-semibold inline-block shadow-sm">
                                        Edit
                                    </a>
                                    <form action="{{ route('attendance.destroy', $record->id) }}" method="POST" class="inline-block"
                                          onsubmit="return confirm('Delete attendance record of {{ $record->student_name }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-600 text-white text-xs px-3 py-1.5 rounded hover:bg-red-700 font-semibold shadow-sm">
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

    {{-- Edit Student Modal --}}
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 flex">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Edit Student</h3>
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Full Name</label>
                    <input type="text" id="edit-name"
                           class="w-full border border-gray-300 rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Email Address</label>
                    <input type="email" id="edit-email"
                           class="w-full border border-gray-300 rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-5">
                <button onclick="closeEditModal()"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 font-semibold text-sm">
                    Cancel
                </button>
                <button onclick="saveStudent()"
                        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 font-semibold text-sm shadow">
                    Save Changes
                </button>
            </div>
        </div>
    </div>

<script>
    let currentStudentId = null;

    function openEditModal(id, name, email) {
        currentStudentId = id;
        document.getElementById('edit-name').value = name;
        document.getElementById('edit-email').value = email;
        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        currentStudentId = null;
    }

    function saveStudent() {
        const name  = document.getElementById('edit-name').value;
        const email = document.getElementById('edit-email').value;

        fetch(`/students/${currentStudentId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                             || '{{ csrf_token() }}'
            },
            body: JSON.stringify({ name, email })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                closeEditModal();
                location.reload();
            }
        });
    }

    function deleteStudent(id) {
        if (!confirm('Are you sure you want to remove this student?')) return;

        fetch(`/students/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                             || '{{ csrf_token() }}'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`student-row-${id}`)?.remove();
            }
        });
    }
</script>

</x-layout>