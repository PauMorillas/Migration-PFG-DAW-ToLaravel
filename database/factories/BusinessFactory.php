<?php 
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Business;
use App\Models\User;

class BusinessFactory extends Factory {
    // Modelo sobre el que se hace la fábrica
    protected $model = Business::class;

    public function definition(){
        return [
            'name' => fake()->company(),
            'email' => fake()->unique()->companyEmail(),
            'phone' => fake()->numerify('#########'),
            'open_hours' => fake()->time('H:i:s', '09:00:00'),
            'close_hours' => fake()->time('H:i:s', '20:00:00'),
            'open_days' => '1,2,3,4,5,6,7',
            'user_id' => User::factory(),
        ];
    }
}