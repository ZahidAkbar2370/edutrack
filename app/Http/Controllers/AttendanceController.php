<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
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

        $attendanceGroups = Attendance::query()
            ->when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
            ->select('class_id', 'section_id', 'attendance_date')
            ->selectRaw('COUNT(*) as total_students')
            ->selectRaw("SUM(CASE WHEN attendance_status = 'present' THEN 1 ELSE 0 END) as present_count")
            ->selectRaw("SUM(CASE WHEN attendance_status = 'absent' THEN 1 ELSE 0 END) as absent_count")
            ->selectRaw("SUM(CASE WHEN attendance_status = 'leave' THEN 1 ELSE 0 END) as leave_count")
            ->groupBy('class_id', 'section_id', 'attendance_date')
            ->orderByDesc('attendance_date')
            ->get();

        $classIds = $attendanceGroups->pluck('class_id')->unique();
        $sectionIds = $attendanceGroups->pluck('section_id')->unique();

        $classes = SchoolClass::whereIn('id', $classIds)->get()->keyBy('id');
        $sections = Section::whereIn('id', $sectionIds)->get()->keyBy('id');

        return view('attendance.index', compact('attendanceGroups', 'classes', 'sections'));
    }

    public function create()
    {
        $schoolId = $this->resolveSchoolId();
        $classes = $this->classesForSchool($schoolId);
        $sections = $this->sectionsForSchool($schoolId);

        return view('attendance.create', compact('classes', 'sections'));
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
            'attendance_date' => 'required|date',
            'students' => 'required|array|min:1',
            'students.*' => 'required|in:present,absent',
        ]);

        if (! $schoolId) {
            return redirect('attendance/create')->with('error', 'No school found. Please register a school first.');
        }

        if (! $this->validClassAndSection($schoolId, $request->class_id, $request->section_id)) {
            return redirect('attendance/create')->with('error', 'Invalid class or section selected for this school.');
        }

        $exists = Attendance::where('school_id', $schoolId)
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->whereDate('attendance_date', $request->attendance_date)
            ->exists();

        if ($exists) {
            return redirect('attendance/create')->with('error', 'Attendance already marked for this class, section and date.');
        }

        $studentIds = array_keys($request->students);
        $validCount = Student::where('school_id', $schoolId)
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->whereIn('id', $studentIds)
            ->count();

        if ($validCount !== count($studentIds)) {
            return redirect('attendance/create')->with('error', 'Invalid student list for selected class and section.');
        }

        DB::transaction(function () use ($request, $schoolId) {
            foreach ($request->students as $studentId => $status) {
                Attendance::create([
                    'school_id' => $schoolId,
                    'class_id' => $request->class_id,
                    'section_id' => $request->section_id,
                    'student_id' => $studentId,
                    'attendance_date' => $request->attendance_date,
                    'attendance_status' => $status,
                ]);
            }
        });

        return redirect('attendance')->with('success', 'Attendance saved successfully');
    }

    public function show(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'attendance_date' => 'required|date',
        ]);

        $schoolId = $this->resolveSchoolId();

        $attendances = Attendance::with('student')
            ->when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->whereDate('attendance_date', $request->attendance_date)
            ->orderBy('created_at')
            ->get();

        $schoolClass = SchoolClass::find($request->class_id);
        $section = Section::find($request->section_id);

        return view('attendance.show', compact(
            'attendances',
            'schoolClass',
            'section'
        ));
    }

    public function update(Request $request)
    {
        $schoolId = $this->resolveSchoolId();

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'attendance_date' => 'required|date',
            'students' => 'required|array|min:1',
            'students.*' => 'required|in:present,absent,leave',
        ]);

        DB::transaction(function () use ($request, $schoolId) {
            foreach ($request->students as $studentId => $status) {
                Attendance::when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
                    ->where('class_id', $request->class_id)
                    ->where('section_id', $request->section_id)
                    ->where('student_id', $studentId)
                    ->whereDate('attendance_date', $request->attendance_date)
                    ->update(['attendance_status' => $status]);
            }
        });

        return redirect()->route('attendance.show', [
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'attendance_date' => $request->attendance_date,
        ])->with('success', 'Attendance updated successfully');
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
