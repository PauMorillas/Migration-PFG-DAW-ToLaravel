<?php

namespace App\DDD\Backoffice\Booking\Domain\Command;

use App\DDD\Backoffice\Shared\ValueObject\Email;

final readonly class SendConfirmationMailCommand
{
    protected function __construct(
        public Email $email,
        public array $data
    ) {}

    public static function createFromValueObjects(Email $email, array $data): self {
        return new self(
            $email,
            $data
        );
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
