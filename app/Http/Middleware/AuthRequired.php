<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class AuthRequired
{
    public function handle($request, Closure $next)
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login.show');
        }
        return $next($request);
    }
}
