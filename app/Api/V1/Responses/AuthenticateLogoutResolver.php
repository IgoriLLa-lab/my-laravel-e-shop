<?php

namespace App\Api\V1\Responses;

use Illuminate\Contracts\Auth\Factory;

class AuthenticateLogoutResolver
{
    public function __construct(
        private Factory $auth,
    )
    {
    }

    public function with(mixed $data = null): static
    {
         return $this;

    }

    public function resolve(): bool
    {
        $this->auth->guard('jwt')->logout();

        return true;
    }

}
