<?php

namespace Database\Seeders\Production;

use Illuminate\Database\Seeder;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Contracts\Calculators\DSABCalculator;
use Maggomann\FilamentTournamentLeagueAdministration\Domain\Game\Contracts\Calculators\HDLCalculator;
use Maggomann\FilamentTournamentLeagueAdministration\Models\CalculationType;

class CalculationTypesTableSeeder extends Seeder
{
    public function run(): void
    {
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
}
