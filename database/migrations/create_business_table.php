<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


return new class extends Migration {
    public function up(): void
    {
        Schema::create(
            'businesses',
            function (Blueprint $table) {
                // El -> se usa para acceder a métodos y propiedades de un objeto en PHP
                $table->id(); // Crea una columna id (BIGINT, autoincremental, clave primaria)
                $table->string('name'); // Crea una columna name (VARCHAR)
                $table->string('email')->unique(); // Crea una columna email con la restricción UNIQUE (VARCHAR)
                $table->char('phone', 9);
                $table->time('open_hours');
                $table->time('close_hours');
                $table->string('open_days');
                // TODO: QUITAR NULLABLE CUANDO SE ASIGNE LA RELACIÓN
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');

                // Simplicita que se creen las columnas created_at, updated_at y delted_at
                // OBLIGATORIO SI EL MODELO USA SOFTDELETES
                $table->timestamps();
                $table->softDeletes();
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
