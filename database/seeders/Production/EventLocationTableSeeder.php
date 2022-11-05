<?php

namespace Database\Seeders\Production;

use Illuminate\Database\Seeder;
use Maggomann\FilamentTournamentLeagueAdministration\Models\EventLocation;

class EventLocationTableSeeder extends Seeder
{
    public function run(): void
    {
        EventLocation::updateOrCreate(
            ['name' => EventLocation::AS_DEFAULT_NAME],
        );
    }
}
