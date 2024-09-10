<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $user = Auth::user();
        if (!$user || !$user->roles->contains('name', $role)) {
            return redirect()->route('home')->with('error', 'You do not have access to this resource.');
        }

        return $next($request);
    }
}
