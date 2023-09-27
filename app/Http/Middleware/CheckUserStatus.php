<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->status !== 'مفعل') {
            Auth::logout(); // Log out the user
            flash('حسابك لم يعد مفعل , الرجاء التواصل مع الدعم لتفعيله مرة اخرى')->error();
            return redirect('/login');
        }

        return $next($request);
    }
}
