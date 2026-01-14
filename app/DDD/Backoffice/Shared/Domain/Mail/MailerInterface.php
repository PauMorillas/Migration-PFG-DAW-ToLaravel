<?php

namespace App\DDD\Backoffice\Shared\Domain\Mail;


use App\DDD\Backoffice\Shared\Domain\Entity\Mail\MailMessage;

interface MailerInterface
{
    public function send(MailMessage $message): void;
}
