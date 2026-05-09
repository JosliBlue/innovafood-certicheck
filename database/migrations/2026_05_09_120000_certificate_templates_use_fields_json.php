<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Instalaciones que ya tenían la columna layout antes del editor visual.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('certificate_templates')) {
            return;
        }

        Schema::table('certificate_templates', function (Blueprint $table) {
            if (Schema::hasColumn('certificate_templates', 'layout')) {
                $table->dropColumn('layout');
            }
        });

        Schema::table('certificate_templates', function (Blueprint $table) {
            if (! Schema::hasColumn('certificate_templates', 'fields')) {
                $table->json('fields')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('certificate_templates')) {
            return;
        }

        Schema::table('certificate_templates', function (Blueprint $table) {
            if (Schema::hasColumn('certificate_templates', 'fields')) {
                $table->dropColumn('fields');
            }
        });

        Schema::table('certificate_templates', function (Blueprint $table) {
            if (! Schema::hasColumn('certificate_templates', 'layout')) {
                $table->json('layout')->nullable();
            }
        });
    }
};
