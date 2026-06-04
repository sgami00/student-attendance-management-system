<?php
namespace App\Http\Controllers;

use App\Models\{Attendance, Student, SchoolClass};
use Illuminate\Http\Request;

class AttendanceController extends Controller 
{
    public function create(SchoolClass $schoolClass) { 
        return view('attendance.create', ['schoolClass' => $schoolClass->load('students')]); 
    }

    public function store(Request $request, SchoolClass $schoolClass) {
        foreach ($request->statuses as $studentId => $status) {
            $student = Student::find($studentId);
            if ($student) {
                Attendance::updateOrCreate(
                    ['school_class_id' => $schoolClass->id, 'student_id_number' => $student->student_id_number, 'attendance_date' => $request->attendance_date],
                    ['status' => $status, 'student_name' => $student->name]
                );
            }
        }
        return redirect()->route('dashboard');
    }

    public function analytics(Request $request) {
        $query = Attendance::query();
        if ($request->has('attendance_date') && $request->attendance_date) {
            $query->where('attendance_date', $request->attendance_date);
        }

        $attendanceData = $query->get();
        $presentCount = (clone $query)->where('status', 'present')->count();
        $absentCount = (clone $query)->where('status', 'absent')->count();
        $lateCount = (clone $query)->where('status', 'late')->count();
        
        return view('analytics.index', compact('attendanceData', 'presentCount', 'absentCount', 'lateCount'));
    }

    public function edit($id) {
        $attendance = Attendance::findOrFail($id);
        return view('attendance.edit', compact('attendance'));
    }

    public function updateInline(Request $request, $id) {
        Attendance::findOrFail($id)->update($request->validate(['status' => 'required|in:present,absent,late']));
        return response()->json(['success' => true]);
    }

    public function destroyInline($id) {
        Attendance::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    // ─── NEW: Student Attendance Log ───────────────────────────────────────────
    public function studentLog(Student $student)
    {
        $attendances = $student->attendances()
            ->with('schoolClass')
            ->orderBy('attendance_date', 'desc')
            ->get();

        $presentCount = $attendances->where('status', 'present')->count();
        $absentCount  = $attendances->where('status', 'absent')->count();
        $lateCount    = $attendances->where('status', 'late')->count();
        $total        = $attendances->count();

        return view('attendance.student-log', compact(
            'student', 'attendances', 'presentCount', 'absentCount', 'lateCount', 'total'
        ));
    }
}