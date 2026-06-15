<?php

namespace App\Http\Middleware;

use App\Models\Membership;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class MembershipPlanMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!empty(Auth::user()->membership_expiry_date) && Auth::user()->membership_expiry_date < now() && Auth::user()->role != 'super-admin') {
            Auth::logout();
            return redirect('login')->with('error', 'Your membership plan has expired. Please renew your membership plan to continue using the system.');
        }

        return $next($request);
    }
}
