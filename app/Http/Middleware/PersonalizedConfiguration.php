<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PersonalizedConfiguration
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
//        dd('this is the middle request-------------');
        return $next($request);
    }
}
