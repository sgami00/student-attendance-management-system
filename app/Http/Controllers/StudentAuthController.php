<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use Illuminate\Http\Request;

class StudentAuthController extends Controller
{
    /**
     * Show the Student Login page.
     */
    public function showLogin()
    {
        return view('auth.student-login');
    }

    /**
     * Handle the Student Login form submission.
     * No password needed — students log in with Student ID Number only.
     */
    public function login(Request $request)
    {
        $request->validate([
            'student_id_number' => 'required|string',
        ]);

        $student = Student::where('student_id_number', trim($request->student_id_number))->first();

        if (!$student) {
            return back()
                ->withInput()
                ->with('error', 'No student found with that ID number. Please check and try again.');
        }

        // Store student ID in session
        session(['student_id' => $student->id]);

        return redirect()->route('student.attendance');
    }

    /**
     * Show the student's own attendance log.
     */
    public function attendance()
    {
        $studentId = session('student_id');

        if (!$studentId) {
            return redirect()->route('student.login')
                ->with('error', 'Please sign in first.');
        }

        $student = Student::findOrFail($studentId);

        $attendances = $student->attendances()
            ->with('schoolClass')
            ->orderBy('attendance_date', 'desc')
            ->get();

        $presentCount = $attendances->where('status', 'present')->count();
        $absentCount  = $attendances->where('status', 'absent')->count();
        $lateCount    = $attendances->where('status', 'late')->count();
        $total        = $attendances->count();
        $attendanceRate = $total > 0 ? round(($presentCount / $total) * 100) : 0;

        return view('attendance.student-portal', compact(
            'student', 'attendances', 'presentCount', 'absentCount', 'lateCount', 'total', 'attendanceRate'
        ));
    }

    /**
     * Log out the student.
     */
    public function logout(Request $request)
    {
        $request->session()->forget('student_id');
        return redirect()->route('student.login');
    }
}