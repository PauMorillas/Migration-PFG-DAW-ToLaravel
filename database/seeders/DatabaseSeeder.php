<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\User;
use App\Models\Business;
use App\Models\Booking;
use App\Models\PreBooking;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // usuario de prueba
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Crear 5 negocios asociados a ese usuario
        // Guardamos la colección en una variable para poder usar el primer negocio
        $businesses = Business::factory(5)->create([
            'user_id' => $user->id,
        ]);

        // Tomamos el primer negocio creado para hacer las pruebas
        $primerNegocio = $businesses->first();

        // Crear 10 servicios asociados al primer negocio de tu usuario
        $services = Service::factory(10)->create([
            'business_id' => $primerNegocio->id,
        ]);

        // Tomamos el primer servicio creado
        $servicioDePrueba = $services->first();

        // --- PARA EL CALENDARIO ---

        // Creamos un cliente ficticio que hará las reservas
        $cliente = User::factory()->create([
            'name' => 'Cliente Feliz',
            'email' => 'cliente@gmail.com',
        ]);

        // Creamos 3 Reservas Confirmadas
        Booking::factory(3)->create([
            'service_id' => $servicioDePrueba->id,
            'user_id' => $cliente->id,
        ]);

        // Creamos 2 Pre-Reservas
        PreBooking::factory(2)->create([
            'service_id' => $servicioDePrueba->id,
            'user_id' => null, // Simulamos que aún no ha iniciado sesión/confirmado
        ]);

    }
}
