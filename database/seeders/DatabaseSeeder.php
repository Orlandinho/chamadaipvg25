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
    }
}
