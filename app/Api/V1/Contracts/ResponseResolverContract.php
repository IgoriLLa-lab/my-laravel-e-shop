<?php

namespace App\Api\V1\Contracts;

use App\Http\Requests\Api\AuthenticateFormRequest;

interface ResponseResolverContract
{
    public function with(mixed $data = null): static;

    public function resolve(): mixed;
}
