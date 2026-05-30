<x-layout>
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Record Attendance for {{ $schoolClass->name }}</h2>
        <form action="{{ route('attendance.store', $schoolClass->id) }}" method="POST">
            @csrf
            <div class="mb-4 max-w-xs">
                <label class="block text-sm font-medium mb-1">Session Date</label>
                <input type="date" name="attendance_date" value="{{ date('Y-m-d') }}" class="w-full border rounded p-2" required>
            </div>

            <table class="w-full text-left mt-4 border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="p-3">Student ID</th>
                        <th class="p-3">Student Name</th>
                        <th class="p-3">Status Matrix</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schoolClass->students as $student)
                        <tr class="border-b">
                            <td class="p-3 text-gray-600 font-mono text-sm">{{ $student->student_id_number }}</td>
                            <td class="p-3 font-medium">{{ $student->name }}</td>
                            <td class="p-3 space-x-4">
                                <label><input type="radio" name="statuses[{{ $student->id }}]" value="present" checked> Present</label>
                                <label><input type="radio" name="statuses[{{ $student->id }}]" value="absent"> Absent</label>
                                <label><input type="radio" name="statuses[{{ $student->id }}]" value="late"> Late</label>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button class="mt-6 bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">Save System Records</button>
        </form>
    </div>
</x-layout>