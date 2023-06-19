<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tournament_league_game_encounter_home_player')) {
            Schema::create('tournament_league_game_encounter_home_player', function (Blueprint $table) {
                $table->unsignedBigInteger('game_encounter_id')->nullable()->index();
                $table->unsignedBigInteger('player_id')->nullable()->index();
            });
        }
    }

    public function down(): void
    {
        if (app()->environment('staging', 'production')) {
            return;
        }

        Schema::dropIfExists('tournament_league_game_encounter_home_player');
    }
};
