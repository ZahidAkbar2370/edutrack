<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $sections = Section::with('schoolClass', 'school')
            ->where('school_id', Auth::user()->school_id)
            ->orderBy('section_name', 'asc')
            ->get();

        return view('schooladmin.section.index', compact('sections'));
    }

    public function updatePublicationStatus(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'publication_status' => 'required|in:active,inactive',
        ]);
        
        
        $section = Section::find($request->section_id);
        $section->update([
            'publication_status' => $request->publication_status,
        ]);

        return redirect('section')->with('success', 'Section Publication Status Updated Successfully!');
    }

    // public function create()
    // {
    //     $schoolId = Auth::user()->school_id;
    //     if (! $schoolId) {
    //         return redirect('home')->with('error', 'No school is assigned to this user.');
    //     }
    //     $classes = $this->classesForSchool($schoolId);

    //     return view('schooladmin.section.create', compact('classes'));
    // }

    // public function store(Request $request)
    // {
    //     $schoolId = Auth::user()->school_id;

    //     $request->validate([
    //         'class_id' => 'required|exists:classes,id',
    //         'section_name' => 'required|string|max:255',
    //     ]);

    //     if (! $schoolId) {
    //         return redirect('section/create')->with('error', 'No school is assigned to this user.');
    //     }

    //     $class = SchoolClass::where('id', $request->class_id)
    //         ->where('school_id', $schoolId)
    //         ->first();

    //     if (! $class) {
    //         return redirect('section/create')->with('error', 'Invalid class selected for this school.');
    //     }

    //     Section::create([
    //         'school_id' => $schoolId,
    //         'class_id' => $request->class_id,
    //         'section_name' => $request->section_name,
    //     ]);

    //     return redirect('section')->with('success', 'Section created successfully');
    // }

    // public function show($id)
    // {
    //     $section = Section::with('schoolClass', 'school')->find($id);

    //     return view('schooladmin.section.show', compact('section'));
    // }

    // public function edit($id)
    // {
    //     $section = Section::find($id);
    //     $schoolId = Auth::user()->school_id;
    //     if (! $schoolId) {
    //         return redirect('home')->with('error', 'No school is assigned to this user.');
    //     }
    //     $classes = $this->classesForSchool($schoolId);

    //     return view('schooladmin.section.edit', compact('section', 'classes'));
    // }

    // public function update(Request $request, $id)
    // {
    //     $schoolId = Auth::user()->school_id;
    //     if (! $schoolId) {
    //         return redirect('section')->with('error', 'No school is assigned to this user.');
    //     }

    //     $request->validate([
    //         'class_id' => 'required|exists:classes,id',
    //         'section_name' => 'required|string|max:255',
    //     ]);

    //     $section = Section::find($id);

    //     if ($section) {
    //         $class = SchoolClass::where('id', $request->class_id)
    //             ->where('school_id', $schoolId)
    //             ->first();

    //         if (! $class) {
    //             return redirect('section/edit/' . $id)->with('error', 'Invalid class selected for this school.');
    //         }

    //         $section->update([
    //             'class_id' => $request->class_id,
    //             'section_name' => $request->section_name,
    //         ]);

    //         return redirect('section')->with('success', 'Section updated successfully');
    //     }

    //     return redirect('section')->with('error', 'Section not found');
    // }

    // public function destroy($id)
    // {
    //     $section = Section::find($id);

    //     if ($section) {
    //         $section->delete();

    //         return redirect('section')->with('success', 'Section deleted successfully');
    //     }

    //     return redirect('section')->with('error', 'Section not found');
    // }
}
