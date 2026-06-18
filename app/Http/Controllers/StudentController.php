<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\DailyTest;
use App\Models\MonthlyFee;
use App\Models\ParentModel;
use App\Models\Student;
use App\Models\StudentDocument;
use App\Services\StudentCsvService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    // List of students (Active, Completed, Banned, Inactive)
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

    // Create new student
    public function create()
    {
        $classes = loginSchoolActiveClasses();
        $sections = loginSchoolActiveSections();

        return view('student.create', compact('classes', 'sections'));
    }

    // Store new student
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
            'student_photo' => 'nullable|image|mimes:jpeg,jpg,png',
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
                'student_photo' => 'Admin/images/student/profiles/default.png',
                'student_roll_number' => Student::generateRollNumber(),
                'student_admission_date' => $request->student_admission_date,
                'status' => $request->status,
            ]);

            if ($request->hasFile('student_photo')) {
                $photo = $request->file('student_photo');
                $photoName = uniqid('profile_') . '_' . $student->id . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('admin/images/student/profiles'), $photoName);

                $student->update([
                    'student_photo' => 'admin/images/student/profiles/' . $photoName,
                ]);
            }

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

    // View Selected Student Detail by id
    public function show($studentId)
    {
        $student = Student::with('schoolClass', 'section', 'school', 'parent')->find($studentId);

        $attendanceStats = [
            'total' => Attendance::where('student_id', $studentId)->count(),
            'present' => Attendance::where('student_id', $studentId)->where('attendance_status', 'present')->count(),
            'absent' => Attendance::where('student_id', $studentId)->where('attendance_status', 'absent')->count(),
            'leave' => Attendance::where('student_id', $studentId)->where('attendance_status', 'leave')->count(),
        ];

        $dailyTestStats = [
            'total' => DailyTest::where('student_id', $studentId)->count(),
            'attempted' => DailyTest::where('student_id', $studentId)->where('daily_test_obtained', '>', 0)->count(),
            'not_attempted' => DailyTest::where('student_id', $studentId)->where('daily_test_obtained', 0)->count(),
        ];

        return view('student.show', compact('student', 'attendanceStats', 'dailyTestStats'));
    }

    // Edit Selected Student by id
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

    // Update Selected Student by id
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
            'student_photo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            'parent_name' => 'required|string|max:255',
            'parent_phone_no' => 'required|string|max:255',
            'parent_email' => 'nullable|email|max:255',
            'parent_address' => 'nullable|string|max:255',
            'status' => 'required|in:active,completed,banned,inactive',
        ]);

        $student = Student::with('parent')->findOrFail($studentId);

        DB::transaction(function () use ($request, $student, $studentId) {

            if ($request->hasFile('student_photo')) {
                $photo = $request->file('student_photo');
                $photoName = uniqid('profile_') . '_' . $studentId . '.' . $photo->getClientOriginalExtension();
                $photo->move(public_path('admin/images/student/profiles'), $photoName);

                $student->update([
                    'student_photo' => 'admin/images/student/profiles/' . $photoName ?? null,
                ]);
            }


            $student->update([
                'class_id' => $request->class_id,
                'section_id' => $request->section_id,
                'student_name' => $request->student_name,
                'student_email' => $request->student_email,
                'student_phone_no' => $request->student_phone_no,
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

    // Destroy Selected Student by id
    public function destroy($studentId)
    {
        $student = Student::findOrFail($studentId);
        $student->delete();

        return redirect('student')->with('success', 'Student deleted successfully');
    }

    // List of deleted students
    public function trashStudents()
    {
        $students = Student::onlyTrashed()->where('school_id', Auth::user()->school_id)->get();

        return view('student.trash_students', compact('students'));
    }

    // Restore Deleted Student by id
    public function restoreTrashStudent($studentId)
    {
        $student = Student::onlyTrashed()->where('school_id', Auth::user()->school_id)->findOrFail($studentId);
        $student->restore();

        return redirect('student/trash')->with('success', 'Student restored successfully');
    }

    // Attendance History of Selected Student by id
    public function attendanceHistory($studentId)
    {
        $student = Student::with('schoolClass', 'section')->findOrFail($studentId);

        $attendanceHistory = Attendance::where('student_id', $studentId)
            ->orderByDesc('created_at')
            ->get();

        return view('student.attendance_history', compact('student', 'attendanceHistory'));
    }

    // Daily Test History of Selected Student by id
    public function dailyTestHistory($studentId)
    {
        $student = Student::with('schoolClass', 'section')->findOrFail($studentId);

        $dailyTestHistory = DailyTest::with('teacher')
            ->where('student_id', $studentId)
            ->orderByDesc('created_at')
            ->get();

        return view('student.daily_test_history', compact('student', 'dailyTestHistory'));
    }

    // Fee History of Selected Student by id
    public function feeHistory($studentId)
    {
        $student = Student::with('schoolClass', 'section')->findOrFail($studentId);

        $feeHistory = MonthlyFee::where('student_id', $studentId)->get();

        return view('student.fee_history', compact('student', 'feeHistory'));
    }

    // Upgrade Class Form Setup
    public function upgradeClass()
    {
        $classes = loginSchoolActiveClasses();
        $sections = loginSchoolActiveSections();

        return view('student.upgradeclass', compact('classes', 'sections'));
    }

    // Upgrade Class Store by Class and Section ID
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

    // Student Documents by id
    public function documents($studentId)
    {
        $student = Student::findOrFail($studentId);
        $documents = StudentDocument::where('student_id', $studentId)->get();

        return view('student.documents', compact('student', 'documents'));
    }

    // Store student document by student id
    public function storeDocument(Request $request, $studentId)
    {
        $student = Student::where('school_id', Auth::user()->school_id)
            ->findOrFail($studentId);
        $request->validate([
            'document_title' => 'required|string|max:255',
            'document_file' => 'required|file|mimes:jpeg,jpg,png',
        ]);
        $file = $request->file('document_file');
        $extension = $file->getClientOriginalExtension();
        $filename = uniqid($request->document_title . '_') . '_' . $studentId . '.' . $extension;
        $directory = public_path('admin/images/student/documents/' . $student->id);
        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }
        $file->move($directory, $filename);
        StudentDocument::create([
            'student_id' => $student->id,
            'document_title' => $request->document_title,
            'document_file' => 'admin/images/student/documents/' . $student->id . '/' . $filename,
        ]);
        return redirect('student/documents/' . $student->id)
            ->with('success', 'Document uploaded successfully.');
    }

    // Import Form Setup
    public function importForm()
    {
        return view('student.import');
    }

    // Import Template Download
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

    // Import Students from CSV File
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

    // Export Students to CSV File
    public function exportCsv(Request $request, StudentCsvService $csv): Response
    {
        $students = $csv->filteredQuery(Auth::user()->school_id, $request)->get();
        $filename = 'students-' . date('Y-m-d-His') . '.csv';

        if(empty($students) || $students->count() == 0) {
            return redirect()->back()->with('error', 'No students records found');
        }

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

    // Export attendance history records to CSV by student id
    public function exportAttendanceHistoryCsv($studentId)
    {
        $attendances = Attendance::with('student')
            ->whereHas('student', fn ($query) => $query->where('school_id', Auth::user()->school_id)->where('status', 'active'))
            ->where('school_id', Auth::user()->school_id)
            ->where('student_id', $studentId)
            ->orderBy('created_at')
            ->get();

            if(empty($attendances) || $attendances->count() == 0) {
                return redirect()->back()->with('error', 'No attendance history records found');
            }

        return response()->streamDownload(function () use ($attendances) {
            $csv = fopen('php://output', 'w');
            fputcsv($csv, ['Sr No.', 'Student Name', 'Roll Number', 'Date', 'Status']);
            foreach ($attendances as $key => $attendance) {
                fputcsv($csv, [$key + 1, $attendance->student->student_name, $attendance->student->student_roll_number, $attendance->attendance_date, strtoupper($attendance->attendance_status)]);
            }
            fclose($csv);
        }, 'Attendance_By_StudentID_'.$studentId.'.csv');
    }

    // Export daily test history records to CSV by student id
    public function exportDailyTestHistoryCsv($studentId)
    {
        $dailyTests = DailyTest::with('student')
            ->whereHas('student', fn ($query) => $query->where('school_id', Auth::user()->school_id)->where('status', 'active'))
            ->where('school_id', Auth::user()->school_id)
            ->where('student_id', $studentId)
            ->orderBy('created_at')
            ->get();
        
            if(empty($dailyTests) || $dailyTests->count() == 0) {
                return redirect()->back()->with('error', 'No daily test history records found');
            }

        return response()->streamDownload(function () use ($dailyTests) {
            $csv = fopen('php://output', 'w');
            fputcsv($csv, ['Sr No.', 'Student Name', 'Roll Number', 'Test Name', 'Subject', 'Test Obtained', 'Test Total', 'Test Percentage']);
            if(!empty($dailyTests) && $dailyTests->count() > 0) {
                foreach ($dailyTests as $key => $dailyTest) {
                    fputcsv($csv, [$key + 1, $dailyTest->student->student_name, $dailyTest->student->student_roll_number, $dailyTest->daily_test_name, $dailyTest->subject, $dailyTest->daily_test_obtained, $dailyTest->daily_test_total, $dailyTest->daily_test_percentage]);
                }
            }
            fclose($csv);
        }, 'Daily_Test_By_StudentID_'.$studentId.'.csv');
    }

    // export fee history records to CSV by student id
    public function exportFeeHistoryCsv($studentId)
    {
        $feeHistory = MonthlyFee::with('student')
            ->whereHas('student', fn ($query) => $query->where('school_id', Auth::user()->school_id)->where('status', 'active'))
            ->where('school_id', Auth::user()->school_id)
            ->where('student_id', $studentId)
            ->orderBy('created_at')
            ->get();
        
            if(empty($feeHistory) || $feeHistory->count() == 0) {
                return redirect()->back()->with('error', 'No fee history records found');
            }

        return response()->streamDownload(function () use ($feeHistory) {
            $csv = fopen('php://output', 'w');
            fputcsv($csv, ['Sr No.', 'Student Name', 'Roll Number', 'Payment Date', 'Monthly Fee', 'Any Fine', 'Any Discount', 'Total Amount', 'Paid Amount', 'Remaining Amount']);
            if(!empty($feeHistory) && $feeHistory->count() > 0) {
                foreach ($feeHistory as $key => $fee) {
                    fputcsv($csv, [$key + 1, $fee->student->student_name, $fee->student->student_roll_number, $fee->payment_date, $fee->monthly_fee_amount, $fee->any_fine_amount, $fee->any_discount_amount, $fee->total_amount, $fee->paid_amount, $fee->remaining_amount]);
                }
            }
            fclose($csv);
        }, 'Fee_History_By_StudentID_'.$studentId.'.csv');
    }
}
