<?php

namespace App\Repositories;

use App\Models\LeagueTable;

interface LeagueTableRepositoryInterface
{
    public function updateOrCreate($teamId, array $data);
    public function getOrderedTable();
}
