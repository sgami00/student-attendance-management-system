<?php

namespace App\Http\Controllers;

use App\Models\{SchoolClass, Student};
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index()
    {
        return view('classes.index', [
            'classes' => SchoolClass::with('students')->get()
        ]);
    }

    public function create()
    {
        return view('classes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'code' => 'required|unique:school_classes'
        ]);
        $validated['teacher_id'] = auth()->id() ?? 1;
        SchoolClass::create($validated);
        return redirect()->route('dashboard');
    }

    public function show($id)
    {
        return view('classes.show', [
            'class' => SchoolClass::with(['students', 'attendances'])->findOrFail($id)
        ]);
    }

    public function edit($id)
    {
        return view('classes.edit', ['class' => SchoolClass::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $class = SchoolClass::findOrFail($id);
        $class->update($request->validate(['name' => 'required', 'code' => 'required']));
        return redirect()->route('dashboard');
    }

    public function destroy($id)
    {
        $class = SchoolClass::findOrFail($id);
        \App\Models\Attendance::where('school_class_id', $id)->delete();
        $class->students()->detach();
        $class->delete();
        return redirect()->route('dashboard');
    }

    public function addStudent(Request $request)
    {
        $student = Student::firstOrCreate(
            ['student_id_number' => $request->student_id_number],
            $request->validate([
                'student_id_number' => 'required',
                'name'              => 'required',
                'email'             => 'required|email',
            ])
        );
        SchoolClass::findOrFail($request->school_class_id)->students()->syncWithoutDetaching($student->id);
        return redirect()->back();
    }

    public function updateStudent(Request $request, $id)
    {
        Student::findOrFail($id)->update($request->validate([
            'name'  => 'required',
            'email' => 'required'
        ]));
        return response()->json(['success' => true]);
    }

    public function destroyStudent($id)
    {
        $s = Student::findOrFail($id);
        $s->schoolClasses()->detach();
        $s->delete();
        return response()->json(['success' => true]);
    }

    // /my-students → Lahat ng students ng teacher across all classes
    public function allStudents()
    {
        $teacherId = auth()->id() ?? 1;

        $classes = SchoolClass::where('teacher_id', $teacherId)
            ->with('students')
            ->get();

        $allStudents = $classes->flatMap(fn($c) => $c->students)->unique('id')->values();

        return view('classes.all-students', compact('classes', 'allStudents'));
    }
}