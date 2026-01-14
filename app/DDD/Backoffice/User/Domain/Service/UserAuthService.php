<?php

namespace App\DDD\Backoffice\User\Domain\Service;

use App\DDD\Backoffice\User\Domain\ValueObject\AuthUserId;
use App\Models\User;
use Illuminate\Http\Request;

final readonly class UserAuthService
{
    private User $user;

    protected function __construct(User $user) {
        $this->user = $user;
    }

    public static function createFromRequest(Request $request): self {
        return new self(
            $request->user(),
        );
    }

    public static function createFromAuth(): self {
        return new self(
            auth()->user(),
        );
    }

    public function getAuthUserId(): AuthUserId {
        return AuthUserId::createFromInt($this->user->id);
    }

    public function isCliente(): bool
    {
        return $this->user->isCliente();
    }

    public function isGerente(): bool
    {
        return $this->user->isGerente();
    }

}
