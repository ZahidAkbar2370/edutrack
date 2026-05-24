<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
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

        $subjects = Subject::with('school')
            ->when($schoolId, fn ($query) => $query->where('school_id', $schoolId))
            ->orderBy('subject_name')
            ->get();

        return view('subject.index', compact('subjects'));
    }

    public function create()
    {
        return view('subject.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject_name' => 'required|string|max:255',
        ]);

        $schoolId = $this->resolveSchoolId();

        if (! $schoolId) {
            return redirect('subject/create')->with('error', 'No school found. Please register a school first.');
        }

        Subject::create([
            'school_id' => $schoolId,
            'subject_name' => $request->subject_name,
        ]);

        return redirect('subject')->with('success', 'Subject created successfully');
    }

    public function show($id)
    {
        $subject = Subject::with('school')->find($id);

        return view('subject.show', compact('subject'));
    }

    public function edit($id)
    {
        $subject = Subject::find($id);

        return view('subject.edit', compact('subject'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'subject_name' => 'required|string|max:255',
        ]);

        $subject = Subject::find($id);

        if ($subject) {
            $subject->update([
                'subject_name' => $request->subject_name,
            ]);

            return redirect('subject')->with('success', 'Subject updated successfully');
        }

        return redirect('subject')->with('error', 'Subject not found');
    }

    public function destroy($id)
    {
        $subject = Subject::find($id);

        if ($subject) {
            $subject->delete();

            return redirect('subject')->with('success', 'Subject deleted successfully');
        }

        return redirect('subject')->with('error', 'Subject not found');
    }
}
