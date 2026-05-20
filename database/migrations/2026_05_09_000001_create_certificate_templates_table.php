<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('background_mime', 128);
            $table->longText('background_base64');
            $table->string('background_back_mime', 128)->nullable();
            $table->longText('background_back_base64')->nullable();
            /** Posiciones por campo (coordenadas de diseño); ver CertificateTemplate::DESIGN_WIDTH / DESIGN_HEIGHT. */
            $table->json('fields')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_templates');
    }
};
