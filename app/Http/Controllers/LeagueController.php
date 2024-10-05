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

        if ($teams->count() < 2) {
            return response()->json(['error' => 'Not enough teams to generate fixtures.'], 400);
        }

        $week = 1;
        foreach ($teams as $homeTeam) {
            foreach ($teams as $awayTeam) {
                if ($homeTeam->id != $awayTeam->id) {
                    // Create a new match
                    $this->matchRepo->create([
                        'home_team_id' => $homeTeam->id,
                        'away_team_id' => $awayTeam->id,
                        'week' => $week++
                    ]);
                }
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
            $match->home_score = rand(0, $homeTeam->strength);
            $match->away_score = rand(0, $awayTeam->strength);
            $match->save();

            // Update league table
            $this->updateLeagueTable($homeTeam, $awayTeam, $match->home_score, $match->away_score);
        }

        return response()->json(['message' => 'Matches simulated for week ' . $week]);
    }

    private function updateLeagueTable($homeTeam, $awayTeam, $homeScore, $awayScore)
    {
        // Update home team table
        $homeTeamTableData = $this->calculateTeamTable($homeScore, $awayScore);
        $this->leagueTableRepo->updateOrCreate($homeTeam->id, $homeTeamTableData);

        // Update away team table
        $awayTeamTableData = $this->calculateTeamTable($awayScore, $homeScore);
        $this->leagueTableRepo->updateOrCreate($awayTeam->id, $awayTeamTableData);
    }

    private function calculateTeamTable($goalsFor, $goalsAgainst)
    {
        // Logic to calculate wins, losses, draws, goal difference, and points
        return [
            'matches_played' => 1, // Example, adjust this for actual logic
            'wins' => $goalsFor > $goalsAgainst ? 1 : 0,
            'draws' => $goalsFor == $goalsAgainst ? 1 : 0,
            'losses' => $goalsFor < $goalsAgainst ? 1 : 0,
            'points' => $goalsFor > $goalsAgainst ? 3 : ($goalsFor == $goalsAgainst ? 1 : 0),
            'goal_difference' => $goalsFor - $goalsAgainst,
        ];
    }

    // Show current league table
    public function showLeagueTable()
    {
        $leagueTable = $this->leagueTableRepo->getOrderedTable();
        return response()->json($leagueTable);
    }
}

