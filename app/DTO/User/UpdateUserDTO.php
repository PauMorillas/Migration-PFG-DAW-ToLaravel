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
        public readonly string $userId,
        protected string $name,
        protected string $email,
        protected ?string $password,
        protected string $role,
        protected ?string $telephone = null)
    {
        parent::__construct($name, $email, $role, $telephone);
    }

    public static function createFromArray(array $data, int $userId): self
    {
        return new self(
            userId: $userId,
            name: $data['name'],
            email: $data['email'],
            password: $data['password'],
            role: $data['role'],
            telephone: array_key_exists('telephone', $data)
                ? $data['telephone']
                : null,
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
            telephone: $user->telephone,
        );
    }

    public function toArray(): array {
        return parent::toArray() + [
            'userId'=> $this->userId,
        ];
    }

    public function getPassword(): string {
        return $this->password;
    }

}
