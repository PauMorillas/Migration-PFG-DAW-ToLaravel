<?php

namespace App\DDD\Backoffice\Shared\Application;

use App\DDD\Backoffice\Shared\Domain\Entity\Mail\MailMessage;
use App\DDD\Backoffice\Shared\ValueObject\Email;
use Illuminate\Support\Facades\Mail;

final class MailFactory
{

    protected function __construct()
    {
    }

    public static function create(
        string $to,
        string $subject,
        string $view,
        array $data,
    ): MailMessage {
        return MailMessage::create(
            to: $to,
            subject: $subject,
            view: $view,
            data: $data
        );
    }

    public static function preBookingConfirmation
    (Email $to, array $data): MailMessage {
        return MailMessage::create(
            to: $to->value(),
            subject: 'Confirma tu Reserva',
            view: 'emails.confirm-prebooking',
            data: $data
        );
    }
}
