<?php

namespace App\Http\Middleware;

use App\Models\Membership;
use App\Models\Student;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class StudentLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!empty(Auth::user()->membership_id) && Auth::user()->membership_id !== null) {
            $membership = Membership::find(Auth::user()->membership_id);

            $students = Student::where('school_id', Auth::user()->school_id)->count();

            if($membership->students_limit != null && $membership->students_limit <= $students) {
                return redirect()->back()->with('error', 'Student limit is reached for your membership plan. Please upgrade your membership plan to add more students.');
            }
        }

        return $next($request);

    }
}
