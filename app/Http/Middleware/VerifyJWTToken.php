<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class VerifyJWTToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->exception('The provided auth token has expired', 401);
            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException ||
                     $e instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
                return response()->exception('The provided auth token is invalid', 400);
            } else {
                return response()->exception('No authorization token provided', 401);
            }
        }
        return $next($request);
    }
}
