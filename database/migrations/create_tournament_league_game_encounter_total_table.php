<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tournament_league_game_encounter_total')) {
            Schema::create('tournament_league_game_encounter_total', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('game_id')->nullable()->index();
                $table->integer('home_team_win_total')->default(0);
                $table->integer('home_team_defeat_total')->default(0);
                $table->integer('guest_team_win_total')->default(0);
                $table->integer('guest_team_defeat_total')->default(0);
                $table->integer('home_team_points_leg_total')->default(0);
                $table->integer('guest_team_points_leg_total')->default(0);
                $table->unsignedBigInteger('high_score_home_player_id')->nullable()->index('tlget_high_score_home_player_id_index');
                $table->unsignedBigInteger('high_score_guest_player_id')->nullable()->index('tlget_high_score_guest_player_id_index');
                $table->unsignedBigInteger('high_finish_home_player_id')->nullable()->index('tlget_high_finish_home_player_id_index');
                $table->unsignedBigInteger('high_finish_guest_player_id')->nullable()->index('tlget_high_finish_guest_player_id_index');
                $table->unsignedBigInteger('short_game_home_player_id')->nullable()->index('tlget_short_game_home_player_id_index');
                $table->unsignedBigInteger('short_game_guest_player_id')->nullable()->index('tlget_short_game_guest_player_id_index');
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

        Schema::dropIfExists('tournament_league_game_encounter_total');
    }
};
