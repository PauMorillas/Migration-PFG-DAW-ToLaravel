<?php

namespace App\DDD\Backoffice\Shared\Domain\Mail;


use App\DDD\Backoffice\Shared\Domain\Entity\Mail\MailMessage;

interface MailerServiceInterface
{
    public function send(MailMessage $message, bool $async = true): void;
    public function sendAction(MailMessage $message): void;
}
