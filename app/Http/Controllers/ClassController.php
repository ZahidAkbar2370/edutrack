<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    // Get school_id from logged-in user, or first school when auth is not set up
    private function resolveSchoolId(): ?string
    {
        if (Auth::check() && Auth::user()->school_id) {
            return Auth::user()->school_id;
        }

        return School::orderBy('created_at')->value('id');
    }

    public function index()
    {
        $schoolId = $this->resolveSchoolId();

        $classes = SchoolClass::with('school')
            ->when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
            ->orderBy('class_name')
            ->get();

        return view('school.class.index', compact('classes'));
    }

    public function create()
    {
        return view('school.class.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_name' => 'required|string|max:255',
        ]);

        $schoolId = $this->resolveSchoolId();

        if (! $schoolId) {
            return redirect('class/create')->with('error', 'No school found. Please register a school first.');
        }

        SchoolClass::create([
            'school_id' => $schoolId,
            'class_name' => $request->class_name,
        ]);

        return redirect('class')->with('success', 'Class created successfully');
    }

    public function show($id)
    {
        $class = SchoolClass::with('school')->find($id);

        return view('school.class.show', compact('class'));
    }

    public function edit($id)
    {
        $class = SchoolClass::find($id);

        return view('school.class.edit', compact('class'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'class_name' => 'required|string|max:255',
        ]);

        $class = SchoolClass::find($id);

        if ($class) {
            $class->update([
                'class_name' => $request->class_name,
            ]);

            return redirect('class')->with('success', 'Class updated successfully');
        }

        return redirect('class')->with('error', 'Class not found');
    }

    public function destroy($id)
    {
        $class = SchoolClass::find($id);

        if ($class) {
            $class->delete();

            return redirect('class')->with('success', 'Class deleted successfully');
        }

        return redirect('class')->with('error', 'Class not found');
    }
}
