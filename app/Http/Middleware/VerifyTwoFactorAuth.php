<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class VerifyTwoFactorAuth
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        
        // Skip if 2FA is not enabled for user
        if (!$user->two_factor_enabled) {
            return $next($request);
        }
        
        // Skip if already verified in this session
        if (session('2fa_verified')) {
            return $next($request);
        }
        
        // Store intended URL for redirect after verification
        if (!$request->session()->has('url.intended')) {
            session(['url.intended' => $request->url()]);
        }
        
        return redirect()->route('2fa.verify');
    }
}