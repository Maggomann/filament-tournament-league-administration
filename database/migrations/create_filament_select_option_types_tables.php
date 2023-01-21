<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        collect([
            'tournament_league_modes',
            'tournament_league_dart_types',
            'tournament_league_qualification_levels',
        ])->each(function (string $tableName) {
            if (! Schema::hasTable($tableName)) {
                Schema::create($tableName, function (Blueprint $table) {
                    $table->id();
                    $table->string('title_translation_key');
                    $table->timestamps();
                    $table->softDeletes();
                });
            }
        });
    }

    public function down(): void
    {
        if (app()->environment('staging', 'production')) {
            return;
        }

        collect([
            'tournament_league_modes',
            'tournament_league_dart_types',
            'tournament_league_qualification_levels',
        ])->each(fn (string $tableName) => Schema::dropIfExists($tableName));
    }
};
