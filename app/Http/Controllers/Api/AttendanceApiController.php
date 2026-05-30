<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceApiController extends Controller
{
    // 1. GET ALL ATTENDANCE
    public function index() {
    return response()->json(Attendance::with(['student', 'schoolClass'])->get(), 200);
}
    // 2. CREATE ATTENDANCE VIA API
    public function store(Request $request) {
        $validated = $request->validate([
            'school_class_id'   => 'required|exists:school_classes,id',
            'student_id_number' => 'required|exists:students,student_id_number', // Ginamit ang totoong student card number
            'attendance_date'   => 'required|date',
            'status'            => 'required|in:present,absent,late',
        ]);

        // Kukunin natin ang student name mula sa database para isama sa pag-save
        $student = Student::where('student_id_number', $validated['student_id_number'])->first();

        // Pagsasamahin ang validation data at ang student name
        $saveData = array_merge($validated, [
            'student_name' => $student ? $student->name : 'Unknown Student'
        ]);

        // Gagamit ng updateOrCreate para maiwasan ang duplicate key constraint error sa database mo
        $attendance = Attendance::updateOrCreate(
            [
                'school_class_id'   => $saveData['school_class_id'],
                'student_id_number' => $saveData['student_id_number'],
                'attendance_date'   => $saveData['attendance_date'],
            ],
            [
                'status'       => $saveData['status'],
                'student_name' => $saveData['student_name'],
            ]
        );

        return response()->json(['message' => 'Created/Updated Successfully', 'data' => $attendance], 201);
    }

    // 3. UPDATE STATUS VIA API
    public function update(Request $request, $id) {
        $attendance = Attendance::findOrFail($id);
        $validated = $request->validate(['status' => 'required|in:present,absent,late']);
        
        $attendance->update($validated);
        return response()->json(['message' => 'Updated Successfully', 'data' => $attendance], 200);
    }

    // 4. DELETE RECORD VIA API
    public function destroy($id) {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();
        return response()->json(['message' => 'Record deleted'], 200);
    }
}