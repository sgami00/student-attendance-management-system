{{-- File: resources/views/attendance/edit.blade.php --}}
<x-layout>
    <div class="max-w-md mx-auto bg-white p-6 rounded shadow border-t-4 border-blue-600 mt-10">
        <h2 class="text-xl font-bold text-gray-800 mb-2">Edit Attendance Record</h2>
        <p class="text-xs text-gray-500 mb-6">Baguhin ang status ng attendance na naitala mula sa QR system.</p>

        <!-- Ang form na ito ay tatawag sa update route ng AttendanceController -->
        <form action="{{ route('attendance.update', $attendance->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-xs font-semibold uppercase text-gray-400 tracking-wider">Student Name</label>
                    <div class="text-base font-medium text-gray-800 p-2 bg-gray-50 rounded border mt-1">{{ $attendance->student_name }}</div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase text-gray-400 tracking-wider">ID Number</label>
                    <div class="font-mono text-sm text-gray-600 p-2 bg-gray-50 rounded border mt-1">{{ $attendance->student_id_number }}</div>
                </div>

                <div>
                    <label class="block text-xs font-semibold uppercase text-gray-400 tracking-wider">Date Logged</label>
                    <div class="text-sm text-gray-600 p-2 bg-gray-50 rounded border mt-1">{{ $attendance->attendance_date }}</div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Update Attendance Status</label>
                    <div class="flex gap-4 p-3 bg-gray-50 rounded border">
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="status" value="present" {{ $attendance->status === 'present' ? 'checked' : '' }} class="text-blue-600">
                            <span class="ml-2 text-sm text-gray-700 font-medium">Present</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="status" value="absent" {{ $attendance->status === 'absent' ? 'checked' : '' }} class="text-blue-600">
                            <span class="ml-2 text-sm text-gray-700 font-medium">Absent</span>
                        </label>
                        <label class="inline-flex items-center cursor-pointer">
                            <input type="radio" name="status" value="late" {{ $attendance->status === 'late' ? 'checked' : '' }} class="text-blue-600">
                            <span class="ml-2 text-sm text-gray-700 font-medium">Late</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- BUTTON CONTROLS -->
            <div class="flex justify-end gap-2">
                <a href="javascript:history.back()" class="bg-gray-200 text-gray-700 px-4 py-2 rounded text-sm hover:bg-gray-300 font-semibold transition">Cancel</a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700 font-semibold shadow transition">Save Changes</button>
            </div>
        </form>
    </div>
</x-layout>