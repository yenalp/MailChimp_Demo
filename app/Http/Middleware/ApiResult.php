<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiResult
{
    /**
     * Handle an outgoing response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $res = $next($request);
        $context = app()->make('AppContext');
        return $res->setContent(
            json_encode(
                $context
                ->getResponseFormatter()
                ->wrapResult(
                    json_decode(
                        $res->getContent()
                    )
                )
            )
        );
    }
}
