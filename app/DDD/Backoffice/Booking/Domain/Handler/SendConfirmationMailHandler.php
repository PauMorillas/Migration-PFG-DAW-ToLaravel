<?php

namespace App\DDD\Backoffice\Booking\Domain\Handler;

use App\DDD\Backoffice\Booking\Domain\Command\SendConfirmationMailCommand;
use App\DDD\Backoffice\Shared\Application\MailFactory;
use App\DDD\Backoffice\Shared\Domain\Mail\MailerServiceInterface;

final readonly class SendConfirmationMailHandler
{
    public function __construct(
        private MailerServiceInterface $mailer,
    )
    {}

    public function __invoke(SendConfirmationMailCommand $command): void
    {
        $this->sendConfirmationMail($command);
    }

    private function sendConfirmationMail($command): void {
        $mailMessage = MailFactory::preBookingConfirmation
        ($command->getEmail(), $command->getData());

        $this->mailer->send($mailMessage);
    }
}
