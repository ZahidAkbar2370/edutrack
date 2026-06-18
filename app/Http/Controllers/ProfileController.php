<?php

namespace App\Http\Controllers;

use App\Models\Membership;
use App\Models\School;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function profile()
    {
        $school = School::with('membership', 'user')->find(Auth::user()->school_id);

        return view('profile.profile', compact('school'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string|max:255',
            'school_email' => 'nullable|email',
            'school_phone_no' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:255',

            'priciple_name' => 'required|string|max:255',
            'priciple_email' => 'nullable|email',
            'priciple_phone_no' => 'nullable|string|max:255',
        ]);

        $school = School::find(Auth::user()->school_id);

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

        return redirect('profile')->with('success', 'Profile updated successfully');
    }

    public function changePassword()
    {
        return view('profile.change_password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (! Hash::check($request->old_password, $user->password)) {
            return redirect('change-password')->with('error', 'Your old password is incorrect');
        }

        User::where('id', $user->id)->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect('change-password')->with('success', 'Password changed successfully');
    }

    public function transactionHistory()
    {
        $transactions = Transaction::with('membership')
            ->where('school_id', Auth::user()->school_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('profile.transaction_history', compact('transactions'));
    }

    public function pricing()
    {
        $memberships = Membership::orderBy('membership_price', 'asc')->get();
        $currentMembership = User::with('membership')->where('id', Auth::user()->id)->first();

        return view('profile.pricing', compact('memberships', 'currentMembership'));
    }
}
