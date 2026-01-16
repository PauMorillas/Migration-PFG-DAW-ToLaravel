<?php

namespace Tests\Feature\Bookings;

use App\DDD\Backoffice\Shared\ValueObject\Uuid;
use App\Models\PreBooking;
use App\Models\Service;
use App\Repositories\Contracts\PreBookingRepositoryInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CountOverlappingPreBookingsRealDBTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_counts_overlapping_prebookings_correctly()
    {
        $service = Service::factory()->create();
        $serviceId = $service->id;

        // Booking 1: empieza antes y termina dentro del periodo
        PreBooking::create([
            'service_id' => $serviceId,
            'token' => 'token1',
            'expiration_date' => now()->addMinutes(30),
            'start_date' => '2025-01-10 09:00:00',
            'end_date' => '2025-01-10 11:00:00',
            'user_name' => 'Juan Pérez',
            'user_email' => 'juan.perez@email.com',
            'user_phone' => '600111222',
            'user_pass' => 'password123',
            'uuid' => Uuid::random()->value()
        ]);

        // Booking 2: empieza dentro y termina después del periodo
        PreBooking::create([
            'service_id' => $serviceId,
            'token' => 'token2',
            'expiration_date' => now()->addMinutes(30),
            'start_date' => '2025-01-10 11:30:00',
            'end_date' => '2025-01-10 12:30:00',
            'user_name' => 'María López',
            'user_email' => 'maria.lopez@email.com',
            'user_phone' => '611333444',
            'user_pass' => 'password123',
            'uuid' => Uuid::random()->value()
        ]);

        // Booking 3: fuera del periodo (no debería contarse)
        PreBooking::create([
            'service_id' => $serviceId,
            'token' => 'token3',
            'expiration_date' => now()->addMinutes(30),
            'start_date' => '2025-01-10 13:00:00',
            'end_date' => '2025-01-10 14:00:00',
            'user_name' => 'Carlos Ruiz',
            'user_email' => 'carlos.ruiz@email.com',
            'user_phone' => '622555666',
            'user_pass' => 'password123',
            'uuid' => Uuid::random()->value()
        ]);

        $repo = app(PreBookingRepositoryInterface::class);
        $count = $repo->countOverlappingPreBookings(
            $serviceId,
            '2025-01-10 10:00:00',
            '2025-01-10 12:00:00',
            null
        );

        // Solo los 2 primeros pre-bookings se solapan
        $this->assertEquals(2, $count);
    }
}
