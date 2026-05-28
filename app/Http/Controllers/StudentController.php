<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\DailyTest;
use App\Models\ParentModel;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Services\StudentCsvService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class StudentController extends Controller
{
    private function classesForSchool(string $schoolId)
    {
        return SchoolClass::where('school_id', $schoolId)
            ->orderBy('class_name')
            ->get();
    }

    private function sectionsForSchool(string $schoolId)
    {
        return Section::with('schoolClass')
            ->where('school_id', $schoolId)
            ->orderBy('section_name')
            ->get();
    }

    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        if (! $schoolId) {
            return redirect('home')->with('error', 'No school is assigned to this user.');
        }
        $classes = $this->classesForSchool($schoolId);
        $sections = $this->sectionsForSchool($schoolId);

        $students = Student::with('schoolClass', 'section', 'parent')
            ->where('school_id', $schoolId)
            ->when($request->filled('class_id'), fn ($query) => $query->where('class_id', $request->class_id))
            ->when($request->filled('section_id'), fn ($query) => $query->where('section_id', $request->section_id))
            ->when($request->filled('name'), function ($query) use ($request) {
                $name = '%' . $request->name . '%';
                $query->where(function ($q) use ($name) {
                    $q->where('student_name', 'like', $name)
                        ->orWhere('student_roll_number', 'like', $name);
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->orderBy('student_name')
            ->get();

        $filters = $request->only(['class_id', 'section_id', 'name', 'status']);
        $statuses = Student::STATUSES;

        return view('student.index', compact('students', 'classes', 'sections', 'filters', 'statuses'));
    }

    public function create()
    {
        $schoolId = Auth::user()->school_id;
        if (! $schoolId) {
            return redirect('home')->with('error', 'No school is assigned to this user.');
        }
        $classes = $this->classesForSchool($schoolId);
        $sections = $this->sectionsForSchool($schoolId);

        $statuses = Student::STATUSES;

        return view('student.create', compact('classes', 'sections', 'statuses'));
    }

    public function importForm()
    {
        return view('student.import');
    }

    public function importTemplate()
    {
        $filename = 'students-import-template.csv';

        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, StudentCsvService::HEADERS);
            fputcsv($out, [
                'Ali Khan',
                'One',
                'A',
                '101',
                'ali@example.com',
                '923001234567',
                date('Y-m-d'),
                'Mr. Khan',
                '923009876543',
                'parent@example.com',
                '123 Main Street',
                'active',
            ]);
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function importStore(Request $request, StudentCsvService $csv)
    {
        $schoolId = Auth::user()->school_id;

        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        if (! $schoolId) {
            return redirect('student/import')->with('error', 'No school is assigned to this user.');
        }

        $result = $csv->import($request->file('csv_file'), $schoolId);

        if ($result['imported'] === 0 && count($result['errors']) > 0) {
            return redirect('student/import')
                ->with('error', 'Import failed. No students were added.')
                ->with('import_errors', $result['errors']);
        }

        $message = $result['imported'] . ' student(s) imported successfully.';
        if (count($result['errors']) > 0) {
            return redirect('student')
                ->with('success', $message)
                ->with('warning', count($result['errors']) . ' row(s) skipped.')
                ->with('import_errors', array_slice($result['errors'], 0, 50));
        }

        return redirect('student')->with('success', $message);
    }

    public function exportCsv(Request $request, StudentCsvService $csv): Response
    {
        $schoolId = Auth::user()->school_id;
        if (! $schoolId) {
            return redirect('student')->with('error', 'No school is assigned to this user.');
        }
        $students = $csv->filteredQuery($schoolId, $request)->get();
        $filename = 'students-' . date('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($csv, $students) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($out, StudentCsvService::HEADERS);
            foreach ($students as $student) {
                fputcsv($out, $csv->rowFromStudent($student));
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function store(Request $request)
    {
        $schoolId = Auth::user()->school_id;

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'student_name' => 'required|string|max:255',
            'student_email' => 'nullable|email|max:255',
            'student_phone_no' => 'nullable|string|max:255',
            'student_roll_number' => 'nullable|string|max:255',
            'student_admission_date' => 'nullable|date',
            'student_photo' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            'parent_name' => 'required|string|max:255',
            'parent_phone_no' => 'required|string|max:255',
            'parent_email' => 'nullable|email|max:255',
            'parent_address' => 'nullable|string|max:255',
            'status' => 'required|in:' . implode(',', Student::STATUSES),
        ]);

        if (! $schoolId) {
            return redirect('student/create')->with('error', 'No school is assigned to this user.');
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
                'student_photo' => $this->resolveStudentPhoto($request),
                'student_roll_number' => $request->student_roll_number,
                'student_admission_date' => $request->student_admission_date,
                'status' => $request->status,
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

        if (! $student) {
            return view('student.show', compact('student'));
        }

        $attendanceStats = [
            'total' => Attendance::where('student_id', $student->id)->count(),
            'present' => Attendance::where('student_id', $student->id)->where('attendance_status', 'present')->count(),
            'absent' => Attendance::where('student_id', $student->id)->where('attendance_status', 'absent')->count(),
            'leave' => Attendance::where('student_id', $student->id)->where('attendance_status', 'leave')->count(),
        ];

        $dailyTestStats = [
            'total' => DailyTest::where('student_id', $student->id)->count(),
            'attempted' => DailyTest::where('student_id', $student->id)->where('daily_test_obtained', '>', 0)->count(),
            'not_attempted' => DailyTest::where('student_id', $student->id)->where('daily_test_obtained', 0)->count(),
        ];

        return view('student.show', compact('student', 'attendanceStats', 'dailyTestStats'));
    }

    // Attendance history — selected student only
    public function attendanceHistory(Request $request, $id)
    {
        $student = Student::with('schoolClass', 'section')->findOrFail($id);

        $attendanceHistory = Attendance::where('student_id', $student->id)
            ->orderByDesc('attendance_date')
            ->get();

        $attendanceStats = [
            'total' => Attendance::where('student_id', $student->id)->count(),
            'present' => Attendance::where('student_id', $student->id)->where('attendance_status', 'present')->count(),
            'absent' => Attendance::where('student_id', $student->id)->where('attendance_status', 'absent')->count(),
            'leave' => Attendance::where('student_id', $student->id)->where('attendance_status', 'leave')->count(),
        ];

        return view('student.attendance_history', compact('student', 'attendanceHistory', 'attendanceStats'));
    }

    // Daily test history — selected student only
    public function dailyTestHistory(Request $request, $id)
    {
        $student = Student::with('schoolClass', 'section')->findOrFail($id);

        $dailyTestHistory = DailyTest::with('teacher')
            ->where('student_id', $student->id)
            ->orderByDesc('daily_test_date')
            ->orderByDesc('created_at')
            ->get();

        $dailyTestStats = [
            'total' => DailyTest::where('student_id', $student->id)->count(),
            'attempted' => DailyTest::where('student_id', $student->id)->where('daily_test_obtained', '>', 0)->count(),
            'not_attempted' => DailyTest::where('student_id', $student->id)->where('daily_test_obtained', 0)->count(),
        ];

        return view('student.daily_test_history', compact('student', 'dailyTestHistory', 'dailyTestStats'));
    }

    public function edit($id)
    {
        $student = Student::with('parent')->find($id);
        $schoolId = Auth::user()->school_id;
        if (! $schoolId) {
            return redirect('student')->with('error', 'No school is assigned to this user.');
        }
        $classes = $this->classesForSchool($schoolId);
        $sections = $this->sectionsForSchool($schoolId);

        $statuses = Student::STATUSES;

        return view('student.edit', compact('student', 'classes', 'sections', 'statuses'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', Student::STATUSES),
        ]);

        $schoolId = Auth::user()->school_id;
        if (! $schoolId) {
            return redirect('student')->with('error', 'No school is assigned to this user.');
        }
        $student = Student::when($schoolId, fn ($q) => $q->where('school_id', $schoolId))->find($id);

        if (! $student) {
            return redirect('student')->with('error', 'Student not found');
        }

        $student->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Student status updated to ' . ucfirst($request->status) . '.');
    }

    public function update(Request $request, $id)
    {
        $schoolId = Auth::user()->school_id;
        if (! $schoolId) {
            return redirect('student')->with('error', 'No school is assigned to this user.');
        }

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'student_name' => 'required|string|max:255',
            'student_email' => 'nullable|email|max:255',
            'student_phone_no' => 'nullable|string|max:255',
            'student_roll_number' => 'nullable|string|max:255',
            'student_admission_date' => 'nullable|date',
            'student_photo' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
            'parent_name' => 'required|string|max:255',
            'parent_phone_no' => 'required|string|max:255',
            'parent_email' => 'nullable|email|max:255',
            'parent_address' => 'nullable|string|max:255',
            'status' => 'required|in:' . implode(',', Student::STATUSES),
        ]);

        $student = Student::with('parent')->find($id);

        if (! $student) {
            return redirect('student')->with('error', 'Student not found');
        }

        if (! $this->validClassAndSection($schoolId, $request->class_id, $request->section_id)) {
            return redirect('student/edit/' . $id)->with('error', 'Invalid class or section selected for this school.');
        }

        DB::transaction(function () use ($request, $student, $schoolId) {
            $photoPath = $this->resolveStudentPhoto($request, $student->student_photo);

            if ($photoPath !== $student->student_photo) {
                $this->deleteUploadedPhoto($student->student_photo);
            }

            $student->update([
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'student_name' => $request->student_name,
                'student_email' => $request->student_email,
                'student_phone_no' => $request->student_phone_no,
                'student_photo' => $photoPath,
                'student_roll_number' => $request->student_roll_number,
                'student_admission_date' => $request->student_admission_date,
                'status' => $request->status,
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

        if (! $student) {
            return redirect('student')->with('error', 'Student not found');
        }

        DB::transaction(function () use ($student) {
            // Model boot cascades soft delete to parent, attendances, daily tests
            $student->delete();
        });

        return redirect('student')->with('success', 'Student and related records deleted successfully');
    }

    public function upgradeClass()
    {
        $schoolId = Auth::user()->school_id;
        if (! $schoolId) {
            return redirect('student')->with('error', 'No school is assigned to this user.');
        }
        $classes = $this->classesForSchool($schoolId);
        $sections = $this->sectionsForSchool($schoolId);

        return view('student.upgradeclass', compact('classes', 'sections'));
    }

    public function upgradeClassStore(Request $request)
    {
        $schoolId = Auth::user()->school_id;

        $request->validate([
            'from_class_id' => 'required|exists:classes,id',
            'from_section_id' => 'required|exists:sections,id',
            'to_class_id' => 'required|exists:classes,id',
            'to_section_id' => 'required|exists:sections,id',
        ]);

        if (! $schoolId) {
            return redirect('student/upgrade-class')->with('error', 'No school is assigned to this user.');
        }

        if (
            $request->from_class_id === $request->to_class_id
            && $request->from_section_id === $request->to_section_id
        ) {
            return redirect('student/upgrade-class')->with('error', 'From and To class/section cannot be the same.');
        }

        if (! $this->validClassAndSection($schoolId, $request->from_class_id, $request->from_section_id)) {
            return redirect('student/upgrade-class')->with('error', 'Invalid source class or section.');
        }

        if (! $this->validClassAndSection($schoolId, $request->to_class_id, $request->to_section_id)) {
            return redirect('student/upgrade-class')->with('error', 'Invalid destination class or section.');
        }

        $updated = Student::where('school_id', $schoolId)
            ->where('class_id', $request->from_class_id)
            ->where('section_id', $request->from_section_id)
            ->update([
                'class_id' => $request->to_class_id,
                'section_id' => $request->to_section_id,
            ]);

        if ($updated === 0) {
            return redirect('student/upgrade-class')->with('error', 'No students found in the selected class and section.');
        }

        return redirect('student')->with('success', $updated . ' student(s) promoted to the new class successfully.');
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

    // Save uploaded image, or default placeholder path if none selected
    private function resolveStudentPhoto(Request $request, ?string $currentPhoto = null): string
    {
        if ($request->hasFile('student_photo')) {
            $stored = $request->file('student_photo')->store('students', 'public');

            return 'storage/' . $stored;
        }

        if ($currentPhoto) {
            return $currentPhoto;
        }

        return Student::DEFAULT_PHOTO;
    }

    // Remove old uploaded file when replaced (not the shared default image)
    private function deleteUploadedPhoto(?string $path): void
    {
        if (! $path || $path === Student::DEFAULT_PHOTO || str_starts_with($path, 'http')) {
            return;
        }

        $fullPath = public_path($path);

        if (File::isFile($fullPath)) {
            File::delete($fullPath);
        }
    }
}
