<?php

namespace App\Repositories;

use App\Models\LeagueTable;
use Illuminate\Database\Eloquent\Collection;

interface LeagueTableRepositoryInterface
{
    public function createLeague(array $payload): LeagueTable;

    public function getLeagueTableByWeek(int $week): Collection;
    
    public function getLeagueTableTillWeek(int $week): Collection;
}
