<?php

namespace App\DTO\User;

use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use JsonSerializable;
use App\DTO\User\BaseUserDTO;

class UpdateUserDTO extends BaseUserDTO implements Arrayable, JsonSerializable
{
    public function __construct(
        protected readonly string $userId,
        protected string $name,
        protected string $email,
        protected readonly ?string $password,
        protected string $role)
    {
        parent::__construct($name, $email, $role);
    }

    public static function createFromArray(array $data, int $userId): self
    {
        return new self(
            userId: $userId,
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            role: $data['role'],
        );
    }

    public function createFromModel(User $user): self
    {
        return new self(
            userId: $user->userId,
            name: $user->name,
            email: $user->email,
            password: null,
            role: $user->role,
        );
    }

    public function toArray(): array {
        return [
            'userId'=> $this->userId,
            'name'=> $this->name,
            'email'=> $this->email,
            'role'=> $this->role,
        ];
    }

    public function getPassword(): string {
        return $this->password;
    }

}
