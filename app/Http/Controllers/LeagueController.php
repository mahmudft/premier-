<?php

namespace App\Http\Controllers;

use App\Repositories\TeamRepositoryInterface;
use App\Repositories\MatchRepositoryInterface;
use App\Repositories\LeagueTableRepositoryInterface;
use Illuminate\Http\Request;

class LeagueController extends Controller
{
    protected $teamRepo;
    protected $matchRepo;
    protected $leagueTableRepo;

    public function __construct(
        TeamRepositoryInterface $teamRepo,
        MatchRepositoryInterface $matchRepo,
        LeagueTableRepositoryInterface $leagueTableRepo
    ) {
        $this->teamRepo = $teamRepo;
        $this->matchRepo = $matchRepo;
        $this->leagueTableRepo = $leagueTableRepo;
    }

    // Generate fixtures
    public function generateFixtures()
    {
        $teams = $this->teamRepo->all();

        if ($teams->count() != 4) {
            return response()->json(['error' => 'There must be exactly 4 teams to generate fixtures.'], 400);
        }

        $matches = [];
        $week = 1;

        // Generate round-robin home and away matches
        for ($i = 0; $i < $teams->count(); $i++) {
            for ($j = $i + 1; $j < $teams->count(); $j++) {
                // Home and away matches
                $matches[] = [
                    'home_team_id' => $teams[$i]->id,
                    'away_team_id' => $teams[$j]->id,
                    'week' => $week,
                ];
                $matches[] = [
                    'home_team_id' => $teams[$j]->id,
                    'away_team_id' => $teams[$i]->id,
                    'week' => $week,
                ];
                $week++;
            }
        }

        // Create two matches per week for 5 weeks
        shuffle($matches); // Randomize match order
        $currentWeek = 1;
        foreach ($matches as $index => $match) {
            $this->matchRepo->create([
                'home_team_id' => $match['home_team_id'],
                'away_team_id' => $match['away_team_id'],
                'week' => $currentWeek,
            ]);

            // Assign two matches per week
            if (($index + 1) % 2 == 0) {
                $currentWeek++;
            }

            if ($currentWeek > 5) {
                break;
            }
        }

        return response()->json(['message' => 'Fixtures generated successfully.']);
    }

    // Simulate matches for a given week
    public function simulateWeek($week)
    {
        $matches = $this->matchRepo->findByWeek($week);
    
        if ($matches->isEmpty()) {
            return response()->json(['error' => 'No matches for this week.'], 404);
        }
    
        foreach ($matches as $match) {
            $homeTeam = $match->homeTeam;
            $awayTeam = $match->awayTeam;
    
            // Simulate scores based on team strength
            $homeScore = rand(0, $homeTeam->strength);
            $awayScore = rand(0, $awayTeam->strength);
    
            // Use the repository to update match scores
            $this->matchRepo->updateScore($match->id, $homeScore, $awayScore);
    
            // Update league table
            $this->updateLeagueTable($homeTeam, $awayTeam, $homeScore, $awayScore);
        }
    
        return response()->json(['message' => 'Matches simulated for week ' . $week]);
    }
    

  

    private function calculateTeamTable($goalsFor, $goalsAgainst)
    {
        // Logic to calculate wins, losses, draws, points, and goal difference
        return [
            'matches_played' => 1, // Increment based on current state
            'wins' => $goalsFor > $goalsAgainst ? 1 : 0,
            'draws' => $goalsFor == $goalsAgainst ? 1 : 0,
            'losses' => $goalsFor < $goalsAgainst ? 1 : 0,
            'points' => $goalsFor > $goalsAgainst ? 3 : ($goalsFor == $goalsAgainst ? 1 : 0),
            'goal_difference' => $goalsFor - $goalsAgainst,
        ];
    }


    // Show current league table
    public function showLeagueTable($week)
    {
        $teams = $this->teamRepo->all();
        $leagueTable = $this->leagueTableRepo->getOrCreate($week, $teams);
        return response()->json([
            'week' => $week,
            'leagueTable' => $leagueTable, 
        ]);
    }

}
