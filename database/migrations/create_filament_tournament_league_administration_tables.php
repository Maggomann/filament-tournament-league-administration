<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Calculators\DSABCalculator;
use Maggomann\FilamentTournamentLeagueAdministration\Contracts\Calculators\HDLCalculator;
use Maggomann\FilamentTournamentLeagueAdministration\Models\CalculationType;

return new class extends Migration
{
    public function up()
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

        $this->addCalcutaionTypes();
    }

    private function addCalcutaionTypes()
    {
        // uebersetzung
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
