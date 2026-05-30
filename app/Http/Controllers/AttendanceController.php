<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function create(SchoolClass $schoolClass) 
    {
        $schoolClass->load('students');
        return view('attendance.create', compact('schoolClass'));
    }

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
                // FIXED: Idinagdag ang student_id_number sa criteria array
                Attendance::updateOrCreate(
                    [
                        'school_class_id'   => $schoolClass->id,
                        'student_id_number' => $student->student_id_number, // Dito nalalaman ng system kung kanino itatatak ang status
                        'attendance_date'   => $request->attendance_date,
                    ],
                    [
                        'status'            => $status,
                        'student_name'      => $student->name,
                    ]
                );
            }
        }

        return redirect()->route('dashboard')->with('success', 'Attendance recorded perfectly.');
    }

    public function analytics() 
    {
        $attendanceRecords = Attendance::with(['student', 'schoolClass'])->get();

        $presentCount = Attendance::where('status', 'present')->count();
        $absentCount = Attendance::where('status', 'absent')->count();
        $lateCount = Attendance::where('status', 'late')->count();

        return view('analytics.index', compact('attendanceRecords', 'presentCount', 'absentCount', 'lateCount'));
    }
}