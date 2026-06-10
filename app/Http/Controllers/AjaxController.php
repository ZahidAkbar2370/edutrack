<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AjaxController extends Controller
{
    public function sectionsByClassId($classId)
    {
        $sections = Section::where('school_id', Auth::user()->school_id)->where('publication_status', 'active')->where('class_id', $classId)->orderBy('created_at', 'desc')->get();

        return response()->json($sections);
    }

    public function studentsBySectionId($classId, $sectionId)
    {
        // dd($classId, $sectionId);
        $students = Student::where('school_id', Auth::user()->school_id)->where('status', 'active')->where('class_id', $classId)->where('section_id', $sectionId)->orderBy('created_at', 'desc')->get();

        // dd($classId);

        return response()->json($students);
    }
}
