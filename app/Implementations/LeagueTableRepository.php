<?php
namespace App\Implementations;

use App\Models\LeagueTable;
use App\Repositories\LeagueTableRepositoryInterface;

class LeagueTableRepository implements LeagueTableRepositoryInterface
{
    protected $leagueTable;

    public function __construct(LeagueTable $leagueTable)
    {
        $this->leagueTable = $leagueTable;
    }

    public function updateOrCreate($teamId, array $data)
    {
        return $this->leagueTable->updateOrCreate(['team_id' => $teamId], $data);
    }

    public function getOrderedTable()
    {
        return $this->leagueTable->with('team')
            ->orderBy('points', 'desc')
            ->orderBy('goal_difference', 'desc')
            ->get();
    }
}
