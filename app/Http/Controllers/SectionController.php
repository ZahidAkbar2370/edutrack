<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    // Get school_id from logged-in user, or first school when auth is not set up
    private function resolveSchoolId(): ?string
    {
        if (Auth::check() && Auth::user()->school_id) {
            return Auth::user()->school_id;
        }

        return School::orderBy('created_at')->value('id');
    }

    private function classesForSchool(?string $schoolId)
    {
        return SchoolClass::when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
            ->orderBy('class_name')
            ->get();
    }

    public function index()
    {
        $schoolId = $this->resolveSchoolId();

        $sections = Section::with('schoolClass', 'school')
            ->when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
            ->orderBy('section_name')
            ->get();

        return view('section.index', compact('sections'));
    }

    public function create()
    {
        $schoolId = $this->resolveSchoolId();
        $classes = $this->classesForSchool($schoolId);

        return view('section.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $schoolId = $this->resolveSchoolId();

        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'section_name' => 'required|string|max:255',
        ]);

        if (! $schoolId) {
            return redirect('section/create')->with('error', 'No school found. Please register a school first.');
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
        $schoolId = $this->resolveSchoolId();
        $classes = $this->classesForSchool($schoolId);

        return view('section.edit', compact('section', 'classes'));
    }

    public function update(Request $request, $id)
    {
        $schoolId = $this->resolveSchoolId();

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
