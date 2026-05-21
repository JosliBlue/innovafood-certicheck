<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

/**
 * Pasa plantillas antiguas con archivo en disco a MIME + Base64 en columna (para BD nueva véase la migración create).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('certificate_templates')) {
            return;
        }

        if (! Schema::hasColumn('certificate_templates', 'background_path')) {
            return;
        }

        Schema::table('certificate_templates', function (Blueprint $table) {
            $table->string('background_mime', 128)->nullable()->after('name');
            $table->longText('background_base64')->nullable()->after('background_mime');
        });

        $disk = Storage::disk('public');

        $rows = DB::table('certificate_templates')->whereNotNull('background_path')->get();

        foreach ($rows as $row) {
            $path = $row->background_path;
            if ($path === null || $path === '') {
                continue;
            }

            try {
                if (! $disk->exists($path)) {
                    continue;
                }

                $binary = $disk->get($path);
                $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                $mime = match ($extension) {
                    'png' => 'image/png',
                    'webp' => 'image/webp',
                    default => 'image/jpeg',
                };

                DB::table('certificate_templates')->where('id', $row->id)->update([
                    'background_mime' => $mime,
                    'background_base64' => base64_encode($binary),
                ]);

                $disk->delete($path);
            } catch (Throwable) {
                continue;
            }
        }

        Schema::table('certificate_templates', function (Blueprint $table) {
            $table->dropColumn('background_path');
        });
    }

    public function down(): void
    {
        // Irreversible: los binarios ya no se restauran a disco.
    }
};
