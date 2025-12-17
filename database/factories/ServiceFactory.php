<?php 

namespace Database\Factories;

use App\Models\Business;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

Class ServiceFactory extends Factory {
    protected $model = Service::class;

    public function definition(){
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'location' => fake()->address(),
            'price' => fake()->randomFloat(2, 0, 100),
            'duration' => fake()->randomDigit(),
            'business_id' => Business::factory(),
        ];
    }
}