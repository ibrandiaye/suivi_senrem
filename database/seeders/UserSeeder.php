<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Coordonnateur SENRM Enda',
                'email' => 'admin@senrm-enda.org',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+221 77 123 45 67',
                'region' => 'Dakar',
            ],
            [
                'name' => 'Superviseur National',
                'email' => 'superviseur@senrm-enda.org',
                'password' => Hash::make('password'),
                'role' => 'superviseur',
                'phone' => '+221 77 234 56 78',
                'region' => 'Kaolack',
            ],
            [
                'name' => 'Animateur Relais Terrain 1',
                'email' => 'animateur1@senrm-enda.org',
                'password' => Hash::make('password'),
                'role' => 'animateur',
                'phone' => '+221 77 345 67 89',
                'region' => 'Thiès',
            ],
            [
                'name' => 'Animateur Relais Terrain 2',
                'email' => 'animateur2@senrm-enda.org',
                'password' => Hash::make('password'),
                'role' => 'animateur',
                'phone' => '+221 77 456 78 90',
                'region' => 'Fatick',
            ],
        ];

        foreach ($users as $u) {
            User::updateOrCreate(['email' => $u['email']], $u);
        }
    }
}
