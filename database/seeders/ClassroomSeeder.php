<?php

namespace Database\Seeders;

use App\Models\Classroom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Classroom::factory()->createMany([
            [
                'name' => 'Adolescentes',
                'slug' => 'adolescentes',
                'description' => 'Classe para alunos entre 12 e 17 anos de idade.',
            ],
            [
                'name' => 'Jovens',
                'slug' => 'jovens',
                'description' => 'Classe para alunos entre 18 e 35 anos de idade.',
            ],
            [
                'name' => 'Bereanos',
                'slug' => 'bereanos',
                'description' => 'Classe para alunos desde os 18 anos de idade.',
            ],
            [
                'name' => 'Catecúmenos',
                'slug' => 'catecumenos',
                'description' => 'Classe para alunos de qualquer idade que desejam professar sua fé e se tornarem membros da igreja',
            ],
            [
                'name' => 'Cordeirinhos',
                'slug' => 'cordeirinhos',
                'description' => 'Classe para crianças entre 5 e 8 anos de idade.',
            ],
        ]);
    }
}
