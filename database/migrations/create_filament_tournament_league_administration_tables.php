<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Calculators\DSABCalculator;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Calculators\HDLCalculator;
use Maggomann\FilamentTournamentLeagueAdministration\Models\CalculationType;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tournament_league_calculation_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('description')->nullable();
            $table->string('calculator')->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tournament_league_federations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('calculation_type_id')->nullable()->index();
            $table->string('name')->index();
            $table->string('slug')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tournament_league_leagues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('federation_id')->nullable()->index();
            $table->string('name')->index();
            $table->string('slug')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tournament_league_teams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('league_id')->nullable()->index();
            $table->string('name')->index();
            $table->string('slug')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tournament_league_players', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id')->nullable()->index();
            $table->string('name')->index();
            $table->string('email')->nullable()->unique()->index();
            $table->string('slug')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tournament_league_game_days', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_schedule_id')->index();
            $table->unsignedInteger('day')->index();
            $table->timestamp('started_at')->nullable()->index();
            $table->timestamp('ended_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        DB::update("
            ALTER TABLE tournament_league_game_days
            ADD COLUMN game_schedule_day_unique varchar (512) 
            GENERATED ALWAYS AS
            (
                CONCAT(
                    CONCAT(day, '#', game_schedule_id),
                    '#',
                    IF(deleted_at IS NULL, '-',  deleted_at)
                )
            ) VIRTUAL;
        ");

        Schema::table('tournament_league_game_days', function (Blueprint $table) {
            $table->unique(['game_schedule_day_unique'], 'game_schedule_day_unique_index');
        });

        Schema::create('tournament_league_game_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('federation_id')->nullable()->index();
            $table->morphs('gameschedulable', 'gameschedulable_index');
            $table->string('name')->index();
            $table->timestamp('started_at')->nullable()->index();
            $table->timestamp('ended_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('game_schedule_team', function (Blueprint $table) {
            $table->unsignedBigInteger('game_schedule_id')->nullable()->index('game_schedule_id_index');
            $table->unsignedBigInteger('team_id')->nullable()->index('team_id_index');
        });

        Schema::create('game_schedule_player', function (Blueprint $table) {
            $table->unsignedBigInteger('game_schedule_id')->nullable()->index('game_schedule_id_index');
            $table->unsignedBigInteger('player_id')->nullable()->index('player_id_index');
        });

        Schema::create('tournament_league_games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_schedule_id')->nullable()->index();
            $table->unsignedBigInteger('game_day_id')->nullable()->index();
            $table->unsignedBigInteger('home_team_id')->nullable()->index();
            $table->unsignedBigInteger('guest_team_id')->nullable()->index();
            $table->integer('home_points_legs')->nullable();
            $table->integer('guest_points_legs')->nullable();
            $table->integer('home_points_games')->nullable();
            $table->integer('guest_points_games')->nullable();
            $table->boolean('has_an_overtime')->default(false)->index();
            $table->integer('home_points_after_draw')->nullable();
            $table->integer('guest_points_after_draw')->nullable();
            $table->timestamp('started_at')->nullable()->index();
            $table->timestamp('ended_at')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tournament_league_total_team_points', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_schedule_id')->nullable()->index();
            $table->unsignedBigInteger('team_id')->nullable()->index();
            $table->integer('total_number_of_encounters')->nullable(); // Anzahl Begegnungen
            $table->integer('total_wins')->nullable();
            $table->integer('total_defeats')->nullable();
            $table->integer('total_draws')->nullable();
            $table->integer('total_victory_after_defeats')->nullable();
            $table->integer('total_home_points_games')->nullable();
            $table->integer('total_guest_points_games')->nullable();
            $table->integer('total_home_points_after_draw')->nullable();
            $table->integer('total_guest_points_after_draw')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Ein Spielplan kann einem Verband zugewiesen sein // Ein Verband kann mehrere Spielpläne haben
        // Ein Spielplan kann einer Liga zugewiesen haben // Eine Liga kann mehrere Spielpläne haben
        // Ein Soielplan muss Spieler zugewiesen haben (das ist sogar ein muss) // Ein Spieler kann mehrere Spielpläne zugewiesen sein
        // Ein Spielplan kann mehrere Preise haben
        // Ein Spielplan hat mehrere Spieltage
        // Ein Spieltag hat mehrere Spiele
        // Ein Spiel hat Spielregeln (501 (301, 701, 1001 usw.)
        // Ein Spiel kann eine Spielrundenart haben (Hin/ Rückrunde / oder leer bleiben)
        // EIn Spiel hat einen Heim- und Gastspieler
        // usw

        $this->addCalcutaionTypes();
    }

    private function addCalcutaionTypes(): void
    {
        // TODO: Übersetzung einbauen
        $now = now();
        $calculators = [
            [
                'name' => 'HDL',
                'description' => 'Herner Dart Liga / Win: 2pkt. Lose: 0pkt. Draw: jeweils 1pkt',
                'calculator' => HDLCalculator::getMorphClass(),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'DSAB',
                'description' => 'Deutscher Sportautomaten Bund / Win: 3pkt. Lose: 0pkt. Draw: Verlängerung => Gewinner 2pkt | Verlierer 1pkt.',
                'calculator' => DSABCalculator::getMorphClass(),
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        CalculationType::insert($calculators);
    }
};
