<?php

namespace App\Jobs;

use App\DDD\Backoffice\Shared\Domain\Entity\Mail\MailMessage;
use App\DDD\Backoffice\Shared\Domain\Mail\MailerServiceInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class SendBookingConfirmationEmail implements ShouldQueue
{
    use Queueable, Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected function __construct(
        private MailMessage $mailMessage,
    )
    {
    }

    public static function create(MailMessage $mailMessage): self {
        return new self($mailMessage);
    }

    /**
     * Execute the job.
     */
    public function handle(
        // Aqui van las dependencias del job
        // si necesitas un servicio que cree algo, va aquí
        MailerServiceInterface $mailerService,
    ): void
    {
        // TODO: Aquí va el envío del Mail
        // En otro caso se llama al command de enviar mail
        $mailerService->send($this->mailMessage);

        // TODO: AL NECESITAR EJECUTAR EL JOB DE MANERA SÍNCRONA O ASÍNCRONA
        // DESDE FUERA DEL JOB LO QUE HICE FUE EJECUTAR SOLO LA ACCION de Enviar
    }
}
