<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new Class extends Migration {
    public function up(): void {
        Schema::create('services', function(Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->string('location');
            $table->float('price');
            $table->integer('duration');

            // TODO: QUITAR NULLABLE CUANDO SE ASIGNE LA RELACIÓN
            $table->foreignId('business_id')->nullable()->constrained('businesses');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('services');
    }
};