<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolClassController extends Controller
{
    public function index() {
        // Reads only classes owned by authenticated teacher
        $classes = SchoolClass::where('teacher_id', Auth::id())->withCount('students')->get();
        return view('classes.index', compact('classes'));
    }

    public function create() {
        return view('classes.create');
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:school_classes,code|max:50',
        ]);

        $validated['teacher_id'] = Auth::id();
        SchoolClass::create($validated);

        return redirect()->route('dashboard')->with('success', 'Class created successfully!');
    }

    public function show(SchoolClass $class) {
        $class->load('students');
        return view('classes.show', compact('class'));
    }

    public function edit(SchoolClass $class) {
        return view('classes.edit', compact('class'));
    }

    public function update(Request $request, SchoolClass $class) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:school_classes,code,' . $class->id,
        ]);

        $class->update($validated);
        return redirect()->route('dashboard')->with('success', 'Class updated successfully!');
    }

    public function destroy(SchoolClass $class) {
        $class->delete();
        return redirect()->route('dashboard')->with('success', 'Class deleted successfully!');
    }
}