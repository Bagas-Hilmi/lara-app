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
        // Cek apakah pengguna sudah login dan memiliki peran yang diminta
        if (!Auth::check() || !Auth::user()->hasRole($role)) {
            // Jika tidak sesuai, tampilkan pesan 403 Forbidden
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
