<?php

namespace App\DTO;

final readonly class AuthenticateDTO
{
    public function __construct(
        private string $email,
        #[\SensitiveParameter]
        private string $password
    )
    {
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }



    public function toArray(): array
    {
        return [
            'email' => $this->email,
        ];
    }

}
