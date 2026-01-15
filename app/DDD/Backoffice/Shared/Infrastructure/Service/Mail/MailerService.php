<?php

namespace App\DDD\Backoffice\Shared\Infrastructure\Service\Mail;

use App\DDD\Backoffice\Shared\Domain\Entity\Mail\MailMessage;
use App\DDD\Backoffice\Shared\Domain\Mail\MailerServiceInterface;
use App\Jobs\SendBookingConfirmationEmail;
use Illuminate\Support\Facades\Mail;

final readonly class MailerService implements MailerServiceInterface
{

    // Método que usa el Job
    public function send(MailMessage $message): void
    {
        Mail::send(
            $message->getView(),
            $message->getData(),

            function ($mail) use ($message) {
                $mail->to($message->getTo())
                    ->subject($message->getSubject());
            }
        );
    }

    public function sendAsync(MailMessage $message): void {
        SendBookingConfirmationEmail::dispatch($message)->onQueue('emails');
    }

    public function sendSync(MailMessage $message): void {
        $this->send($message);
    }
}
