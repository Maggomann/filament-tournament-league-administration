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
            $table->timestamp('start')->nullable()->index();
            $table->timestamp('end')->nullable()->index();
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
            $table->timestamp('period_start')->nullable()->index();
            $table->timestamp('period_end')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tournament_league_game_schedule_tournament_league_leagues', function (Blueprint $table) {
            $table->unsignedBigInteger('tournament_league_game_schedule_id')->nullable()->index('game_schedule_id_index');
            $table->unsignedBigInteger('tournament_league_league_id')->nullable()->index('league_league_id_index');
            $table->string('league_name')->index('name_index');
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
