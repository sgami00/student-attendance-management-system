<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use App\Models\SchoolClass;
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

        // Late counts as 50%, absent counts as 0%
        $attendanceRate = $total > 0
            ? round((($presentCount + ($lateCount * 0.5)) / $total) * 100)
            : 0;

        return view('attendance.student-portal', compact(
            'student', 'attendances', 'presentCount', 'absentCount', 'lateCount', 'total', 'attendanceRate'
        ));
    }

    /**
     * Show the self-enrollment form.
     * Student enters a Class Code to enroll themselves.
     */
    public function showEnroll()
    {
        $studentId = session('student_id');

        if (!$studentId) {
            return redirect()->route('student.login')
                ->with('error', 'Please sign in first.');
        }

        $student = Student::findOrFail($studentId);

        // Load the classes the student is already enrolled in
        $enrolledClasses = $student->schoolClasses()->get();

        return view('attendance.student-enroll', compact('student', 'enrolledClasses'));
    }

    /**
     * Handle self-enrollment form submission.
     * Looks up the class by code, then attaches the student if not yet enrolled.
     */
    public function enroll(Request $request)
    {
        $studentId = session('student_id');

        if (!$studentId) {
            return redirect()->route('student.login')
                ->with('error', 'Please sign in first.');
        }

        $request->validate([
            'class_code' => 'required|string|max:50',
        ]);

        $student = Student::findOrFail($studentId);

        // Find the class by code (case-insensitive)
        $class = SchoolClass::whereRaw('LOWER(code) = ?', [strtolower(trim($request->class_code))])->first();

        if (!$class) {
            return back()
                ->withInput()
                ->with('error', 'No class found with that code. Please double-check and try again.');
        }

        // Check if already enrolled
        $alreadyEnrolled = $student->schoolClasses()->where('school_class_id', $class->id)->exists();

        if ($alreadyEnrolled) {
            return back()
                ->withInput()
                ->with('warning', "You are already enrolled in \"{$class->name}\".");
        }

        // Attach (enroll) the student to the class
        $student->schoolClasses()->attach($class->id);

        return redirect()->route('student.enroll')
            ->with('success', "Successfully enrolled in \"{$class->name}\" ({$class->code})!");
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