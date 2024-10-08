<?php

namespace App\Implementations;

use App\Models\LeagueTable;
use App\Repositories\LeagueTableRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class LeagueTableRepository implements LeagueTableRepositoryInterface
{
    protected $leagueTable;

    public function __construct(LeagueTable $leagueTable)
    {
        $this->leagueTable = $leagueTable;
    }

    public function createLeague(array $paylaod): LeagueTable
    {
        return $this->leagueTable->create($paylaod);
    }
    public function getLeagueTableByWeek(int $week): Collection
    {
        return $this->leagueTable->with('team')->where('weekly', $week)->get();
    }

    public function getLeagueTableTillWeek(int $week): Collection
    {
        return $this->leagueTable->with('team')->where('weekly', '<', $week)->get();
    }

}
