<?php

namespace App\Http\Middleware;

use App\Api\V1\Storages\TokenIdentityStorage;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        auth()->shouldUse('jwt');

        app()->bind(null, TokenIdentityStorage::class);

        return $next($request);
    }
}
