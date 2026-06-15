<?php

namespace App\Http\Middleware;

use App\Models\Membership;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FeeManagementMiddleware
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

            if($membership->allowed_fee_management == false) {
                return redirect('dashboard')->with('error', 'Fee management is not allowed for your membership plan. Please upgrade your membership plan to enable fee management.');
            }
        }

        return $next($request);
    }
}
