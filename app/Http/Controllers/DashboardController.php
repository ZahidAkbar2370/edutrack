<?php

namespace App\Http\Controllers;

use App\Models\DailyTest;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $schoolId = Auth::user()->school_id;
        if (! $schoolId) {
            return redirect('home')->with('error', 'No school is assigned to this user.');
        }

        $students = fn () => Student::query()->where('school_id', $schoolId);

        $stats = [
            'total_students' => $students()->count(),
            'active_students' => $students()->where('status', Student::STATUS_ACTIVE)->count(),
            'completed_students' => $students()->where('status', Student::STATUS_COMPLETED)->count(),
            'banned_students' => $students()->where('status', Student::STATUS_BANNED)->count(),
            'inactive_students' => $students()->where('status', Student::STATUS_INACTIVE)->count(),
            'total_classes' => SchoolClass::query()
                ->where('school_id', $schoolId)
                ->count(),
            'total_daily_tests' => DailyTest::query()
                ->where('school_id', $schoolId)
                ->count(),
            'total_teachers' => Teacher::query()
                ->where('school_id', $schoolId)
                ->count(),
        ];

        return view('school-admin.dashboard', compact('stats'));
    }
}
