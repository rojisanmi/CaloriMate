<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class Role
{
    public function handle($request, Closure $next, ...$roles)
    {
        $current = Session::get('user_role');
        if (!$current || !in_array($current, $roles)) {
            abort(403, 'Forbidden');
        }
        return $next($request);
    }
}
