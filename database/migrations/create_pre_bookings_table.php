<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new Class extends Migration {
    public function up(): void {
        Schema::create('pre_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('token');
            $table->dateTime('expiration_date');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('user_name');
            $table->string('user_email');
            $table->string('user_phone');
            $table->string('pass_hash');

            // La tabla que tendrá la relación no será esta será bookings
            // esta entidad pretende ser una entidad intermedia para poder lograr bloqueos temporales

            /*$table->foreignId('user_id')->nullable()
                ->constrained('pre_bookings')
                ->onDelete('cascade');*/

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down (): void {
        Schema::dropIfExists('pre_bookings');
    }
};
