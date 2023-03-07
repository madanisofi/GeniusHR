<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class GITSolution
{
    public function handle($request, Closure $next)
    {
        App::setLocale(Auth::user()->lang);

        return $next($request);
    }
}
