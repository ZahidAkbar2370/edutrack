<?php

namespace App\Http\Middleware;

use App\Models\Membership;
use App\Models\Teacher;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TeacherLimitMiddleware
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

            $teachers = Teacher::where('school_id', Auth::user()->school_id)->count();

            if($membership->teachers_limit != null && $membership->teachers_limit <= $teachers) {
                return redirect('teacher')->with('error', 'Teacher limit is reached for your membership plan. Please upgrade your membership plan to add more teachers.');
            }
        }

        return $next($request);
    }
}
