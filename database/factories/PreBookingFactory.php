<?php

namespace Database\Factories;

use App\Models\PreBooking;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class PreBookingFactory extends Factory
{
    protected $model = PreBooking::class;

    public function definition(): array
    {
        $startDate = Carbon::instance($this->faker->dateTimeBetween('now', '+1 week'))
            ->setTime($this->faker->numberBetween(9, 17), 30); // Acabadas en :30 para que no pise a la otra

        $endDate = (clone $startDate)->addMinutes(30);

        return [
            'token' => Str::random(20), // Token aleatorio para el correo
            'uuid' => Str::uuid(),
            'expiration_date' => now()->addMinutes(30), // Caduca en 30 minutos
            'start_date' => $startDate,
            'end_date' => $endDate,
            'user_name' => $this->faker->name(),
            'user_email' => $this->faker->unique()->safeEmail(),
            'user_phone' => $this->faker->numerify('6########'),
            'user_pass' => Hash::make('password123'),
        ];
    }
}
