<?php

namespace Database\Seeders;

use Database\Factories\FederationFactory;
use Illuminate\Database\Seeder;

class FederationsTableSeeder extends Seeder
{
    public function run(): void
    {
        FederationFactory::new()
            ->times(3)
            ->create();
    }
}
