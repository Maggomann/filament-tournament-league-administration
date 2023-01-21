<?php

use Database\Seeders\FilamentTournamentProductionTableSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maggomann\FilamentTournamentLeagueAdministration\Models\CalculationType;

return new class() extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tournament_league_total_team_points')) {
            Schema::create('tournament_league_total_team_points', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('game_schedule_id')->nullable()->index();
                $table->unsignedBigInteger('team_id')->nullable()->index();
                $table->integer('placement')->default(999)->index();
                $table->integer('total_number_of_encounters')->default(0);
                $table->integer('total_wins')->default(0);
                $table->integer('total_defeats')->default(0);
                $table->integer('total_draws')->default(0);
                $table->integer('total_victory_after_defeats')->default(0);
                $table->integer('total_home_points_legs')->default(0);
                $table->integer('total_guest_points_legs')->default(0);
                $table->integer('total_home_points_games')->default(0);
                $table->integer('total_guest_points_games')->default(0);
                $table->integer('total_home_points_from_games_and_legs')->default(0);
                $table->integer('total_guest_points_from_games_and_legs')->default(0);
                $table->integer('total_points')->default(0);
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (DB::table(app(CalculationType::class)->getTable())->first() === null) {
            Artisan::call('db:seed', [
                '--class' => FilamentTournamentProductionTableSeeder::class,
            ]);
        }
    }

    public function down(): void
    {
        if (app()->environment('staging', 'production')) {
            return;
        }

        Schema::dropIfExists('tournament_league_total_team_points');
    }
};
