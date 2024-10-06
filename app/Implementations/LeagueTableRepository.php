<?php

namespace App\Implementations;

use App\Models\LeagueTable;
use App\Repositories\LeagueTableRepositoryInterface;


use Illuminate\Support\Facades\Log;

class LeagueTableRepository implements LeagueTableRepositoryInterface
{
    protected $leagueTable;

    public function __construct(LeagueTable $leagueTable)
    {
        $this->leagueTable = $leagueTable;
    }

    public function getOrC($teamId, array $data)
    {
        return $this->leagueTable->updateOrCreate(['team_id' => $teamId], $data);
    }

    public function getOrCreate($week, $teams)
    {
        $league = $this->checkLeagueTable($teams, $week);

        return $league;
    }


    public function checkLeagueTable($teams, $week)
    {

        $league = $this->leagueTable->with('team')->where('weekly', $week)->get();

        if ($league->count() == 0) {
            foreach ($teams as $key => $value) {
                $goalsFor = rand(0, 15);
                $goalsAgainst = rand(0, 20);
                $payload = [
                    'matches_played' => rand(7, 10),
                    'team_id' => $value['id'],
                    'wins' => $goalsFor > $goalsAgainst ? 1 : 0,
                    'draws' => $goalsFor == $goalsAgainst ? 1 : 0,
                    'losses' => $goalsFor < $goalsAgainst ? 1 : 0,
                    'points' => $goalsFor > $goalsAgainst ? 3 : ($goalsFor == $goalsAgainst ? 1 : 0),
                    'goal_difference' => $goalsFor - $goalsAgainst,
                    'weekly' => (int)$week,
                ];
                $this->leagueTable->create($payload);
            }
        }

        $league = $this->leagueTable->with('team')->where('weekly', (int)$week)->get();

        return $league;
    }


    public function getChampionShipByWeek($week)
    {
        $league = $this->leagueTable->with('team')->where('weekly', '<', (int)$week + 1)->get();

        $teamStatistics = [];
        $final_stats = [];

        foreach ($league as $match) {
            $teamId = $match['team_id'];

            if (!isset($teamStatistics[$teamId])) {
                $teamStatistics[$teamId] = [
                    'name' => $match['team']['name'],
                    'matches_played' => 0,
                    'wins' => 0,
                ];
            }

            $teamStatistics[$teamId]['matches_played'] += $match['matches_played'];
            $teamStatistics[$teamId]['wins'] += $match['wins'];
        }

        foreach ($teamStatistics as $teamId => $stats) {
            $winRate = $stats['wins'] / $stats['matches_played'] * 100;
            array_push($final_stats, [
                'name' => $stats['name'],
                'value' => round($winRate, 2)
            ]);

        }




        return $final_stats;
    }
}
