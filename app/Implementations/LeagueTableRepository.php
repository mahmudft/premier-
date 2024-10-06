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


    public function checkLeagueTable($teams, $week){
        Log::info('Week: ' . $week);
        $league = $this->leagueTable->with('team')->where('weekly', $week)->get();
        if($league->count() == 0){
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
        // dd($league);
        return $league;
    }


    public function getOrderedTable()
    {
        return $this->leagueTable->with('team')
            ->orderBy('points', 'desc')
            ->orderBy('goal_difference', 'desc')
            ->get();
    }
}
