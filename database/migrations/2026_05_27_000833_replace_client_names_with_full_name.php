<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('clients', 'first_names')) {
            return;
        }

        Schema::table('clients', function (Blueprint $table) {
            $table->string('full_name')->default('')->after('id_card');
        });

        foreach (DB::table('clients')->orderBy('id')->lazy() as $client) {
            DB::table('clients')->where('id', $client->id)->update([
                'full_name' => trim("{$client->first_names} {$client->last_names}"),
            ]);
        }

        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['first_names', 'last_names']);
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('clients', 'full_name')) {
            return;
        }

        Schema::table('clients', function (Blueprint $table) {
            $table->string('last_names')->default('')->after('id_card');
            $table->string('first_names')->default('')->after('last_names');
        });

        foreach (DB::table('clients')->orderBy('id')->lazy() as $client) {
            $parts = preg_split('/\s+/', trim($client->full_name), 2, PREG_SPLIT_NO_EMPTY) ?: ['', ''];

            DB::table('clients')->where('id', $client->id)->update([
                'first_names' => $parts[0],
                'last_names' => $parts[1] ?? '',
            ]);
        }

        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('full_name');
        });
    }
};
