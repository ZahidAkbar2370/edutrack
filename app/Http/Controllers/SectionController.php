<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    private function classesForSchool(string $schoolId)
    {
        return SchoolClass::where('school_id', $schoolId)
            ->orderBy('class_name')
            ->get();
    }

    public function index(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        if (! $schoolId) {
            return redirect('home')->with('error', 'No school is assigned to this user.');
        }

        $sections = Section::with('schoolClass', 'school')
            ->where('school_id', $schoolId)
            ->orderBy('section_name')
            ->get();

        return view('section.index', compact('sections'));
    }

    public function create()
    {
        $schoolId = Auth::user()->school_id;
        if (! $schoolId) {
            return redirect('home')->with('error', 'No school is assigned to this user.');
        }
        $classes = $this->classesForSchool($schoolId);

        return view('section.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $schoolId = Auth::user()->school_id;

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_name' => 'required|string|max:255',
        ]);

        if (! $schoolId) {
            return redirect('section/create')->with('error', 'No school is assigned to this user.');
        }

        $class = SchoolClass::where('id', $request->class_id)
            ->where('school_id', $schoolId)
            ->first();

        if (! $class) {
            return redirect('section/create')->with('error', 'Invalid class selected for this school.');
        }

        Section::create([
            'school_id' => $schoolId,
            'class_id' => $request->class_id,
            'section_name' => $request->section_name,
        ]);

        return redirect('section')->with('success', 'Section created successfully');
    }

    public function show($id)
    {
        $section = Section::with('schoolClass', 'school')->find($id);

        return view('section.show', compact('section'));
    }

    public function edit($id)
    {
        $section = Section::find($id);
        $schoolId = Auth::user()->school_id;
        if (! $schoolId) {
            return redirect('home')->with('error', 'No school is assigned to this user.');
        }
        $classes = $this->classesForSchool($schoolId);

        return view('section.edit', compact('section', 'classes'));
    }

    public function update(Request $request, $id)
    {
        $schoolId = Auth::user()->school_id;
        if (! $schoolId) {
            return redirect('section')->with('error', 'No school is assigned to this user.');
        }

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_name' => 'required|string|max:255',
        ]);

        $section = Section::find($id);

        if ($section) {
            $class = SchoolClass::where('id', $request->class_id)
                ->where('school_id', $schoolId)
                ->first();

            if (! $class) {
                return redirect('section/edit/' . $id)->with('error', 'Invalid class selected for this school.');
            }

            $section->update([
                'class_id' => $request->class_id,
                'section_name' => $request->section_name,
            ]);

            return redirect('section')->with('success', 'Section updated successfully');
        }

        return redirect('section')->with('error', 'Section not found');
    }

    public function destroy($id)
    {
        $section = Section::find($id);

        if ($section) {
            $section->delete();

            return redirect('section')->with('success', 'Section deleted successfully');
        }

        return redirect('section')->with('error', 'Section not found');
    }
}
