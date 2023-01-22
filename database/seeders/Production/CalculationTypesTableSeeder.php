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
                'description' => 'Herner Dart Liga',
                'calculator' => HDLCalculator::class,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'DSAB',
                'description' => 'Deutscher Sportautomaten Bund',
                'calculator' => DSABCalculator::class,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        CalculationType::insert($calculators);
    }
}
