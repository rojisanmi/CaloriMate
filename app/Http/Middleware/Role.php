<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class Role
{
    public function handle($request, Closure $next, ...$roles)
    {
        // API (Sanctum): use authenticated user's role
        if ($request->user()) {
            $current = (string) $request->user()->role;
            if (in_array($current, $roles)) {
                return $next($request);
            }
            abort(403, 'Forbidden');
        }

        // Web: session-based role
        $current = Session::get('user_role');
        if (!Session::has('user_role') || !in_array((string) $current, $roles)) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
