<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    // Get school_id from logged-in user, or first school when auth is not set up
    private function resolveSchoolId(): ?string
    {
        if (Auth::check() && Auth::user()->school_id) {
            return Auth::user()->school_id;
        }

        return School::orderBy('created_at')->value('id');
    }

    public function index()
    {
        $schoolId = $this->resolveSchoolId();

        $teachers = Teacher::with('school')
            ->when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
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

        $schoolId = $this->resolveSchoolId();

        if (! $schoolId) {
            return redirect('teacher/create')->with('error', 'No school found. Please register a school first.');
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
