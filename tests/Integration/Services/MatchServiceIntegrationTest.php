<?php

namespace Tests\Integration\Services;

use App\Models\Team;
use App\Models\Matchs;
use App\Services\MatchService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\TeamsSeeder;

class MatchServiceIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $matchService;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(TeamsSeeder::class); // Ensure there are teams in the database for testing
        $this->matchService = $this->app->make(MatchService::class);
    }

    public function test_createOrGetMatchByWeek()
    {
        $teams = Team::all();
        $this->assertGreaterThanOrEqual(4, $teams->count(), 'Not enough teams found in the database.');
        $week = 2;
        Matchs::factory()->create([
            'home_team_id' => $teams[0]->id,
            'away_team_id' => $teams[1]->id,
            'home_score' => 1,
            'away_score' => 2,
            'week' => $week,
        ]);
        Matchs::factory()->create([
            'home_team_id' => $teams[2]->id,
            'away_team_id' => $teams[3]->id,
            'home_score' => 3,
            'away_score' => 1,
            'week' => $week,
        ]);
        $matches = $this->matchService->createOrGetMatchByWeek($week);
        $this->assertDatabaseCount('matchs', 2);
        $this->assertEquals(2, $matches->count());
        foreach ($matches as $match) {
            $this->assertEquals($week, $match->week);
        }
    }
}
