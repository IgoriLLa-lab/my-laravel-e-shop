<?php

namespace App\Api\V1\Actions;

use App\Api\V1\Auth\JWT;
use App\DTO\AuthenticateDTO;
use App\Guards\JWTGuard;
use App\Http\Requests\Api\AuthenticateFormRequest;
use Illuminate\Contracts\Auth\Factory;

class TokenCreateAction
{
    public function __construct(
        private JWT     $jwt,
        private Factory $auth
    )
    {
    }

    public function handle(AuthenticateDto $dto): ?array
    {
        /** @var JWTGuard $guard */
        $guard = $this->auth->guard('jwt');

        $userId = $guard->retrieveIdByCredentials(
            $dto->getEmail(),
            $dto->getPassword()
        );

        if ($userId === null) {
            return null;
        }

        return [
            $this->jwt->createToken($userId),
            $this->jwt->createToken($userId, refresh: true),
        ];
    }
}
