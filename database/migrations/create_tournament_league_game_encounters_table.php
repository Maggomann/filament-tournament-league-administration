<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tournament_league_game_encounters')) {
            Schema::create('tournament_league_game_encounters', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('game_id')->index('tlge_game_id_index');
                $table->unsignedBigInteger('game_encounter_type_id')->index('tlge_game_encounter_type_id_index');
                $table->integer('order')->default(0)->index('tlge_order_index');
                $table->integer('home_team_win')->default(0);
                $table->integer('home_team_defeat')->default(0);
                $table->integer('guest_team_win')->default(0);
                $table->integer('guest_team_defeat')->default(0);
                $table->integer('home_team_points_leg')->default(0);
                $table->integer('guest_team_points_leg')->default(0);
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        if (app()->environment('staging', 'production')) {
            return;
        }

        Schema::dropIfExists('tournament_league_game_encounters');
    }
};
