<?php

namespace Database\Seeders;

use App\Enums\Roles;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'slug' => 'administrador',
            'email' => 'admin@ipvgchamada.com',
            'password' => bcrypt('password'),
            'role_id' => 1,
            'classroom_id' => null,
            'email_verified_at' => now(),
        ]);

        User::factory()->createMany([
            [
                'name' => 'Antonio Orlando',
                'email' => 'orlando@example.com',
                'slug' => 'antonio-orlando',
                'role_id' => 1,
            ],
            [
                'name' => 'Irene Martins',
                'email' => 'irene-martins@example.com',
                'slug' => 'irene-martins',
                'role_id' => 2,
            ],
            [
                'name' => 'Guilherme Martins',
                'email' => 'guilherme-martins@example.com',
                'slug' => 'guilherme-martins',
                'role_id' => 3,
            ]
        ]);
    }
}
