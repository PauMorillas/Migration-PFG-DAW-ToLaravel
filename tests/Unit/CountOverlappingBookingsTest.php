<?php

namespace Tests\Unit;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Eloquent\BookingRepository;
use Mockery;
use Tests\TestCase;

class CountOverlappingBookingsTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function testDetectsOverlappingBookingsWithMock(): void {
        // Crear un mock del repo
        $repoMock = Mockery::mock(BookingRepositoryInterface::class);

        $repoMock->shouldReceive('countOverlappingBookings')
            ->with(
                1,
                '2025-01-10 10:00:00',
                '2025-01-10 12:00:00',
                null,
                BookingStatus::ACTIVA
            ) // Parámetros que espera la función del should
            ->once()
            ->andReturn(1);

        // Llamamos al método
        $count = $repoMock->countOverlappingBookings(
            serviceId: 1,
            startDate: '2025-01-10 10:00:00',
            endDate: '2025-01-10 12:00:00',
            status: BookingStatus::ACTIVA,
        );

        // Afirma que devuelve lo que se simula en el test
        $this->assertEquals(1, $count);
    }
}
