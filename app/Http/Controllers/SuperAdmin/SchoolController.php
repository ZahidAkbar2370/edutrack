<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\DailyTest;
use Illuminate\Http\Request;
use App\Models\Membership;
use App\Models\School;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SchoolController extends Controller
{
    public function index(Request $request)
    {
        $schoolName = $request->school_name;
        $membershipId = $request->membership_id;
        $expiryStatus = $request->expiry_status;
        $city = $request->city;

        $schools = School::with(['membership', 'user']);

        if ($schoolName) {
            $schools->where('school_name', 'like', "%{$schoolName}%");
        }

        if ($membershipId) {
            $schools->where('membership_id', $membershipId);
        }

        if ($expiryStatus) {
            $schools->whereHas('user', function ($query) use ($expiryStatus) {
                if ($expiryStatus == 'expired') {
                    $query->where('membership_expiry_date', '<', now());
                } else {
                    $query->where('membership_expiry_date', '>=', now());
                }
            });
        }

        if ($city) {
            $schools->where('city', 'like', "%{$city}%");
        }

        $schools = $schools->latest()->get();

        $memberships = Membership::all();

        return view('superadmin.school.index', compact('schools', 'memberships'));
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
        try {
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
                    'transaction_purpose' => 'membership_renew',
                    'membership_id' => $request->membership_id,
                    'membership_expire_date' => $request->membership_expiry_date,
                    'transaction_amount' => $request->paid_amount,
                    'transaction_note' => 'Membership purchase',
                ]);
            });

            return redirect('school')->with('success', 'School created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'School creation failed. ' . $e->getMessage());
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
        $school = School::findOrFail($id);

        $request->validate([
            'school_name' => 'required|string|max:255',
            'school_email' => 'nullable|email|unique:schools,school_email,' . $school->id,
            'school_phone_no' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:255',

            'priciple_name' => 'required|string|max:255',
            'priciple_email' => 'nullable|email|unique:schools,priciple_email,' . $school->id,
            'priciple_phone_no' => 'nullable|string|max:255',
        ]);

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

        return redirect('school/show/' . $school->id)->with('success', 'School & Principal Information updated successfully');
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

    function updateMembership($schoolId)
    {
        $school = School::with('user')->findOrFail($schoolId);
        $memberships = Membership::all();

        return view('superadmin.school.upgrade_membership', compact('school', 'memberships'));
    }

    function updateMembershipStore(Request $request, $schoolId)
    {
        $request->validate([
            'membership_id' => 'required|exists:memberships,id',
            'membership_expiry_date' => 'required|date',
            'transaction_purpose' => 'required|in:membership_renew,membership_upgrade,membership_downgrade,domain_hosting,other',
            'transaction_prove_image' => 'nullable|image|mimes:jpeg,jpg,png',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () use ($request, $schoolId) {

                $school = School::with('user')->findOrFail($schoolId);

                // Update school membership
                $school->update([
                    'membership_id' => $request->membership_id,
                ]);

                // Update user membership + expiry date
                if ($school->user) {
                    $school->user->update([
                        'membership_id' => $request->membership_id,
                        'membership_expiry_date' => $request->membership_expiry_date,
                    ]);
                }

                // Transaction proof image upload
                $imagePath = null;
                if ($request->hasFile('transaction_prove_image')) {
                    $image = $request->file('transaction_prove_image');
                    $imageName = uniqid('transaction_') . '_' . $school->id . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('admin/images/school/transactions'), $imageName);
                    $imagePath = 'admin/images/school/transactions/' . $imageName;
                }

                // Create transaction
                Transaction::create([
                    'school_id' => $school->id,
                    'transaction_purpose' => $request->transaction_purpose,
                    'transaction_prove_image' => $imagePath,
                    'membership_id' => $request->membership_id,
                    'membership_expire_date' => $request->membership_expiry_date,
                    'transaction_amount' => $request->paid_amount,
                    'transaction_note' => 'Membership ' . str_replace('_', ' ', $request->transaction_purpose),
                ]);
            });

            return redirect('school/show/' . $schoolId)->with('success', 'Membership updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Membership update failed. ' . $e->getMessage());
        }
    }

    function transactionHistory($schoolId)
    {
        $school = School::with('user')->findOrFail($schoolId);
        $transactionHistory = Transaction::with('membership')->where('school_id', $schoolId)->orderBy('created_at', 'desc')->get();

        return view('superadmin.school.transaction_history', compact('school', 'transactionHistory'));
    }

    function changePassword($schoolId)
    {
        $school = School::with('user')->findOrFail($schoolId);

        return view('superadmin.school.change_username_password', compact('school'));
    }

    function changePasswordStore(Request $request, $schoolId)
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $school = School::with('user')->findOrFail($schoolId);

        if (! $school->user) {
            return redirect()->back()->with('error', 'No login account found for this school');
        }

        $school->user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect('school/show/' . $school->id)->with('success', 'School password changed successfully');
    }
}
