<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        if (! $schoolId) {
            return redirect('home')->with('error', 'No school is assigned to this user.');
        }

        $teachers = Teacher::with('school')
            ->where('school_id', $schoolId)
            ->orderBy('teacher_name')
            ->get();

        return view('teacher.index', compact('teachers'));
    }

    public function create()
    {
        return view('teacher.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'teacher_name' => 'required|string|max:255',
            'teacher_email' => 'nullable|email|max:255',
            'teacher_phone_no' => 'nullable|string|max:255',
            'teacher_address' => 'nullable|string|max:255',
        ]);

        $schoolId = Auth::user()->school_id;

        if (! $schoolId) {
            return redirect('teacher/create')->with('error', 'No school is assigned to this user.');
        }

        Teacher::create([
            'school_id' => $schoolId,
            'teacher_name' => $request->teacher_name,
            'teacher_email' => $request->teacher_email,
            'teacher_phone_no' => $request->teacher_phone_no,
            'teacher_address' => $request->teacher_address,
        ]);

        return redirect('teacher')->with('success', 'Teacher created successfully');
    }

    public function show($id)
    {
        $teacher = Teacher::with('school')->find($id);

        return view('teacher.show', compact('teacher'));
    }

    public function edit($id)
    {
        $teacher = Teacher::find($id);

        return view('teacher.edit', compact('teacher'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'teacher_name' => 'required|string|max:255',
            'teacher_email' => 'nullable|email|max:255',
            'teacher_phone_no' => 'nullable|string|max:255',
            'teacher_address' => 'nullable|string|max:255',
        ]);

        $teacher = Teacher::find($id);

        if ($teacher) {
            $teacher->update([
                'teacher_name' => $request->teacher_name,
                'teacher_email' => $request->teacher_email,
                'teacher_phone_no' => $request->teacher_phone_no,
                'teacher_address' => $request->teacher_address,
            ]);

            return redirect('teacher')->with('success', 'Teacher updated successfully');
        }

        return redirect('teacher')->with('error', 'Teacher not found');
    }

    public function destroy($id)
    {
        $teacher = Teacher::find($id);

        if ($teacher) {
            $teacher->delete();

            return redirect('teacher')->with('success', 'Teacher deleted successfully');
        }

        return redirect('teacher')->with('error', 'Teacher not found');
    }
}
