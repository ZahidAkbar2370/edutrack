<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Membership;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class SchoolController extends Controller
{
    public function index(Request $request)
    {
        $schools = School::with('membership', 'user')->orderBy('created_at', 'desc')->get();

        return view('superadmin.school.index', compact('schools'));
    }

    public function create()
    {
        $memberships = Membership::all();

        return view('superadmin.school.create', compact('memberships'));
    }

    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'membership_id' => 'required|exists:memberships,id',
            'school_name' => 'required|string|max:255',
            'school_email' => 'nullable|email|unique:schools',
            'school_phone_no' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:255',

            'priciple_name' => 'required|string|max:255',
            'priciple_email' => 'nullable|email|unique:schools',
            'priciple_phone_no' => 'nullable|string|max:255',

            'user_email' => 'required|email|unique:users,email',
            'user_password' => 'required|string|max:255',
            'user_role' => 'required|in:super-admin,school-admin,priciple,teacher,student,parent',
            'membership_expiry_date' => 'required',
            'paid_amount' => 'required',
        ]);
        // Create school, user, and transaction
        try{
        DB::transaction(function () use ($request) {

            // Create school
            $school = School::create([
                'membership_id' => $request->membership_id,
                'school_name' => $request->school_name,
                'school_email' => $request->school_email,
                'school_phone_no' => $request->school_phone_no,
                'city' => $request->city,
                'address' => $request->address,

                'priciple_name' => $request->priciple_name,
                'priciple_email' => $request->priciple_email,
                'priciple_phone_no' => $request->priciple_phone_no,
            ]);

            // Create user
            User::create([
                'school_id' => $school->id,
                'membership_id' => $request->membership_id,
                'name' => $request->school_name,
                'email' => $request->user_email,
                'password' => Hash::make($request->user_password),
                'role' => $request->user_role,
                'membership_expiry_date' => $request->membership_expiry_date,
            ]);

            // Create transaction
            Transaction::create([
                'school_id' => $school->id,
                'transaction_purpose' => 'membership',
                'membership_id' => $request->membership_id,
                'transaction_amount' => $request->paid_amount,
                'transaction_note' => 'Membership purchase for ' . $request->school_name,
            ]);
        });

        return redirect('school')->with('success', 'School created successfully');
    }
    catch(\Exception $e){
        // return redirect('school')->with('error', 'School creation failed');
        dd($e);
        }
    }

    public function show($id)
    {
        $school = School::with('membership', 'user')->find($id);

        return view('superadmin.school.show', compact('school'));
    }

    public function edit($id)
    {
        $memberships = Membership::all();
        $school = School::find($id);

        return view('superadmin.school.edit', compact('school', 'memberships'));
    }

    public function update(Request $request, $id)
    {
        $school = School::find($id);

        $school->update([
            'school_name' => $request->school_name,
            'school_email' => $request->school_email,
            'school_phone_no' => $request->school_phone_no,
            'city' => $request->city,
            'address' => $request->address,

            'priciple_name' => $request->priciple_name,
            'priciple_email' => $request->priciple_email,
            'priciple_phone_no' => $request->priciple_phone_no,
        ]);

        return redirect('schools')->with('success', 'School & Principal Information updated successfully');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $school = School::find($id);
            if ($school) {
                $school->delete();
                $user = User::where('school_id', $id)->first();

                if ($user) {
                    $user->delete();
                }
                return redirect('schools')->with('success', 'School & User Information deleted successfully');
            }
            return redirect('schools')->with('error', 'School not found');
        });
    }
}
