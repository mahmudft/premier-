<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    protected $model = Team::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word, // Random team name
            'strength' => $this->faker->numberBetween(50, 100), // Random strength between 50 and 100
        ];
    }
}
