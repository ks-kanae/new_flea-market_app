<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfProfileIncomplete
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
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        if (
            !$user->hasVerifiedEmail()
            && !$request->routeIs('verification.*')
            && !$request->routeIs('logout')
        ) {
            return redirect()->route('verification.notice');
        }

        if (
            $user->hasVerifiedEmail()
            && !$user->profile_completed
            && !$request->routeIs([
                'profile.*',
                'verification.*',
                'logout',
            ])
        ) {
            return redirect()->route('profile.edit');
        }

        return $next($request);
    }
}
