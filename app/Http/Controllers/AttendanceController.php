<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\ParentModel;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\WhatsappDevice;
use App\Models\WhatsappMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    // Show all attendance records
    public function index(Request $request)
    {
        $classes = loginSchoolActiveClasses();
        $sections = loginSchoolActiveSections();

        $attendanceGroups = Attendance::query()
        ->with(['schoolClass', 'section'])
        ->where('school_id', Auth::user()->school_id)
        ->when($request->filled('class_id'), fn ($query) =>
            $query->where('class_id', $request->class_id))
        ->when($request->filled('section_id'), fn ($query) =>
            $query->where('section_id', $request->section_id))
        ->when($request->filled('date'), fn ($query) =>
            $query->whereDate('attendance_date', $request->date))
        ->when(! $request->filled('date') && $request->filled('date_from'), fn ($query) =>
            $query->whereDate('attendance_date', '>=', $request->date_from))
        ->when(! $request->filled('date') && $request->filled('date_to'), fn ($query) =>
            $query->whereDate('attendance_date', '<=', $request->date_to))
        ->select(
            'class_id',
            'section_id',
            'attendance_date'
        )
        ->selectRaw('COUNT(*) as total_students')
        ->selectRaw("SUM(CASE WHEN attendance_status = 'present' THEN 1 ELSE 0 END) as present_count")
        ->selectRaw("SUM(CASE WHEN attendance_status = 'absent' THEN 1 ELSE 0 END) as absent_count")
        ->selectRaw("SUM(CASE WHEN attendance_status = 'leave' THEN 1 ELSE 0 END) as leave_count")
        ->groupBy('class_id', 'section_id', 'attendance_date')
        ->orderByDesc('attendance_date')
        ->get();

        return view('schooladmin.attendance.index', compact(
            'attendanceGroups',
            'classes',
            'sections',
        ));
    }

    // Show the form for creating a new attendance record
    public function create()
    {
        $classes = loginSchoolActiveClasses();
        $sections = loginSchoolActiveSections();

        return view('schooladmin.attendance.create', compact('classes', 'sections'));
    }

    // Store a new attendance record
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
                    'attendance_code' => Attendance::generateAttendanceCode(),
                ]);
            }
        });

        return redirect('attendance')->with('success', 'Attendance saved successfully');
    }

    // Show the form for editing an existing attendance record
    public function edit($classId, $sectionId, $attendanceDate)
    {
        $attendances = Attendance::with('student')
            ->whereHas('student', fn ($query) => $query->where('school_id', Auth::user()->school_id)->where('status', 'active'))
            ->where('school_id', Auth::user()->school_id)
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->whereDate('attendance_date', $attendanceDate)
            ->orderBy('created_at')
            ->get();

        $schoolClass = SchoolClass::find($classId);
        $section = Section::find($sectionId);
        
        return view('schooladmin.attendance.edit', compact('attendances', 'schoolClass', 'section', 'attendanceDate'));
    }

    // Update an existing attendance record
    public function update(Request $request)
    {

        $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'attendance_date' => 'required|date',
            'students' => 'required|array|min:1',
            'students.*' => 'required|in:present,absent,leave',
        ]);

        DB::transaction(function () use ($request) {

            foreach ($request->students as $studentId => $status) {
                Attendance::where('id', $request->attendance_id)->where('student_id', $studentId)
                    ->update(['attendance_status' => $status]);
            }

        });

        return redirect()->route('attendance.show', [
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'attendance_date' => $request->attendance_date,
        ])->with('success', 'Attendance updated successfully');
    }

    // Show the details of an attendance record
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

        return view('schooladmin.attendance.show', compact(
            'attendances',
            'schoolClass',
            'section'
        ));
    }

    function reportAttendance($classId, $sectionId, $attendanceDate)
    {
        $attendanceList = Attendance::where('school_id', Auth::user()->school_id)->where('class_id', $classId)->where('section_id', $sectionId)->where('attendance_date', $attendanceDate)->get();

        $whatsappDevice = WhatsappDevice::where('school_id', Auth::user()->school_id)->first();

        $class = SchoolClass::find($classId);
        $section = Section::find($sectionId);
        $school = School::find(Auth::user()->school_id);

        foreach($attendanceList as $attendance) {
            $student = Student::find($attendance->student_id);
            $parent = ParentModel::where('student_id', $student->id)->first();


            WhatsappMessage::create([
                'school_id' => Auth::user()->school_id,
                'student_id' => $student->id,
                'parent_id' => $parent->id,
                'message_type' => 'attendance',
                'from_number' => $whatsappDevice->wachat_device_number,
                'to_number' => $parent->parent_phone_no,
                'message' => 'Aslam o Alaikum, '.$parent->parent_name.'\n\n Attendance Update\n- Roll #: '.$student->student_roll_number.'\n- Student Name: '.$student->student_name.'\n- Class: '.$class->class_name.'\n- Section: '.$section->section_name.'\n- Attendance Date: '.$attendance->attendance_date.'\n- Attendance Status: '.$attendance->attendance_status.'\n\n Best Regard, \n '.$school->school_name,
            ]);

            $attendance->whatsapp_status = 'sent';
            $attendance->update();
        }

        return redirect()->back()->with('success', 'Attendance reported successfully');

        // $sendMessageUrl = env('WACHAT_API_URL').'/send-message';
            // $apiKey = env('WACHAT_API_KEY');

            // $messageBodyToStudent = "Aslam o Alaikum, ".$student->student_name.
            //             "\n\n Attendance Update".
            //             "\n-  Roll #: ".$student->student_roll_number.
            //             "\n-  Class: ".$class->class_name.
            //             "\n-  Section: ".$section->section_name.
            //             "\n-  Attendance Date: ". $attendance->attendance_date.
            //             "\n-  Attendance Status: ".$attendance->attendance_status.
            //             "\n\n Best Regard, \n ".$school->school_name;

            // $sendMessageResponseToStudent = Http::post($sendMessageUrl, [
            //     'api_key' => $apiKey,
            //     'sender' => $whatsappDevice->wachat_device_number,
            //     'number' => $student->student_phone_no,
            //     'message' => $messageBodyToStudent,
            // ]);


            // $messageBodyToParent = "Aslam o Alaikum, ".$parent->parent_name.
            //             "\n\n Attendance Update".
            //             "\n-  Student Name: ".$student->student_name.
            //             "\n-  Roll #: ".$student->student_roll_number.
            //             "\n-  Class: ".$class->class_name.
            //             "\n-  Section: ".$section->section_name.
            //             "\n-  Attendance Date: ". $attendance->attendance_date.
            //             "\n-  Attendance Status: ".$attendance->attendance_status.
            //             "\n\n Best Regard, \n ".$school->school_name;

            // $sendMessageResponseToParent = Http::post($sendMessageUrl, [
            //     'api_key' => $apiKey,
            //     'sender' => $whatsappDevice->wachat_device_number,
            //     'number' => $parent->parent_phone_no,
            //     'message' => $messageBodyToParent,
            // ]);

    }

    // Export attendance records to CSV
    public function exportToCsv($classId, $sectionId, $attendanceDate)
    {
        $attendances = Attendance::with('student')
            ->whereHas('student', fn ($query) => $query->where('school_id', Auth::user()->school_id)->where('status', 'active'))
            ->where('school_id', Auth::user()->school_id)
            ->where('class_id', $classId)
            ->where('section_id', $sectionId)
            ->whereDate('attendance_date', $attendanceDate)
            ->orderBy('created_at')
            ->get();


            if(empty($attendances) || $attendances->count() == 0) {
                return redirect()->back()->with('error', 'No attendance history records found');
            }

        $schoolClass = SchoolClass::find($classId);
        $section = Section::find($sectionId);

        return response()->streamDownload(function () use ($attendances) {
            $csv = fopen('php://output', 'w');
            fputcsv($csv, ['Sr No.', 'Student Name', 'Roll Number', 'Date', 'Status']);
            foreach ($attendances as $key => $attendance) {
                fputcsv($csv, [$key + 1, $attendance->student->student_name, $attendance->student->student_roll_number, $attendance->attendance_date, strtoupper($attendance->attendance_status)]);
            }
            fclose($csv);
        }, 'Attendance_By_Class_'.$schoolClass->class_name.'_Section_'.$section->section_name.'_Date_'.$attendanceDate.'.csv');
    }
}
