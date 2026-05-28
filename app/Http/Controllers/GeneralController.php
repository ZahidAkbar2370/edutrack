<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\General;
use App\Models\School;

class GeneralController extends Controller
{
    public function index()
    {
        return view('setting.general');
    }

    public function create()
    {
        return view('setting.general.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
        ]);

        $general = School::create($request->all());

        return redirect()->route('general.index')->with('success', 'General settings created successfully');
    }

    public function show($id)
    {
        return view('setting.general.show', compact('general'));
    }

    public function edit($id)
    {
        return view('setting.general.edit', compact('general'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
        ]);

        $general = School::find($id);
        $general->update($request->all());

        return redirect()->route('general.index')->with('success', 'General settings updated successfully');
    }
}
