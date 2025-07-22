<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class ImpersonateMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (session()->has('impersonate')) {
            $impersonateUserId = session('impersonate');
            $impersonatedUser = User::find($impersonateUserId);

            if ($impersonatedUser) {
                auth()->onceUsingId($impersonatedUser->id);
            }
        }

        return $next($request);
    }
}
