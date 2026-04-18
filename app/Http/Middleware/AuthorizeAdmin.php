<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (Auth::guard('Admin')->check()) {
            $admin = Auth::guard('Admin')->user();
            $admin_role = $admin->getRoleName();

            if (in_array($admin_role, $roles)) {
                return $next($request);
            }
        }

        abort(403, 'Forbidden. Kamu tidak punya akses ke halaman ini.');
    }
}
