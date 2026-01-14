<?php

namespace App\DDD\Backoffice\Shared\Infrastructure\Mail;

use App\DDD\Backoffice\Shared\Domain\Entity\Mail\MailMessage;
use App\DDD\Backoffice\Shared\Domain\Mail\MailerInterface;
use Illuminate\Support\Facades\Mail;

final readonly class LaravelMailer implements MailerInterface
{

    public function send(MailMessage $message): void
    {
        Mail::send(
            $message->getView(),
            $message->getData(),

            function ($mail) use ($message){
                $mail->to($message->getTo())
                    ->subject($message->getSubject());
            }
        );
    }
}
