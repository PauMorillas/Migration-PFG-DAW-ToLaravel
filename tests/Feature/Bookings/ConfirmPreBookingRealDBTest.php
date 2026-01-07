<?php

namespace Tests\Feature\Bookings;

use App\DTO\Booking\BookingResponseDTO;
use App\Models\PreBooking;
use App\Models\Service;
use App\Models\User;
use App\Services\PreBookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConfirmPreBookingRealDBTest extends TestCase
{
    use RefreshDatabase;
    public function test_it_confirms_a_prebooking_successfully(): void
    {
        $service = Service::factory()->create();
        $user = User::factory()->create();

        $preBooking = PreBooking::create([
            'service_id' => $service->id,
            'token' => 'valid-token',
            'expiration_date' => now()->addMinutes(30),
            'start_date' => '2025-01-10 10:00:00',
            'end_date' => '2025-01-10 12:00:00',
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_phone' => $user->telephone,
            'user_pass' => 'hashed',
        ]);

        $serviceLayer = app(PreBookingService::class);

        $result = $serviceLayer->confirmPreBooking(
            $service->id,
            $user->id,
            'valid-token'
        );

        // assertion 1: Se creó la booking
        $this->assertDatabaseHas('bookings', [
            'service_id' => $service->id,
            'user_id' => $user->id,
        ]);

        // assertion 2: Devuelve un DTO válido
        $this->assertInstanceOf(BookingResponseDTO::class, $result);
    }
}
