<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        // Generamos una fecha aleatoria entre hoy y la próxima semana, entre las 09:00 y las 17:00
        $startDate = Carbon::instance($this->faker->dateTimeBetween('now', '+1 week'))
            ->setTime($this->faker->numberBetween(9, 17), 0);

        // El fin es 30 minutos después
        $endDate = (clone $startDate)->addMinutes(30);

        return [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => 'ACTIVA',
            // service_id y user_id se los pasaremos desde el Seeder
        ];
    }
}
