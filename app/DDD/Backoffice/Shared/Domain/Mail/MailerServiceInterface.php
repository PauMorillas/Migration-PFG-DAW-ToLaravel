<?php

namespace App\DDD\Backoffice\Shared\Domain\Mail;


use App\DDD\Backoffice\Shared\Domain\Entity\Mail\MailMessage;

interface MailerServiceInterface
{
    public function send(MailMessage $message): void;
    public function sendAsync(MailMessage $message): void;
    public function sendSync(MailMessage $message): void;

}
