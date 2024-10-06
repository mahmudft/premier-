<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teamNames = ['Liverpool', 'Arsenal', 'Chelsea', 'Manchester United'];

        foreach ($teamNames as $name) {
            Team::create([
                'name' => $name,               
                'strength' => rand(80, 100)  
            ]);
        }
    }
}
