<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Classes\AppContext;

class ApiContext
{
    public function handle(\Illuminate\Http\Request $request, Closure $next)
    {
        $context = new AppContext(AppContext::SOURCE_API);
        $context->request = $request;
        app()->instance('AppContext', $context);
        return $next($request);
    }
}
