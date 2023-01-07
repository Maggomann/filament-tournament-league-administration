<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tournament_league_games')) {
            Schema::create('tournament_league_games', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('game_schedule_id')->nullable()->index();
                $table->unsignedBigInteger('game_day_id')->nullable()->index();
                $table->unsignedBigInteger('home_team_id')->nullable()->index();
                $table->unsignedBigInteger('guest_team_id')->nullable()->index();
                $table->integer('home_points_legs')->default(0);
                $table->integer('guest_points_legs')->default(0);
                $table->integer('home_points_games')->default(0);
                $table->integer('guest_points_games')->default(0);
                $table->boolean('has_an_overtime')->default(false)->index();
                $table->integer('home_points_after_draw')->default(0);
                $table->integer('guest_points_after_draw')->default(0);
                $table->timestamp('started_at')->nullable()->index();
                $table->timestamp('ended_at')->nullable()->index();
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

        Schema::dropIfExists('tournament_league_games');
    }
};
