<?php

namespace App\Api\V1\Actions;

use App\Api\V1\Auth\Exceptions\JWTExpiredException;
use App\Api\V1\Auth\Exceptions\JWTParserException;
use App\Api\V1\Auth\Exceptions\JWTValidatorException;
use App\Api\V1\Auth\JWT;

class RefreshTokenAction
{
    public function __construct(
        private JWT $jwt,
    )
    {
    }

    /**
     * @throws JWTValidatorException
     * @throws JWTParserException
     * @throws JWTExpiredException
     */
    public function handle(string $refreshToken): ?array
    {
        $userId = $this->jwt->parseToken($refreshToken);

        if ($userId === null) {
            return null;
        }

        return [
            $this->jwt->createToken($userId),
            $this->jwt->createToken($userId, refresh: true),
        ];
    }
}
