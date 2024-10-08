<?php

namespace Database\Factories;

use App\Models\Matchs;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class MatchsFactory extends Factory
{
    protected $model = Matchs::class;

    public function definition()
    {
        return [
            'home_team_id' => Team::factory(), // Assumes you also have a TeamFactory
            'away_team_id' => Team::factory(), // Assumes you also have a TeamFactory
            'home_score' => $this->faker->numberBetween(0, 5), // Random score between 0 and 5
            'away_score' => $this->faker->numberBetween(0, 5), // Random score between 0 and 5
            'week' => $this->faker->numberBetween(1, 20), // Assuming a 38-week season
        ];
    }
}
