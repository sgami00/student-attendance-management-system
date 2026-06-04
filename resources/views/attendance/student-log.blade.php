<x-layout>

    <div class="mb-6">
        <a href="{{ url()->previous() }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
            ← Back
        </a>
    </div>

    @php
        $attendanceRate = $total > 0 ? round((($presentCount + ($lateCount * 0.5)) / $total) * 100) : 0;
    @endphp

    <div class="bg-white rounded shadow p-6 mb-6 border-t-4 border-indigo-600 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $student->name }}</h1>
            <p class="text-sm text-gray-500 mt-1">
                ID: <span class="font-mono bg-gray-100 px-1.5 py-0.5 rounded text-xs">{{ $student->student_id_number }}</span>
                &nbsp;·&nbsp; {{ $student->email }}
            </p>
        </div>
        <div class="text-center md:text-right">
            <div class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-0.5">Attendance Rate</div>
            <div class="text-4xl font-extrabold
                @if($attendanceRate >= 75) text-emerald-500
                @elseif($attendanceRate >= 50) text-yellow-500
                @else text-red-500
                @endif
            ">{{ $attendanceRate }}%</div>

        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded shadow p-5 text-center border-t-4 border-emerald-400">
            <div class="text-xs font-semibold uppercase tracking-widest text-emerald-500 mb-2">Present</div>
            <div class="text-5xl font-extrabold text-emerald-500">{{ $presentCount }}</div>
            <div class="text-xs text-gray-400 mt-1">sessions</div>
        </div>
        <div class="bg-white rounded shadow p-5 text-center border-t-4 border-red-400">
            <div class="text-xs font-semibold uppercase tracking-widest text-red-400 mb-2">Absent</div>
            <div class="text-5xl font-extrabold text-red-400">{{ $absentCount }}</div>
            <div class="text-xs text-gray-400 mt-1">sessions</div>
        </div>
        <div class="bg-white rounded shadow p-5 text-center border-t-4 border-yellow-400">
            <div class="text-xs font-semibold uppercase tracking-widest text-yellow-500 mb-2">Late</div>
            <div class="text-5xl font-extrabold text-yellow-400">{{ $lateCount }}</div>
            <div class="text-xs text-gray-400 mt-1">sessions</div>
        </div>
    </div>

    <div class="bg-white rounded shadow overflow-hidden">
        <div class="p-5 border-b border-gray-100">
            <h2 class="text-lg font-bold text-gray-800">Attendance History</h2>
            <p class="text-xs text-gray-400 mt-0.5">{{ $total }} record(s) · latest first</p>
        </div>

        @if($attendances->isEmpty())
            <div class="p-10 text-center text-gray-400 italic">
                <i class="fa-regular fa-folder-open text-3xl mb-3 block"></i>
                No attendance records found for this student.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                            <th class="px-5 py-3 font-semibold">Date</th>
                            <th class="px-5 py-3 font-semibold">Class</th>
                            <th class="px-5 py-3 font-semibold">Status</th>
                            <th class="px-5 py-3 font-semibold text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $record)
                            <tr class="border-t border-gray-100 hover:bg-gray-50 transition-colors" id="log-row-{{ $record->id }}">
                                <td class="px-5 py-3">
                                    <div class="font-medium text-gray-800">
                                        {{ \Carbon\Carbon::parse($record->attendance_date)->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ \Carbon\Carbon::parse($record->attendance_date)->format('l') }}
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="text-gray-700">{{ $record->schoolClass->name ?? '—' }}</div>
                                    <span class="font-mono text-xs bg-gray-100 px-1.5 py-0.5 rounded text-gray-500">
                                        {{ $record->schoolClass->code ?? '' }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    @if($record->status === 'present')
                                        <span class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full uppercase">Present</span>
                                    @elseif($record->status === 'late')
                                        <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-3 py-1 rounded-full uppercase">Late</span>
                                    @else
                                        <span class="bg-red-100 text-red-600 text-xs font-semibold px-3 py-1 rounded-full uppercase">Absent</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-center space-x-1 whitespace-nowrap">
                                    <button onclick="openEditModal({{ $record->id }}, '{{ $record->status }}')"
                                            class="bg-blue-600 text-white text-xs px-3 py-1.5 rounded hover:bg-blue-700 font-semibold shadow-sm">
                                        Edit
                                    </button>
                                    <button onclick="deleteRecord({{ $record->id }})"
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

    <div id="edit-modal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-sm mx-4">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Edit Attendance Status</h3>
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-600 mb-2">Status</label>
                <select id="edit-status" name="status"
                        class="w-full border border-gray-300 rounded p-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    <option value="present">Present</option>
                    <option value="absent">Absent</option>
                    <option value="late">Late</option>
                </select>
            </div>
            <div class="flex gap-2 justify-end">
                <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 text-sm rounded bg-gray-200 text-gray-700 hover:bg-gray-300 font-semibold">
                    Cancel
                </button>
                <button type="button" onclick="saveEdit()"
                        class="px-4 py-2 text-sm rounded bg-indigo-600 text-white hover:bg-indigo-700 font-semibold">
                    Save
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentRecordId = null;

        function openEditModal(id, status) {
            currentRecordId = id;
            document.getElementById('edit-status').value = status;
            document.getElementById('edit-modal').classList.remove('hidden');
        }

        function closeEditModal() {
            currentRecordId = null;
            document.getElementById('edit-modal').classList.add('hidden');
        }

        function saveEdit() {
            if (!currentRecordId) return;
            const status = document.getElementById('edit-status').value;
            fetch(`/attendance-log/${currentRecordId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ status }),
            })
            .then(res => res.json())
            .then(data => { if (data.success) { closeEditModal(); location.reload(); } })
            .catch(() => alert('Failed to update. Please try again.'));
        }

        function deleteRecord(id) {
            if (!confirm('Delete this attendance record?')) return;
            fetch(`/attendance-log/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            })
            .then(res => res.json())
            .then(data => { if (data.success) document.getElementById(`log-row-${id}`)?.remove(); })
            .catch(() => alert('Failed to delete. Please try again.'));
        }
    </script>

</x-layout>