<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        $user = new User();
        $user->fill([
            'name' => 'Marco Ehrt',
            'email' => 'admin@admin.com',
        ]);
        $user->password = '$2y$10$DLQ0aDNr.JVhH/7lJ3betevYLV.Xbc/ex6txm5FaFdAqT.DgKEyF6';
        $user->save();
    }
}
