<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\DailyTest;
use App\Models\ParentModel;
use App\Models\Student;
use App\Services\StudentCsvService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $classes = loginSchoolActiveClasses();
        $sections = loginSchoolActiveSections();

        $students = Student::with('schoolClass', 'section', 'parent')
            ->where('school_id', Auth::user()->school_id)
            ->when($request->filled('class_id'), function ($query) use ($request) {
                $query->where('class_id', $request->class_id);
            })
            ->when($request->filled('section_id'), function ($query) use ($request) {
                $query->where('section_id', $request->section_id);
            })
            ->when($request->filled('name_roll_number'), function ($query) use ($request) {
                $name_roll_number = '%' . $request->name_roll_number . '%';
                $query->where(function ($q) use ($name_roll_number) {
                    $q->where('student_name', 'like', $name_roll_number)
                        ->orWhere('student_roll_number', 'like', $name_roll_number);
                });
            })
            ->when($request->filled('status'), fn($query) => $query->where('status', $request->status))
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.index', compact('students', 'classes', 'sections'));
    }

    public function create()
    {
        $classes = loginSchoolActiveClasses();
        $sections = loginSchoolActiveSections();

        return view('student.create', compact('classes', 'sections'));
    }

    public function store(Request $request)
    {
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
            'status' => 'required|in:active,completed,banned,inactive',
        ]);

        DB::transaction(function () use ($request) {
            $student = Student::create([
                'user_id' => Auth::user()->id,
                'school_id' => Auth::user()->school_id,
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'student_name' => $request->student_name,
                'student_email' => $request->student_email,
                'student_phone_no' => $request->student_phone_no,
                'student_photo' => 'images/default-student-profile.png',
                'student_roll_number' => Student::generateRollNumber(),
                'student_admission_date' => $request->student_admission_date,
                'status' => $request->status,
            ]);

            ParentModel::create([
                'school_id' => Auth::user()->school_id,
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

    // Edit student — selected student only
    public function edit($studentId)
    {
        $student = Student::with('parent')->findOrFail($studentId);
        $schoolId = Auth::user()->school_id;
        if (! $schoolId) {
            return redirect('student')->with('error', 'No school is assigned to this user.');
        }
        $classes = loginSchoolActiveClasses();
        $sections = loginSchoolActiveSections();

        return view('student.edit', compact('student', 'classes', 'sections'));
    }

    public function trashStudents()
    {
        $students = Student::onlyTrashed()->where('school_id', Auth::user()->school_id)->get();

        return view('student.trash_students', compact('students'));
    }

    public function restoreTrashStudent($studentId)
    {
        $student = Student::onlyTrashed()->where('school_id', Auth::user()->school_id)->findOrFail($studentId);
        $student->restore();

        return redirect('student/trash')->with('success', 'Student restored successfully');
    }

    // Attendance history — selected student only
    public function attendanceHistory($studentId)
    {
        $student = Student::with('schoolClass', 'section')->findOrFail($studentId);

        $attendanceHistory = Attendance::where('student_id', $studentId)
            ->orderByDesc('created_at')
            ->get();

        return view('student.attendance_history', compact('student', 'attendanceHistory'));
    }

    // Daily test history — selected student only
    public function dailyTestHistory($studentId)
    {
        $student = Student::with('schoolClass', 'section')->findOrFail($studentId);

        $dailyTestHistory = DailyTest::with('teacher')
            ->where('student_id', $studentId)
            ->orderByDesc('created_at')
            ->get();

        return view('student.daily_test_history', compact('student', 'dailyTestHistory'));
    }

    // Update student — selected student only
    public function update(Request $request, $studentId)
    {
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
            'status' => 'required|in:active,completed,banned,inactive',
        ]);

        $student = Student::with('parent')->findOrFail($studentId);

        DB::transaction(function () use ($request, $student, $studentId) {

            $student->update([
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'student_name' => $request->student_name,
                'student_email' => $request->student_email,
                'student_phone_no' => $request->student_phone_no,
                'student_photo' => 'images/default-student-profile.png',
                'student_roll_number' => $request->student_roll_number,
                'student_admission_date' => $request->student_admission_date,
                'status' => $request->status,
            ]);

            if (!empty($student->parent)) {
                $student->parent->update([
                    'parent_name' => $request->parent_name,
                    'parent_phone_no' => $request->parent_phone_no,
                    'parent_email' => $request->parent_email,
                    'parent_address' => $request->parent_address,
                ]);
            } else {
                ParentModel::create([
                    'school_id' => Auth::user()->school_id,
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

    // Destroy student — selected student only
    public function destroy($studentId)
    {
        $student = Student::findOrFail($studentId);
        $student->delete();

        return redirect('student')->with('success', 'Student deleted successfully');
    }

    public function upgradeClass()
    {
        $classes = loginSchoolActiveClasses();
        $sections = loginSchoolActiveSections();

        return view('student.upgradeclass', compact('classes', 'sections'));
    }

    public function upgradeClassStore(Request $request)
    {
        $request->validate([
            'from_class_id' => 'required|exists:classes,id',
            'from_section_id' => 'required|exists:sections,id',
            'to_class_id' => 'required|exists:classes,id',
            'to_section_id' => 'required|exists:sections,id',
        ]);

        if (
            $request->from_class_id === $request->to_class_id
            && $request->from_section_id === $request->to_section_id
        ) {
            return redirect('student/upgrade-class')->with('error', 'From and To class/section cannot be the same.');
        }

        DB::beginTransaction();

            $updated = Student::where('school_id', Auth::user()->school_id)
                ->where('class_id', $request->from_class_id)
                ->where('section_id', $request->from_section_id)
                ->update([
                    'class_id' => $request->to_class_id,
                    'section_id' => $request->to_section_id,
                ]);

        DB::commit();

        if ($updated === 0) {
            return redirect('student/upgrade-class')->with('error', 'No students found in the selected class and section.');
        }

        return redirect('student')->with('success', $updated . ' student(s) promoted to the new class successfully.');
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
}
