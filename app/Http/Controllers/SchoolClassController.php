<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;

class SchoolClassController extends Controller
{
    // DISPLAY THE DASHBOARD LIST CARDS
    public function index()
    {
        $classes = SchoolClass::with('students')->get();
        return view('classes.index', compact('classes'));
    }

    // SHOW SINGLE CLASS DETAILS WITH STUDENTS & LOG RECORDS
    public function show($id)
    {
        // Eager load sa relations para gumana ang codes at ang logs sa show view
        $class = SchoolClass::with(['students', 'attendances' => function($query) {
            $query->orderBy('attendance_date', 'desc');
        }])->findOrFail($id);

        return view('classes.show', compact('class'));
    }

    // EDIT FORM VIEW FOR THE WHOLE CLASS RESOURCE NAME
    public function edit($id)
    {
        $class = SchoolClass::findOrFail($id);
        return view('classes.edit', compact('class'));
    }

    // UPDATE CLASS NAME DETAILS
    public function update(Request $request, $id)
    {
        $class = SchoolClass::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:school_classes,code,' . $class->id,
        ]);

        $class->update($validated);

        return redirect()->route('dashboard')->with('success', 'Class details updated successfully.');
    }

    // DELETE ENTIRE CLASS INSTANCE
    public function destroy($id)
    {
        $class = SchoolClass::findOrFail($id);
        $class->delete(); // Buburahin nito ang klase (Tiyakin na may cascade onDelete soft rules sa db)

        return redirect()->route('dashboard')->with('success', 'Class and its assets deleted permanently.');
    }

    // CUSTOM METHOD: ENROLL NEW STUDENT TO THE CLASS FROM QUICK FORM CARD
    public function addStudent(Request $request)
    {
        $validated = $request->validate([
            'school_class_id'   => 'required|exists:school_classes,id',
            'student_id_number' => 'required|unique:students,student_id_number',
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|unique:students,email',
        ]);

        // 1. Gumawa ng malinis na Student row entry sa master database list table
        $student = Student::create([
            'student_id_number' => trim($validated['student_id_number']),
            'name'              => trim($validated['name']),
            'email'             => trim($validated['email']),
        ]);

        // 2. I-attach o idikit ang estudyante sa piniling Class session room relationship handler
        $class = SchoolClass::findOrFail($validated['school_class_id']);
        $class->students()->attach($student->id);

        return redirect()->back()->with('success', 'Student enrolled and QR Pass code issued successfully.');
    }
}