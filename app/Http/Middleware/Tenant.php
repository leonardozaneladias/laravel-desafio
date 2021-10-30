<?php

namespace App\Http\Middleware;

use Closure;

class Tenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        $user = \Auth::user();
        \Tenant::setTenant($user);
        return $next($request);
    }
}
