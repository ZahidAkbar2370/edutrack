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

        $students = fn () => Student::query()->where('school_id', Auth::user()->school_id);

        $stats = [
            'total_students' => $students()->count(),
            'active_students' => $students()->where('status', 'active')->count(),
            'completed_students' => $students()->where('status', 'completed')->count(),
            'banned_students' => $students()->where('status', 'banned')->count(),
            'inactive_students' => $students()->where('status', 'inactive')->count(),
            'total_classes' => SchoolClass::query()
                ->where('school_id', Auth::user()->school_id)
                ->count(),
            'total_daily_tests' => DailyTest::query()
                ->where('school_id', Auth::user()->school_id)
                ->count(),
            'total_teachers' => Teacher::query()
                ->where('school_id', Auth::user()->school_id)
                ->count(),
        ];

        return view('schooladmin.dashboard.dashboard', compact('stats'));
    }
}
