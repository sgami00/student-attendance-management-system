<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    public function index() { return view('classes.index', ['classes' => SchoolClass::all()]); }
    
    public function create() { return view('classes.create'); }

    public function store(Request $request) {
        $validated = $request->validate(['name' => 'required', 'code' => 'required|unique:school_classes']);
        $validated['teacher_id'] = 1; // Default fix para sa error mo
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
        // Manual cleanup para hindi mag-error ang database constraint
        \App\Models\Attendance::where('school_class_id', $id)->delete();
        $class->students()->detach();
        $class->delete();
        return redirect()->route('dashboard');
    }
}