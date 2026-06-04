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
}