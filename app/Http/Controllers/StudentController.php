<?php

namespace App\Http\Controllers;

use App\Models\ParentModel;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    // Get school_id from logged-in user, or first school when auth is not set up
    private function resolveSchoolId(): ?string
    {
        if (Auth::check() && Auth::user()->school_id) {
            return Auth::user()->school_id;
        }

        return School::orderBy('created_at')->value('id');
    }

    private function classesForSchool(?string $schoolId)
    {
        return SchoolClass::when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
            ->orderBy('class_name')
            ->get();
    }

    private function sectionsForSchool(?string $schoolId)
    {
        return Section::with('schoolClass')
            ->when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
            ->orderBy('section_name')
            ->get();
    }

    public function index()
    {
        $schoolId = $this->resolveSchoolId();

        $students = Student::with('schoolClass', 'section', 'parent')
            ->when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.index', compact('students'));
    }

    public function create()
    {
        $schoolId = $this->resolveSchoolId();
        $classes = $this->classesForSchool($schoolId);
        $sections = $this->sectionsForSchool($schoolId);

        return view('student.create', compact('classes', 'sections'));
    }

    public function store(Request $request)
    {
        $schoolId = $this->resolveSchoolId();

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'student_name' => 'required|string|max:255',
            'student_email' => 'nullable|email|max:255',
            'student_phone_no' => 'nullable|string|max:255',
            'student_roll_number' => 'nullable|string|max:255',
            'student_admission_date' => 'nullable|date',
            'parent_name' => 'required|string|max:255',
            'parent_phone_no' => 'required|string|max:255',
            'parent_email' => 'nullable|email|max:255',
            'parent_address' => 'nullable|string|max:255',
        ]);

        if (! $schoolId) {
            return redirect('student/create')->with('error', 'No school found. Please register a school first.');
        }

        if (! $this->validClassAndSection($schoolId, $request->class_id, $request->section_id)) {
            return redirect('student/create')->with('error', 'Invalid class or section selected for this school.');
        }

        DB::transaction(function () use ($request, $schoolId) {
            $student = Student::create([
                'school_id' => $schoolId,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'student_name' => $request->student_name,
                'student_email' => $request->student_email,
                'student_phone_no' => $request->student_phone_no,
                'student_roll_number' => $request->student_roll_number,
                'student_admission_date' => $request->student_admission_date,
            ]);

            ParentModel::create([
                'school_id' => $schoolId,
                'student_id' => $student->id,
                'parent_name' => $request->parent_name,
                'parent_phone_no' => $request->parent_phone_no,
                'parent_email' => $request->parent_email,
                'parent_address' => $request->parent_address,
            ]);
        });

        return redirect('student')->with('success', 'Student created successfully');
    }

    public function show($id)
    {
        $student = Student::with('schoolClass', 'section', 'school', 'parent')->find($id);

        return view('student.show', compact('student'));
    }

    public function edit($id)
    {
        $student = Student::with('parent')->find($id);
        $schoolId = $this->resolveSchoolId();
        $classes = $this->classesForSchool($schoolId);
        $sections = $this->sectionsForSchool($schoolId);

        return view('student.edit', compact('student', 'classes', 'sections'));
    }

    public function update(Request $request, $id)
    {
        $schoolId = $this->resolveSchoolId();

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'student_name' => 'required|string|max:255',
            'student_email' => 'nullable|email|max:255',
            'student_phone_no' => 'nullable|string|max:255',
            'student_roll_number' => 'nullable|string|max:255',
            'student_admission_date' => 'nullable|date',
            'parent_name' => 'required|string|max:255',
            'parent_phone_no' => 'required|string|max:255',
            'parent_email' => 'nullable|email|max:255',
            'parent_address' => 'nullable|string|max:255',
        ]);

        $student = Student::with('parent')->find($id);

        if (! $student) {
            return redirect('student')->with('error', 'Student not found');
        }

        if (! $this->validClassAndSection($schoolId, $request->class_id, $request->section_id)) {
            return redirect('student/edit/' . $id)->with('error', 'Invalid class or section selected for this school.');
        }

        DB::transaction(function () use ($request, $student, $schoolId) {
            $student->update([
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'student_name' => $request->student_name,
                'student_email' => $request->student_email,
                'student_phone_no' => $request->student_phone_no,
                'student_roll_number' => $request->student_roll_number,
                'student_admission_date' => $request->student_admission_date,
            ]);

            if ($student->parent) {
                $student->parent->update([
                    'parent_name' => $request->parent_name,
                    'parent_phone_no' => $request->parent_phone_no,
                    'parent_email' => $request->parent_email,
                    'parent_address' => $request->parent_address,
                ]);
            } else {
                ParentModel::create([
                    'school_id' => $schoolId,
                    'student_id' => $student->id,
                    'parent_name' => $request->parent_name,
                    'parent_phone_no' => $request->parent_phone_no,
                    'parent_email' => $request->parent_email,
                    'parent_address' => $request->parent_address,
                ]);
            }
        });

        return redirect('student')->with('success', 'Student updated successfully');
    }

    public function destroy($id)
    {
        $student = Student::find($id);

        if ($student) {
            $student->delete();

            return redirect('student')->with('success', 'Student deleted successfully');
        }

        return redirect('student')->with('error', 'Student not found');
    }

    private function validClassAndSection(?string $schoolId, string $classId, string $sectionId): bool
    {
        if (! $schoolId) {
            return false;
        }

        $class = SchoolClass::where('id', $classId)->where('school_id', $schoolId)->exists();

        $section = Section::where('id', $sectionId)
            ->where('class_id', $classId)
            ->where('school_id', $schoolId)
            ->exists();

        return $class && $section;
    }
}
