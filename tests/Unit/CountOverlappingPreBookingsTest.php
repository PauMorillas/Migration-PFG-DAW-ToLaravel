<?php

namespace Tests\Unit;

use App\Models\PreBooking;
use App\Repositories\Contracts\PreBookingRepositoryInterface;
use Mockery;
use Tests\TestCase;

class CountOverlappingPreBookingsTest extends TestCase
{

    public function testDetectsOverlappingPreBookingsWithMock()
    {
        $repoMock = Mockery::mock(PreBookingRepositoryInterface::class);

        $repoMock->shouldReceive('countOverlappingPreBookings')
            ->with(
                1,
                '2025-01-10 11:00:00',
                '2025-01-10 12:00:00',
                null,
            )
            ->once()
            ->andReturn(1);

        $count = $repoMock->countOverlappingPreBookings(
            serviceId: 1,
            startDate: '2025-01-10 11:00:00',
            endDate: '2025-01-10 12:00:00',
            ignorePreBookingId: null
        );

        $this->assertEquals(1, $count);
    }

}
