<?php
namespace App\Http\Controllers;
use App\Models\{SchoolClass, Attendance, Student};
use Illuminate\Http\Request;

class AttendanceController extends Controller {
    public function create(SchoolClass $schoolClass) { return view('attendance.create', ['schoolClass' => $schoolClass->load('students')]); }
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
    public function updateInline(Request $request, $id) {
        Attendance::findOrFail($id)->update($request->validate(['status' => 'required|in:present,absent,late']));
        return response()->json(['success' => true]);
    }
    public function destroyInline($id) {
        Attendance::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}