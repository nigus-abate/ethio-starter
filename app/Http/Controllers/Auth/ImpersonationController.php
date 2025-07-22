<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ActivityLog;

class ImpersonationController extends Controller
{
 
    public function start(Request $request, User $user)
    {

        // ActivityLogger::log(
        //     'user.impersonate',
        //     auth()->user(),
        //     $user, // impersonated user
        //     [],
        //     "Started impersonating {$user->name}"
        // );

        // Validate target can be impersonated
        if (!$user->canBeImpersonated()) {
            abort(403);
        }

        $request->session()->put('impersonate', $user->id);
        
        return redirect()->route('dashboard');
    }

    public function stop(Request $request)
    {
        $impersonatorId = $request->session()->get('impersonate');

        // ActivityLogger::log(
        //     'post.created',
        //     $causer,
        //     $subject,
        //     [],
        //     'User impersonat logged in',
        //     request()->ip(),
        //     request()->header('User-Agent')
        // );
        
        if ($impersonatorId) {
            $request->session()->forget('impersonate');
        }
        
        return redirect()->route('users.index');
        // or make with intented url
    }
}
