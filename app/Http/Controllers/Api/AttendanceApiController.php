<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceApiController extends Controller
{
    // GET /api/attendance
    public function index(Request $request)
    {
        $query = Attendance::with(['student', 'schoolClass']);

        if ($request->filled('school_class_id'))  $query->where('school_class_id', $request->school_class_id);
        if ($request->filled('attendance_date'))   $query->where('attendance_date', $request->attendance_date);
        if ($request->filled('status'))            $query->where('status', $request->status);
        if ($request->filled('student_name'))      $query->where('student_name', 'like', '%' . $request->student_name . '%');
        if ($request->filled('student_id_number')) $query->where('student_id_number', $request->student_id_number);

        return response()->json($query->get(), 200, [], JSON_PRETTY_PRINT);
    }

    // GET /api/attendance/{id}
    public function show($id)
    {
        $attendance = Attendance::with(['student', 'schoolClass'])->findOrFail($id);
        return response()->json($attendance, 200, [], JSON_PRETTY_PRINT);
    }

    // POST /api/attendance
    public function store(Request $request)
    {
        $request->validate([
            'school_class_id'   => 'required|exists:school_classes,id',
            'student_id_number' => 'required|string',
            'attendance_date'   => 'nullable|date',
            'status'            => 'nullable|in:present,absent,late',
        ]);

        $class   = SchoolClass::findOrFail($request->school_class_id);
        $student = Student::where('student_id_number', $request->student_id_number)->first();

        $wasCreated = $wasEnrolled = false;

        if (!$student) {
            $request->validate([
                'name'  => 'required|string|max:255',
                'email' => 'required|email|unique:students,email',
            ]);
            $student = Student::create([
                'student_id_number' => $request->student_id_number,
                'name'              => $request->name,
                'email'             => $request->email,
            ]);
            $wasCreated = true;
        }

        if (!$class->students()->where('students.id', $student->id)->exists()) {
            $class->students()->attach($student->id);
            $wasEnrolled = true;
        }

        $attendance = Attendance::updateOrCreate(
            [
                'school_class_id'   => $class->id,
                'student_id_number' => $student->student_id_number,
                'attendance_date'   => $request->attendance_date ?? today()->toDateString(),
            ],
            [
                'status'       => $request->status ?? 'present',
                'student_name' => $student->name,
            ]
        );

        $actions = [];
        if ($wasCreated)  $actions[] = 'Student created';
        if ($wasEnrolled) $actions[] = 'Enrolled in class';
        $actions[] = 'Attendance recorded';

        return response()->json([
            'message'    => implode(' → ', $actions) . '.',
            'student'    => $student,
            'class'      => $class->only(['id', 'name', 'code']),
            'attendance' => $attendance,
        ], 201, [], JSON_PRETTY_PRINT);
    }

    // PUT/PATCH /api/attendance/{id}
    public function update(Request $request, $id)
    {
        $attendance = Attendance::findOrFail($id);

        $request->validate([
            'school_class_id'   => 'sometimes|exists:school_classes,id',
            'student_id_number' => 'sometimes|string',
            'name'              => 'sometimes|string|max:255',
            'email'             => 'sometimes|email',
            'attendance_date'   => 'sometimes|date',
            'status'            => 'sometimes|in:present,absent,late',
        ]);

        if ($request->filled('student_id_number') || $request->filled('name') || $request->filled('email')) {
            $studentIdNumber = $request->student_id_number ?? $attendance->student_id_number;
            $student = Student::where('student_id_number', $studentIdNumber)->first();

            if ($student) {
                if ($request->filled('name'))              $student->name              = $request->name;
                if ($request->filled('email'))             $student->email             = $request->email;
                if ($request->filled('student_id_number')) $student->student_id_number = $request->student_id_number;
                $student->save();
            }
        }

        $attendanceData = [];
        if ($request->filled('school_class_id'))   $attendanceData['school_class_id']   = $request->school_class_id;
        if ($request->filled('student_id_number'))  $attendanceData['student_id_number'] = $request->student_id_number;
        if ($request->filled('name'))               $attendanceData['student_name']      = $request->name;
        if ($request->filled('attendance_date'))    $attendanceData['attendance_date']   = $request->attendance_date;
        if ($request->filled('status'))             $attendanceData['status']            = $request->status;

        $attendance->update($attendanceData);

        return response()->json([
            'message' => 'Attendance updated successfully.',
            'data'    => $attendance->fresh()->load(['student', 'schoolClass']),
        ], 200, [], JSON_PRETTY_PRINT);
    }

    // DELETE /api/attendance/{id}
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $attendance->delete();

        return response()->json([
            'message' => 'Attendance record deleted.',
        ], 200, [], JSON_PRETTY_PRINT);
    }

    // DELETE /api/attendance/all
    public function destroyAll()
    {
        $count = Attendance::count();

        // Use query()->delete() instead of truncate() to avoid
        // FK constraint issues with MySQL
        Attendance::query()->delete();

        return response()->json([
            'message' => 'All attendance records deleted successfully.',
            'deleted' => $count,
        ], 200, [], JSON_PRETTY_PRINT);
    }

    // GET /api/attendance/students
    public function getAllStudents(Request $request)
    {
        if ($request->filled('class_id')) {
            $students = Student::whereHas('schoolClasses', function ($q) use ($request) {
                $q->where('school_classes.id', $request->class_id);
            })->with('schoolClasses:id,name,code')->get();
        } else {
            $students = Student::with('schoolClasses:id,name,code')->get();
        }

        return response()->json([
            'total'    => $students->count(),
            'students' => $students,
        ], 200, [], JSON_PRETTY_PRINT);
    }

    // GET /api/attendance/student/{id}
    public function showStudent($id)
    {
        $student = Student::with('schoolClasses:id,name,code')->findOrFail($id);
        return response()->json($student, 200, [], JSON_PRETTY_PRINT);
    }

    // PUT/PATCH /api/attendance/student/{id}
    public function updateStudent(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $request->validate([
            'name'              => 'sometimes|required|string|max:255',
            'email'             => 'sometimes|required|email|unique:students,email,' . $id,
            'student_id_number' => 'sometimes|required|string|unique:students,student_id_number,' . $id,
        ]);

        $student->update($request->only(['name', 'email', 'student_id_number']));

        return response()->json([
            'message' => 'Student updated successfully.',
            'student' => $student->fresh()->load('schoolClasses:id,name,code'),
        ], 200, [], JSON_PRETTY_PRINT);
    }

    // DELETE /api/attendance/student/{id}
    public function destroyStudent($id)
    {
        $student = Student::findOrFail($id);
        $student->schoolClasses()->detach();
        $student->delete();

        return response()->json([
            'message' => 'Student deleted successfully.',
        ], 200, [], JSON_PRETTY_PRINT);
    }
}