<?php

namespace App\DDD\Backoffice\Shared\Domain\Entity\Mail;

final readonly class MailMessage
{
    public function __construct(
        protected string $to,
        protected string $subject,
        protected string $view,
        protected array  $data = [],
    )
    {
    }

    public static function create
    (string $to, string $subject, string $view, array $data): self
    {
        return new self($to, $subject, $view, $data);
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getView(): string
    {
        return $this->view;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
