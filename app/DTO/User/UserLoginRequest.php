<?php

namespace App\DTO\User;

class UserLoginRequest
{
    public function __construct(private readonly string $email,
                                private readonly string $password)
    {
    }

    public static function createFromArray(array $data): self{
        return new self(
            email: $data['email'],
            password: $data['password']
        );
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
