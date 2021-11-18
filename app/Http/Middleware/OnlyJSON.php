<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OnlyJSON
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->isMethod('post')) return $next($request);
        if (!$request->isJson()) return redirect(route('api.unauthorized'));
        return $next($request);
    }
}
