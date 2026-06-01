<?php

namespace App\Http\Controllers;

use App\Models\{SchoolClass, Student};
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index() { return view('classes.index', ['classes' => SchoolClass::all()]); }
    
    public function create() { return view('classes.create'); }

    public function store(Request $request) {
        $validated = $request->validate(['name' => 'required', 'code' => 'required|unique:school_classes']);
        $validated['teacher_id'] = 1; // Temporary fix para sa database constraint
        SchoolClass::create($validated);
        return redirect()->route('dashboard');
    }

    public function show($id) { return view('classes.show', ['class' => SchoolClass::with('students')->findOrFail($id)]); }

    public function edit($id) { return view('classes.edit', ['class' => SchoolClass::findOrFail($id)]); }

    public function update(Request $request, $id) {
        $class = SchoolClass::findOrFail($id);
        $class->update($request->validate(['name' => 'required', 'code' => 'required']));
        return redirect()->route('dashboard');
    }

    public function destroy($id) {
        $class = SchoolClass::findOrFail($id);
        // Clean up bago burahin para hindi mag-error ang database
        \App\Models\Attendance::where('school_class_id', $id)->delete();
        $class->students()->detach();
        $class->delete();
        return redirect()->route('dashboard');
    }

    public function addStudent(Request $request) {
        $student = Student::create($request->validate([
            'student_id_number' => 'required|unique:students', 
            'name' => 'required', 
            'email' => 'required|email'
        ]));
        SchoolClass::findOrFail($request->school_class_id)->students()->attach($student->id);
        return redirect()->back();
    }

    public function updateStudent(Request $request, $id) {
        Student::findOrFail($id)->update($request->validate(['name' => 'required', 'email' => 'required']));
        return response()->json(['success' => true]);
    }

    public function destroyStudent($id) {
        $s = Student::findOrFail($id); 
        $s->classes()->detach(); 
        $s->delete();
        return response()->json(['success' => true]);
    }
}