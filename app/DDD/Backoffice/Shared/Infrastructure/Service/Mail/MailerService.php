<?php

namespace App\DDD\Backoffice\Shared\Infrastructure\Service\Mail;

use App\DDD\Backoffice\Shared\Domain\Entity\Mail\MailMessage;
use App\DDD\Backoffice\Shared\Domain\Mail\MailerServiceInterface;
use App\Jobs\SendMailJob;
use Illuminate\Support\Facades\Mail;

final readonly class MailerService implements MailerServiceInterface
{

    /**
     * Acción REAL de enviar mail
     * (Funcion que usa el Job)
     */
    public function sendAction(MailMessage $message): void
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

    private function sendAsync(MailMessage $message): void
    {
        SendMailJob::dispatch($message)
            ->onQueue('emails');
    }

    public function send(MailMessage $message, bool $async = true): void
    {
        if ($async) {
            $this->sendAsync($message);
            return;
        }

        SendMailJob::dispatchSync($message);
    }
}
