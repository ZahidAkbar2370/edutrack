<?php

namespace App\Http\Middleware;

use App\Models\Membership;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DailyTestMiddleware
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

            if($membership->allowed_daily_test == false) {
                return redirect('dashboard')->with('error', 'Daily test is not allowed for your membership plan. Please upgrade your membership plan to enable daily test.');
            }
        }

        return $next($request);
    }
}
