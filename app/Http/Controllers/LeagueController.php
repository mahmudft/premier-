<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Services\{LeagueService, MatchService, TeamService};

class LeagueController extends Controller
{
    protected $teamRepo;
    protected $matchService;
    protected $leagueService;

    public function __construct(
        MatchService $matchService,
        LeagueService $leagueService
    ) {
        $this->matchService = $matchService;
        $this->leagueService = $leagueService;
    }


    public function simulateChampionshipByWeek(int $week): JsonResponse
    {
        $statistics = $this->leagueService->getChampionShipByWeek($week);
        return response()->json($statistics);
    }


    public function simulateMatchByWeek(int $week): JsonResponse
    {
        $matches = $this->matchService->createOrGetMatchByWeek($week);
        return response()->json($matches);
    }


    public function showLeagueTable(int $week): JsonResponse
    {
        $leagueTable = $this->leagueService->checkLeagueTable($week);
        return response()->json($leagueTable);
    }
}
