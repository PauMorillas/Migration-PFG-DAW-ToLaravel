<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('status')->default('ACTIVA');

            $table->foreignId('service_id')->nullable()
                ->constrained('services')
                ->onDelete('cascade');

            $table->foreignId('user_id')->nullable()
                ->constrained('users')
                ->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }
};
