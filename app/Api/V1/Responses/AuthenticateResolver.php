<?php

namespace App\Api\V1\Responses;

use App\Api\V1\Contracts\ResponseResolverContract;
use App\DTO\AuthenticateDTO;
use App\Http\Requests\Api\AuthenticateFormRequest;
use Illuminate\Contracts\Auth\Factory;
use src\Auth\JWT;

class AuthenticateResolver implements ResponseResolverContract
{
    private ?AuthenticateDTO $authenticateDTO = null;

    public function __construct(
        private JWT     $jwt,
        private Factory $authFactory,
    )
    {
    }

    public function with(mixed $data = null): static
    {
        $this->authenticateDTO = $data;

        return $this;
    }

    public function resolve(): ?string
    {
        $id = $this->authFactory->guard('jwt')->retrieveIdByCredentials(
            $this->authenticateDTO->getEmail(),
            $this->authenticateDTO->getPassword()
        );

        if ($id === null) {
            return null;
        }

        return $this->jwt->createToken((string)$id);
    }
}
