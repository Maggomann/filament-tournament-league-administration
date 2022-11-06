<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Maggomann\FilamentTournamentLeagueAdministration\Tests\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // TODO: muss wieder raus
        if (User::whereEmail('admin@admin.com')->exists()) {
            return;
        }

        $user = new User();
        $user->fill([
            'name' => 'Marco Ehrt',
            'email' => 'admin@admin.com',
        ]);
        $user->password = '$2y$10$DLQ0aDNr.JVhH/7lJ3betevYLV.Xbc/ex6txm5FaFdAqT.DgKEyF6';
        $user->save();
    }
}
