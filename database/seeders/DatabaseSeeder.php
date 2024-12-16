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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Antonio Orlando',
            'email' => 'orlando@example.com',
            'slug' => 'antonio-orlando',
            'role_id' => 1,
        ]);
    }
}
