<?php

namespace Database\Seeders;

use Database\Seeders\Production\CalculationTypesTableSeeder;
use Database\Seeders\Production\DartTypesTableSeeder;
use Database\Seeders\Production\EventLocationTableSeeder;
use Database\Seeders\Production\GameEncounterTypesTableSeeder;
use Database\Seeders\Production\ModesTableSeeder;
use Database\Seeders\Production\PlayerRolesTableSeeder;
use Database\Seeders\Production\QualificationLevelsTableSeeder;
use Illuminate\Database\Seeder;

class FilamentTournamentProductionTableSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CalculationTypesTableSeeder::class,
            DartTypesTableSeeder::class,
            ModesTableSeeder::class,
            QualificationLevelsTableSeeder::class,
            EventLocationTableSeeder::class,
            PlayerRolesTableSeeder::class,
            GameEncounterTypesTableSeeder::class,
        ]);
    }
}
