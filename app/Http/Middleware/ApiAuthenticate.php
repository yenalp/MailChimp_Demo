<?php namespace App\Http\Middleware;

use App\Exceptions\NotAuthorisedException;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use App\Classes\AppContext;

class ApiAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @return mixed
     *
     */
    public function handle(Request $request, Closure $next)
    {
        $currentContext = app()->make('AppContext');

        $currentUrl = $request->url();
        $ipAddress = $request->ip();

        /**
        * Check SITE-ID was passed in
        */
        if (!$currentContext->siteId) {
            throw new NotAuthorisedException(
                "You do not have permission to access this item",
                "The request to '{$currentUrl}' from ip address '{$ipAddress}'"
                ." did not contain a 'X-Auth-SITE-ID' header."
            );
        }

        $siteId = $currentContext->siteId;
        $dateExpires = new Carbon();
        $sessionTimeOut = env('SESSION_TIMEOUT');

        /**
         * Check for headers
         */
        if (!$request->header("X-Auth-Id-{$siteId}")
            && !$request->header("X-Auth-Token-{$siteId}")
        ) {
            throw new NotAuthorisedException(
                "You do not have permission to access this item",
                "The request to '{$currentUrl}' from ip address '{$ipAddress}'"
                ." did not contain a 'X-Auth-Id-{$siteId}' and 'X-Auth-Token-{$siteId}' header."
            );
        }

        /**
         * Check if auth details are correct
         */
        $xAuthUserId = $request->header("X-Auth-Id-{$siteId}");
        $xAuthToken = $request->header("X-Auth-Token-{$siteId}");

        $user = User::where('id', '=', $xAuthUserId)
            ->where('token_'.strtolower($siteId), '=', $xAuthToken)
            ->first();

        if ($user === null) {
            throw new NotAuthorisedException(
                "You do not have permission to access this item",
                "The request to '{$currentUrl}' from ip address '{$ipAddress}'"
                ." for user id '{$xAuthUserId}' and auth token '{$xAuthToken}'."
                ." did not exist in the database."
            );
        }

        if (!$user->canLogin()) {
            throw new NotAuthorisedException(
                "You do not have permission to access this item",
                "The request to '${$currentUrl}' from ip address '{$ipAddress}'"
                ." for user id '{$xAuthUserId}' and auth token '{$xAuthToken}'."
                ." is not permitted to log in to the system."
            );
        }

        /**
         * Check to see if the date period is longer than 2 hours
         */
        if ($user['token_expires_'.strtolower($siteId)]->diffInMinutes($dateExpires) > $sessionTimeOut) {
            throw new NotAuthorisedException(
                "Your session has expired, please log in again.",
                "The request to '{$currentUrl}' from ip address '{$ipAddress}'"
                ." for user id '{$user->id}' used the expired auth token ."
                ." '{$xAuthToken}'."
            );
        }

        $currentContext->user = $user;

        return $next($request);
    }
}
