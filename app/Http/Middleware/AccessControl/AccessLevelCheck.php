<?php

namespace App\Http\Middleware\AccessControl;

use Closure;
use Illuminate\Http\Request;
use App\Classes\AppContext;
use App\Exceptions\NotAuthorisedException;

class AccessLevelCheck
{
    public function handle(Request $request, Closure $next, $accessLevel)
    {
        $context = app()->make('AppContext');

        if ($context->getAccessLevel() > $accessLevel) {
            $currentUrl = $request->url();
            throw new NotAuthorisedException(
                "You do not have permission to access this item",
                "The request to '{$currentUrl}'"
                ." for user '{$context->user->user_name}(id: {$context->user->id})' was denied."
                ."  This is because this endpoint can only be accessed by users who"
                ." have an access level <= '{$accessLevel}'."
            );
        }
        return $next($request);
    }
}
