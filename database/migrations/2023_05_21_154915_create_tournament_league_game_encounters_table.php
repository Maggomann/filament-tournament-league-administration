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
                $table->unsignedBigInteger('game_id')->index();
                $table->unsignedBigInteger('player_encounter_type_id')->index();
                $table->unsignedBigInteger('order')->default(0)->index();
                $table->unsignedBigInteger('home_player_1_id')->nullable()->index();
                $table->unsignedBigInteger('home_player_2_id')->nullable()->index();
                $table->unsignedBigInteger('guest_player_1_id')->nullable()->index();
                $table->unsignedBigInteger('guest_player_2_id')->nullable()->index();
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
