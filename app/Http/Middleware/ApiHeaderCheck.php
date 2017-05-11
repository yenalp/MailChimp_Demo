<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Classes\AppContext;
use App\Exceptions\NotAuthorisedException;

class ApiHeaderCheck
{
    public function handle(\Illuminate\Http\Request $request, Closure $next)
    {
        /**
        * Check SITE-ID was passed in
        */
        if (!$request->header('X-Auth-SITE-ID')) {
            throw new NotAuthorisedException(
                "You do not have permission to access this item",
                "The request did not contain a 'X-Auth-SITE-ID' header."
            );
        }

        $siteId = $request->header('X-Auth-SITE-ID');

        /**
        * Ensure it is a valid site id
        */
        if (!in_array($siteId, config('constants.SITE_IDS'))) {
            throw new NotAuthorisedException(
                "You do not have permission to access this item",
                "The request contained an 'X-Auth-SITE-ID' header "
                ." which contained an invalid value of {$siteId}."
            );
        }

        $context = app()->make('AppContext');
        $context->siteId = $siteId;

        return $next($request);
    }
}
