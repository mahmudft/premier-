<?php

namespace App\Services;

use App\Implementations\{LeagueTableRepository, TeamRepository};
use App\Models\{Team, LeagueTable};
use App\Helpers\TeamStatistic;
use Illuminate\Database\Eloquent\Collection;

class LeagueService
{
    protected $leagueRepository;
    protected $teamService;

    public function __construct(LeagueTableRepository $leagueRepository, TeamService $teamService)
    {
        $this->leagueRepository = $leagueRepository;
        $this->teamService = $teamService;
    }

    public function generateRandomLeagueRecord(int $week, Team $team): array
    {
        $goalsFor = rand(0, 15);
        $goalsAgainst = rand(0, 20);
        $payload = [
            'matches_played' => rand(7, 10),
            'team_id' => $team['id'],
            'wins' => $goalsFor > $goalsAgainst ? 1 : 0,
            'draws' => $goalsFor == $goalsAgainst ? 1 : 0,
            'losses' => $goalsFor < $goalsAgainst ? 1 : 0,
            'points' => $goalsFor > $goalsAgainst ? 3 : ($goalsFor == $goalsAgainst ? 1 : rand(1, 6)),
            'goal_difference' => $goalsFor - $goalsAgainst,
            'weekly' => (int) $week,
        ];

        return $payload;
    }

    public function createInitialStatisticRecord(LeagueTable $match): array
    {
        return [
            'name' => $match['team']['name'],
            'matches_played' => 0,
            'wins' => 0,
        ];
    }

    public function checkLeagueTable(int $week): Collection
    {
        $league = $this->leagueRepository->getLeagueTableByWeek($week);
        $teams = $this->teamService->getAllTeams();
        if ($league->isEmpty()) {
            foreach ($teams as $team) {
                $payload = $this->generateRandomLeagueRecord($week, $team);
                $this->leagueRepository->createLeague($payload);
            }

            $league = $this->leagueRepository->getLeagueTableByWeek($week);
        }

        return $league;
    }

    public function getChampionShipByWeek(int $week): array
    {
        $league = $this->leagueRepository->getLeagueTableTillWeek($week + 1);
        $teamStatistics = [];
        foreach ($league as $match) {
            $teamId = $match['team_id'];

            if (!isset($teamStatistics[$teamId])) {
                $teamStatistics[$teamId] = $this->createInitialStatisticRecord($match);
            }

            $teamStatistics[$teamId]['matches_played'] += $match['matches_played'];
            $teamStatistics[$teamId]['wins'] += $match['wins'];
        }
        $finalStats = array_map(function (array $stats): TeamStatistic {
            $winRate = $stats['wins'] / $stats['matches_played'] * 100;

            return new TeamStatistic($stats['name'], round($winRate, 2));
        }, $teamStatistics);

        return $finalStats;
    }
}
