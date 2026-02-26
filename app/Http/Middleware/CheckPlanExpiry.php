<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanExpiry
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Skip check for admin users
            if ($user->hasRole('admin')) {
                return $next($request);
            }
            
            // Skip check for employees (users with create_id set)
            if ($user->create_id) {
                return $next($request);
            }
            
            // Check if user has no plan
            if (!$user->plan_id) {
                return redirect()->route('user.plans')->with('error', 'Please subscribe to a plan to access this feature.');
            }
            
            // Check if plan is expired
            if ($user->isPlanExpired()) {
                return redirect()->route('user.plans')->with('error', 'Your plan has expired. Please subscribe to a plan to continue.');
            }
        }
        return $next($request);
    }
}
