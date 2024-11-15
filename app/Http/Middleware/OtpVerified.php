<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(auth('admin')->user() == null && Auth::check() && Auth::user()->email_verified_at == null )
        {
            if (url()->current() == route('otp') || url()->current() == route('otp-verify') || url()->current() == route('logout')) {
                return $next($request);
            }

            return redirect()->route('otp'); 
        }

        return $next($request);
    }
}
