<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class SchoolClassApiController extends Controller
{
    // GET /api/classes
    public function index()
    {
        $classes = SchoolClass::with('students')->get();
        return response()->json($classes, 200, [], JSON_PRETTY_PRINT);
    }

    // POST /api/classes
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'code'       => 'required|string|unique:school_classes,code',
            'teacher_id' => 'required|exists:users,id',
        ]);

        $class = SchoolClass::create($validated);

        return response()->json([
            'message' => 'Class created successfully.',
            'data'    => $class,
        ], 201, [], JSON_PRETTY_PRINT);
    }

    // GET /api/classes/{id}
    public function show($id)
    {
        $class = SchoolClass::with('students')->findOrFail($id);
        return response()->json($class, 200, [], JSON_PRETTY_PRINT);
    }

    // PUT /api/classes/{id}
    public function update(Request $request, $id)
    {
        $class = SchoolClass::findOrFail($id);

        $validated = $request->validate([
            'name'       => 'sometimes|required|string|max:255',
            'code'       => 'sometimes|required|string|unique:school_classes,code,' . $id,
            'teacher_id' => 'sometimes|required|exists:users,id',
        ]);

        $class->update($validated);

        return response()->json([
            'message' => 'Class updated successfully.',
            'data'    => $class->fresh(),
        ], 200, [], JSON_PRETTY_PRINT);
    }

    // DELETE /api/classes/{id}
    public function destroy($id)
    {
        $class = SchoolClass::findOrFail($id);

        Attendance::where('school_class_id', $id)->delete();
        $class->students()->detach();
        $class->delete();

        return response()->json([
            'message' => 'Class and its related data deleted successfully.',
        ], 200, [], JSON_PRETTY_PRINT);
    }

    // POST /api/classes/{id}/students
    public function addStudent(Request $request, $id)
    {
        $class = SchoolClass::findOrFail($id);

        $request->validate([
            'student_id_number' => 'required|string',
        ]);

        $student = Student::where('student_id_number', $request->student_id_number)->first();

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
        }

        $class->students()->syncWithoutDetaching([$student->id]);

        return response()->json([
            'message' => 'Student added to class successfully.',
            'student' => $student,
            'class'   => $class->load('students'),
        ], 201, [], JSON_PRETTY_PRINT);
    }

    // GET /api/classes/{id}/students
    public function getStudents($id)
    {
        $class = SchoolClass::with('students')->findOrFail($id);

        return response()->json([
            'class'    => $class->only(['id', 'name', 'code']),
            'students' => $class->students,
            'total'    => $class->students->count(),
        ], 200, [], JSON_PRETTY_PRINT);
    }

    // GET /api/teachers/{teacher_id}/students
    // Lahat ng students ng isang teacher across all classes niya
    public function getTeacherStudents($teacher_id)
    {
        $classes = SchoolClass::where('teacher_id', $teacher_id)
            ->with('students')
            ->get();

        if ($classes->isEmpty()) {
            return response()->json([
                'message'  => 'No classes found for this teacher.',
                'teacher_id' => $teacher_id,
                'total_classes'  => 0,
                'total_students' => 0,
                'classes'  => [],
            ], 200, [], JSON_PRETTY_PRINT);
        }

        // Collect all students, grouped by class
        $classesData = $classes->map(function ($class) {
            return [
                'class_id'   => $class->id,
                'class_name' => $class->name,
                'class_code' => $class->code,
                'students'   => $class->students,
                'total'      => $class->students->count(),
            ];
        });

        // All unique students across all classes (walang duplicate)
        $allStudents = $classes->flatMap(function ($class) {
            return $class->students;
        })->unique('id')->values();

        return response()->json([
            'teacher_id'     => $teacher_id,
            'total_classes'  => $classes->count(),
            'total_students' => $allStudents->count(),
            'all_students'   => $allStudents,
            'by_class'       => $classesData,
        ], 200, [], JSON_PRETTY_PRINT);
    }
}