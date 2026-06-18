<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\School;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $schools = User::where('role', 'school-admin')->count();
        $activeSchools = User::where('role', 'school-admin')->where('membership_expiry_date', '>=', now())->count();
        $expiredSchools = User::where('role', 'school-admin')->where('membership_expiry_date', '<', now())->count();

        $totalEarnings = Transaction::sum('transaction_amount');

        return view('superadmin.dashboard', compact('schools', 'activeSchools', 'expiredSchools', 'totalEarnings'));
    }
}
