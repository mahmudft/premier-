<?php

namespace App\Http\Controllers;

use App\Repositories\TeamRepositoryInterface;
use App\Repositories\MatchRepositoryInterface;
use App\Repositories\LeagueTableRepositoryInterface;

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
    

    public function simulateChampionshipByWeek($week){
        $statistics = $this->leagueTableRepo->getChampionShipByWeek($week);
       return response()->json($statistics);

    }


    public function simulateMatchByWeek($week)
    {
        $matches = $this->matchRepo->createOrGetMatchByWeek($week);
        return response()->json($matches);
    }
    

    public function showLeagueTable($week)
    {
        $teams = $this->teamRepo->all();
        $leagueTable = $this->leagueTableRepo->getOrCreate($week, $teams);
        return response()->json($leagueTable);
    }

}
