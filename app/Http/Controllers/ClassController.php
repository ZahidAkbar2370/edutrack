<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    public function index(Request $request)
    {
        $classes = SchoolClass::with('school')
            ->where('school_id', Auth::user()->school_id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('class.index', compact('classes'));
    }

    function updatePublicationStatus(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:classes,id',
            'publication_status' => 'required|in:active,inactive',
        ]);

        $class = SchoolClass::find($request->class_id);
        $class->update([
            'publication_status' => $request->publication_status,
        ]);

        return redirect('class')->with('success', 'Class Publication Status Updated Successfully!');
    }

    // public function create()
    // {
    //     return view('school.class.create');
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'class_name' => 'required|string|max:255',
    //     ]);

    //     $schoolId = Auth::user()->school_id;

    //     if (! $schoolId) {
    //         return redirect('class/create')->with('error', 'No school is assigned to this user.');
    //     }

    //     SchoolClass::create([
    //         'school_id' => $schoolId,
    //         'class_name' => $request->class_name,
    //     ]);

    //     return redirect('class')->with('success', 'Class created successfully');
    // }

    // public function show($id)
    // {
    //     $class = SchoolClass::with('school')->find($id);

    //     return view('school.class.show', compact('class'));
    // }

    // public function edit($id)
    // {
    //     $class = SchoolClass::find($id);

    //     return view('school.class.edit', compact('class'));
    // }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'class_name' => 'required|string|max:255',
    //     ]);

    //     $class = SchoolClass::find($id);

    //     if ($class) {
    //         $class->update([
    //             'class_name' => $request->class_name,
    //         ]);

    //         return redirect('class')->with('success', 'Class updated successfully');
    //     }

    //     return redirect('class')->with('error', 'Class not found');
    // }

    // public function destroy($id)
    // {
    //     $class = SchoolClass::find($id);

    //     if ($class) {
    //         $class->delete();

    //         return redirect('class')->with('success', 'Class deleted successfully');
    //     }

    //     return redirect('class')->with('error', 'Class not found');
    // }
}
