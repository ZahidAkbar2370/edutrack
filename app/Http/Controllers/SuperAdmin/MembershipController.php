<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use Illuminate\Http\Request;

class MembershipController extends Controller
{

    public function index(Request $request)
    {
        $memberships = Membership::query()->orderBy('membership_name')->get();

        return view('superadmin.membership.index', compact('memberships'));
    }

    public function create()
    {
        return view('superadmin.membership.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'membership_name' => 'required|string|max:255',
            'membership_price' => 'required',
            'students_limit' => 'nullable',
            'teachers_limit' => 'nullable',
            'allowed_attendance' => 'required',
            'allowed_daily_test' => 'required',
            'allowed_student_card' => 'required',
            'allowed_whatsapp_message' => 'required',
            'allowed_whatsapp_announcement' => 'required',
        ]);

        Membership::create($request->all());

        return redirect('memberships')->with('success', 'Membership created successfully');
    }

    public function show($id)
    {
        $membership = Membership::find($id);

        return view('superadmin.membership.show', compact('membership'));
    }

    public function edit($id)
    {
        $membership = Membership::find($id);

        return view('superadmin.membership.edit', compact('membership'));
    }

    public function update(Request $request, $id)
    {
        $membership = Membership::find($id);

        if ($membership) {
            $membership->update($request->all());

            return redirect('memberships')->with('success', 'Membership updated successfully');
        }

        return redirect('memberships')->with('error', 'Membership not found');
    }

    public function destroy($id)
    {
        $membership = Membership::find($id);

        if ($membership) {
            $membership->delete();

            return redirect('memberships')->with('success', 'Membership deleted successfully');
        }

        return redirect('memberships')->with('error', 'Membership not found');  
    }

}
