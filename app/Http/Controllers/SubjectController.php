<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $subjects = Subject::with('school')
            ->where('school_id', Auth::user()->school_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('schooladmin.subject.index', compact('subjects'));
    }

    public function updatePublicationStatus(Request $request)
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'publication_status' => 'required|in:active,inactive',
        ]);
        
        $subject = Subject::find($request->subject_id);
        $subject->update([
            'publication_status' => $request->publication_status,
        ]);

        return redirect('subject')->with('success', 'Subject Publication Status Updated Successfully!');
    }

    // public function create()
    // {
    //     return view('schooladmin.subject.create');
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'subject_name' => 'required|string|max:255',
    //     ]);

    //     $schoolId = Auth::user()->school_id;

    //     if (! $schoolId) {
    //         return redirect('subject/create')->with('error', 'No school is assigned to this user.');
    //     }

    //     Subject::create([
    //         'school_id' => $schoolId,
    //         'subject_name' => $request->subject_name,
    //     ]);

    //     return redirect('subject')->with('success', 'Subject created successfully');
    // }

    // public function show($id)
    // {
    //     $subject = Subject::with('school')->find($id);

    //     return view('schooladmin.subject.show', compact('subject'));
    // }

    // public function edit($id)
    // {
    //     $subject = Subject::find($id);

    //     return view('schooladmin.subject.edit', compact('subject'));
    // }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'subject_name' => 'required|string|max:255',
    //     ]);

    //     $subject = Subject::find($id);

    //     if ($subject) {
    //         $subject->update([
    //             'subject_name' => $request->subject_name,
    //         ]);

    //         return redirect('subject')->with('success', 'Subject updated successfully');
    //     }

    //     return redirect('subject')->with('error', 'Subject not found');
    // }

    // public function destroy($id)
    // {
    //     $subject = Subject::find($id);

    //     if ($subject) {
    //         $subject->delete();

    //         return redirect('subject')->with('success', 'Subject deleted successfully');
    //     }

    //     return redirect('subject')->with('error', 'Subject not found');
    // }
}
