<?php

namespace App\Http\Controllers;

use App\Models\DailyTest;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DailyTestController extends Controller
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

        $testGroups = DailyTest::query()
            ->when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
            ->select('class_id', 'section_id', 'daily_test_date', 'daily_test_name', 'teacher_id', 'subject')
            ->selectRaw('MAX(daily_test_total) as total_marks')
            ->selectRaw('COUNT(*) as total_students')
            ->groupBy('class_id', 'section_id', 'daily_test_date', 'daily_test_name', 'teacher_id', 'subject')
            ->orderByDesc('daily_test_date')
            ->get();

        $classIds = $testGroups->pluck('class_id')->unique();
        $sectionIds = $testGroups->pluck('section_id')->unique();
        $teacherIds = $testGroups->pluck('teacher_id')->unique();

        $classes = SchoolClass::whereIn('id', $classIds)->get()->keyBy('id');
        $sections = Section::whereIn('id', $sectionIds)->get()->keyBy('id');
        $teachers = Teacher::whereIn('id', $teacherIds)->get()->keyBy('id');

        return view('daily-test.index', compact('testGroups', 'classes', 'sections', 'teachers'));
    }

    public function create()
    {
        $schoolId = $this->resolveSchoolId();
        $classes = $this->classesForSchool($schoolId);
        $sections = $this->sectionsForSchool($schoolId);

        return view('daily-test.create', compact('classes', 'sections'));
    }

    public function studentsList(Request $request)
    {
        $schoolId = $this->resolveSchoolId();

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
        ]);

        if (! $this->validClassAndSection($schoolId, $request->class_id, $request->section_id)) {
            return response()->json(['message' => 'Invalid class or section'], 422);
        }

        $students = Student::where('school_id', $schoolId)
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->orderBy('student_name')
            ->get(['id', 'student_name', 'student_roll_number']);

        return response()->json($students);
    }

    public function store(Request $request)
    {
        $schoolId = $this->resolveSchoolId();

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'daily_test_date' => 'required|date',
            'daily_test_name' => 'required|string|max:255',
            'teacher_name' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'daily_test_total' => 'required|integer|min:1',
            'students' => 'required|array|min:1',
            'students.*' => 'required|integer|min:0',
        ]);

        if (! $schoolId) {
            return redirect('daily-test/create')->with('error', 'No school found. Please register a school first.');
        }

        if (! $this->validClassAndSection($schoolId, $request->class_id, $request->section_id)) {
            return redirect('daily-test/create')->with('error', 'Invalid class or section selected for this school.');
        }

        $totalMarks = (int) $request->daily_test_total;

        foreach ($request->students as $obtained) {
            if ((int) $obtained > $totalMarks) {
                return redirect('daily-test/create')->with('error', 'Obtained marks cannot be greater than total marks.');
            }
        }

        $teacher = Teacher::firstOrCreate(
            ['school_id' => $schoolId, 'teacher_name' => $request->teacher_name],
            ['school_id' => $schoolId, 'teacher_name' => $request->teacher_name]
        );

        $exists = DailyTest::where('school_id', $schoolId)
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->whereDate('daily_test_date', $request->daily_test_date)
            ->where('daily_test_name', $request->daily_test_name)
            ->where('teacher_id', $teacher->id)
            ->where('subject', $request->subject)
            ->exists();

        if ($exists) {
            return redirect('daily-test/create')->with('error', 'Daily test already exists for this class, section, date and test name.');
        }

        $studentIds = array_keys($request->students);
        $validCount = Student::where('school_id', $schoolId)
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->whereIn('id', $studentIds)
            ->count();

        if ($validCount !== count($studentIds)) {
            return redirect('daily-test/create')->with('error', 'Invalid student list for selected class and section.');
        }

        DB::transaction(function () use ($request, $schoolId, $teacher, $totalMarks) {
            foreach ($request->students as $studentId => $obtained) {
                $obtained = (int) $obtained;
                $percentage = $totalMarks > 0 ? round(($obtained / $totalMarks) * 100, 2) : 0;

                DailyTest::create([
                    'school_id' => $schoolId,
                    'class_id' => $request->class_id,
                    'section_id' => $request->section_id,
                    'student_id' => $studentId,
                    'teacher_id' => $teacher->id,
                    'daily_test_date' => $request->daily_test_date,
                    'daily_test_name' => $request->daily_test_name,
                    'subject' => $request->subject,
                    'daily_test_obtained' => $obtained,
                    'daily_test_total' => $totalMarks,
                    'daily_test_percentage' => $percentage,
                ]);
            }
        });

        return redirect('daily-test')->with('success', 'Daily test saved successfully');
    }

    public function show(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'daily_test_date' => 'required|date',
            'daily_test_name' => 'required|string',
            'teacher_id' => 'required|exists:teachers,id',
            'subject' => 'required|string',
        ]);

        $schoolId = $this->resolveSchoolId();

        $dailyTests = DailyTest::with('student', 'teacher')
            ->when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->whereDate('daily_test_date', $request->daily_test_date)
            ->where('daily_test_name', $request->daily_test_name)
            ->where('teacher_id', $request->teacher_id)
            ->where('subject', $request->subject)
            ->orderBy('created_at')
            ->get();

        $schoolClass = SchoolClass::find($request->class_id);
        $section = Section::find($request->section_id);
        $totalMarks = $dailyTests->first()?->daily_test_total ?? 0;

        return view('daily-test.show', compact('dailyTests', 'schoolClass', 'section', 'totalMarks'));
    }

    public function update(Request $request)
    {
        $schoolId = $this->resolveSchoolId();

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'daily_test_date' => 'required|date',
            'daily_test_name' => 'required|string',
            'teacher_id' => 'required|exists:teachers,id',
            'subject' => 'required|string',
            'daily_test_total' => 'required|integer|min:1',
            'students' => 'required|array|min:1',
            'students.*' => 'required|integer|min:0',
        ]);

        $totalMarks = (int) $request->daily_test_total;

        foreach ($request->students as $obtained) {
            if ((int) $obtained > $totalMarks) {
                return redirect()->back()->with('error', 'Obtained marks cannot be greater than total marks.');
            }
        }

        DB::transaction(function () use ($request, $schoolId, $totalMarks) {
            foreach ($request->students as $studentId => $obtained) {
                $obtained = (int) $obtained;
                $percentage = $totalMarks > 0 ? round(($obtained / $totalMarks) * 100, 2) : 0;

                DailyTest::when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
                    ->where('class_id', $request->class_id)
                    ->where('section_id', $request->section_id)
                    ->where('student_id', $studentId)
                    ->whereDate('daily_test_date', $request->daily_test_date)
                    ->where('daily_test_name', $request->daily_test_name)
                    ->where('teacher_id', $request->teacher_id)
                    ->where('subject', $request->subject)
                    ->update([
                        'daily_test_obtained' => $obtained,
                        'daily_test_total' => $totalMarks,
                        'daily_test_percentage' => $percentage,
                    ]);
            }
        });

        return redirect()->route('daily-test.show', [
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'daily_test_date' => $request->daily_test_date,
            'daily_test_name' => $request->daily_test_name,
            'teacher_id' => $request->teacher_id,
            'subject' => $request->subject,
        ])->with('success', 'Daily test updated successfully');
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
