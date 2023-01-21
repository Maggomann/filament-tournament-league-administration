<?php

namespace Database\Seeders;

use Database\Factories\AddressFactory;
use Illuminate\Database\Seeder;
use Maggomann\FilamentTournamentLeagueAdministration\Models\Player;

class PlayerAddressTableSeeder extends Seeder
{
    public function run(): void
    {
        Player::get()->each(fn (Player $player) => AddressFactory::new()
            ->create([
                'addressable_id' => $player->id,
                'addressable_type' => $player->getMorphClass(),
            ])
        );
    }
}
