<?php

namespace App\Api\V1\Storages;

class TokenIdentityStorage //сделать интерфейс CartIdentityStorageContract что бы и для тестов
{
    public function get(): ?string
    {
        return request()->bearerToken();
    }
}
