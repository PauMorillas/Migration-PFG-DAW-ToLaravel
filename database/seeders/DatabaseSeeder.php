<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\User;
use App\Models\Business;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Crear 5 negocios asociados a ese usuario
        Business::factory(5)->create([
            'user_id' => $user->id,
        ]);

        Service::factory(10)->create([
            'business_id' => Business::factory(),
        ]);
    }
}
