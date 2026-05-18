<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class Role
{
    public function handle($request, Closure $next, ...$roles)
    {
        // 1. Cek dari request user (untuk API/Sanctum)
        if ($request->user()) {
            if (in_array((string) $request->user()->role, $roles)) {
                return $next($request);
            }
        }

        // 2. Cek dari Session (untuk Web)
        $current = Session::get('user_role');
        if (Session::has('user_role') && in_array((string) $current, $roles)) {
            return $next($request);
        }

        abort(403, 'Forbidden');
    }
}
