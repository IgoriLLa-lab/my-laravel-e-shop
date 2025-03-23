<?php

namespace App\Http\Middleware;

use Exceptions\JWTExpiredException;
use Exceptions\JWTValidatorException;
use Illuminate\Auth\Middleware\Authenticate;
use JWT;
use Mockery\Exception;

class AuthenticateApi extends Authenticate
{
    protected function authenticate($request, array $guards): void
    {
        $token = $request->bearerToken();

        $jwt = app(JWT::class);

        try {
           $id = $jwt->parseToken($token);

            auth()->loginUsingId($id);
        } catch (Exception $e) {
          //TODO такой вариант тоже возможен вместо GUARD
        } catch (JWTExpiredException|JWTValidatorException $e) {
        }


    }
}
