<?php

namespace App\Repositories;

use App\Models\LeagueTable;

interface LeagueTableRepositoryInterface
{
    public function getOrCreate($week, $teams);
    public function checkLeagueTable($teams, $week);
    public function getChampionShipByWeek($week);
}
