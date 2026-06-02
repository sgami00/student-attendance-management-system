<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceApiController extends Controller
{
    // 1. GET ALL ATTENDANCE
    public function index(Request $request)
    {
        $query = Attendance::with(['student', 'schoolClass']);

        if ($request->filled('school_class_id')) {
            $query->where('school_class_id', $request->school_class_id);
        }

        if ($request->filled('attendance_date')) {
            $query->where('attendance_date', $request->attendance_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('student_name')) {
            $query->where('student_name', 'like', '%' . $request->student_name . '%');
        }

        if ($request->filled('student_id_number')) {
            $query->where('student_id_number', $request->student_id_number);
        }

        return response()->json($query->get(), 200, [], JSON_PRETTY_PRINT);
    }

    // 2. CREATE ATTENDANCE VIA API
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_class_id'   => 'required|exists:school_classes,id',
            'student_id_number' => 'required|exists:students,student_id_number',
            'attendance_date'   => 'required|date',
            'status'            => 'required|in:present,absent,late',
        ]);

        $student = Student::where('student_id_number', $validated['student_id_number'])->first();

        $saveData = array_merge($validated, [
            'student_name' => $student ? $student->name : 'Unknown Student'
        ]);

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

        return response()->json([
            'message' => 'Created/Updated Successfully',
            'data'    => $attendance,
        ], 201, [], JSON_PRETTY_PRINT);
    }

    // 3. UPDATE STATUS VIA API
    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        $validated  = $request->validate(['status' => 'required|in:present,absent,late']);

        $attendance->update($validated);

        return response()->json([
            'message' => 'Updated Successfully',
            'data'    => $attendance,
        ], 200, [], JSON_PRETTY_PRINT);
    }

    // 4. DELETE RECORD VIA API
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return response()->json([
            'message' => 'Record deleted',
        ], 200, [], JSON_PRETTY_PRINT);
    }
}