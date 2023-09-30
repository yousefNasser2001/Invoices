<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserWorkingHours
{

    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Check if both start and end are null, allowing unrestricted access
        if ($user->working_hours_start === null && $user->working_hours_end === null) {
            return $next($request);
        }

        $currentTime = now()->format('H:i:s');

        if ($user->working_hours_start <= $currentTime && $currentTime <= $user->working_hours_end) {
            return $next($request);
        }

        Auth::logout();
        flash('You are not allowed to access the control panel at this time.')->error();
        return redirect()->route('login');
    }

}
