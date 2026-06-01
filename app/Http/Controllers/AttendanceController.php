<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // OPEN THE ATTENDANCE RECORDING INTERFACE PAGE
    public function create(SchoolClass $schoolClass) 
    {
        $schoolClass->load('students');
        return view('attendance.create', compact('schoolClass'));
    }

    // SAVE THE HYBRID ATTENDANCE LIST GRID VIA FORM BLOCK SUBMIT
    public function store(Request $request, SchoolClass $schoolClass) 
    {
        $request->validate([
            'attendance_date' => 'required|date',
            'statuses' => 'required|array',
            'statuses.*' => 'required|in:present,absent,late'
        ]);

        foreach ($request->statuses as $studentId => $status) {
            $student = Student::find($studentId);

            if ($student) {
                // updateOrCreate para hindi magkaroon ng sql row duplicate lock constraints
                Attendance::updateOrCreate(
                    [
                        'school_class_id'   => $schoolClass->id,
                        'student_id_number' => $student->student_id_number, 
                        'attendance_date'   => $request->attendance_date,
                    ],
                    [
                        'status'            => $status,
                        'student_name'      => $student->name,
                    ]
                );
            }
        }

        // Ibalik ang user direkta sa Class overview history log page
        return redirect()->route('classes.show', $schoolClass->id)
                         ->with('success', 'Attendance sheet processed flawlessly.');
    }

    // SHOW THE SPECIFIC ROW SINGLE ATTENDANCE EDIT PREVIEW SHEET
    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);
        return view('attendance.edit', compact('attendance'));
    }

    // COMMIT THE INDIVIDUAL ATTENDANCE RECORD CELL UPDATE
    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:present,absent,late'
        ]);

        $attendance->update($validated);

        return redirect()->route('classes.show', $attendance->school_class_id)
                         ->with('success', 'Attendance logs cell successfully synchronized.');
    }

    // REMOVE AN INDIVIDUAL STUDENT RECORD ROW FROM SELECTION LOGS
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $classId = $attendance->school_class_id; 
        
        $attendance->delete();

        return redirect()->route('classes.show', $classId)
                         ->with('success', 'Student logs record row has been unlinked.');
    }

    // ANALYTICS COUNT DISTRIBUTION METER PANEL
    public function analytics() 
    {
        $attendanceRecords = Attendance::with(['student', 'schoolClass'])->get();

        $presentCount = Attendance::where('status', 'present')->count();
        $absentCount = Attendance::where('status', 'absent')->count();
        $lateCount = Attendance::where('status', 'late')->count();

        return view('analytics.index', compact('attendanceRecords', 'presentCount', 'absentCount', 'lateCount'));
    }
}