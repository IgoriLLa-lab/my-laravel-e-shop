<?php

namespace App\Guards;

use Exceptions\JWTExpiredException;
use Exceptions\JWTValidatorException;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use JWT;

class JWTGuard implements Guard
{
    use GuardHelpers;

    public function __construct(
        private readonly JWT     $jwt,
        UserProvider             $provider,
        private readonly Request $request
    )
    {
        $this->provider = $provider;
    }

    /**
     * @throws JWTExpiredException
     * @throws JWTValidatorException
     */
    public function user()
    {
        if ($this->user !== null) {
            return $this->user;
        }

        $token = $this->request->bearerToken();

        if ($token === null) {
            return null;
        }

        $id = $this->jwt->parseToken($token);

        return $this->user = $this->provider->retrieveById($id);

    }

    /**
     * @param array $credentials
     * @return bool|Authenticatable|null
     */
    public function validate(array $credentials = []): bool|Authenticatable|null
    {
        return $this->provider->retrieveByCredentials($credentials);
    }
}
