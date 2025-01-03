<?php

namespace Database\Factories;

use App\Models\Classroom;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->firstName() . ' ' . fake()->lastName();
        return [
            'classroom_id' => Classroom::all()->random()->id,
            'name' => $name,
            'slug' => Str::slug($name , '-'),
            'dob' => fake()->dateTimeBetween('-60 years', '-2 years')->format('Y-m-d'),
        ];
    }
}
