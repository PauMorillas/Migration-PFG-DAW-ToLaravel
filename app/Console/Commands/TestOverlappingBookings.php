<?php

namespace App\Console\Commands;

use App\Enums\BookingStatus;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Console\Command;

class TestOverlappingBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-overlapping-bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para testear la función que cuenta Reservas solapadas';

    /**
     * Execute the console command.
     */
    public function handle(BookingRepositoryInterface $repo)
    {
        $count = $repo->countOverlappingBookings(
            serviceId: 1,
            startDate: '2025-01-10 11:00:00',
            endDate: '2025-01-10 13:00:00',
            status: BookingStatus::ACTIVA
        );

        if ($count > 0) {
            $this->error("SOLAPAMIENTO DETECTADO: ({$count} reservas)");
        } else {
            $this->info("No hay solapamientos");
        }
    }
}
