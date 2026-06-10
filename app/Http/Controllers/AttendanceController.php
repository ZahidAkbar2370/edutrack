<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $filterClasses = loginSchoolActiveClasses();
        $filterSections = loginSchoolActiveSections();

        $attendanceGroups = Attendance::query()
            ->whereHas('student', fn ($query) => $query->where('school_id', Auth::user()->school_id)->where('status', 'active'))
            ->where('school_id', Auth::user()->school_id)
            ->when($request->filled('class_id'), fn ($query) => $query->where('class_id', $request->class_id))
            ->when($request->filled('section_id'), fn ($query) => $query->where('section_id', $request->section_id))
            ->when($request->filled('date'), fn ($query) => $query->whereDate('attendance_date', $request->date))
            ->when(! $request->filled('date') && $request->filled('date_from'), fn ($query) => $query->whereDate('attendance_date', '>=', $request->date_from))
            ->when(! $request->filled('date') && $request->filled('date_to'), fn ($query) => $query->whereDate('attendance_date', '<=', $request->date_to))
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

        $filters = $request->only(['class_id', 'section_id', 'date', 'date_from', 'date_to']);

        return view('attendance.index', compact(
            'attendanceGroups',
            'classes',
            'sections',
            'filterClasses',
            'filterSections',
            'filters'
        ));
    }

    public function create()
    {
        $classes = loginSchoolActiveClasses();
        $sections = loginSchoolActiveSections();

        return view('attendance.create', compact('classes', 'sections'));
    }

    // public function studentsList(Request $request)
    // {
    //     $schoolId = Auth::user()->school_id;
    //     if (! $schoolId) {
    //         return response()->json(['message' => 'No school is assigned to this user.'], 422);
    //     }

    //     $request->validate([
    //         'class_id' => 'required|exists:classes,id',
    //         'section_id' => 'required|exists:sections,id',
    //     ]);

    //     if (! $this->validClassAndSection($schoolId, $request->class_id, $request->section_id)) {
    //         return response()->json(['message' => 'Invalid class or section'], 422);
    //     }

    //     $students = Student::where('school_id', $schoolId)
    //         ->active()
    //         ->where('class_id', $request->class_id)
    //         ->where('section_id', $request->section_id)
    //         ->orderBy('student_name')
    //         ->get(['id', 'student_name', 'student_roll_number']);

    //     return response()->json($students);
    // }

    public function store(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'attendance_date' => 'required|date',
            'students' => 'required|array|min:1',
            'students.*' => 'required|in:present,absent,leave',
        ]);

        $exists = Attendance::where('school_id', Auth::user()->school_id)
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->whereDate('attendance_date', $request->attendance_date)
            ->exists();

        if ($exists) {
            return redirect('attendance/create')->with('error', 'Attendance already marked for this class, section and date.');
        }

        $studentIds = array_keys($request->students);
        $validCount = Student::where('school_id', Auth::user()->school_id)
            ->where('status', 'active')
            ->where('class_id', $request->class_id)
            ->where('section_id', $request->section_id)
            ->whereIn('id', $studentIds)
            ->count();

        if ($validCount !== count($studentIds)) {
            return redirect('attendance/create')->with('error', 'Invalid student list for selected class and section.');
        }

        DB::transaction(function () use ($request) {
            foreach ($request->students as $studentId => $status) {
                Attendance::create([
                    'user_id' => Auth::user()->id,
                    'school_id' => Auth::user()->school_id,
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

        $attendances = Attendance::with('student')
            ->whereHas('student', fn ($query) => $query->where('school_id', Auth::user()->school_id)->where('status', 'active'))
            ->where('school_id', Auth::user()->school_id)
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
        $schoolId = Auth::user()->school_id;
        if (! $schoolId) {
            return redirect('attendance')->with('error', 'No school is assigned to this user.');
        }

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'attendance_date' => 'required|date',
            'students' => 'required|array|min:1',
            'students.*' => 'required|in:present,absent,leave',
        ]);

        DB::transaction(function () use ($request, $schoolId) {
            foreach ($request->students as $studentId => $status) {
                Attendance::where('school_id', $schoolId)
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
