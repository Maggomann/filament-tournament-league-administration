<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Factories\FederationFactory;

class FederationsTableSeeder extends Seeder
{
    public function run()
    {
        FederationFactory::new()
            ->times(3)
            ->create();
    }
}
