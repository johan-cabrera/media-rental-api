<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario Administrador
        User::updateOrCreate(
            ['email' => 'admin@mediarental.com'],
            [
                'name'     => 'Administrador General',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ]
        );

        // Usuario Regular
        User::updateOrCreate(
            ['email' => 'user@mediarental.com'],
            [
                'name'     => 'Operador Regular',
                'password' => Hash::make('password123'),
                'role'     => 'user',
            ]
        );
    }
}
