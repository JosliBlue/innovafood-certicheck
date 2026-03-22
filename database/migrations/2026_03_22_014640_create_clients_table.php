<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            // Datos personales
            $table->string('id_card');
            $table->date('birthday')->nullable();
            $table->string('last_names');
            $table->string('first_names');

            // Datos del curso/suscripción
            $table->string('course_name');
            $table->string('subscription_type');

            // Fechas
            $table->date('expires_at');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
